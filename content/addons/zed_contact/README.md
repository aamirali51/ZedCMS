# Zed Contact — Contact Form Addon

A professional contact form addon for Zed CMS demonstrating the Addon DX APIs.

## Features

- ✅ **Shortcode System** — `[contact_form]` shortcode
- ✅ **AJAX Handler** — Secure form submission
- ✅ **Settings API** — Auto-generated settings page
- ✅ **Nonce Security** — CSRF protection
- ✅ **Email Helper** — HTML email support
- ✅ **Responsive Design** — Mobile-first Tailwind CSS

## Installation

1. Enable the addon in `/admin/addons`
2. Configure settings at `/admin/addon-settings/zed_contact`
3. Add `[contact_form]` to any page

## Usage

### Basic Shortcode
```
[contact_form]
```

### With Custom Class
```
[contact_form class="my-custom-class"]
```

## Settings

| Setting | Description | Default |
|---------|-------------|---------|
| **Send Emails To** | Recipient email address | admin@example.com |
| **Subject Prefix** | Prefix for email subjects | [Contact] |
| **Success Message** | Thank you message | Thank you! Your message... |
| **Button Text** | Submit button label | Send Message |
| **Send Copy** | Email copy to sender | Off |

## File Structure

```
zed_contact/
├── addon.php              ← Main addon file
├── assets/
│   └── contact-form.js    ← Frontend JavaScript
└── README.md              ← This file
```

## API Endpoints

- `POST /api/ajax/zed_contact_submit` — Form submission handler

## Developer Notes

This addon demonstrates:
- Folder-based addon structure
- Asset organization (JS, CSS, images)
- Settings API with multiple field types
- Shortcode registration and rendering
- AJAX handler with security
- Email sending with HTML templates

## License

MIT
