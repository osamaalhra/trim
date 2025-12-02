<?php
session_start();
require_once '../config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التقارير - نظام إدارة الوقت</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar">
                <!-- نفس الشريط الجانبي -->
            </div>
            <div class="col-md-10 main-content">
                <h2>التقارير والإحصائيات</h2>
                <!-- محتوى التقارير -->
            </div>
        </div>
    </div>
</body>
</html>