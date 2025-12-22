<?php
/**
 * Zed Contact — Native Contact Form Addon
 * 
 * Demonstrates all Addon DX APIs:
 * - Addon Settings API (auto-generated UI)
 * - Shortcode System
 * - AJAX Handler
 * - Nonce/Security Helpers
 * - Email Helper
 * 
 * @package ZedCMS\Addons
 */

use Core\Router;

// =============================================================================
// 1. REGISTER SETTINGS
// Creates a page at /admin/addon-settings/zed_contact
// =============================================================================

zed_register_addon_settings('zed_contact', [
    'title' => 'Contact Form Settings',
    'description' => 'Configure the contact form behavior and appearance.',
    'fields' => [
        [
            'id' => 'recipient',
            'type' => 'email',
            'label' => 'Send Emails To',
            'description' => 'Email address to receive form submissions',
            'default' => 'admin@example.com'
        ],
        [
            'id' => 'subject_prefix',
            'type' => 'text',
            'label' => 'Subject Prefix',
            'description' => 'Prefix added to email subjects',
            'default' => '[Contact] '
        ],
        [
            'id' => 'success_message',
            'type' => 'textarea',
            'label' => 'Success Message',
            'description' => 'Message shown after successful submission',
            'default' => 'Thank you! Your message has been sent successfully.'
        ],
        [
            'id' => 'button_text',
            'type' => 'text',
            'label' => 'Submit Button Text',
            'default' => 'Send Message'
        ],
        [
            'id' => 'enable_copy',
            'type' => 'toggle',
            'label' => 'Send Copy to Sender',
            'description' => 'Send a confirmation email to the person submitting the form',
            'default' => false
        ],
        // Appearance Settings
        [
            'id' => 'primary_color',
            'type' => 'color',
            'label' => 'Primary Color',
            'description' => 'Button and focus color',
            'default' => '#6366f1'
        ],
        [
            'id' => 'button_style',
            'type' => 'select',
            'label' => 'Button Style',
            'options' => [
                'rounded' => 'Rounded',
                'square' => 'Square',
                'pill' => 'Pill (Fully Rounded)'
            ],
            'default' => 'rounded'
        ],
        [
            'id' => 'form_shadow',
            'type' => 'toggle',
            'label' => 'Enable Form Shadow',
            'description' => 'Add shadow effect to form container',
            'default' => false
        ],
    ]
]);

// =============================================================================
// 2. REGISTER SHORTCODE
// Usage: [contact_form] or [contact_form class="my-custom-class"]
// =============================================================================

zed_register_shortcode('contact_form', function($attrs, $content) {
    // Get settings
    $buttonText = zed_get_addon_option('zed_contact', 'button_text', 'Send Message');
    $primaryColor = zed_get_addon_option('zed_contact', 'primary_color', '#6366f1');
    $buttonStyle = zed_get_addon_option('zed_contact', 'button_style', 'rounded');
    $formShadow = zed_get_addon_option('zed_contact', 'form_shadow', false);
    
    $customClass = $attrs['class'] ?? '';
    
    // Button border radius based on style
    $buttonRadius = match($buttonStyle) {
        'square' => 'rounded-none',
        'pill' => 'rounded-full',
        default => 'rounded-lg'
    };
    
    // Form container classes
    $containerClass = $formShadow ? 'shadow-lg p-6 bg-white rounded-xl' : '';
    
    // Generate nonce for CSRF protection
    $nonce = zed_create_nonce('zed_contact_submit');
    
    // Get base URL for script
    $baseUrl = Router::getBasePath();
    $addonUrl = $baseUrl . '/content/addons/zed_contact';
    
    // Inline CSS for dynamic primary color
    $customStyles = <<<CSS
<style>
    .zed-contact-form-wrapper input:focus,
    .zed-contact-form-wrapper textarea:focus {
        border-color: {$primaryColor};
        box-shadow: 0 0 0 3px {$primaryColor}20;
    }
    .zed-contact-form-wrapper button[type="submit"] {
        background-color: {$primaryColor};
    }
    .zed-contact-form-wrapper button[type="submit"]:hover {
        filter: brightness(0.9);
    }
</style>
CSS;
    
    // Return the form HTML
    return $customStyles . <<<HTML
<div class="zed-contact-form-wrapper {$customClass} {$containerClass}">
    <form id="zed-contact-form" class="space-y-4">
        <input type="hidden" name="_zed_nonce" value="{$nonce}">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="zc-name" class="block text-sm font-medium text-gray-700 mb-1">Your Name *</label>
                <input type="text" id="zc-name" name="name" required 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                    placeholder="John Doe">
            </div>
            <div>
                <label for="zc-email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                <input type="email" id="zc-email" name="email" required 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                    placeholder="john@example.com">
            </div>
        </div>
        
        <div>
            <label for="zc-subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
            <input type="text" id="zc-subject" name="subject" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                placeholder="How can we help?">
        </div>
        
        <div>
            <label for="zc-message" class="block text-sm font-medium text-gray-700 mb-1">Message *</label>
            <textarea id="zc-message" name="message" rows="5" required 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none"
                placeholder="Your message here..."></textarea>
        </div>
        
        <div id="zc-feedback" class="hidden px-4 py-3 rounded-lg text-sm font-medium"></div>
        
        <button type="submit" id="zc-submit"
            class="w-full md:w-auto px-8 py-3 bg-indigo-600 text-white font-semibold {$buttonRadius} hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
            <span class="btn-text">{$buttonText}</span>
            <span class="btn-loading hidden">
                <svg class="animate-spin inline-block w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Sending...
            </span>
        </button>
    </form>
</div>

<link rel="stylesheet" href="{$addonUrl}/assets/contact-form.css">
<script src="{$addonUrl}/assets/contact-form.js" defer></script>
HTML;
});

