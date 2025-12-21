# Tutorial: Build an Analytics Addon

Let's build a real-world addon that tracks page views and displays them in the footer.

## Goal
1.  Intercept every page request.
2.  Increment a counter in the database.
3.  Display "Views: X" in the footer.

## Step 1: Create the Addon File

Create `content/addons/simple_analytics.php`.

```php
<?php
/**
 * Simple Analytics Addon
 */

use Core\Event;
use Core\Database;

// 1. Create Table on Load (Quick & Dirty Migration)
$db = Database::getInstance();
try {
    $db->query("SELECT 1 FROM analytics LIMIT 1");
} catch (Exception $e) {
    $db->query("CREATE TABLE analytics (
        path VARCHAR(255) PRIMARY KEY,
        views INT DEFAULT 0
    )");
}

// 2. Track Views
Event::on('route_request', function($request) {
    $uri = $request['uri'];
    
    // Ignore Admin & API
    if (str_starts_with($uri, '/admin')) return;
    
    $db = Database::getInstance();
    $db->query(
        "INSERT INTO analytics (path, views) VALUES (:path, 1) 
         ON DUPLICATE KEY UPDATE views = views + 1",
        ['path' => $uri]
    );
});

// 3. Display in Footer
Event::on('zed_footer', function() {
    $uri = $_SERVER['REQUEST_URI'];
    $db = Database::getInstance();
    $row = $db->queryOne("SELECT views FROM analytics WHERE path = :path", ['path' => $uri]);
    $views = $row['views'] ?? 0;
    
    echo "<div style='text-align:center; padding: 20px; color: #666;'>
            This page has been viewed <strong>{$views}</strong> times.
          </div>";
});
```

## Step 2: Test It

1.  Refresh your homepage.
2.  Scroll to the bottom.
3.  See "This page has been viewed 1 times."
4.  Refresh again. It increments!

## What did we learn?
*   **Auto-migration:** We checked for the table and created it if missing.
*   **Event Listeners:** We used `route_request` for logic and `zed_footer` for display.
*   **Database:** We used `ON DUPLICATE KEY UPDATE` to simplify logic.
