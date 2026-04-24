<?php
// Credential shim. Prefer environment variables or `config.php` in production.
if (defined('SMTP_EMAIL') && defined('SMTP_PASS')) {
	define('EMAIL', SMTP_EMAIL);
	define('PASS', SMTP_PASS);
} else {
	define('EMAIL', getenv('TNP_SMTP_EMAIL') ?: 'your-email@example.com');
	define('PASS', getenv('TNP_SMTP_PASS') ?: 'your-smtp-password');
}
?>