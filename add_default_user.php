<?php
// add_default_user.php - تشغيل هذا الملف مرة واحدة فقط لإضافة مستخدم افتراضي
require_once 'config.php';

try {
    $username = 'admin';
    $password = 'admin123';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$username, $hashed_password, 'admin']);
    
    echo "تم إضافة المستخدم الافتراضي بنجاح!<br>";
    echo "اسم المستخدم: admin<br>";
    echo "كلمة المرور: admin123<br>";
    echo "<strong>تحذير: قم بحذف هذا الملف بعد الاستخدام لأسباب أمنية</strong>";
    
} catch(PDOException $e) {
    if($e->getCode() == 23000) {
        echo "المستخدم موجود مسبقاً في النظام";
    } else {
        echo "خطأ: " . $e->getMessage();
    }
}
?>