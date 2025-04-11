<?php
// Database configuration with caching
class DatabaseConfig {
    private static $cachedConfig = null;
    private static $isFetching = false;
    private static $fetchPromise = null;

    public static function getConfig() {
        // Return cached config if available
        if (self::$cachedConfig !== null) {
            return self::$cachedConfig;
        }

        // If already fetching, return the promise
        if (self::$isFetching && self::$fetchPromise !== null) {
            return self::$fetchPromise;
        }

        // First time fetch
        self::$isFetching = true;
        self::$fetchPromise = self::fetchConfig();
        
        return self::$fetchPromise;
    }

    private static function fetchConfig() {
        try {
            // Try multiple configuration sources
            $sources = [
                'local' => [
                    'host' => 'localhost',
                    'dbname' => 'search_app',
                    'username' => 'root',
                    'password' => '',
                    'charset' => 'utf8mb4'
                ],
                'fallback' => [
                    'host' => 'backup.db.example.com',
                    'dbname' => 'search_app',
                    'username' => 'fallback_user',
                    'password' => 'fallback_pass',
                    'charset' => 'utf8mb4'
                ]
            ];

            // Try local first, then fallback
            foreach ($sources as $source => $config) {
                try {
                    // In a real implementation, you would test the connection here
                    self::$cachedConfig = $config;
                    return $config;
                } catch (Exception $e) {
                    error_log("Failed to use $source database config: " . $e->getMessage());
                    continue;
                }
            }

            throw new Exception("All database configuration sources failed");
        } catch (Exception $e) {
            error_log("Database config error: " . $e->getMessage());
            // Return safe defaults
            return [
                'host' => 'localhost',
                'dbname' => 'search_app',
                'username' => '',
                'password' => '',
                'charset' => 'utf8mb4'
            ];
        } finally {
            self::$isFetching = false;
        }
    }
}

// Return the configuration
return DatabaseConfig::getConfig();
