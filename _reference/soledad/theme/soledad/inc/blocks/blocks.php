<?php

require_once __DIR__ . '/class-penci-library-source.php';
require_once __DIR__ . '/class-penci-library.php';

add_action('init', function () {
    if (defined('ELEMENTOR_VERSION')) {
        new Penci_Library;
    }
});
