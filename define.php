<?php
/** Central configuration for Library Table Booking & Payment System. Use environment variables in production. */
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_NAME', getenv('DB_NAME') ?: 'library_table_booking');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('BASE_URL', getenv('BASE_URL') ?: 'http://localhost/library-table-booking');
define('LIBRARY_EMAIL', getenv('LIBRARY_EMAIL') ?: 'library@example.com');
define('LIBRARY_EMAIL_PASSWORD', getenv('LIBRARY_EMAIL_PASSWORD') ?: 'change-me-app-password');
define('UPI_ID', getenv('UPI_ID') ?: 'library@upi');
define('PAYEE_NAME', getenv('PAYEE_NAME') ?: 'City Central Library');
define('RAZORPAY_KEY_ID', getenv('RAZORPAY_KEY_ID') ?: '');
define('RAZORPAY_KEY_SECRET', getenv('RAZORPAY_KEY_SECRET') ?: '');
define('CURRENCY', getenv('CURRENCY') ?: 'INR');
define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.gmail.com');
define('SMTP_PORT', (int)(getenv('SMTP_PORT') ?: 587));
define('QR_PROVIDER', getenv('QR_PROVIDER') ?: 'https://api.qrserver.com/v1/create-qr-code/');
define('BOOKING_PRICES', [30 => 800, 90 => 2200]);
define('APP_INSTALLED_FLAG', __DIR__ . '/.installed');
?>
