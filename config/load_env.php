<?php
/**
 * Environment Variable Loader
 * Loads variables from .env file into PHP environment
 * 
 * Usage: require_once 'load_env.php'; (before config.php)
 */

if (!function_exists('load_env_file')) {
    /**
     * Load environment variables from .env file
     * @param string $file_path Path to .env file
     * @return bool True if file loaded successfully
     */
    function load_env_file($file_path = __DIR__ . '/.env') {
        if (!file_exists($file_path)) {
            // Try .env.example as fallback
            $example_path = __DIR__ . '/env.example';
            if (file_exists($example_path)) {
                error_log("Warning: .env file not found. Using env.example as template. Please create .env file.");
                return false;
            }
            return false;
        }

        $lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Parse KEY=VALUE format
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // Remove quotes if present
                if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                    (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                    $value = substr($value, 1, -1);
                }

                // Set environment variable if not already set
                if (!getenv($key)) {
                    putenv("$key=$value");
                    $_ENV[$key] = $value;
                    $_SERVER[$key] = $value;
                }
            }
        }

        return true;
    }
}

// Auto-load .env file when this file is included
load_env_file();

?>

