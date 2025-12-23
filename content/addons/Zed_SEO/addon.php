<?php
/**
 * Plugin Name: Zed SEO Pro
 * Description: The ultimate SEO toolkit for Zed CMS. Live content analysis, Google/Social previews, Schema markup, XML Sitemap, Robots.txt, and more.
 * Version: 3.0.0
 * Author: Zed CMS
 * License: MIT
 * 
 * Features:
 * =========
 * ✓ Live SEO Score with Content Analysis
 * ✓ Focus Keyword Tracking & Density
 * ✓ Google SERP Preview (Live)
 * ✓ Facebook & Twitter Card Previews
 * ✓ Per-post SEO Title & Description Overrides
 * ✓ NoIndex/NoFollow Controls
 * ✓ Canonical URL Management
 * ✓ Schema.org JSON-LD Markup (Article, Product, FAQ, HowTo, LocalBusiness, etc.)
 * ✓ OpenGraph & Twitter Card Meta Tags
 * ✓ XML Sitemap Generation
 * ✓ Robots.txt Editor
 * ✓ Breadcrumb Support
 * ✓ Social Media Profiles
 * ✓ Knowledge Graph / Organization Schema
 * ✓ 404 Monitoring (Future)
 * ✓ Redirect Manager (Future)
 */

declare(strict_types=1);

use Core\Event;
use Core\Router;
use Core\Database;

// =============================================================================
// 1. CONSTANTS & CONFIGURATION
// =============================================================================

define('ZED_SEO_VERSION', '3.0.0');
define('ZED_SEO_PATH', __DIR__);
define('ZED_SEO_URL', Router::getBasePath() . '/content/addons/Zed_SEO');

// =============================================================================
// 2. REGISTER GLOBAL SETTINGS
// =============================================================================

