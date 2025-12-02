<?php
/**
 * ملف تركيب نظام TRMS
 * يهيئ قاعدة البيانات وإنشاء المستخدم الإداري الأول
 */

// منع الوصول المباشر في الإنتاج
if (file_exists('config/constants.php')) {
    die('النظام مثبت بالفعل. لحذف الملف: install.php');
}

// فحص متطلبات النظام
$requirements = [
    'PHP 7.4+' => version_compare(PHP_VERSION, '7.4.0', '>='),
    'MySQLi' => extension_loaded('mysqli'),
    'PDO' => extension_loaded('pdo'),
    'OpenSSL' => extension_loaded('openssl'),
    'JSON' => extension_loaded('json'),
    'Mbstring' => extension_loaded('mbstring'),
    'Fileinfo' => extension_loaded('fileinfo'),
    'GD' => extension_loaded('gd'),
    'Zip' => extension_loaded('zip')
];

$all_requirements_met = !in_array(false, $requirements, true);

// معالجة نموذج التثبيت
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $success = false;
    
    // جمع البيانات من النموذج
    $db_host = $_POST['db_host'] ?? 'localhost';
    $db_port = $_POST['db_port'] ?? '3306';
    $db_name = $_POST['db_name'] ?? 'trms_database';
    $db_user = $_POST['db_user'] ?? '';
    $db_pass = $_POST['db_pass'] ?? '';
    
    $admin_username = $_POST['admin_username'] ?? '';
    $admin_email = $_POST['admin_email'] ?? '';
    $admin_password = $_POST['admin_password'] ?? '';
    $admin_confirm = $_POST['admin_confirm'] ?? '';
    
    $site_title = $_POST['site_title'] ?? 'TRMS';
    $site_url = $_POST['site_url'] ?? '';
    
    // التحقق من البيانات
    if (empty($db_host) || empty($db_name) || empty($db_user)) {
        $errors[] = 'جميع حقول قاعدة البيانات مطلوبة';
    }
    
    if (empty($admin_username) || empty($admin_email) || empty($admin_password)) {
        $errors[] = 'جميع حقول المسؤول مطلوبة';
    }
    
    if ($admin_password !== $admin_confirm) {
        $errors[] = 'كلمات مرور المسؤول غير متطابقة';
    }
    
    if (strlen($admin_password) < 8) {
        $errors[] = 'كلمة مرور المسؤول يجب أن تكون 8 أحرف على الأقل';
    }
    
    // إذا لم تكن هناك أخطاء، حاول الاتصال بقاعدة البيانات
    if (empty($errors)) {
        try {
            // اختبار الاتصال بقاعدة البيانات
            $test_conn = new mysqli($db_host, $db_user, $db_pass, '', $db_port);
            
            if ($test_conn->connect_error) {
                throw new Exception('فشل الاتصال بقاعدة البيانات: ' . $test_conn->connect_error);
            }
            
            // إنشاء قاعدة البيانات إذا لم تكن موجودة
            if (!$test_conn->select_db($db_name)) {
                if (!$test_conn->query("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
                    throw new Exception('فشل إنشاء قاعدة البيانات: ' . $test_conn->error);
                }
                $test_conn->select_db($db_name);
            }
            
            // قراءة ملف SQL
            $sql_file = __DIR__ . '/database/schema.sql';
            if (!file_exists($sql_file)) {
                throw new Exception('ملف SQL غير موجود');
            }
            
            $sql = file_get_contents($sql_file);
            if (empty($sql)) {
                throw new Exception('ملف SQL فارغ');
            }
            
            // تنفيذ الاستعلامات
            $queries = array_filter(array_map('trim', explode(';', $sql)));
            
            foreach ($queries as $query) {
                if (!empty($query)) {
                    if (!$test_conn->query($query)) {
                        throw new Exception('فشل تنفيذ الاستعلام: ' . $test_conn->error . '<br>الاستعلام: ' . htmlspecialchars($query));
                    }
                }
            }
            
            // إنشاء المستخدم الإداري
            $admin_password_hash = password_hash($admin_password, PASSWORD_BCRYPT, ['cost' => 12]);
            $now = date('Y-m-d H:i:s');
            
            $insert_admin = "INSERT INTO users (username, email, password_hash, full_name, role, daily_financial_target, work_hours_per_day, created_at, updated_at) 
                             VALUES (?, ?, ?, 'مدير النظام', 'admin', 5000, 8, ?, ?)";
            
            $stmt = $test_conn->prepare($insert_admin);
            $stmt->bind_param('sssss', $admin_username, $admin_email, $admin_password_hash, $now, $now);
            
            if (!$stmt->execute()) {
                throw new Exception('فشل إنشاء مستخدم المسؤول: ' . $stmt->error);
            }
            
            $stmt->close();
            
            // إنشاء ملف الإعدادات
            $config_template = file_get_contents(__DIR__ . '/config/database.php.template');
            $config_content = str_replace(
                [
                    '{{DB_HOST}}',
                    '{{DB_PORT}}',
                    '{{DB_NAME}}',
                    '{{DB_USER}}',
                    '{{DB_PASS}}',
                    '{{SITE_URL}}',
                    '{{SITE_TITLE}}'
                ],
                [
                    $db_host,
                    $db_port,
                    $db_name,
                    $db_user,
                    $db_pass,
                    $site_url,
                    $site_title
                ],
                $config_template
            );
            
            // حفظ ملف الإعدادات
            if (!file_put_contents(__DIR__ . '/config/database.php', $config_content)) {
                throw new Exception('فشل حفظ ملف الإعدادات');
            }
            
            // إنشاء ملف constants.php
            $constants_content = "<?php\n// ملف الإعدادات - تم إنشاؤه تلقائياً\n";
            if (!file_put_contents(__DIR__ . '/config/constants.php', $constants_content)) {
                throw new Exception('فشل حفظ ملف الثوابت');
            }
            
            // إغلاق الاتصال
            $test_conn->close();
            
            $success = true;
            
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تركيب نظام TRMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #4361ee, #7209b7);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .install-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin: 50px auto;
            max-width: 800px;
        }
        .install-header {
            background: linear-gradient(135deg, #4361ee, #3a56d4);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 2rem;
        }
        .install-logo {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .requirement-list li {
            padding: 0.5rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }
        .requirement-met {
            background: #d4edda;
            color: #155724;
        }
        .requirement-not-met {
            background: #f8d7da;
            color: #721c24;
        }
        .step {
            display: none;
        }
        .step.active {
            display: block;
        }
        .step-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #4361ee;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto 1rem;
        }
    </style>
</head>
<body>
    <div class="install-container">
        <div class="install-header text-center">
            <div class="install-logo">
                <i class="fas fa-chart-line"></i>
            </div>
            <h1>تركيب نظام TRMS</h1>
            <p class="mb-0">نظام إدارة المهام والإيرادات</p>
        </div>
        
        <div class="p-5">
            <?php if (isset($success) && $success): ?>
                <div class="alert alert-success text-center">
                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                    <h3>تم التثبيت بنجاح!</h3>
                    <p class="mb-3">تم تركيب نظام TRMS بنجاح على خادمك.</p>
                    
                    <div class="alert alert-info text-start">
                        <h5>معلومات الدخول:</h5>
                        <p><strong>اسم المستخدم:</strong> <?php echo htmlspecialchars($admin_username); ?></p>
                        <p><strong>البريد الإلكتروني:</strong> <?php echo htmlspecialchars($admin_email); ?></p>
                        <p><strong>رابط الدخول:</strong> <a href="<?php echo htmlspecialchars($site_url); ?>/login.php" target="_blank"><?php echo htmlspecialchars($site_url); ?>/login.php</a></p>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-exclamation-triangle"></i> خطوات أمنية مهمة:</h5>
                        <ol class="mb-0">
                            <li>احذف ملف install.php من الخادم</li>
                            <li>غير أذونات المجلدات إلى 755 والملفات إلى 644</li>
                            <li>قم بتغيير كلمة مرور المسؤول بعد أول دخول</li>
                            <li>احفظ معلومات الدخول في مكان آمن</li>
                        </ol>
                    </div>
                    
                    <a href="<?php echo htmlspecialchars($site_url); ?>/login.php" class="btn btn-success btn-lg mt-3">
                        <i class="fas fa-sign-in-alt me-2"></i> الانتقال لتسجيل الدخول
                    </a>
                </div>
                
            <?php else: ?>
                <!-- الخطوة 1: متطلبات النظام -->
                <div class="step active" id="step1">
                    <div class="text-center mb-4">
                        <div class="step-icon">
                            <i class="fas fa-server"></i>
                        </div>
                        <h3>متطلبات النظام</h3>
                        <p class="text-muted">تحقق من توفر جميع المتطلبات قبل المتابعة</p>
                    </div>
                    
                    <ul class="requirement-list list-unstyled">
                        <?php foreach ($requirements as $requirement => $met): ?>
                            <li class="<?php echo $met ? 'requirement-met' : 'requirement-not-met'; ?>">
                                <i class="fas fa-<?php echo $met ? 'check' : 'times'; ?> me-2"></i>
                                <?php echo $requirement; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <button class="btn btn-secondary" disabled>
                            <i class="fas fa-arrow-right me-2"></i> السابق
                        </button>
                        <button class="btn btn-primary" onclick="nextStep()" <?php echo $all_requirements_met ? '' : 'disabled'; ?>>
                            التالي <i class="fas fa-arrow-left me-2"></i>
                        </button>
                    </div>
                </div>
                
                <!-- الخطوة 2: إعدادات قاعدة البيانات -->
                <div class="step" id="step2">
                    <div class="text-center mb-4">
                        <div class="step-icon">
                            <i class="fas fa-database"></i>
                        </div>
                        <h3>إعدادات قاعدة البيانات</h3>
                        <p class="text-muted">أدخل معلومات اتصال قاعدة البيانات MySQL</p>
                    </div>
                    
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php foreach ($errors as $error): ?>
                                <div><?php echo $error; ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" id="installForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">اسم الخادم</label>
                                <input type="text" class="form-control" name="db_host" value="<?php echo $_POST['db_host'] ?? 'localhost'; ?>" required>
                                <small class="text-muted">عادةً localhost</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">رقم المنفذ</label>
                                <input type="number" class="form-control" name="db_port" value="<?php echo $_POST['db_port'] ?? '3306'; ?>" required>
                                <small class="text-muted">عادةً 3306</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">اسم قاعدة البيانات</label>
                                <input type="text" class="form-control" name="db_name" value="<?php echo $_POST['db_name'] ?? 'trms_database'; ?>" required>
                                <small class="text-muted">سيتم إنشاؤها إذا لم تكن موجودة</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">اسم المستخدم</label>
                                <input type="text" class="form-control" name="db_user" value="<?php echo $_POST['db_user'] ?? ''; ?>" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">كلمة المرور</label>
                                <input type="password" class="form-control" name="db_pass" value="<?php echo $_POST['db_pass'] ?? ''; ?>">
                                <small class="text-muted">اتركها فارغة إذا لم تكن هناك كلمة مرور</small>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">
                                <i class="fas fa-arrow-right me-2"></i> السابق
                            </button>
                            <button type="button" class="btn btn-primary" onclick="nextStep()">
                                التالي <i class="fas fa-arrow-left me-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- الخطوة 3: إعدادات المسؤول -->
                <div class="step" id="step3">
                    <div class="text-center mb-4">
                        <div class="step-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h3>إعدادات المسؤول</h3>
                        <p class="text-muted">أنشئ حساب المسؤول الرئيسي للنظام</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">اسم مستخدم المسؤول</label>
                            <input type="text" class="form-control" name="admin_username" value="<?php echo $_POST['admin_username'] ?? ''; ?>" required>
                            <small class="text-muted">سيكون لديه جميع الصلاحيات</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">بريد المسؤول الإلكتروني</label>
                            <input type="email" class="form-control" name="admin_email" value="<?php echo $_POST['admin_email'] ?? ''; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">كلمة مرور المسؤول</label>
                            <input type="password" class="form-control" name="admin_password" required>
                            <small class="text-muted">8 أحرف على الأقل</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تأكيد كلمة المرور</label>
                            <input type="password" class="form-control" name="admin_confirm" required>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary" onclick="prevStep()">
                            <i class="fas fa-arrow-right me-2"></i> السابق
                        </button>
                        <button type="button" class="btn btn-primary" onclick="nextStep()">
                            التالي <i class="fas fa-arrow-left me-2"></i>
                        </button>
                    </div>
                </div>
                
                <!-- الخطوة 4: إعدادات الموقع -->
                <div class="step" id="step4">
                    <div class="text-center mb-4">
                        <div class="step-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h3>إعدادات الموقع</h3>
                        <p class="text-muted">أدخل معلومات موقعك الإلكتروني</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">عنوان الموقع</label>
                            <input type="text" class="form-control" name="site_title" value="<?php echo $_POST['site_title'] ?? 'TRMS'; ?>" required>
                            <small class="text-muted">سيظهر في شريط العنوان</small>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">رابط الموقع</label>
                            <input type="url" class="form-control" name="site_url" value="<?php echo $_POST['site_url'] ?? 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']); ?>" required>
                            <small class="text-muted">يجب أن يبدأ بـ http:// أو https://</small>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-4">
                        <h5><i class="fas fa-info-circle"></i> ملاحظات قبل التثبيت:</h5>
                        <ul class="mb-0">
                            <li>تأكد من صحة جميع المعلومات المدخلة</li>
                            <li>سيكون حساب المسؤول هو الحساب الوحيد في البداية</li>
                            <li>يمكنك إضافة مستخدمين آخرين بعد الدخول</li>
                            <li>احفظ معلومات الدخول في مكان آمن</li>
                        </ul>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary" onclick="prevStep()">
                            <i class="fas fa-arrow-right me-2"></i> السابق
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-play me-2"></i> بدء التثبيت
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
    <script>
        let currentStep = 1;
        
        function showStep(step) {
            document.querySelectorAll('.step').forEach(s => {
                s.classList.remove('active');
            });
            document.getElementById('step' + step).classList.add('active');
            currentStep = step;
        }
        
        function nextStep() {
            if (currentStep < 4) {
                showStep(currentStep + 1);
            }
        }
        
        function prevStep() {
            if (currentStep > 1) {
                showStep(currentStep - 1);
            }
        }
        
        // التحقق من نموذج قاعدة البيانات
        document.querySelector('button[type="submit"]').addEventListener('click', function(e) {
            e.preventDefault();
            
            // جمع جميع الحقول
            const form = document.getElementById('installForm');
            const formData = new FormData(form);
            
            // إضافة الحقول من الخطوات الأخرى
            ['admin_username', 'admin_email', 'admin_password', 'admin_confirm', 'site_title', 'site_url'].forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    formData.append(field, input.value);
                }
            });
            
            // إرسال النموذج
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.body.innerHTML = xhr.responseText;
                }
            };
            xhr.send(formData);
        });
    </script>
</body>
</html>