# Users & Roles (RBAC)

Zed CMS uses a Role-Based Access Control (RBAC) system to secure the admin panel.

## Default Roles

| Role | Permissions |
|------|-------------|
| **Administrator** | Can do everything. Manage settings, users, plugins, themes. |
| **Editor** | Can edit, publish, and delete ANY post/page. Can manage categories and menus. |
| **Author** | Can create, edit, and publish THEIR OWN posts. Cannot touch others' content. |
| **Subscriber** | Can only manage their profile (Profile page coming soon). Read-only otherwise. |

## Capabilities Matrix

| Capability | Admin | Editor | Author | Subscriber |
|------------|-------|--------|--------|------------|
| `view_dashboard` | ✅ | ✅ | ✅ | ❌ |
| `edit_content` | ✅ | ✅ | ✅ | ❌ |
| `publish_content`| ✅ | ✅ | ✅ | ❌ |
| `delete_others_content` | ✅ | ✅ | ❌ | ❌ |
| `manage_categories` | ✅ | ✅ | ❌ | ❌ |
| `upload_media` | ✅ | ✅ | ✅ | ❌ |
| `manage_users` | ✅ | ❌ | ❌ | ❌ |
| `manage_settings`| ✅ | ❌ | ❌ | ❌ |

## Developer API

### Checking Permissions

Use `zed_current_user_can($capability)` to protect your addon pages.

```php
if (!zed_current_user_can('manage_settings')) {
    die("Access Denied");
}
```

### Checking Ownership

The system automatically checks ownership for Authors.
*   **Editor/Admin:** `delete_content` with ID 5 -> Allowed.
*   **Author:** `delete_content` with ID 5 -> Implementation checks if `author_id == current_user_id`.

## Creating Users

Go to **Users** in the sidebar.
1.  Click **Add User**.
2.  Enter Email, Password, and Role.
3.  The system autogenerates a hash using `password_hash($pw, PASSWORD_DEFAULT)`.
