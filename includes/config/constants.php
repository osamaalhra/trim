<?php
/**
 * ملف الثوابت لنظام TRMS
 * جميع الثوابت والإعدادات العامة للنظام
 */

// منع الوصول المباشر
if (!defined('TRMS_ACCESS')) {
    header('HTTP/1.0 403 Forbidden');
    die('Direct access not allowed');
}

// إصدار النظام
define('TRMS_VERSION', '1.0.0');
define('TRMS_BUILD', '2024.01.001');

// مسارات النظام
define('ROOT_PATH', dirname(dirname(__FILE__)));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');
define('CACHE_PATH', ROOT_PATH . '/cache');
define('LOG_PATH', ROOT_PATH . '/logs');

// إعدادات الجلسة
define('SESSION_NAME', 'TRMS_SESSION');
define('SESSION_LIFETIME', 7200); // ثانية (ساعتين)
define('SESSION_REGENERATE', 300); // ثانية (5 دقائق)

// أمان النظام
define('ENCRYPTION_KEY', 'TRMS_SECURE_KEY_2024_CHANGE_IN_PRODUCTION');
define('CSRF_TOKEN_NAME', 'trms_csrf_token');
define('CSRF_TOKEN_LIFETIME', 3600); // ثانية

// قيود النظام
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10MB
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900); // ثانية (15 دقيقة)
define('API_RATE_LIMIT', 100); // طلبات في الساعة

// إعدادات البريد الإلكتروني
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'noreply@trms.com');
define('SMTP_PASS', 'password');
define('SMTP_SECURE', 'tls');

// إعدادات التطبيق
define('APP_NAME', 'TRMS - نظام إدارة الإيرادات');
define('APP_DESCRIPTION', 'نظام متكامل لإدارة المهام وتتبع الإيرادات');
define('APP_KEYWORDS', 'مهام, إدارة, إيرادات, تخطيط, تتبع');
define('APP_AUTHOR', 'فريق TRMS');
define('APP_LANG', 'ar');
define('APP_TIMEZONE', 'Asia/Riyadh');
define('APP_CURRENCY', 'ريال');
define('APP_DATE_FORMAT', 'Y/m/d');
define('APP_TIME_FORMAT', 'H:i');
define('APP_DATETIME_FORMAT', 'Y/m/d H:i');

// ألوان النظام
define('COLOR_PRIMARY', '#4361ee');
define('COLOR_SECONDARY', '#7209b7');
define('COLOR_SUCCESS', '#4cc9f0');
define('COLOR_WARNING', '#f72585');
define('COLOR_DANGER', '#ff595e');
define('COLOR_INFO', '#3a0ca3');
define('COLOR_LIGHT', '#f8f9fa');
define('COLOR_DARK', '#212529');

// روابط النظام
define('BASE_URL', 'http://localhost/trms');
define('API_URL', BASE_URL . '/api');
define('ASSETS_URL', BASE_URL . '/assets');
define('UPLOADS_URL', BASE_URL . '/uploads');

// إعدادات قاعدة البيانات (يتم استيرادها من ملف database.php)
define('DB_PREFIX', 'trms_');

// إعدادات التخزين المؤقت
define('CACHE_ENABLED', true);
define('CACHE_TIME', 3600); // ثانية
define('CACHE_METHOD', 'file'); // file, redis, memcached

// إعدادات التسجيل
define('LOG_LEVEL', 'DEBUG'); // DEBUG, INFO, WARNING, ERROR
define('LOG_MAX_SIZE', 10 * 1024 * 1024); // 10MB
define('LOG_RETENTION_DAYS', 30);

// API Keys (تغييرها في الإنتاج)
define('GOOGLE_API_KEY', 'YOUR_GOOGLE_API_KEY');
define('RECAPTCHA_SITE_KEY', 'YOUR_RECAPTCHA_SITE_KEY');
define('RECAPTCHA_SECRET_KEY', 'YOUR_RECAPTCHA_SECRET_KEY');

