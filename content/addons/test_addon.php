<?php

declare(strict_types=1);

/**
 * Test Addon - Proof of Concept
 * 
 * Demonstrates the event-driven architecture of Zed CMS.
 * Listen to 'route_request' and claim the /hello URL.
 */

use Core\Event;
use Core\Router;

// Register our listener for route requests
Event::on('route_request', function (array $request): void {
    // Check if this is our URL
    if ($request['uri'] === '/hello') {
        // We own this URL! Return our message.
        Router::setHandled('Antigravity CMS is Alive');
    }
}, 10);