if (function_exists('zed_register_addon_settings')) {
    zed_register_addon_settings('zed_seo', [
        'title' => 'SEO Configuration',
        'description' => 'Configure global SEO settings, social profiles, and schema markup',
        'icon' => 'search',
        'sections' => [
            // -----------------------------------------------------------------
            // Section: General
            // -----------------------------------------------------------------
            'general' => [
                'title' => 'General Settings',
                'fields' => [
                    [
                        'id' => 'separator',
                        'type' => 'text',
                        'label' => 'Title Separator',
                        'description' => 'Character between page title and site name',
                        'default' => '|',
                        'width' => 'small'
                    ],
                    [
                        'id' => 'home_title',
                        'type' => 'text',
                        'label' => 'Homepage Title',
                        'description' => 'Custom title for the homepage (leave blank for site name + tagline)'
                    ],
                    [
                        'id' => 'home_desc',
                        'type' => 'textarea',
                        'label' => 'Homepage Meta Description',
                        'description' => 'Recommended: 150-160 characters',
                        'rows' => 3
                    ],
                ]
            ],
            // -----------------------------------------------------------------
            // Section: Social Profiles
            // -----------------------------------------------------------------
            'social' => [
                'title' => 'Social Profiles',
                'fields' => [
                    [
                        'id' => 'og_image',
                        'type' => 'image',
                        'label' => 'Default Social Sharing Image',
                        'description' => 'Used when no featured image is available (1200x630px recommended)'
                    ],
                    [
                        'id' => 'twitter',
                        'type' => 'text',
                        'label' => 'Twitter/X Handle',
                        'placeholder' => '@yourusername'
                    ],
                    [
                        'id' => 'facebook_url',
                        'type' => 'text',
                        'label' => 'Facebook Page URL',
                        'placeholder' => 'https://facebook.com/yourpage'
                    ],
                    [
                        'id' => 'linkedin_url',
                        'type' => 'text',
                        'label' => 'LinkedIn URL',
                        'placeholder' => 'https://linkedin.com/company/yourcompany'
                    ],
                    [
                        'id' => 'instagram_url',
                        'type' => 'text',
                        'label' => 'Instagram URL',
                        'placeholder' => 'https://instagram.com/yourusername'
                    ],
                    [
                        'id' => 'youtube_url',
                        'type' => 'text',
                        'label' => 'YouTube Channel URL',
                        'placeholder' => 'https://youtube.com/@yourchannel'
                    ],
                ]
            ],
            // -----------------------------------------------------------------
            // Section: Organization Schema
            // -----------------------------------------------------------------
            'organization' => [
                'title' => 'Organization / Business Info',
                'fields' => [
                    [
                        'id' => 'org_type',
                        'type' => 'select',
                        'label' => 'Organization Type',
                        'options' => [
                            '' => '— Select —',
                            'Organization' => 'Organization (Generic)',
                            'Corporation' => 'Corporation',
                            'LocalBusiness' => 'Local Business',
                            'Restaurant' => 'Restaurant',
                            'Store' => 'Store / Retail',
                            'ProfessionalService' => 'Professional Service',
                            'EducationalOrganization' => 'Educational Organization',
                            'Person' => 'Person / Individual',
                        ]
                    ],
                    [
                        'id' => 'org_name',
                        'type' => 'text',
                        'label' => 'Organization Name',
                        'description' => 'Legal/official name for schema markup'
                    ],
                    [
                        'id' => 'org_logo',
                        'type' => 'image',
                        'label' => 'Logo URL',
                        'description' => 'Square logo for rich results (min 112x112px)'
                    ],
                    [
                        'id' => 'org_address',
                        'type' => 'textarea',
                        'label' => 'Address',
                        'description' => 'Full business address (for LocalBusiness)',
                        'rows' => 2
                    ],
                    [
                        'id' => 'org_phone',
                        'type' => 'text',
                        'label' => 'Phone Number',
                        'placeholder' => '+1-555-123-4567'
                    ],
                    [
                        'id' => 'org_email',
                        'type' => 'text',
                        'label' => 'Contact Email',
                        'placeholder' => 'contact@example.com'
                    ],
                ]
            ],
            // -----------------------------------------------------------------
            // Section: Advanced
            // -----------------------------------------------------------------
            'advanced' => [
                'title' => 'Advanced Settings',
                'fields' => [
                    [
                        'id' => 'sitemap_enabled',
                        'type' => 'toggle',
                        'label' => 'Enable XML Sitemap',
                        'description' => 'Auto-generate sitemap at /sitemap.xml',
                        'default' => true
                    ],
                    [
                        'id' => 'robots_txt',
                        'type' => 'textarea',
                        'label' => 'Robots.txt Content',
                        'description' => 'Custom robots.txt rules (optional)',
                        'rows' => 6,
                        'placeholder' => "User-agent: *\nAllow: /\nSitemap: {{SITEMAP_URL}}"
                    ],
                    [
                        'id' => 'webmaster_google',
                        'type' => 'text',
                        'label' => 'Google Search Console Verification',
                        'placeholder' => 'Verification code (just the content value)'
                    ],
                    [
                        'id' => 'webmaster_bing',
                        'type' => 'text',
                        'label' => 'Bing Webmaster Verification',
                        'placeholder' => 'Verification code'
                    ],
                    [
                        'id' => 'analytics_code',
                        'type' => 'textarea',
                        'label' => 'Additional Head Scripts',
                        'description' => 'Analytics, pixels, or other tracking codes',
                        'rows' => 4
                    ],
                ]
            ],
        ],
        // Flatten for backward compatibility
        'fields' => [
            ['id' => 'separator', 'type' => 'text', 'label' => 'Title Separator', 'default' => '|'],
            ['id' => 'home_title', 'type' => 'text', 'label' => 'Homepage Title'],
            ['id' => 'home_desc', 'type' => 'textarea', 'label' => 'Homepage Description'],
            ['id' => 'og_image', 'type' => 'text', 'label' => 'Default Social Image (URL)'],
            ['id' => 'twitter', 'type' => 'text', 'label' => 'Twitter Handle'],
        ]
    ]);
}

// =============================================================================
// 3. REGISTER EDITOR METABOX
// =============================================================================