// =============================================================================
// 3. REGISTER AJAX HANDLER
// Endpoint: POST /api/ajax/zed_contact_submit
// =============================================================================

zed_register_ajax('zed_contact_submit', function($data) {
    // A. Verify CSRF Token
    $nonce = $data['_zed_nonce'] ?? '';
    if (!zed_verify_nonce($nonce, 'zed_contact_submit')) {
        return [
            'success' => false,
            'message' => 'Security check failed. Please refresh the page and try again.'
        ];
    }
    
    // B. Validate required fields
    $name = trim(strip_tags($data['name'] ?? ''));
    $email = trim($data['email'] ?? '');
    $subject = trim(strip_tags($data['subject'] ?? 'No Subject'));
    $message = trim(strip_tags($data['message'] ?? ''));
    
    if (empty($name)) {
        return ['success' => false, 'message' => 'Please enter your name.'];
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Please enter a valid email address.'];
    }
    
    if (empty($message)) {
        return ['success' => false, 'message' => 'Please enter a message.'];
    }
    
    // C. Get settings from Addon Settings API
    $recipient = zed_get_addon_option('zed_contact', 'recipient', 'admin@example.com');
    $prefix = zed_get_addon_option('zed_contact', 'subject_prefix', '[Contact] ');
    $successMsg = zed_get_addon_option('zed_contact', 'success_message', 'Thank you! Your message has been sent.');
    $sendCopy = zed_get_addon_option('zed_contact', 'enable_copy', false);
    
    // D. Build email body
    $emailBody = <<<BODY
<h2>New Contact Form Submission</h2>

<table style="width: 100%; border-collapse: collapse;">
    <tr>
        <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; width: 120px;">Name:</td>
        <td style="padding: 10px; border-bottom: 1px solid #eee;">{$name}</td>
    </tr>
    <tr>
        <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">Email:</td>
        <td style="padding: 10px; border-bottom: 1px solid #eee;"><a href="mailto:{$email}">{$email}</a></td>
    </tr>
    <tr>
        <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">Subject:</td>
        <td style="padding: 10px; border-bottom: 1px solid #eee;">{$subject}</td>
    </tr>
</table>

<h3 style="margin-top: 20px;">Message:</h3>
<div style="background: #f9f9f9; padding: 15px; border-radius: 8px; white-space: pre-wrap;">{$message}</div>

<hr style="margin: 20px 0; border: none; border-top: 1px solid #eee;">
<p style="color: #666; font-size: 12px;">Sent via Zed Contact Form</p>
BODY;

    // E. Send email using Email Helper
    $sent = zed_mail([
        'to' => $recipient,
        'subject' => $prefix . $subject,
        'body' => $emailBody,
        'html' => true,
        'reply_to' => $email,
    ]);
    
    // F. Send copy to sender if enabled
    if ($sendCopy && $sent) {
        $siteName = zed_get_option('site_title', 'Our Website');
        $copyBody = <<<COPY
<h2>Thank you for contacting us!</h2>
<p>This is a copy of your message to {$siteName}.</p>

<h3>Your Message:</h3>
<div style="background: #f9f9f9; padding: 15px; border-radius: 8px; white-space: pre-wrap;">{$message}</div>

<p style="margin-top: 20px;">We'll get back to you as soon as possible.</p>
COPY;
        
        zed_mail([
            'to' => $email,
            'subject' => 'Copy of your message to ' . $siteName,
            'body' => $copyBody,
            'html' => true,
        ]);
    }
    
    // G. Return response
    if ($sent) {
        return [
            'success' => true,
            'message' => $successMsg
        ];
    } else {
        // For local development without mail server
        // Uncomment below to simulate success:
        // return ['success' => true, 'message' => $successMsg . ' (Simulated)'];
        
        return [
            'success' => false,
            'message' => 'Failed to send email. Please try again later or contact us directly.'
        ];
    }
    
}, require_auth: false, method: 'POST');
