<?php

declare(strict_types=1);

namespace Core;

/**
 * Zero CMS Micro-Kernel Application
 * 
 * The core application class. In a micro-kernel architecture,
 * this class does minimal work—dispatching events to addons/plugins.
 */
final class App
{
    /**
     * The singleton instance.
     */
    private static ?App $instance = null;

    /**
     * Create a new App instance.
     *
     * @param array<string, mixed> $config The application configuration.
     */
    public function __construct(
        private readonly array $config = []
    ) {
        self::$instance = $this;
    }

    /**
     * Get the application instance.
     *
     * @return self|null
     */
    public static function getInstance(): ?self
    {
        return self::$instance;
    }

    /**
     * Get a configuration value.
     *
     * @param string $key     Dot-notation key (e.g., 'database.host').
     * @param mixed  $default Default value if key not found.
     * @return mixed
     */
    public function config(string $key, mixed $default = null): mixed
    {
        $keys = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $k) {
            if (!is_array($value) || !array_key_exists($k, $value)) {
                return $default;
            }
            $value = $value[$k];
        }

        return $value;
    }

    /**
     * Run the application.
     *
     * This is the entry point for the micro-kernel.
     * It bootstraps core services and dispatches control to addons.
     *
     * @return void
     */
    public function run(): void
    {
        // 1. Fire the 'app_init' event - addons can register hooks here
        Event::trigger('app_init', $this);

        // 2. Store database config for lazy initialization (only connects when needed)
        if ($dbConfig = $this->config('database')) {
            Database::setConfig($dbConfig);
        }

        // 3. Run pending migrations (safe upgrade system)
        // Only executes migrations that haven't run yet, tracked in zed_options
        try {
            Migrations::run();
        } catch (\Exception $e) {
            // Silently fail - don't break the app if migrations fail
            // Errors are logged in the migration system
        }

        // 4. Check remember me cookie for persistent login
        Auth::checkRememberCookie();

        // 5. Fire the 'app_ready' event - system is ready
        Event::trigger('app_ready', $this);

        // 6. Dispatch the route - let addons handle the request
        $uri = Router::getCurrentUri();
        $method = Router::getCurrentMethod();
        $response = Router::dispatch($uri, $method);

        // 7. Output the response
        if ($response !== null) {
            // Allow filtering the final output
            $response = Event::filter('app_output', $response);
            echo $response;
        }

        // 8. Fire the 'app_shutdown' event - cleanup
        Event::trigger('app_shutdown', $this);
    }
}