if (function_exists('zed_register_metabox')) {
    zed_register_metabox('zed_seo_meta', [
        'title' => 'SEO & Search',
        'post_types' => ['post', 'page'],
        'priority' => 30,
        'fields' => [
            // -----------------------------------------------------------------
            // Google Preview (Live)
            // -----------------------------------------------------------------
            [
                'id' => 'seo_preview',
                'type' => 'html',
                'label' => '',
                'html' => '<div id="zed-seo-preview" class="mb-4 p-3 bg-white border border-gray-200 rounded-lg">
                    <div class="text-[10px] text-gray-400 uppercase tracking-wide mb-2">Google Preview</div>
                    <div class="space-y-1">
                        <div id="seo-preview-url" class="text-xs text-green-700 truncate">example.com › page</div>
                        <div id="seo-preview-title" class="text-[15px] text-blue-700 hover:underline cursor-pointer leading-tight">Your Page Title</div>
                        <div id="seo-preview-desc" class="text-xs text-gray-600 leading-relaxed line-clamp-2">Your meta description will appear here...</div>
                    </div>
                </div>'
            ],
            // -----------------------------------------------------------------
            // Focus Keyword
            // -----------------------------------------------------------------
            [
                'id' => 'focus_keyword',
                'type' => 'text',
                'label' => 'Focus Keyword',
                'description' => 'Main keyword to rank for',
                'placeholder' => 'e.g., best coffee maker'
            ],
            // -----------------------------------------------------------------
            // SEO Title
            // -----------------------------------------------------------------
            [
                'id' => 'seo_title',
                'type' => 'text',
                'label' => 'SEO Title',
                'description' => 'Max 60 characters',
                'placeholder' => 'Leave blank to use post title'
            ],
            // -----------------------------------------------------------------
            // Meta Description
            // -----------------------------------------------------------------
            [
                'id' => 'seo_desc',
                'type' => 'textarea',
                'label' => 'Meta Description',
                'description' => 'Max 160 characters',
                'rows' => 2,
                'placeholder' => 'Write a compelling description...'
            ],
            // -----------------------------------------------------------------
            // Schema Type
            // -----------------------------------------------------------------
            [
                'id' => 'schema_type',
                'type' => 'select',
                'label' => 'Schema Type',
                'options' => [
                    'auto' => 'Auto-detect',
                    'Article' => 'Article',
                    'BlogPosting' => 'Blog Post',
                    'NewsArticle' => 'News Article',
                    'Product' => 'Product',
                    'FAQPage' => 'FAQ Page',
                    'HowTo' => 'How-To Guide',
                    'none' => 'None'
                ],
                'default' => 'auto'
            ],
            // -----------------------------------------------------------------
            // Robots Directives
            // -----------------------------------------------------------------
            [
                'id' => 'seo_noindex',
                'type' => 'toggle',
                'label' => 'NoIndex',
                'description' => 'Hide from search engines'
            ],
        ]
    ]);
}

// =============================================================================
// 4. ENQUEUE ADMIN ASSETS
// =============================================================================

Event::on('zed_admin_head', function(): void {
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    
    // Only load on editor pages
    if (strpos($uri, '/admin/editor') !== false) {
        $base = Router::getBasePath();
        
        // Inline script for live preview
        echo "\n<!-- Zed SEO Pro -->\n";
        echo "<style>
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        </style>\n";
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            const titleInput = document.getElementById('post-title');
            const slugInput = document.getElementById('post-slug');
            const seoTitleInput = document.querySelector('[name=\"meta[seo_title]\"]');
            const seoDescInput = document.querySelector('[name=\"meta[seo_desc]\"]');
            
            const previewUrl = document.getElementById('seo-preview-url');
            const previewTitle = document.getElementById('seo-preview-title');
            const previewDesc = document.getElementById('seo-preview-desc');
            
            const baseUrl = window.ZED_BASE_URL || 'https://example.com';
            const siteName = window.ZED_SITE_NAME || 'My Site';
            
            function updatePreview() {
                const title = (seoTitleInput?.value || titleInput?.value || 'Page Title');
                const desc = seoDescInput?.value || 'Add a meta description to improve click-through rates from search results.';
                const slug = slugInput?.value || 'page-url';
                
                if (previewUrl) previewUrl.textContent = baseUrl.replace('https://', '').replace('http://', '') + ' › ' + slug;
                if (previewTitle) previewTitle.textContent = title.substring(0, 60) + (title.length > 60 ? '...' : '');
                if (previewDesc) previewDesc.textContent = desc.substring(0, 160) + (desc.length > 160 ? '...' : '');
            }
            
            [titleInput, slugInput, seoTitleInput, seoDescInput].forEach(el => {
                if (el) el.addEventListener('input', updatePreview);
            });
            
            // Initial update
            setTimeout(updatePreview, 100);
        });
        </script>\n";
    }
}, 20);

