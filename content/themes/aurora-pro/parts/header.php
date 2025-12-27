<?php
/**
 * Aurora Pro - Header Part (Smart Selector)
 * 
 * This file checks if the Header Builder addon is active and has configuration.
 * If so, it renders the custom header from the builder.
 * Otherwise, it falls back to the default header template.
 *
 * @package AuroraPro
 */

declare(strict_types=1);

// Check if header builder addon is active and has configuration
if (function_exists('zed_has_header_builder') && zed_has_header_builder()) {
    // Header builder is configured - output body + builder header
    ?>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-sans antialiased min-h-screen flex flex-col">

    <!-- Skip to content for accessibility -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-primary text-white px-4 py-2 rounded-lg z-50">
        Skip to content
    </a>

    <?php 
    // Render the header builder output
    echo zed_render_header_builder(); 
    ?>

    <!-- Main Content Wrapper -->
    <main id="main-content" class="flex-1">
<?php
} else {
    // Fall back to default header
    require __DIR__ . '/header-default.php';
}
