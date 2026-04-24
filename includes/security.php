<?php
/**
 * Security Helper Functions
 * Provides additional security utilities for the application
 */

/**
 * Sanitize output to prevent XSS attacks
 * @param string $data The data to sanitize
 * @return string Sanitized data
 */
function sanitize_output($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Validate and sanitize email
 * @param string $email Email to validate
 * @return bool|string Returns false if invalid, sanitized email if valid
 */
function validate_email($email) {
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : false;
}

/**
 * Validate and sanitize URL
 * @param string $url URL to validate
 * @return bool|string Returns false if invalid, sanitized URL if valid
 */
function validate_url($url) {
    $url = filter_var($url, FILTER_SANITIZE_URL);
    return filter_var($url, FILTER_VALIDATE_URL) ? $url : false;
}

/**
 * Check if user is authenticated
 * @param string $required_role Required user role (admin, student_int, student_place, company)
 * @return bool True if authenticated and has required role
 */
function is_authenticated($required_role = null) {
    if (!isset($_SESSION['user'])) {
        return false;
    }
    
    if ($required_role !== null && $_SESSION['user'] !== $required_role) {
        return false;
    }
    
    return true;
}

/**
 * Require authentication - redirect if not authenticated
 * @param string $required_role Required user role
 * @param string $redirect_url URL to redirect to if not authenticated
 */
function require_auth($required_role = null, $redirect_url = 'svnit.php') {
    if (!is_authenticated($required_role)) {
        header("Location: $redirect_url");
        exit;
    }
}

/**
 * Rate limiting helper (simple implementation)
 * @param string $identifier Unique identifier (e.g., user ID, IP)
 * @param int $max_attempts Maximum attempts allowed
 * @param int $time_window Time window in seconds
 * @return bool True if allowed, false if rate limited
 */
function check_rate_limit($identifier, $max_attempts = 5, $time_window = 300) {
    $key = 'rate_limit_' . md5($identifier);
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['attempts' => 1, 'first_attempt' => time()];
        return true;
    }
    
    $data = $_SESSION[$key];
    
    // Reset if time window has passed
    if (time() - $data['first_attempt'] > $time_window) {
        $_SESSION[$key] = ['attempts' => 1, 'first_attempt' => time()];
        return true;
    }
    
    // Check if max attempts exceeded
    if ($data['attempts'] >= $max_attempts) {
        return false;
    }
    
    // Increment attempts
    $_SESSION[$key]['attempts']++;
    return true;
}

/**
 * Log security events
 * @param string $event Event description
 * @param array $data Additional data to log
 */
function log_security_event($event, $data = []) {
    $log_file = 'security_log.txt';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    
    $log_entry = "[$timestamp] IP: $ip | Event: $event | Data: " . json_encode($data) . " | UA: $user_agent\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}

?>

