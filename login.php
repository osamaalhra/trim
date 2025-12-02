<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - TRMS</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/auth.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
</head>
<body class="auth-page">
    <!-- خلفية متحركة -->
    <div class="background-animation">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
    </div>
    
    <!-- الشريط العلوي -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <div class="logo-icon me-2">
                    <i class="fas fa-chart-line"></i>
                </div>
                <span class="brand-text">
                    <span class="fw-bold">TRMS</span>
                </span>
            </a>
            <div class="navbar-text">
                <a href="index.php" class="text-white-50">
                    <i class="fas fa-home me-1"></i> العودة للرئيسية
                </a>
            </div>
        </div>
    </nav>
    
    <!-- المحتوى الرئيسي -->
    <main class="auth-container">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-lg-8">
                    <div class="row shadow-lg rounded-4 overflow-hidden">
                        <!-- الشعار والمعلومات -->
                        <div class="col-lg-6 d-none d-lg-block auth-side">
                            <div class="auth-side-content">
                                <div class="auth-logo mb-4">
                                    <div class="logo-icon-large">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <h2 class="mt-3">TRMS</h2>
                                    <p class="text-light opacity-75">نظام إدارة الإيرادات الذكي</p>
                                </div>
                                
                                <div class="auth-features">
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-brain"></i>
                                        </div>
                                        <div class="feature-text">
                                            <h5>تخطيط ذكي</h5>
                                            <p>خطط لمهامك بذكاء واحصل على أعلى إيراد</p>
                                        </div>
                                    </div>
                                    
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-chart-bar"></i>
                                        </div>
                                        <div class="feature-text">
                                            <h5>تحليل متقدم</h5>
                                            <p>تتبع أداءك وحلل بياناتك باحترافية</p>
                                        </div>
                                    </div>
                                    
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-shield-alt"></i>
                                        </div>
                                        <div class="feature-text">
                                            <h5>أمان كامل</h5>
                                            <p>بياناتك محمية بأعلى معايير الأمان</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="auth-testimonial mt-5">
                                    <div class="testimonial-content">
                                        <p class="fst-italic">"TRMS غير طريقة عملي تماماً، زادت إيراداتي بنسبة 40%"</p>
                                        <div class="testimonial-author">
                                            <strong>أحمد محمد</strong>
                                            <span>مطور ويب مستقل</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- نموذج تسجيل الدخول -->
                        <div class="col-lg-6">
                            <div class="auth-form-wrapper p-5">
                                <div class="text-center mb-5">
                                    <h2 class="auth-title">مرحباً بعودتك</h2>
                                    <p class="auth-subtitle">سجل الدخول للمتابعة</p>
                                </div>
                                
                                <!-- رسائل الخطأ والنجاح -->
                                <?php if (isset($_SESSION['error'])): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (isset($_SESSION['success'])): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php endif; ?>
                                
                                <form id="loginForm" action="includes/login_process.php" method="POST" novalidate>
                                    <div class="mb-4">
                                        <label for="username" class="form-label">
                                            <i class="fas fa-user me-2"></i>اسم المستخدم أو البريد الإلكتروني
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-user"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="username" 
                                                   name="username" 
                                                   placeholder="أدخل اسم المستخدم أو البريد الإلكتروني"
                                                   required>
                                        </div>
                                        <div class="invalid-feedback" id="usernameError">
                                            يرجى إدخال اسم المستخدم أو البريد الإلكتروني
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="password" class="form-label">
                                            <i class="fas fa-lock me-2"></i>كلمة المرور
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="password" 
                                                   name="password" 
                                                   placeholder="أدخل كلمة المرور"
                                                   required>
                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback" id="passwordError">
                                            يرجى إدخال كلمة المرور
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                            <label class="form-check-label" for="remember">
                                                تذكرني
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid mb-4">
                                        <button type="submit" class="btn btn-primary btn-lg" id="loginBtn">
                                            <span id="loginText">تسجيل الدخول</span>
                                            <span id="loginLoading" class="d-none">
                                                <span class="spinner-border spinner-border-sm me-2"></span>
                                                جاري التحقق...
                                            </span>
                                        </button>
                                    </div>
                                    
                                    <div class="text-center mb-4">
                                        <a href="forgot_password.php" class="text-decoration-none">
                                            <i class="fas fa-key me-1"></i>نسيت كلمة المرور؟
                                        </a>
                                    </div>
                                    
                                    <div class="text-center">
                                        <p class="mb-0">
                                            ليس لديك حساب؟ 
                                            <a href="register.php" class="fw-bold text-decoration-none">
                                                أنشئ حساباً جديداً
                                            </a>
                                        </p>
                                    </div>
                                    
                                    <!-- تسجيل الدخول عبر وسائل التواصل -->
                                    <div class="separator my-4">
                                        <span>أو سجل الدخول باستخدام</span>
                                    </div>
                                    
                                    <div class="row g-2 mb-4">
                                        <div class="col-6">
                                            <button type="button" class="btn btn-outline-dark w-100">
                                                <i class="fab fa-google me-2"></i> Google
                                            </button>
                                        </div>
                                        <div class="col-6">
                                            <button type="button" class="btn btn-outline-primary w-100">
                                                <i class="fab fa-microsoft me-2"></i> Microsoft
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                
                                <!-- معلومات إضافية -->
                                <div class="auth-footer text-center mt-4 pt-3 border-top">
                                    <small class="text-muted">
                                        <i class="fas fa-shield-alt me-1"></i>
                                        بياناتك محمية وفق سياسة الخصوصية والشروط
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- نافذة التحقق من الأمان -->
    <div class="modal fade" id="securityModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-shield-alt text-primary me-2"></i>
                        تحقق من الأمان
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-lock fa-3x text-primary mb-3"></i>
                        <p>لاحظنا محاولات تسجيل دخول غير اعتيادية من جهازك</p>
                    </div>
                    
                    <form id="securityForm">
                        <div class="mb-3">
                            <label class="form-label">التحقق عبر البريد الإلكتروني</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="text" class="form-control" placeholder="رمز التحقق" required>
                                <button class="btn btn-outline-secondary" type="button">
                                    إعادة الإرسال
                                </button>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">أو عبر الهاتف</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-mobile-alt"></i>
                                </span>
                                <input type="text" class="form-control" placeholder="رمز التحقق">
                                <button class="btn btn-outline-secondary" type="button">
                                    إرسال SMS
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary">تأكيد</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/auth.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const loginBtn = document.getElementById('loginBtn');
            const loginText = document.getElementById('loginText');
            const loginLoading = document.getElementById('loginLoading');
            
            // إظهار/إخفاء كلمة المرور
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
            
            // التحقق من النموذج
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                let isValid = true;
                
                // التحقق من اسم المستخدم
                const username = document.getElementById('username').value.trim();
                if (!username) {
                    document.getElementById('usernameError').classList.add('d-block');
                    isValid = false;
                } else {
                    document.getElementById('usernameError').classList.remove('d-block');
                }
                
                // التحقق من كلمة المرور
                const password = document.getElementById('password').value.trim();
                if (!password) {
                    document.getElementById('passwordError').classList.add('d-block');
                    isValid = false;
                } else {
                    document.getElementById('passwordError').classList.remove('d-block');
                }
                
                if (isValid) {
                    // عرض حالة التحميل
                    loginText.classList.add('d-none');
                    loginLoading.classList.remove('d-none');
                    loginBtn.disabled = true;
                    
                    // إرسال النموذج
                    setTimeout(() => {
                        loginForm.submit();
                    }, 1000);
                }
            });
            
            // إزالة رسائل الخطأ عند الكتابة
            document.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', function() {
                    const errorElement = document.getElementById(this.id + 'Error');
                    if (errorElement) {
                        errorElement.classList.remove('d-block');
                    }
                });
            });
            
            // فحص محاولات الدخول السابقة
            checkLoginAttempts();
        });
        
        function checkLoginAttempts() {
            const attempts = localStorage.getItem('login_attempts') || 0;
            if (attempts >= 3) {
                setTimeout(() => {
                    const securityModal = new bootstrap.Modal(document.getElementById('securityModal'));
                    securityModal.show();
                }, 1000);
            }
        }
    </script>
</body>
</html>