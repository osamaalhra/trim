<?php
session_start();
require_once 'config/database.php';
require_once 'auth.php';

// التحقق من أن الطريقة POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

// تنظيف المدخلات
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

// التحقق من البيانات
if (empty($username) || empty($password)) {
    $_SESSION['error'] = 'يرجى إدخال اسم المستخدم وكلمة المرور';
    header('Location: ../login.php');
    exit;
}

// إنشاء كائن المصادقة
$auth = new Authentication($conn);

// محاولة تسجيل الدخول
$result = $auth->login($username, $password);

if ($result['success']) {
    // إذا اختار المستخدم "تذكرني"
    if ($remember) {
        setcookie('trms_remember', session_id(), time() + (30 * 24 * 60 * 60), '/');
    }
    
    // تسجيل وقت الدخول
    $auth->logLogin($_SESSION['user_id']);
    
    // توجيه إلى لوحة التحكم
    header('Location: ../dashboard.php');
    exit;
} else {
    $_SESSION['error'] = $result['message'];
    header('Location: ../login.php');
    exit;
}
?>