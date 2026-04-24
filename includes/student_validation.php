<?php
/**
 * Student Registration Validation Utilities
 * Provides validation functions for improved security and UX
 */

/**
 * Validate password strength
 * Requirements: min 8 chars, at least 1 uppercase, 1 lowercase, 1 number
 * @param string $password Password to validate
 * @return array ['valid' => bool, 'errors' => array of specific issues]
 */
function validate_password_strength($password)
{
    $errors = [];
    
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain at least one uppercase letter (A-Z)";
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must contain at least one lowercase letter (a-z)";
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must contain at least one number (0-9)";
    }
    
    return [
        'valid' => count($errors) === 0,
        'errors' => $errors
    ];
}

/**
 * Sanitize user input - trim whitespace and remove control characters
 * @param string $input Raw user input
 * @return string Sanitized input
 */
function sanitize_student_input($input)
{
    $input = trim($input);
    $input = preg_replace('/[\x00-\x1F\x7F]/', '', $input);
    return $input;
}

/**
 * Validate email format and sanitize
 * @param string $email Email to validate
 * @return array ['valid' => bool, 'email' => sanitized email]
 */
function validate_student_email($email)
{
    $email = sanitize_student_input($email);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $is_valid = filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    
    return [
        'valid' => $is_valid,
        'email' => $is_valid ? $email : ''
    ];
}

/**
 * Check if email already exists in student table
 * @param mysqli $db Database connection
 * @param string $email Email to check
 * @return bool True if exists
 */
function check_email_exists($db, $email)
{
    $stmt = $db->prepare("SELECT EMAIL FROM student WHERE EMAIL = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    return $exists;
}

/**
 * Check if student ID already exists
 * @param mysqli $db Database connection
 * @param string $student_id Student ID to check
 * @return bool True if exists
 */
function check_student_id_exists($db, $student_id)
{
    $stmt = $db->prepare("SELECT STUDENT_ID FROM student WHERE STUDENT_ID = ? LIMIT 1");
    $stmt->bind_param('s', $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    return $exists;
}

/**
 * Validate contact number (10 digits)
 * @param string $contact Contact number
 * @return bool True if valid
 */
function validate_contact_10digit($contact)
{
    $contact = preg_replace('/\D/', '', $contact);
    return strlen($contact) === 10;
}

/**
 * Validate percentage (0-100)
 * @param float $percentage Percentage value
 * @return bool True if valid
 */
function validate_percentage_range($percentage)
{
    $percentage = floatval($percentage);
    return $percentage >= 0 && $percentage <= 100;
}

/**
 * Validate CGPA (0-10)
 * @param float $cgpa CGPA value
 * @return bool True if valid
 */
function validate_cgpa_range($cgpa)
{
    $cgpa = floatval($cgpa);
    return $cgpa >= 0 && $cgpa <= 10;
}
?>