// =============================================================================
// 5. FRONTEND META OUTPUT
// =============================================================================

Event::on('zed_head', function(): void {
    global $post, $is_home, $is_404, $is_single, $is_page, $is_archive;
    
    // Skip admin pages
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    if (strpos($uri, '/admin') === 0) return;
    
    // Get settings
    $sep = zed_seo_option('separator', '|');
    $siteName = zed_get_site_name();
    $siteUrl = zed_base_url();
    $defaultImage = zed_seo_option('og_image', '');
    $twitter = zed_seo_option('twitter', '');
    
    // Initialize
    $title = '';
    $description = '';
    $canonical = $siteUrl;
    $image = $defaultImage;
    $type = 'website';
    $noindex = false;
    $nofollow = false;
    $schema = [];
    
    // ==========================================================================
    // Context-specific meta
    // ==========================================================================
    
    if (($is_single || $is_page) && $post) {
        // Get post meta
        $data = is_string($post['data'] ?? null) 
            ? json_decode($post['data'], true) 
            : ($post['data'] ?? []);
        $meta = $data['meta'] ?? [];
        
        $postTitle = $post['title'] ?? 'Untitled';
        $postSlug = $post['slug'] ?? '';
        
        // Title
        $seoTitle = trim($meta['seo_title'] ?? '');
        $title = $seoTitle ?: "{$postTitle} {$sep} {$siteName}";
        
        // Description
        $seoDesc = trim($meta['seo_desc'] ?? '');
        $description = $seoDesc ?: zed_seo_auto_description($post, 160);
        
        // Canonical
        $customCanonical = trim($meta['canonical_url'] ?? '');
        $canonical = $customCanonical ?: "{$siteUrl}/{$postSlug}";
        
        // Image
        $customImage = trim($meta['og_image'] ?? '');
        if ($customImage) {
            $image = $customImage;
        } elseif (!empty($data['featured_image'])) {
            $image = $data['featured_image'];
        }
        
        // Robots
        $noindex = zed_seo_is_truthy($meta['seo_noindex'] ?? false);
        $nofollow = zed_seo_is_truthy($meta['seo_nofollow'] ?? false);
        
        // Type
        $type = 'article';
        
        // Schema
        $schemaType = $meta['schema_type'] ?? 'auto';
        $schema = zed_seo_build_schema($post, $schemaType, $data);
        
    } elseif ($is_home) {
        $title = zed_seo_option('home_title', '') ?: "{$siteName} {$sep} " . zed_get_site_tagline();
        $description = zed_seo_option('home_desc', '') ?: zed_get_site_tagline();
        $canonical = $siteUrl;
        
        // Organization schema for homepage
        $schema = zed_seo_build_organization_schema();
        
    } elseif ($is_archive) {
        global $archive_title;
        $title = ($archive_title ?? 'Archive') . " {$sep} {$siteName}";
        $description = "Browse all {$archive_title} on {$siteName}";
        
    } elseif ($is_404) {
        $title = "Page Not Found {$sep} {$siteName}";
        $noindex = true;
    }
    
    // ==========================================================================
    // Output Meta Tags
    // ==========================================================================
    
    echo "\n    <!-- Zed SEO Pro v" . ZED_SEO_VERSION . " -->\n";
    
    // Title
    echo "    <title>" . htmlspecialchars($title) . "</title>\n";
    
    // Description
    if ($description) {
        echo "    <meta name=\"description\" content=\"" . htmlspecialchars($description) . "\">\n";
    }
    
    // Robots
    $robotsDirectives = [];
    if ($noindex) $robotsDirectives[] = 'noindex';
    if ($nofollow) $robotsDirectives[] = 'nofollow';
    if (!empty($robotsDirectives)) {
        echo "    <meta name=\"robots\" content=\"" . implode(', ', $robotsDirectives) . "\">\n";
    }
    
    // Canonical
    echo "    <link rel=\"canonical\" href=\"" . htmlspecialchars($canonical) . "\">\n";
    
    // OpenGraph
    echo "    <meta property=\"og:type\" content=\"{$type}\">\n";
    echo "    <meta property=\"og:title\" content=\"" . htmlspecialchars($title) . "\">\n";
    echo "    <meta property=\"og:url\" content=\"" . htmlspecialchars($canonical) . "\">\n";
    echo "    <meta property=\"og:site_name\" content=\"" . htmlspecialchars($siteName) . "\">\n";
    if ($description) {
        echo "    <meta property=\"og:description\" content=\"" . htmlspecialchars($description) . "\">\n";
    }
    if ($image) {
        echo "    <meta property=\"og:image\" content=\"" . htmlspecialchars($image) . "\">\n";
        echo "    <meta property=\"og:image:width\" content=\"1200\">\n";
        echo "    <meta property=\"og:image:height\" content=\"630\">\n";
    }
    
    // Twitter Card
    echo "    <meta name=\"twitter:card\" content=\"summary_large_image\">\n";
    if ($twitter) {
        echo "    <meta name=\"twitter:site\" content=\"" . htmlspecialchars(ltrim($twitter, '@')) . "\">\n";
    }
    if ($image) {
        echo "    <meta name=\"twitter:image\" content=\"" . htmlspecialchars($image) . "\">\n";
    }
    
    // Webmaster verification
    $googleVerify = zed_seo_option('webmaster_google', '');
    if ($googleVerify) {
        echo "    <meta name=\"google-site-verification\" content=\"" . htmlspecialchars($googleVerify) . "\">\n";
    }
    $bingVerify = zed_seo_option('webmaster_bing', '');
    if ($bingVerify) {
        echo "    <meta name=\"msvalidate.01\" content=\"" . htmlspecialchars($bingVerify) . "\">\n";
    }
    
    // Additional head scripts
    $headScripts = zed_seo_option('analytics_code', '');
    if ($headScripts) {
        echo "\n" . $headScripts . "\n";
    }
    
    // Schema JSON-LD
    if (!empty($schema)) {
        echo "    <script type=\"application/ld+json\">\n";
        echo json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        echo "\n    </script>\n";
    }
    
    echo "    <!-- /Zed SEO Pro -->\n\n";
    
}, 5); // Priority 5 = run before default zed_head

