# Zed CMS — Addon Developer Experience Roadmap

> **Created:** 2025-12-22  
> **Goal:** Make addon development so easy that porting WP plugins is trivial

---

## Current State (What We Have ✅)

- Hook System: `Event::on()`, `Event::filter()`
- Route Claiming: Listen to `route_request`
- Database Access: `Database::getInstance()`
- Options API: `zed_get_option()`
- Admin Menu: `zed_admin_menu` event
- Theme Helpers: 70+ `zed_*` functions
- CPT Registration: `zed_register_post_type()`
- Addon Enable/Disable: Admin UI toggle

---

## Missing APIs (Priority Order)

### Phase 1: Core APIs (Enables 70% of plugins)

#### 1. Shortcode System
```php
// Usage:
zed_register_shortcode('contact_form', function($attrs, $content) {
    $email = $attrs['email'] ?? 'default@example.com';
    return '<form action="/api/contact">...</form>';
});

// In post content: [contact_form email="test@example.com"]
// Auto-rendered to HTML in frontend
```

**Implementation Notes:**
- Add to `frontend_addon.php` or `frontend/helpers_shortcodes.php`
- Parse content during `render_blocks()` or after
- Global registry: `$ZED_SHORTCODES`
- Regex: `/\[(\w+)([^\]]*)\](?:(.*?)\[\/\1\])?/s`

---

#### 2. Admin Settings API
```php
// Usage:
zed_register_addon_settings('seo_pack', [
    'title' => 'SEO Pack Settings',
    'fields' => [
        ['id' => 'google_analytics', 'type' => 'text', 'label' => 'GA Tracking ID'],
        ['id' => 'meta_robots', 'type' => 'select', 'label' => 'Robots', 'options' => ['index', 'noindex']],
        ['id' => 'sitemap_enabled', 'type' => 'toggle', 'label' => 'Enable Sitemap'],
    ]
]);

// Auto-generates: /admin/addon-settings/seo_pack
// Auto-saves to: zed_options table with prefix `addon_seo_pack_`
```

**Implementation Notes:**
- Add route handler in `admin_addon.php`
- Field types: text, textarea, toggle, select, number, color, image
- Auto-render form UI with Tailwind styling
- Save handler: loop fields, save to `zed_options`

---

#### 3. AJAX Handler Pattern
```php
// Usage:
zed_register_ajax('submit_contact', function($data) {
    $email = $data['email'] ?? '';
    // Process...
    return ['success' => true, 'message' => 'Sent!'];
}, require_auth: false, method: 'POST');

// Frontend calls: POST /api/ajax/submit_contact
// Auto JSON response, auto CSRF if authenticated
```

**Implementation Notes:**
- Global registry: `$ZED_AJAX_HANDLERS`
- Route: `/api/ajax/{action}`
- Auto JSON encode response
- Auto CSRF token validation for authenticated

---

#### 4. Admin Notices
```php
// Usage:
zed_add_notice('Settings saved successfully!', 'success');
zed_add_notice('API key is invalid.', 'error');

// Auto-displays in admin header area
```

**Implementation Notes:**
- Store in session: `$_SESSION['zed_notices']`
- Render in `admin-layout.php` header
- Types: success, error, warning, info
- Auto-clear after display

---

### Phase 2: Editor Integration

#### 5. Metabox API
```php
// Usage:
zed_register_metabox('seo_fields', [
    'title' => 'SEO Settings',
    'post_types' => ['post', 'page'],
    'fields' => [
        ['id' => 'meta_title', 'type' => 'text', 'label' => 'Meta Title'],
        ['id' => 'meta_description', 'type' => 'textarea', 'label' => 'Meta Description'],
    ]
]);

// Renders in editor sidebar
// Auto-saves to post data JSON
```

**Implementation Notes:**
- Render in `editor.php` sidebar
- Store in `data` JSON column under `meta` key
- Event: `zed_editor_metaboxes`

---

#### 6. Script/Style Enqueue
```php
// Usage:
zed_enqueue_script('my-addon', '/content/addons/my_addon/script.js', ['deps' => ['jquery']]);
zed_enqueue_style('my-addon', '/content/addons/my_addon/style.css');

// Auto-added to head/footer via zed_head/zed_footer events
```

**Implementation Notes:**
- Global registry: `$ZED_SCRIPTS`, `$ZED_STYLES`
- Dependency ordering
- Render in `zed_head` and `zed_footer` events

---

