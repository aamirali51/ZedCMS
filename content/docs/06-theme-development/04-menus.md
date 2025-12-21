# Mastering Navigation Menus

Zed CMS provides a powerful, unopinionated menu system. You build the structure in the Admin Panel (`Visual Menu Builder`), and the theme renders it.

## 1. The `zed_menu()` Helper

This is the primary function to render navigation.

```php
<?= zed_menu('Main Menu', [
    'class' => 'navbar-nav',
    'id' => 'primary-menu'
]) ?>
```

### Arguments

1.  **$name_or_id** (string|int): The exact name of the menu created in Admin (case-insensitive) OR its ID.
2.  **$options** (array):
    *   `class`: CSS class for the `<ul>`. Default: `zed-menu`.
    *   `id`: CSS ID for the `<ul>`.
    *   `depth`: (Coming soon) Limit how many levels deep to render.

## 2. Handling Dropdowns

The helper automatically creates nested `<ul>`s for sub-items.

**HTML Output:**
```html
<ul class="navbar-nav">
    <li><a href="/">Home</a></li>
    <li class="has-children">
        <a href="/services">Services</a>
        <ul class="sub-menu">
            <li><a href="/web">Web Design</a></li>
            <li><a href="/print">Print</a></li>
        </ul>
    </li>
</ul>
```

### CSS for Dropdowns

You need to style the `.sub-menu` class. Here is a modern CSS boilerplate:

```css
/* Base Menu */
.navbar-nav {
    display: flex;
    gap: 20px;
    list-style: none;
    padding: 0;
}

/* Parent Item */
.navbar-nav li {
    position: relative;
}

/* Sub Menu Hidden by default */
.navbar-nav .sub-menu {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    min-width: 200px;
    background: white;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    padding: 10px 0;
    z-index: 50;
}

/* Show on Hover */
.navbar-nav li:hover > .sub-menu {
    display: block;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .navbar-nav {
        flex-direction: column;
    }
    .navbar-nav .sub-menu {
        position: static; /* Stack vertically on mobile */
        box-shadow: none;
        padding-left: 20px;
    }
}
```

## 3. Fallback Logic

What if the user hasn't created a "Main Menu" yet? You should provide a fallback.

```php
<?php
// Try to get specific menu
$menuHtml = zed_menu('Main Menu', ['class' => 'my-nav']);

// If empty, try the FIRST available menu
if (empty($menuHtml)) {
    $menuHtml = zed_primary_menu(['class' => 'my-nav']);
}

// If still empty, hardcode links
if ($menuHtml) {
    echo $menuHtml;
} else {
    echo '<ul class="my-nav"><li><a href="/">Home</a></li></ul>';
}
?>
```

## 4. Mobile Menu Toggle (JavaScript)

Since Zed is unopinionated, you write the toggle logic.

```html
<button id="menu-toggle">Menu</button>
<nav id="site-navigation" class="hidden">
    <?= zed_menu('Main Menu') ?>
</nav>

<script>
    document.getElementById('menu-toggle').addEventListener('click', () => {
        document.getElementById('site-navigation').classList.toggle('hidden');
    });
</script>
```
