<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استعادة كلمة المرور - TRMS</title>
    
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
                <a href="login.php" class="text-white-50">
                    <i class="fas fa-sign-in-alt me-1"></i> تسجيل الدخول
                </a>
            </div>
        </div>
    </nav>
    
    <!-- المحتوى الرئيسي -->
    <main class="auth-container">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-lg-6 col-md-8">
                    <div class="auth-form-wrapper p-5 shadow-lg rounded-4">
                        <div class="text-center mb-5">
                            <div class="password-reset-icon mb-4">
                                <i class="fas fa-key fa-3x text-primary"></i>
                            </div>
                            <h2 class="auth-title">استعادة كلمة المرور</h2>
                            <p class="auth-subtitle">أدخل بريدك الإلكتروني لإعادة تعيين كلمة المرور</p>
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
                        
                        <!-- خطوات استعادة كلمة المرور -->
                        <div class="password-steps mb-5">
                            <div class="steps">
                                <div class="step active" data-step="1">
                                    <div class="step-circle">1</div>
                                    <div class="step-label">البريد الإلكتروني</div>
                                </div>
                                <div class="step" data-step="2">
                                    <div class="step-circle">2</div>
                                    <div class="step-label">التحقق</div>
                                </div>
                                <div class="step" data-step="3">
                                    <div class="step-circle">3</div>
                                    <div class="step-label">كلمة مرور جديدة</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- الخطوة 1: إدخال البريد الإلكتروني -->
                        <div class="step-content" id="step1">
                            <form id="emailForm">
                                <div class="mb-4">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-2"></i>البريد الإلكتروني
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input type="email" 
                                               class="form-control" 
                                               id="email" 
                                               name="email"
                                               placeholder="أدخل بريدك الإلكتروني المسجل"
                                               required>
                                    </div>
                                    <div class="invalid-feedback" id="emailError">
                                        يرجى إدخال بريد إلكتروني صحيح
                                    </div>
                                </div>
                                
                                <div class="d-grid mb-4">
                                    <button type="submit" class="btn btn-primary btn-lg" id="sendCodeBtn">
                                        <span id="sendCodeText">إرسال رمز التحقق</span>
                                        <span id="sendCodeLoading" class="d-none">
                                            <span class="spinner-border spinner-border-sm me-2"></span>
                                            جاري الإرسال...
                                        </span>
                                    </button>
                                </div>
                            </form>
                            
                            <div class="text-center">
                                <p class="mb-0">
                                    تذكرت كلمة المرور؟ 
                                    <a href="login.php" class="fw-bold text-decoration-none">
                                        سجل الدخول الآن
                                    </a>
                                </p>
                            </div>
                        </div>
                        
                        <!-- الخطوة 2: إدخال رمز التحقق -->
                        <div class="step-content d-none" id="step2">
                            <div class="text-center mb-4">
                                <p class="text-muted" id="emailSentTo"></p>
                            </div>
                            
                            <form id="verificationForm">
                                <div class="mb-4">
                                    <label class="form-label">رمز التحقق</label>
                                    <div class="verification-inputs mb-3">
                                        <input type="text" maxlength="1" class="verification-digit" autofocus>
                                        <input type="text" maxlength="1" class="verification-digit">
                                        <input type="text" maxlength="1" class="verification-digit">
                                        <input type="text" maxlength="1" class="verification-digit">
                                        <input type="text" maxlength="1" class="verification-digit">
                                        <input type="text" maxlength="1" class="verification-digit">
                                    </div>
                                    <input type="hidden" id="verificationCode" name="code">
                                    <div class="invalid-feedback" id="codeError">
                                        رمز التحقق غير صحيح
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <div class="text-center">
                                        <p class="text-muted mb-2" id="countdownText">
                                            يمكنك إعادة إرسال الرمز خلال <span id="countdown">120</span> ثانية
                                        </p>
                                        <button type="button" class="btn btn-link text-decoration-none" id="resendCodeBtn" disabled>
                                            إعادة إرسال الرمز
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-outline-secondary" id="backToEmail">
                                        <i class="fas fa-arrow-right me-2"></i>العودة
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="verifyCodeBtn">
                                        التحقق من الرمز
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- الخطوة 3: تعيين كلمة مرور جديدة -->
                        <div class="step-content d-none" id="step3">
                            <form id="passwordForm" action="includes/reset_password_process.php" method="POST">
                                <div class="mb-4">
                                    <label for="new_password" class="form-label">
                                        <i class="fas fa-lock me-2"></i>كلمة المرور الجديدة
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control" 
                                               id="new_password" 
                                               name="new_password"
                                               placeholder="كلمة مرور جديدة قوية"
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback" id="newPasswordError">
                                        كلمة المرور يجب أن تكون 8 أحرف على الأقل
                                    </div>
                                    
                                    <!-- مؤشر قوة كلمة المرور -->
                                    <div class="password-strength mt-2">
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar" id="passwordStrength" style="width: 0%"></div>
                                        </div>
                                        <small class="text-muted" id="passwordStrengthText">قوة كلمة المرور</small>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="confirm_new_password" class="form-label">
                                        <i class="fas fa-lock me-2"></i>تأكيد كلمة المرور الجديدة
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control" 
                                               id="confirm_new_password" 
                                               name="confirm_new_password"
                                               placeholder="أعد إدخال كلمة المرور الجديدة"
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmNewPassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback" id="confirmNewPasswordError">
                                        كلمات المرور غير متطابقة
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-outline-secondary" id="backToVerification">
                                        <i class="fas fa-arrow-right me-2"></i>العودة
                                    </button>
                                    <button type="submit" class="btn btn-success" id="resetPasswordBtn">
                                        <i class="fas fa-save me-2"></i>حفظ كلمة المرور الجديدة
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/auth.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentStep = 1;
            let countdownInterval;
            let countdown = 120;
            let userEmail = '';
            
            // تهيئة الخطوات
            updateSteps();
            
            // الخطوة 1: إرسال البريد الإلكتروني
            document.getElementById('emailForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const email = document.getElementById('email').value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (!email || !emailRegex.test(email)) {
                    document.getElementById('emailError').classList.add('d-block');
                    return;
                }
                
                document.getElementById('emailError').classList.remove('d-block');
                userEmail = email;
                
                // محاكاة إرسال الرمز
                const btn = document.getElementById('sendCodeBtn');
                const text = document.getElementById('sendCodeText');
                const loading = document.getElementById('sendCodeLoading');
                
                text.classList.add('d-none');
                loading.classList.remove('d-none');
                btn.disabled = true;
                
                setTimeout(() => {
                    // تحديث الرسالة
                    document.getElementById('emailSentTo').textContent = 
                        'تم إرسال رمز التحقق إلى: ' + email;
                    
                    // الانتقال للخطوة 2
                    goToStep(2);
                    
                    // إعادة تعيين الزر
                    text.classList.remove('d-none');
                    loading.classList.add('d-none');
                    btn.disabled = false;
                    
                    // بدء العد التنازلي
                    startCountdown();
                }, 1500);
            });
            
            // الخطوة 2: التحقق من الرمز
            document.getElementById('verificationForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const code = getVerificationCode();
                if (code.length !== 6) {
                    document.getElementById('codeError').classList.add('d-block');
                    return;
                }
                
                document.getElementById('codeError').classList.remove('d-block');
                document.getElementById('verificationCode').value = code;
                
                // محاكاة التحقق
                const btn = document.getElementById('verifyCodeBtn');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>جارٍ التحقق...';
                btn.disabled = true;
                
                setTimeout(() => {
                    // الانتقال للخطوة 3
                    goToStep(3);
                    
                    // إعادة تعيين الزر
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    
                    // إيقاف العد التنازلي
                    clearInterval(countdownInterval);
                }, 1000);
            });
            
            // الخطوة 3: تعيين كلمة مرور جديدة
            document.getElementById('passwordForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const newPassword = document.getElementById('new_password').value;
                const confirmPassword = document.getElementById('confirm_new_password').value;
                
                if (!newPassword || newPassword.length < 8) {
                    document.getElementById('newPasswordError').classList.add('d-block');
                    return;
                }
                
                if (newPassword !== confirmPassword) {
                    document.getElementById('confirmNewPasswordError').classList.add('d-block');
                    return;
                }
                
                // إرسال النموذج
                this.submit();
            });
            
            // زر العودة للبريد
            document.getElementById('backToEmail').addEventListener('click', function() {
                goToStep(1);
                clearInterval(countdownInterval);
            });
            
            // زر العودة للتحقق
            document.getElementById('backToVerification').addEventListener('click', function() {
                goToStep(2);
                startCountdown();
            });
            
            // زر إعادة إرسال الرمز
            document.getElementById('resendCodeBtn').addEventListener('click', function() {
                if (this.disabled) return;
                
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>جارٍ الإرسال...';
                this.disabled = true;
                
                setTimeout(() => {
                    // إعادة تعيين العد التنازلي
                    countdown = 120;
                    startCountdown();
                    
                    this.innerHTML = 'إعادة إرسال الرمز';
                    this.disabled = false;
                    
                    // عرض رسالة نجاح
                    showNotification('تم إعادة إرسال رمز التحقق بنجاح', 'success');
                }, 1000);
            });
            
            // إظهار/إخفاء كلمة المرور
            document.getElementById('toggleNewPassword').addEventListener('click', function() {
                const input = document.getElementById('new_password');
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
            
            document.getElementById('toggleConfirmNewPassword').addEventListener('click', function() {
                const input = document.getElementById('confirm_new_password');
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
            
            // قياس قوة كلمة المرور
            document.getElementById('new_password').addEventListener('input', checkPasswordStrength);
            
            // التحقق من تطابق كلمة المرور
            document.getElementById('confirm_new_password').addEventListener('input', checkPasswordMatch);
            
            // تهيئة حقول إدخال رمز التحقق
            initVerificationInputs();
            
            // وظائف مساعدة
            function goToStep(step) {
                currentStep = step;
                updateSteps();
            }
            
            function updateSteps() {
                // تحديث مؤشر الخطوات
                document.querySelectorAll('.password-steps .step').forEach(step => {
                    const stepNum = parseInt(step.getAttribute('data-step'));
                    if (stepNum === currentStep) {
                        step.classList.add('active');
                    } else if (stepNum < currentStep) {
                        step.classList.add('completed');
                        step.classList.remove('active');
                    } else {
                        step.classList.remove('active', 'completed');
                    }
                });
                
                // إظهار محتوى الخطوة الحالية
                document.querySelectorAll('.step-content').forEach(content => {
                    if (content.id === 'step' + currentStep) {
                        content.classList.remove('d-none');
                    } else {
                        content.classList.add('d-none');
                    }
                });
            }
            
            function initVerificationInputs() {
                const inputs = document.querySelectorAll('.verification-digit');
                inputs.forEach((input, index) => {
                    input.addEventListener('input', function() {
                        // السماح بالأرقام فقط
                        this.value = this.value.replace(/\D/g, '');
                        
                        if (this.value.length === 1 && index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        }
                        
                        // تحديث الرمز المخفي
                        updateVerificationCode();
                    });
                    
                    input.addEventListener('keydown', function(e) {
                        if (e.key === 'Backspace' && !this.value && index > 0) {
                            inputs[index - 1].focus();
                            updateVerificationCode();
                        }
                    });
                });
            }
            
            function updateVerificationCode() {
                const inputs = document.querySelectorAll('.verification-digit');
                let code = '';
                inputs.forEach(input => {
                    code += input.value;
                });
                document.getElementById('verificationCode').value = code;
            }
            
            function getVerificationCode() {
                const inputs = document.querySelectorAll('.verification-digit');
                let code = '';
                inputs.forEach(input => {
                    code += input.value;
                });
                return code;
            }
            
            function startCountdown() {
                clearInterval(countdownInterval);
                
                const countdownElement = document.getElementById('countdown');
                const resendBtn = document.getElementById('resendCodeBtn');
                const countdownText = document.getElementById('countdownText');
                
                countdown = 120;
                countdownElement.textContent = countdown;
                resendBtn.disabled = true;
                
                countdownInterval = setInterval(() => {
                    countdown--;
                    countdownElement.textContent = countdown;
                    
                    if (countdown <= 0) {
                        clearInterval(countdownInterval);
                        resendBtn.disabled = false;
                        countdownText.classList.add('d-none');
                    }
                }, 1000);
            }
            
            function checkPasswordStrength() {
                const password = document.getElementById('new_password').value;
                const strengthBar = document.getElementById('passwordStrength');
                const strengthText = document.getElementById('passwordStrengthText');
                
                if (!password) {
                    strengthBar.style.width = '0%';
                    strengthBar.className = 'progress-bar';
                    strengthText.textContent = 'قوة كلمة المرور';
                    return;
                }
                
                let strength = 0;
                if (password.length >= 8) strength += 25;
                if (/[A-Z]/.test(password)) strength += 25;
                if (/[a-z]/.test(password)) strength += 25;
                if (/[0-9]/.test(password)) strength += 25;
                if (/[^A-Za-z0-9]/.test(password)) strength += 25;
                
                strength = Math.min(strength, 100);
                strengthBar.style.width = strength + '%';
                
                if (strength < 40) {
                    strengthBar.className = 'progress-bar bg-danger';
                    strengthText.textContent = 'ضعيفة';
                } else if (strength < 70) {
                    strengthBar.className = 'progress-bar bg-warning';
                    strengthText.textContent = 'متوسطة';
                } else if (strength < 90) {
                    strengthBar.className = 'progress-bar bg-info';
                    strengthText.textContent = 'جيدة';
                } else {
                    strengthBar.className = 'progress-bar bg-success';
                    strengthText.textContent = 'قوية جداً';
                }
            }
            
            function checkPasswordMatch() {
                const password = document.getElementById('new_password').value;
                const confirm = document.getElementById('confirm_new_password').value;
                const error = document.getElementById('confirmNewPasswordError');
                
                if (password !== confirm) {
                    error.classList.add('d-block');
                } else {
                    error.classList.remove('d-block');
                }
            }
            
            function showNotification(message, type) {
                // إنشاء عنصر الإشعار
                const notification = document.createElement('div');
                notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
                notification.style.cssText = 'top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999;';
                notification.innerHTML = `
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                
                document.body.appendChild(notification);
                
                // إزالة الإشعار بعد 5 ثواني
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 5000);
            }
        });
    </script>
</body>
</html>