#### 7. Nonce/CSRF Helpers
```php
// Generate:
$nonce = zed_create_nonce('delete_post_123');

// Verify:
if (!zed_verify_nonce($_POST['nonce'], 'delete_post_123')) {
    die('Security check failed');
}

// In forms:
<?= zed_nonce_field('my_action') ?>
```

**Implementation Notes:**
- HMAC-based with user ID + action + time
- 12-hour expiry by default
- Store secret in config

---

### Phase 3: Advanced

#### 8. Transients/Cache
```php
// Usage:
zed_set_transient('api_response', $data, 3600); // 1 hour
$cached = zed_get_transient('api_response');
zed_delete_transient('api_response');
```

**Implementation Notes:**
- Store in `zed_options` with `_transient_` prefix
- Add `expiry` column or JSON field
- Auto-cleanup on read if expired

---

#### 9. Cron/Scheduled Tasks
```php
// Usage:
zed_schedule_event('daily_cleanup', 'daily', function() {
    // Clean old data...
});

// Runs via: /cron.php (called by server cron)
```

**Implementation Notes:**
- Store schedules in `zed_options`
- Create `/cron.php` endpoint
- Server crontab: `* * * * * curl http://site/cron.php`

---

#### 10. Email Helper
```php
// Usage:
zed_mail([
    'to' => 'user@example.com',
    'subject' => 'Welcome!',
    'body' => '<h1>Hello</h1>',
    'html' => true,
]);
```

**Implementation Notes:**
- Wrapper around `mail()` or SMTP library
- Template support optional
- Event: `zed_mail_send` for addons to modify

---

## Implementation Priority

| # | Feature | Lines Est. | Impact |
|---|---------|------------|--------|
| 1 | Shortcodes | ~80 | HIGH — Content embedding |
| 2 | Admin Settings API | ~150 | HIGH — Every addon needs this |
| 3 | AJAX Handler | ~50 | HIGH — Forms, interactions |
| 4 | Admin Notices | ~30 | MEDIUM — User feedback |
| 5 | Metabox API | ~100 | MEDIUM — Custom fields |
| 6 | Script Enqueue | ~50 | MEDIUM — Proper assets |
| 7 | Nonce Helpers | ~40 | MEDIUM — Security |
| 8 | Transients | ~40 | LOW — Performance |
| 9 | Cron | ~80 | LOW — Background tasks |
| 10 | Email | ~30 | LOW — Notifications |

---

## Implementation Checklist

### Phase 1: Core APIs ☐
- [ ] **Shortcode System** — `zed_register_shortcode()`, parse in content render
- [ ] **Admin Settings API** — `zed_register_addon_settings()`, auto-generate UI
- [ ] **AJAX Handler Pattern** — `zed_register_ajax()`, clean route pattern
- [ ] **Admin Notices** — `zed_add_notice()`, session-based flash messages

### Phase 2: Editor Integration ☐
- [ ] **Metabox API** — `zed_register_metabox()`, sidebar fields in editor
- [ ] **Script/Style Enqueue** — `zed_enqueue_script()`, proper dependency loading
- [ ] **Nonce/CSRF Helpers** — `zed_create_nonce()`, `zed_verify_nonce()`

### Phase 3: Advanced ☐
- [ ] **Transients/Cache** — `zed_set_transient()`, `zed_get_transient()`
- [ ] **Cron/Scheduled Tasks** — `zed_schedule_event()`, `/cron.php` endpoint
- [ ] **Email Helper** — `zed_mail()`, template support

---

## Files to Modify

| Feature | Target File |
|---------|-------------|
| Shortcodes | `frontend_addon.php` or `frontend/helpers_shortcodes.php` |
| Admin Settings API | `admin_addon.php` + new partial |
| AJAX Handler | `admin_addon.php` or `frontend_addon.php` |
| Admin Notices | `admin_addon.php` + `admin-layout.php` |
| Metabox API | `admin_addon.php` + `editor.php` |
| Script Enqueue | `frontend_addon.php` |
| Nonce Helpers | `admin_addon.php` or new `helpers_security.php` |
| Transients | New `frontend/helpers_cache.php` |
| Cron | New `cron.php` + `admin_addon.php` |
| Email | New `frontend/helpers_email.php` |

---

## Command to Resume

```
Implement Phase 1 of ADDON_DX_ROADMAP.md
```

Or specific items:
```
Implement Shortcodes and Admin Settings API from ADDON_DX_ROADMAP.md
```

