<?php
/**
 * Central Configuration File
 * Loads configuration from environment variables or .env file
 * 
 * Priority:
 * 1. System environment variables (highest priority)
 * 2. .env file (loaded via load_env.php)
 * 3. Default values (lowest priority)
 */

// Load .env file if it exists (before reading environment variables)
if (file_exists(__DIR__ . '/load_env.php')) {
    require_once __DIR__ . '/load_env.php';
}

// Database Configuration
define('DB_HOST', getenv('TNP_DB_HOST') ?: 'localhost');
define('DB_USER', getenv('TNP_DB_USER') ?: 'root');
define('DB_PASS', getenv('TNP_DB_PASS') ?: '');
define('DB_NAME', getenv('TNP_DB_NAME') ?: 'placement');

// Email Configuration - SMTP Settings
define('SMTP_EMAIL', getenv('TNP_SMTP_EMAIL') ?: 'your-email@gmail.com');
define('SMTP_PASS', getenv('TNP_SMTP_PASS') ?: '');
define('SMTP_HOST', getenv('TNP_SMTP_HOST') ?: 'smtp.gmail.com');
define('SMTP_PORT', (int)(getenv('TNP_SMTP_PORT') ?: 587));
define('SMTP_SECURE', getenv('TNP_SMTP_SECURE') ?: 'tls');

// Email Sender Information
define('EMAIL_FROM_NAME', getenv('TNP_EMAIL_FROM_NAME') ?: 'T&P SVNIT');
define('EMAIL_FROM_EMAIL', getenv('TNP_EMAIL_FROM_EMAIL') ?: SMTP_EMAIL);

// Application Configuration
define('BASE_URL', getenv('TNP_BASE_URL') ?: null);
define('APP_NAME', getenv('TNP_APP_NAME') ?: 'Training & Placement Portal');

// Security Configuration
define('SESSION_TIMEOUT', (int)(getenv('TNP_SESSION_TIMEOUT') ?: 1800)); // 30 minutes
define('CSRF_EXPIRY', (int)(getenv('TNP_CSRF_EXPIRY') ?: 3600)); // 1 hour

// File Upload Configuration
define('MAX_UPLOAD_SIZE', (int)(getenv('TNP_MAX_UPLOAD_SIZE') ?: 524288)); // 512KB
define('ALLOWED_IMAGE_TYPES', explode(',', getenv('TNP_ALLOWED_IMAGE_TYPES') ?: 'image/png,image/jpeg,image/jpg'));

// Environment Mode
define('ENVIRONMENT', getenv('TNP_ENVIRONMENT') ?: 'development');
define('DISPLAY_ERRORS', (bool)(getenv('TNP_DISPLAY_ERRORS') ?: (ENVIRONMENT === 'development')));

// Set error reporting based on environment
if (ENVIRONMENT === 'production') {
    error_reporting(0);
    ini_set('display_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', DISPLAY_ERRORS ? 1 : 0);
}

/**
 * Create and return a mysqli connection using the central config.
 * Caller should check for errors.
 * @return mysqli
 */
function db_connect()
{
    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($db->connect_errno) {
        // In production you might want to log this instead of echoing.
        error_log('DB connect error: ' . $db->connect_error);
    }
    // Set charset
    $db->set_charset('utf8mb4');
    return $db;
}

/**
 * Send email via SMTP (Gmail)
 * @param string $to recipient email
 * @param string $subject email subject
 * @param string $body email body (HTML)
 * @param string $from sender email
 * @return array ['success' => bool, 'message' => string]
 */
function send_email($to, $subject, $body, $from = null)
{
    if ($from === null) {
        $from = SMTP_EMAIL;
    }
    
    try {
        require_once __DIR__ . '/../lib/class.smtp.php';
        require_once __DIR__ . '/../lib/class.phpmailer.php';
        
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        
        // Use SMTP configuration from environment
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_EMAIL;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        
        $mail->Timeout = 15;
        $mail->ConnectTimeout = 15;
        $mail->setFrom($from ?: EMAIL_FROM_EMAIL, EMAIL_FROM_NAME);
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = strip_tags($body);
        
        if ($mail->send()) {
            return ['success' => true, 'message' => 'Email sent successfully'];
        } else {
            return ['success' => false, 'message' => 'Mailer Error: ' . $mail->ErrorInfo];
        }
    } catch (Exception $e) {
        error_log('Email Exception: ' . $e->getMessage());
        
        // Fallback: Store email in a log file for development/testing
        $email_log = 'email_log.txt';
        $log_entry = "[" . date('Y-m-d H:i:s') . "] TO: $to | SUBJECT: $subject\n";
        $log_entry .= "BODY:\n$body\n";
        $log_entry .= "---\n\n";
        file_put_contents($email_log, $log_entry, FILE_APPEND);
        
        return [
            'success' => true,
            'message' => 'Password reset link created. Email logged locally. For production, set `TNP_SMTP_EMAIL` and `TNP_SMTP_PASS` (Gmail App Password).' 
        ];
    }
}

?>
