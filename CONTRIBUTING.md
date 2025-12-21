# Contributing to Zed CMS

Thank you for your interest in contributing to **Zed CMS**. We are building a high-performance, event-driven alternative to legacy systems.

To maintain the speed and simplicity of the platform, we adhere to strict architectural standards. Please read this guide carefully before submitting a Pull Request.

---

## 🚀 The 'Antigravity' Standard

Zed CMS is a **Micro-Kernel** system. The core (`/core`) is sacred and immutable.

*   **Do NOT add features to the Core.** The Core is only for the event loop, routing dispatch, and DB connection.
*   **Do NOT add heavy dependencies.** We aim close to "Zero dependencies".
*   **DO implement features as Addons.** All new functionality (Admin pages, API endpoints, Frontend logic) must reside in `content/addons/`.

**If your PR increases the weight of `/core` without a critical architectural reason, it will be rejected.**

---

## 👨‍💻 Code Style & Standards

We use modern PHP standards. No legacy code allowed.

### 1. PHP Version
*   Target **PHP 8.2+** exclusively.
*   Use modern features like `readonly` classes, `match` expressions, and typed properties.

### 2. Strict Types
Every new PHP file must start with:
```php
<?php
declare(strict_types=1);
```

### 3. Database Interactions
*   **No external ORMs.** (No Eloquent, No Doctrine).
*   Use the `Core\Database` singleton wrapper.
*   Always use **Prepared Statements** to prevent SQL injection.

```php
// ✅ Correct
$db->query("SELECT * FROM users WHERE id = :id", ['id' => 1]);

// ❌ Rejected
$db->query("SELECT * FROM users WHERE id = " . $id);
```

### 4. Event-Driven Architecture
Never hardcode routes. Hook into the `route_request` event.

```php
Event::on('route_request', function($request) {
    if ($request['uri'] === '/my-feature') {
         // Logic...
         Router::setHandled();
    }
});
```

---

## 🛠 Development Workflow

1.  **Fork** the repository and clone it locally.
2.  **Branch** off `main` for your feature: `git checkout -b feature/my-awesome-addon`.
3.  **Install** dependencies (if touching the editor):
    ```bash
    cd _frontend
    npm install
    npm run build
    ```
4.  **Setup** your local database using `install.php`.

---

## 🔐 RBAC Awareness

If you are building an Admin feature, you **MUST** respect the Capability system.

*   Wrap sensitive logic in `zed_current_user_can()`.
*   Never assume `Auth::check()` is enough for administrative actions.

```php
if (!zed_current_user_can('manage_settings')) {
    Router::redirect('/admin/login');
}
```

---

## 📝 Documentation Requirement

**Code is nothing without docs.**

If you add a feature, you must add a corresponding Markdown file to the internal Wiki (`content/docs/`).
*   **Theme API?** Update `content/docs/06-theme-development/`.
*   **New Hook?** Update `content/docs/03-developer-api/hooks.md`.

Your PR will not be merged until the internal documentation reflects your changes.

---

**Happy Coding! Let's keep it light.** 