// إعدادات المطور
define('DEBUG_MODE', true);
define('MAINTENANCE_MODE', false);
define('SHOW_ERRORS', DEBUG_MODE);
define('LOG_QUERIES', DEBUG_MODE);

// تهيئة المنطقة الزمنية
date_default_timezone_set(APP_TIMEZONE);

// تهيئة الجلسة
session_name(SESSION_NAME);
session_set_cookie_params([
    'lifetime' => SESSION_LIFETIME,
    'path' => '/',
    'domain' => '',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Strict'
]);

if (!session_id()) {
    session_start();
}

// التحقق من وضع الصيانة
if (MAINTENANCE_MODE && !isset($_SESSION['admin'])) {
    header('HTTP/1.1 503 Service Unavailable');
    include(ROOT_PATH . '/maintenance.php');
    exit;
}

// إعدادات عرض الأخطاء
if (SHOW_ERRORS) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
}

// إنشاء المجلدات إذا لم تكن موجودة
$folders = [UPLOAD_PATH, CACHE_PATH, LOG_PATH];
foreach ($folders as $folder) {
    if (!file_exists($folder)) {
        mkdir($folder, 0755, true);
    }
}

// وظائف مساعدة
function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

function is_post() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function is_get() {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

function redirect($url, $permanent = false) {
    if ($permanent) {
        header('HTTP/1.1 301 Moved Permanently');
    }
    header('Location: ' . $url);
    exit;
}

function json_response($data, $status = 200) {
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

function csrf_token() {
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        $_SESSION[CSRF_TOKEN_NAME . '_time'] = time();
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

function validate_csrf($token) {
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        return false;
    }
    
    if ($_SESSION[CSRF_TOKEN_NAME] !== $token) {
        return false;
    }
    
    if (time() - $_SESSION[CSRF_TOKEN_NAME . '_time'] > CSRF_TOKEN_LIFETIME) {
        unset($_SESSION[CSRF_TOKEN_NAME], $_SESSION[CSRF_TOKEN_NAME . '_time']);
        return false;
    }
    
    return true;
}

function generate_random_string($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function format_currency($amount) {
    return number_format($amount, 2) . ' ' . APP_CURRENCY;
}

function format_date($date, $format = null) {
    if (!$format) {
        $format = APP_DATE_FORMAT;
    }
    return date($format, strtotime($date));
}

function format_datetime($datetime, $format = null) {
    if (!$format) {
        $format = APP_DATETIME_FORMAT;
    }
    return date($format, strtotime($datetime));
}

function sanitize_input($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = sanitize_input($value);
        }
        return $data;
    }
    
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function log_message($level, $message, $context = []) {
    $levels = ['DEBUG', 'INFO', 'WARNING', 'ERROR'];
    $current_level = array_search(LOG_LEVEL, $levels);
    $message_level = array_search($level, $levels);
    
    if ($message_level < $current_level) {
        return;
    }
    
    $log_entry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'level' => $level,
        'message' => $message,
        'context' => $context,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        'script' => $_SERVER['PHP_SELF'] ?? 'unknown'
    ];
    
    $log_file = LOG_PATH . '/app_' . date('Y-m-d') . '.log';
    $log_line = json_encode($log_entry, JSON_UNESCAPED_UNICODE) . PHP_EOL;
    
    file_put_contents($log_file, $log_line, FILE_APPEND | LOCK_EX);
}

// تحميل المكتبات المطلوبة تلقائياً
spl_autoload_register(function ($class_name) {
    $directories = [
        ROOT_PATH . '/includes/',
        ROOT_PATH . '/models/',
        ROOT_PATH . '/controllers/',
        ROOT_PATH . '/libraries/'
    ];
    
    foreach ($directories as $directory) {
        $file = $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// تهيئة النظام
log_message('INFO', 'System initialized', ['version' => TRMS_VERSION]);
?>