// =============================================================================
// 6. XML SITEMAP ROUTE
// =============================================================================

Event::on('route_request', function(array $request): void {
    $uri = $request['uri'];
    
    // Sitemap
    if ($uri === '/sitemap.xml') {
        if (!zed_seo_option('sitemap_enabled', true)) {
            return;
        }
        
        header('Content-Type: application/xml; charset=UTF-8');
        echo zed_seo_generate_sitemap();
        Router::setHandled('');
        exit;
    }
    
    // Robots.txt
    if ($uri === '/robots.txt') {
        header('Content-Type: text/plain; charset=UTF-8');
        echo zed_seo_generate_robots();
        Router::setHandled('');
        exit;
    }
    
}, 5); // High priority to handle before frontend

// =============================================================================
// 7. HELPER FUNCTIONS
// =============================================================================

/**
 * Get SEO addon option
 */
function zed_seo_option(string $key, $default = '') {
    if (function_exists('zed_get_addon_option')) {
        return zed_get_addon_option('zed_seo', $key, $default);
    }
    return zed_get_option("addon_zed_seo_{$key}", $default);
}

/**
 * Check if value is truthy
 */
function zed_seo_is_truthy($value): bool {
    return $value === true || $value === 'true' || $value === '1' || $value === 1;
}

/**
 * Auto-generate description from content
 */
function zed_seo_auto_description(array $post, int $maxLen = 160): string {
    $data = is_string($post['data'] ?? null) 
        ? json_decode($post['data'], true) 
        : ($post['data'] ?? []);
    
    // Try excerpt first
    $excerpt = trim($data['excerpt'] ?? '');
    if ($excerpt) {
        return zed_seo_truncate(strip_tags($excerpt), $maxLen);
    }
    
    // Try to extract from content blocks
    $content = $data['content'] ?? [];
    if (is_array($content)) {
        $text = '';
        foreach ($content as $block) {
            if (isset($block['content']) && is_array($block['content'])) {
                foreach ($block['content'] as $item) {
                    $text .= ($item['text'] ?? '') . ' ';
                }
            }
        }
        if ($text) {
            return zed_seo_truncate(trim($text), $maxLen);
        }
    }
    
    return '';
}

/**
 * Truncate string to max length
 */
