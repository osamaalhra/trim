<?php
session_start();
require_once 'config/database.php';
require_once 'includes/auth.php';

// التحقق من أن الطريقة POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../forgot_password.php');
    exit;
}

// تنظيف المدخلات
$email = trim($_POST['email'] ?? '');
$code = trim($_POST['code'] ?? '');
$new_password = $_POST['new_password'] ?? '';

// حسب المرحلة
if (!empty($email) && empty($code)) {
    // المرحلة 1: طلب رمز التحقق
    processEmailRequest($email);
} elseif (!empty($code) && empty($new_password)) {
    // المرحلة 2: التحقق من الرمز
    processCodeVerification($code);
} elseif (!empty($new_password)) {
    // المرحلة 3: تعيين كلمة مرور جديدة
    processPasswordReset($new_password);
} else {
    $_SESSION['error'] = 'بيانات غير صالحة';
    header('Location: ../forgot_password.php');
    exit;
}

function processEmailRequest($email) {
    global $conn;
    
    // التحقق من صحة البريد
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'البريد الإلكتروني غير صالح';
        header('Location: ../forgot_password.php');
        exit;
    }
    
    // التحقق من وجود المستخدم
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $_SESSION['error'] = 'لا يوجد حساب مرتبط بهذا البريد الإلكتروني';
        header('Location: ../forgot_password.php');
        exit;
    }
    
    // إنشاء رمز تحقق
    $verification_code = rand(100000, 999999);
    $expires_at = date('Y-m-d H:i:s', strtotime('+15 minutes'));
    
    // حفظ الرمز في الجلسة (في الإصدار الحقيقي، سيتم حفظه في قاعدة البيانات)
    $_SESSION['reset_email'] = $email;
    $_SESSION['reset_code'] = $verification_code;
    $_SESSION['reset_expires'] = $expires_at;
    
    // إرسال البريد الإلكتروني (في الإصدار الحقيقي)
    // sendVerificationEmail($email, $verification_code);
    
    $_SESSION['success'] = 'تم إرسال رمز التحقق إلى بريدك الإلكتروني';
    $_SESSION['step'] = 2;
    header('Location: ../forgot_password.php?step=2&email=' . urlencode($email));
    exit;
}

function processCodeVerification($code) {
    // التحقق من الرمز
    if (!isset($_SESSION['reset_code']) || $_SESSION['reset_code'] != $code) {
        $_SESSION['error'] = 'رمز التحقق غير صحيح';
        header('Location: ../forgot_password.php?step=2');
        exit;
    }
    
    // التحقق من انتهاء الصلاحية
    if (strtotime($_SESSION['reset_expires']) < time()) {
        $_SESSION['error'] = 'انتهت صلاحية رمز التحقق';
        unset($_SESSION['reset_code'], $_SESSION['reset_expires']);
        header('Location: ../forgot_password.php');
        exit;
    }
    
    $_SESSION['step'] = 3;
    header('Location: ../forgot_password.php?step=3');
    exit;
}

function processPasswordReset($new_password) {
    global $conn;
    
    // التحقق من صحة كلمة المرور
    if (strlen($new_password) < 8) {
        $_SESSION['error'] = 'كلمة المرور يجب أن تكون 8 أحرف على الأقل';
        header('Location: ../forgot_password.php?step=3');
        exit;
    }
    
    // التحقق من وجود جلسة استعادة
    if (!isset($_SESSION['reset_email'])) {
        $_SESSION['error'] = 'انتهت جلسة الاستعادة. يرجى البدء من جديد';
        header('Location: ../forgot_password.php');
        exit;
    }
    
    $email = $_SESSION['reset_email'];
    
    // تحديث كلمة المرور
    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
    $stmt->bind_param("ss", $password_hash, $email);
    
    if ($stmt->execute()) {
        // مسح جلسة الاستعادة
        unset($_SESSION['reset_email'], $_SESSION['reset_code'], $_SESSION['reset_expires']);
        
        // تسجيل الحدث
        logPasswordReset($email);
        
        $_SESSION['success'] = 'تم إعادة تعيين كلمة المرور بنجاح. يمكنك تسجيل الدخول الآن';
        header('Location: ../login.php');
        exit;
    } else {
        $_SESSION['error'] = 'حدث خطأ أثناء تحديث كلمة المرور';
        header('Location: ../forgot_password.php?step=3');
        exit;
    }
}

function logPasswordReset($email) {
    global $conn;
    
    $stmt = $conn->prepare("INSERT INTO password_reset_logs (email, reset_at, ip_address) VALUES (?, NOW(), ?)");
    $ip = $_SERVER['REMOTE_ADDR'];
    $stmt->bind_param("ss", $email, $ip);
    $stmt->execute();
}
?>