# Database Interactions

Zed CMS provides a singleton `Core\Database` class primarily powered by **PDO**.

## Getting the Instance

```php
use Core\Database;

$db = Database::getInstance();
```

## Running Queries

### Select All (`query`)

Returns an array of associative arrays.

```php
$users = $db->query("SELECT * FROM users WHERE role = :role", [
    'role' => 'admin'
]);
```

### Select One (`queryOne`)

Returns a single associative array or `false`.

```php
$post = $db->queryOne("SELECT * FROM zed_content WHERE id = :id", [
    'id' => 1
]);
```

### Insert / Update / Delete

The `query` method handles these too. It returns the `PDOStatement`, but for convenience, use helper methods if available or checking `rowCount()`.

```php
$db->query("UPDATE zed_options SET option_value = :val WHERE option_name = :name", [
    'val' => 'New Site Title',
    'name' => 'site_name'
]);
```

## Creating Tables (Migrations)

Zed does not have a formal migration CLI yet. For addons, verify your table exists at the top of your addon file.

```php
// Check if table exists (simplified)
try {
    $db->query("SELECT 1 FROM my_addon_table LIMIT 1");
} catch (PDOException $e) {
    // Table doesn't exist, create it
    $sql = "CREATE TABLE my_addon_table (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255)
    )";
    $db->query($sql);
}
```