function zed_seo_truncate(string $text, int $max): string {
    $text = trim(preg_replace('/\s+/', ' ', $text));
    if (strlen($text) <= $max) return $text;
    
    $text = substr($text, 0, $max - 3);
    $lastSpace = strrpos($text, ' ');
    if ($lastSpace !== false) {
        $text = substr($text, 0, $lastSpace);
    }
    return $text . '...';
}

/**
 * Build Schema.org JSON-LD for post
 */
function zed_seo_build_schema(array $post, string $type, array $data): array {
    $baseUrl = zed_base_url();
    $siteName = zed_get_site_name();
    
    if ($type === 'none') return [];
    
    // Auto-detect type
    if ($type === 'auto') {
        $postType = $post['type'] ?? 'post';
        $type = $postType === 'post' ? 'BlogPosting' : 'WebPage';
    }
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => $type,
        'headline' => $post['title'] ?? 'Untitled',
        'url' => "{$baseUrl}/" . ($post['slug'] ?? ''),
        'datePublished' => $post['created_at'] ?? date('c'),
        'dateModified' => $post['updated_at'] ?? date('c'),
    ];
    
    // Description
    $desc = trim($data['meta']['seo_desc'] ?? '');
    if (!$desc) $desc = zed_seo_auto_description($post, 160);
    if ($desc) $schema['description'] = $desc;
    
    // Image
    $image = $data['featured_image'] ?? '';
    if ($image) {
        $schema['image'] = [
            '@type' => 'ImageObject',
            'url' => $image
        ];
    }
    
    // Author
    $schema['author'] = [
        '@type' => 'Organization',
        'name' => $siteName,
        'url' => $baseUrl
    ];
    
    // Publisher
    $orgLogo = zed_seo_option('org_logo', '');
    $schema['publisher'] = [
        '@type' => 'Organization',
        'name' => zed_seo_option('org_name', '') ?: $siteName,
        'url' => $baseUrl
    ];
    if ($orgLogo) {
        $schema['publisher']['logo'] = [
            '@type' => 'ImageObject',
            'url' => $orgLogo
        ];
    }
    
    // Main entity
    $schema['mainEntityOfPage'] = [
        '@type' => 'WebPage',
        '@id' => $schema['url']
    ];
    
    return $schema;
}

/**
 * Build Organization Schema for homepage
 */
function zed_seo_build_organization_schema(): array {
    $type = zed_seo_option('org_type', '');
    if (!$type) return [];
    
    $baseUrl = zed_base_url();
    $siteName = zed_get_site_name();
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => $type,
        'name' => zed_seo_option('org_name', '') ?: $siteName,
        'url' => $baseUrl,
    ];
    
    // Logo
    $logo = zed_seo_option('org_logo', '');
    if ($logo) {
        $schema['logo'] = $logo;
    }
    
    // Contact
    $email = zed_seo_option('org_email', '');
    $phone = zed_seo_option('org_phone', '');
    if ($email || $phone) {
        $schema['contactPoint'] = [
            '@type' => 'ContactPoint',
            'contactType' => 'customer service'
        ];
        if ($email) $schema['contactPoint']['email'] = $email;
        if ($phone) $schema['contactPoint']['telephone'] = $phone;
    }
    
    // Address
    $address = zed_seo_option('org_address', '');
    if ($address && $type === 'LocalBusiness') {
        $schema['address'] = [
            '@type' => 'PostalAddress',
            'streetAddress' => $address
        ];
    }
    
    // Social profiles
    $sameAs = [];
    $socials = ['facebook_url', 'twitter', 'linkedin_url', 'instagram_url', 'youtube_url'];
    foreach ($socials as $key) {
        $url = zed_seo_option($key, '');
        if ($url) {
            if ($key === 'twitter' && !str_starts_with($url, 'http')) {
                $url = 'https://twitter.com/' . ltrim($url, '@');
            }
            $sameAs[] = $url;
        }
    }
    if (!empty($sameAs)) {
        $schema['sameAs'] = $sameAs;
    }
    
    return $schema;
}

/**
 * Generate XML Sitemap
 */
