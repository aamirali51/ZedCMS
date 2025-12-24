<?php
/**
 * Zed CMS — Email Helpers
 * 
 * Simple email sending with HTML support.
 * 
 * @package ZedCMS\Helpers
 */

declare(strict_types=1);

use Core\Event;

/**
 * Send an email
 * 
 * @param array $args Email arguments
 *   - to: string|array Recipient(s)
 *   - subject: string Email subject
 *   - body: string Email body (HTML or plain text)
 *   - html: bool Whether body is HTML (default: true)
 *   - from: string|null From address (uses site default if not set)
 *   - from_name: string|null From name
 *   - reply_to: string|null Reply-to address
 *   - headers: array Additional headers
 *   - attachments: array File paths to attach
 * @return bool Success
 */
function zed_mail(array $args): bool
{
    $defaults = [
        'to' => '',
        'subject' => '',
        'body' => '',
        'html' => true,
        'from' => null,
        'from_name' => null,
        'reply_to' => null,
        'headers' => [],
        'attachments' => [],
    ];
    
    $args = array_merge($defaults, $args);
    
    // Allow filtering before send
    $args = Event::filter('zed_mail_args', $args);
    
    // Validate required fields
    if (empty($args['to']) || empty($args['subject']) || empty($args['body'])) {
        return false;
    }
    
    // Convert to array if string
    $to = is_array($args['to']) ? implode(', ', $args['to']) : $args['to'];
    
    // Build headers
    $headers = $args['headers'];
    
    // From header
    $fromAddress = $args['from'] ?? zed_get_option('admin_email', 'noreply@' . ($_SERVER['HTTP_HOST'] ?? 'localhost'));
    $fromName = $args['from_name'] ?? zed_get_option('site_name', 'Zed CMS');
    $headers[] = "From: {$fromName} <{$fromAddress}>";
    
    // Reply-To
    if ($args['reply_to']) {
        $headers[] = "Reply-To: {$args['reply_to']}";
    }
    
    // Content type
    if ($args['html']) {
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Type: text/html; charset=UTF-8";
    } else {
        $headers[] = "Content-Type: text/plain; charset=UTF-8";
    }
    
    $headerString = implode("\r\n", $headers);
    
    // Fire pre-send action
    Event::trigger('zed_mail_before_send', $args);
    
    // Send email
    $result = @mail($to, $args['subject'], $args['body'], $headerString);
    
    // Fire post-send action
    Event::trigger('zed_mail_after_send', $args, $result);
    
    return $result;
}

/**
 * Send email using a template
 * 
 * @param string $template Template name (without .php)
 * @param array $data Variables to pass to template
 * @param array $mailArgs Additional mail arguments (to, subject, etc.)
 * @return bool Success
 */
function zed_mail_template(string $template, array $data, array $mailArgs): bool
{
    // Look for template in theme first, then default
    $themePath = defined('ZED_THEME_PATH') ? ZED_THEME_PATH : '';
    $templateFile = null;
    
    if ($themePath && file_exists($themePath . '/emails/' . $template . '.php')) {
        $templateFile = $themePath . '/emails/' . $template . '.php';
    } elseif (file_exists(__DIR__ . '/../emails/' . $template . '.php')) {
        $templateFile = __DIR__ . '/../emails/' . $template . '.php';
    }
    
    if (!$templateFile) {
        // No template found, use plain body
        return zed_mail($mailArgs);
    }
    
    // Render template
    extract($data, EXTR_SKIP);
    ob_start();
    include $templateFile;
    $body = ob_get_clean();
    
    $mailArgs['body'] = $body;
    $mailArgs['html'] = true;
    
    return zed_mail($mailArgs);
}

/**
 * Queue an email for later sending (requires cron)
 * 
 * @param array $args Same as zed_mail()
 * @return bool Success
 */
function zed_queue_mail(array $args): bool
{
    $queue = zed_get_transient('mail_queue') ?: [];
    $queue[] = $args;
    return zed_set_transient('mail_queue', $queue, 86400); // 24 hour queue
}

/**
 * Process queued emails (call from cron)
 * 
 * @param int $limit Max emails to send per run
 * @return int Number of emails sent
 */
function zed_process_mail_queue(int $limit = 10): int
{
    $queue = zed_get_transient('mail_queue') ?: [];
    
    if (empty($queue)) {
        return 0;
    }
    
    $sent = 0;
    $remaining = [];
    
    foreach ($queue as $i => $args) {
        if ($sent >= $limit) {
            $remaining[] = $args;
            continue;
        }
        
        if (zed_mail($args)) {
            $sent++;
        } else {
            // Keep failed emails in queue for retry
            $remaining[] = $args;
        }
    }
    
    if (empty($remaining)) {
        zed_delete_transient('mail_queue');
    } else {
        zed_set_transient('mail_queue', $remaining, 86400);
    }
    
    return $sent;
}
