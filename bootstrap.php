<?php
/**
 * Bootstrap File - Sets up paths and autoloading
 * Include this file at the top of all PHP files
 */

// Define base directory
define('BASE_DIR', __DIR__);

// Define path constants
define('CONFIG_DIR', BASE_DIR . '/config');
define('INCLUDES_DIR', BASE_DIR . '/includes');
define('LIB_DIR', BASE_DIR . '/lib');
define('ASSETS_DIR', BASE_DIR . '/assets');
define('IMAGES_DIR', ASSETS_DIR . '/images');

// Set include path
set_include_path(get_include_path() . PATH_SEPARATOR . BASE_DIR . PATH_SEPARATOR . CONFIG_DIR . PATH_SEPARATOR . INCLUDES_DIR . PATH_SEPARATOR . LIB_DIR);

/**
 * Helper function to require config files
 */
function require_config($file) {
    require_once CONFIG_DIR . '/' . $file;
}

/**
 * Helper function to require include files
 */
function require_include($file) {
    require_once INCLUDES_DIR . '/' . $file;
}

/**
 * Helper function to get asset URL
 */
function asset_url($path) {
    return '/' . str_replace('\\', '/', str_replace(BASE_DIR, '', ASSETS_DIR)) . '/' . ltrim($path, '/');
}

/**
 * Helper function to get image URL
 */
function image_url($filename) {
    return asset_url('images/' . $filename);
}

// Load configuration
require_config('config.php');
require_config('server.php');

// Load security includes
require_include('csrf.php');

?>