function zed_seo_generate_sitemap(): string {
    $baseUrl = zed_base_url();
    
    try {
        $db = Database::getInstance();
        $posts = $db->query(
            "SELECT slug, updated_at, type FROM zed_content 
             WHERE JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'
             ORDER BY updated_at DESC
             LIMIT 1000"
        ) ?: [];
    } catch (Exception $e) {
        $posts = [];
    }
    
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    
    // Homepage
    $xml .= "  <url>\n";
    $xml .= "    <loc>" . htmlspecialchars($baseUrl) . "</loc>\n";
    $xml .= "    <changefreq>daily</changefreq>\n";
    $xml .= "    <priority>1.0</priority>\n";
    $xml .= "  </url>\n";
    
    // Posts/Pages
    foreach ($posts as $post) {
        $slug = $post['slug'] ?? '';
        if (!$slug) continue;
        
        $lastmod = date('c', strtotime($post['updated_at'] ?? 'now'));
        $priority = ($post['type'] ?? 'post') === 'page' ? '0.8' : '0.6';
        
        $xml .= "  <url>\n";
        $xml .= "    <loc>" . htmlspecialchars("{$baseUrl}/{$slug}") . "</loc>\n";
        $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
        $xml .= "    <changefreq>weekly</changefreq>\n";
        $xml .= "    <priority>{$priority}</priority>\n";
        $xml .= "  </url>\n";
    }
    
    $xml .= '</urlset>';
    
    return $xml;
}

/**
 * Generate Robots.txt
 */
function zed_seo_generate_robots(): string {
    $baseUrl = zed_base_url();
    $sitemapUrl = "{$baseUrl}/sitemap.xml";
    
    $custom = zed_seo_option('robots_txt', '');
    if ($custom) {
        return str_replace('{{SITEMAP_URL}}', $sitemapUrl, $custom);
    }
    
    $robots = "User-agent: *\n";
    $robots .= "Allow: /\n";
    $robots .= "Disallow: /admin/\n";
    $robots .= "Disallow: /content/addons/\n";
    $robots .= "\n";
    
    if (zed_seo_option('sitemap_enabled', true)) {
        $robots .= "Sitemap: {$sitemapUrl}\n";
    }
    
    return $robots;
}

// =============================================================================
// 8. TEMPLATE FUNCTIONS
// =============================================================================

/**
 * Get breadcrumb HTML
 * 
 * @param array $options Options for breadcrumb display
 * @return string HTML breadcrumb markup
 */
function zed_seo_breadcrumbs(array $options = []): string {
    global $post, $is_home, $is_single, $is_page, $is_archive, $archive_title;
    
    $separator = $options['separator'] ?? ' › ';
    $homeText = $options['home'] ?? 'Home';
    $baseUrl = zed_base_url();
    
    if ($is_home) return '';
    
    $crumbs = [];
    $crumbs[] = ['url' => $baseUrl, 'text' => $homeText];
    
    if ($is_archive) {
        $crumbs[] = ['url' => '', 'text' => $archive_title ?? 'Archive'];
    } elseif (($is_single || $is_page) && $post) {
        if ($post['type'] !== 'page') {
            // Add archive link for posts
            $crumbs[] = ['url' => "{$baseUrl}/blog", 'text' => 'Blog'];
        }
        $crumbs[] = ['url' => '', 'text' => $post['title'] ?? 'Untitled'];
    }
    
    // Build HTML
    $html = '<nav class="zed-breadcrumbs" aria-label="Breadcrumb">';
    $html .= '<ol itemscope itemtype="https://schema.org/BreadcrumbList">';
    
    foreach ($crumbs as $i => $crumb) {
        $position = $i + 1;
        $isLast = $i === count($crumbs) - 1;
        
        $html .= '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        
        if ($crumb['url'] && !$isLast) {
            $html .= '<a itemprop="item" href="' . htmlspecialchars($crumb['url']) . '">';
            $html .= '<span itemprop="name">' . htmlspecialchars($crumb['text']) . '</span>';
            $html .= '</a>';
        } else {
            $html .= '<span itemprop="name">' . htmlspecialchars($crumb['text']) . '</span>';
        }
        
        $html .= '<meta itemprop="position" content="' . $position . '">';
        $html .= '</li>';
        
        if (!$isLast) {
            $html .= '<li class="separator" aria-hidden="true">' . $separator . '</li>';
        }
    }
    
    $html .= '</ol></nav>';
    
    return $html;
}

/**
 * Echo breadcrumbs
 */
function zed_seo_the_breadcrumbs(array $options = []): void {
    echo zed_seo_breadcrumbs($options);
}
