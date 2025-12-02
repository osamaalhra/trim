<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد - TRMS</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Password Strength Meter -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/password-strength-meter/2.3.1/password.min.css">
    
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
                <div class="col-lg-10">
                    <div class="row shadow-lg rounded-4 overflow-hidden">
                        <!-- نموذج التسجيل -->
                        <div class="col-lg-7">
                            <div class="auth-form-wrapper p-5">
                                <div class="text-center mb-5">
                                    <h2 class="auth-title">أنشئ حساباً جديداً</h2>
                                    <p class="auth-subtitle">انضم إلى آلاف المحترفين الذين يستخدمون TRMS</p>
                                </div>
                                
                                <!-- خطوات التسجيل -->
                                <div class="registration-steps mb-5">
                                    <div class="steps">
                                        <div class="step active" data-step="1">
                                            <div class="step-circle">1</div>
                                            <div class="step-label">المعلومات الأساسية</div>
                                        </div>
                                        <div class="step" data-step="2">
                                            <div class="step-circle">2</div>
                                            <div class="step-label">تفضيلات العمل</div>
                                        </div>
                                        <div class="step" data-step="3">
                                            <div class="step-circle">3</div>
                                            <div class="step-label">تأكيد الحساب</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- رسائل الخطأ والنجاح -->
                                <?php if (isset($_SESSION['error'])): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php endif; ?>
                                
                                <form id="registerForm" action="includes/register_process.php" method="POST" novalidate>
                                    <!-- الخطوة 1: المعلومات الأساسية -->
                                    <div class="step-content" id="step1">
                                        <div class="row">
                                            <div class="col-md-6 mb-4">
                                                <label for="full_name" class="form-label">
                                                    <i class="fas fa-user me-2"></i>الاسم الكامل
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-user"></i>
                                                    </span>
                                                    <input type="text" 
                                                           class="form-control" 
                                                           id="full_name" 
                                                           name="full_name"
                                                           placeholder="أدخل اسمك الكامل"
                                                           required>
                                                </div>
                                                <div class="invalid-feedback" id="full_nameError">
                                                    يرجى إدخال الاسم الكامل
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6 mb-4">
                                                <label for="username" class="form-label">
                                                    <i class="fas fa-at me-2"></i>اسم المستخدم
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-at"></i>
                                                    </span>
                                                    <input type="text" 
                                                           class="form-control" 
                                                           id="username" 
                                                           name="username"
                                                           placeholder="اختر اسم مستخدم فريد"
                                                           required>
                                                    <button class="btn btn-outline-secondary" type="button" id="checkUsername">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </div>
                                                <div class="invalid-feedback" id="usernameError">
                                                    يرجى اختيار اسم مستخدم فريد
                                                </div>
                                                <small class="text-muted">يمكن استخدام الأحرف الإنجليزية والأرقام والنقاط</small>
                                            </div>
                                            
                                            <div class="col-md-6 mb-4">
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
                                                           placeholder="example@domain.com"
                                                           required>
                                                    <button class="btn btn-outline-secondary" type="button" id="checkEmail">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </div>
                                                <div class="invalid-feedback" id="emailError">
                                                    يرجى إدخال بريد إلكتروني صحيح
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6 mb-4">
                                                <label for="phone" class="form-label">
                                                    <i class="fas fa-phone me-2"></i>رقم الهاتف (اختياري)
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-phone"></i>
                                                    </span>
                                                    <input type="tel" 
                                                           class="form-control" 
                                                           id="phone" 
                                                           name="phone"
                                                           placeholder="05XXXXXXXX">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6 mb-4">
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
                                                           placeholder="كلمة مرور قوية"
                                                           required>
                                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                                <div class="invalid-feedback" id="passwordError">
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
                                            
                                            <div class="col-md-6 mb-4">
                                                <label for="confirm_password" class="form-label">
                                                    <i class="fas fa-lock me-2"></i>تأكيد كلمة المرور
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-lock"></i>
                                                    </span>
                                                    <input type="password" 
                                                           class="form-control" 
                                                           id="confirm_password" 
                                                           name="confirm_password"
                                                           placeholder="أعد إدخال كلمة المرور"
                                                           required>
                                                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                                <div class="invalid-feedback" id="confirm_passwordError">
                                                    كلمات المرور غير متطابقة
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between mt-4">
                                            <button type="button" class="btn btn-outline-secondary" disabled>
                                                <i class="fas fa-arrow-right me-2"></i>السابق
                                            </button>
                                            <button type="button" class="btn btn-primary" id="nextStep1">
                                                التالي <i class="fas fa-arrow-left me-2"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- الخطوة 2: تفضيلات العمل -->
                                    <div class="step-content d-none" id="step2">
                                        <div class="row">
                                            <div class="col-md-6 mb-4">
                                                <label for="daily_target" class="form-label">
                                                    <i class="fas fa-bullseye me-2"></i>الهدف المالي اليومي
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-money-bill"></i>
                                                    </span>
                                                    <input type="number" 
                                                           class="form-control" 
                                                           id="daily_target" 
                                                           name="daily_target"
                                                           placeholder="مثال: 1000"
                                                           min="0"
                                                           step="50"
                                                           value="1000">
                                                    <span class="input-group-text">ريال</span>
                                                </div>
                                                <small class="text-muted">يمكنك تعديله لاحقاً</small>
                                            </div>
                                            
                                            <div class="col-md-6 mb-4">
                                                <label for="work_hours" class="form-label">
                                                    <i class="fas fa-clock me-2"></i>ساعات العمل اليومية
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-clock"></i>
                                                    </span>
                                                    <input type="number" 
                                                           class="form-control" 
                                                           id="work_hours" 
                                                           name="work_hours"
                                                           min="1"
                                                           max="16"
                                                           value="8">
                                                    <span class="input-group-text">ساعة</span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6 mb-4">
                                                <label for="work_start" class="form-label">
                                                    <i class="fas fa-sun me-2"></i>وقت بدء العمل
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-clock"></i>
                                                    </span>
                                                    <select class="form-select" id="work_start" name="work_start">
                                                        <option value="08:00">8:00 صباحاً</option>
                                                        <option value="09:00" selected>9:00 صباحاً</option>
                                                        <option value="10:00">10:00 صباحاً</option>
                                                        <option value="11:00">11:00 صباحاً</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6 mb-4">
                                                <label for="optimization" class="form-label">
                                                    <i class="fas fa-cogs me-2"></i>استراتيجية التخطيط
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-cogs"></i>
                                                    </span>
                                                    <select class="form-select" id="optimization" name="optimization">
                                                        <option value="balanced" selected>متوازن (الربح والوقت)</option>
                                                        <option value="max_revenue">الحد الأقصى للإيراد</option>
                                                        <option value="min_time">الحد الأدنى للوقت</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12 mb-4">
                                                <label class="form-label">
                                                    <i class="fas fa-tags me-2"></i>مجالات عملك (اختياري)
                                                </label>
                                                <div class="selected-tags mb-2" id="selectedTags"></div>
                                                <div class="input-group">
                                                    <input type="text" 
                                                           class="form-control" 
                                                           id="tagInput"
                                                           placeholder="أضف مجال عملك (اضغط Enter)">
                                                    <button class="btn btn-outline-secondary" type="button" id="addTag">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <input type="hidden" id="tags" name="tags">
                                                <small class="text-muted">مثل: برمجة، تصميم، تسويق، كتابة</small>
                                            </div>
                                        </div>
                                        
                                        <div class="form-check mb-4">
                                            <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter" checked>
                                            <label class="form-check-label" for="newsletter">
                                                أرغب في تلقي النشرة الإخبارية ونصائح المنتج
                                            </label>
                                        </div>
                                        
                                        <div class="form-check mb-4">
                                            <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                            <label class="form-check-label" for="terms">
                                                أوافق على 
                                                <a href="terms.php" class="text-decoration-none">الشروط والأحكام</a>
                                                و
                                                <a href="privacy.php" class="text-decoration-none">سياسة الخصوصية</a>
                                            </label>
                                            <div class="invalid-feedback" id="termsError">
                                                يجب الموافقة على الشروط والأحكام
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between mt-4">
                                            <button type="button" class="btn btn-outline-secondary" id="prevStep2">
                                                <i class="fas fa-arrow-right me-2"></i>السابق
                                            </button>
                                            <button type="button" class="btn btn-primary" id="nextStep2">
                                                التالي <i class="fas fa-arrow-left me-2"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- الخطوة 3: تأكيد الحساب -->
                                    <div class="step-content d-none" id="step3">
                                        <div class="text-center mb-5">
                                            <div class="confirmation-icon mb-4">
                                                <i class="fas fa-check-circle fa-4x text-success"></i>
                                            </div>
                                            <h4 class="mb-3">جاهز تقريباً!</h4>
                                            <p class="text-muted">راجع معلوماتك قبل إنشاء الحساب</p>
                                        </div>
                                        
                                        <div class="confirmation-details">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <strong>الاسم الكامل:</strong>
                                                    <span id="confirmFullName"></span>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <strong>اسم المستخدم:</strong>
                                                    <span id="confirmUsername"></span>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <strong>البريد الإلكتروني:</strong>
                                                    <span id="confirmEmail"></span>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <strong>الهاتف:</strong>
                                                    <span id="confirmPhone"></span>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <strong>الهدف اليومي:</strong>
                                                    <span id="confirmTarget"></span> ريال
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <strong>ساعات العمل:</strong>
                                                    <span id="confirmHours"></span> ساعة
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <strong>وقت البدء:</strong>
                                                    <span id="confirmStart"></span>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <strong>استراتيجية التخطيط:</strong>
                                                    <span id="confirmStrategy"></span>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <strong>مجالات العمل:</strong>
                                                    <span id="confirmTags"></span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between mt-5">
                                            <button type="button" class="btn btn-outline-secondary" id="prevStep3">
                                                <i class="fas fa-arrow-right me-2"></i>السابق
                                            </button>
                                            <button type="submit" class="btn btn-success" id="submitRegister">
                                                <i class="fas fa-user-plus me-2"></i>إنشاء الحساب
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
                        
                        <!-- الشعار والمعلومات -->
                        <div class="col-lg-5 d-none d-lg-block auth-side">
                            <div class="auth-side-content">
                                <div class="auth-logo mb-5">
                                    <div class="logo-icon-large">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <h2 class="mt-3">TRMS</h2>
                                    <p class="text-light opacity-75">ابدأ رحلتك نحو الإنتاجية المثلى</p>
                                </div>
                                
                                <div class="benefits-list mb-5">
                                    <h5 class="text-light mb-4">مميزات الانضمام إلى TRMS</h5>
                                    <div class="benefit-item">
                                        <div class="benefit-icon">
                                            <i class="fas fa-rocket"></i>
                                        </div>
                                        <div class="benefit-text">
                                            <h6>14 يوم تجربة مجانية</h6>
                                            <p>استمتع بجميع المميزات دون أي تكلفة</p>
                                        </div>
                                    </div>
                                    
                                    <div class="benefit-item">
                                        <div class="benefit-icon">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                        <div class="benefit-text">
                                            <h6>زيادة الإيرادات</h6>
                                            <p>متوسط زيادة 30% في الإيرادات للمستخدمين</p>
                                        </div>
                                    </div>
                                    
                                    <div class="benefit-item">
                                        <div class="benefit-icon">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="benefit-text">
                                            <h6>توفير الوقت</h6>
                                            <p>وفر 25% من وقت التخطيط اليومي</p>
                                        </div>
                                    </div>
                                    
                                    <div class="benefit-item">
                                        <div class="benefit-icon">
                                            <i class="fas fa-headset"></i>
                                        </div>
                                        <div class="benefit-text">
                                            <h6>دعم فني 24/7</h6>
                                            <p>فريق دعم فني متاح على مدار الساعة</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="testimonials-slider">
                                    <div class="testimonial-slide active">
                                        <p class="testimonial-text">"TRMS ساعدني في تنظيم عملي وزيادة إيراداتي بنسبة 40% في أول شهر"</p>
                                        <div class="testimonial-author">
                                            <strong>سارة العلي</strong>
                                            <span>مصممة UI/UX</span>
                                        </div>
                                    </div>
                                    
                                    <div class="testimonial-slide">
                                        <p class="testimonial-text">"من أفضل الأنظمة التي استخدمتها لإدارة مشاريعي الحرة"</p>
                                        <div class="testimonial-author">
                                            <strong>خالد السعدي</strong>
                                            <span>مطور ويب مستقل</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- نافذة التحقق من البريد -->
    <div class="modal fade" id="verificationModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-envelope text-primary me-2"></i>
                        تحقق من بريدك الإلكتروني
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-envelope-open-text fa-4x text-primary mb-3"></i>
                    <h5 class="mb-3">أرسلنا رمز التحقق</h5>
                    <p class="text-muted mb-4">
                        تم إرسال رمز التحقق إلى بريدك الإلكتروني. 
                        أدخل الرمز لتأكيد حسابك.
                    </p>
                    
                    <div class="verification-inputs mb-4">
                        <input type="text" maxlength="1" class="verification-digit" autofocus>
                        <input type="text" maxlength="1" class="verification-digit">
                        <input type="text" maxlength="1" class="verification-digit">
                        <input type="text" maxlength="1" class="verification-digit">
                        <input type="text" maxlength="1" class="verification-digit">
                        <input type="text" maxlength="1" class="verification-digit">
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary" id="verifyCode">
                            تأكيد الرمز
                        </button>
                        <button type="button" class="btn btn-link text-decoration-none" id="resendCode">
                            إعادة إرسال الرمز
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/password-strength-meter/2.3.1/password.min.js"></script>
    <script src="assets/js/auth.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const registerForm = document.getElementById('registerForm');
            const steps = document.querySelectorAll('.step');
            const stepContents = document.querySelectorAll('.step-content');
            let currentStep = 1;
            
            // تهيئة الخطوات
            updateSteps();
            
            // زر التالي للخطوة 1
            document.getElementById('nextStep1').addEventListener('click', function() {
                if (validateStep1()) {
                    goToStep(2);
                }
            });
            
            // زر التالي للخطوة 2
            document.getElementById('nextStep2').addEventListener('click', function() {
                if (validateStep2()) {
                    updateConfirmation();
                    goToStep(3);
                }
            });
            
            // زر السابق للخطوة 2
            document.getElementById('prevStep2').addEventListener('click', function() {
                goToStep(1);
            });
            
            // زر السابق للخطوة 3
            document.getElementById('prevStep3').addEventListener('click', function() {
                goToStep(2);
            });
            
            // إظهار/إخفاء كلمة المرور
            document.getElementById('togglePassword').addEventListener('click', function() {
                const passwordInput = document.getElementById('password');
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
            
            document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
                const confirmInput = document.getElementById('confirm_password');
                const type = confirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
            
            // التحقق من اسم المستخدم
            document.getElementById('checkUsername').addEventListener('click', checkUsernameAvailability);
            
            // التحقق من البريد الإلكتروني
            document.getElementById('checkEmail').addEventListener('click', checkEmailAvailability);
            
            // قياس قوة كلمة المرور
            document.getElementById('password').addEventListener('input', checkPasswordStrength);
            
            // التحقق من تطابق كلمة المرور
            document.getElementById('confirm_password').addEventListener('input', checkPasswordMatch);
            
            // إضافة الوسوم
            document.getElementById('addTag').addEventListener('click', addTag);
            document.getElementById('tagInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addTag();
                }
            });
            
            // إرسال النموذج
            registerForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (validateAllSteps()) {
                    showVerificationModal();
                }
            });
            
            // وظائف مساعدة
            function goToStep(step) {
                currentStep = step;
                updateSteps();
            }
            
            function updateSteps() {
                // تحديث مؤشر الخطوات
                steps.forEach(s => {
                    const stepNum = parseInt(s.getAttribute('data-step'));
                    if (stepNum === currentStep) {
                        s.classList.add('active');
                    } else if (stepNum < currentStep) {
                        s.classList.add('completed');
                        s.classList.remove('active');
                    } else {
                        s.classList.remove('active', 'completed');
                    }
                });
                
                // إظهار محتوى الخطوة الحالية
                stepContents.forEach(content => {
                    if (content.id === 'step' + currentStep) {
                        content.classList.remove('d-none');
                    } else {
                        content.classList.add('d-none');
                    }
                });
            }
            
            function validateStep1() {
                let isValid = true;
                const fields = ['full_name', 'username', 'email', 'password', 'confirm_password'];
                
                fields.forEach(field => {
                    const input = document.getElementById(field);
                    const error = document.getElementById(field + 'Error');
                    
                    if (!input.value.trim()) {
                        error.classList.add('d-block');
                        isValid = false;
                    } else {
                        error.classList.remove('d-block');
                    }
                });
                
                // التحقق من صحة البريد الإلكتروني
                const email = document.getElementById('email').value;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    document.getElementById('emailError').classList.add('d-block');
                    document.getElementById('emailError').textContent = 'البريد الإلكتروني غير صالح';
                    isValid = false;
                }
                
                // التحقق من تطابق كلمة المرور
                if (document.getElementById('password').value !== document.getElementById('confirm_password').value) {
                    document.getElementById('confirm_passwordError').classList.add('d-block');
                    isValid = false;
                }
                
                return isValid;
            }
            
            function validateStep2() {
                const terms = document.getElementById('terms');
                if (!terms.checked) {
                    document.getElementById('termsError').classList.add('d-block');
                    return false;
                }
                document.getElementById('termsError').classList.remove('d-block');
                return true;
            }
            
            function validateAllSteps() {
                return validateStep1() && validateStep2();
            }
            
            function checkUsernameAvailability() {
                const username = document.getElementById('username').value;
                if (!username) return;
                
                // محاكاة التحقق من الخادم
                const btn = document.getElementById('checkUsername');
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
                btn.disabled = true;
                
                setTimeout(() => {
                    // في التطبيق الحقيقي، سيكون هنا استدعاء AJAX
                    btn.innerHTML = '<i class="fas fa-check text-success"></i>';
                    setTimeout(() => {
                        btn.innerHTML = '<i class="fas fa-check"></i>';
                        btn.disabled = false;
                    }, 1000);
                }, 1000);
            }
            
            function checkEmailAvailability() {
                const email = document.getElementById('email').value;
                if (!email) return;
                
                const btn = document.getElementById('checkEmail');
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
                btn.disabled = true;
                
                setTimeout(() => {
                    btn.innerHTML = '<i class="fas fa-check text-success"></i>';
                    setTimeout(() => {
                        btn.innerHTML = '<i class="fas fa-check"></i>';
                        btn.disabled = false;
                    }, 1000);
                }, 1000);
            }
            
            function checkPasswordStrength() {
                const password = document.getElementById('password').value;
                const strengthBar = document.getElementById('passwordStrength');
                const strengthText = document.getElementById('passwordStrengthText');
                
                if (!password) {
                    strengthBar.style.width = '0%';
                    strengthBar.className = 'progress-bar';
                    strengthText.textContent = 'قوة كلمة المرور';
                    return;
                }
                
                // حساب القوة (مثال مبسط)
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
                const password = document.getElementById('password').value;
                const confirm = document.getElementById('confirm_password').value;
                const error = document.getElementById('confirm_passwordError');
                
                if (password !== confirm) {
                    error.classList.add('d-block');
                } else {
                    error.classList.remove('d-block');
                }
            }
            
            function addTag() {
                const tagInput = document.getElementById('tagInput');
                const tag = tagInput.value.trim();
                
                if (tag) {
                    const tagsContainer = document.getElementById('selectedTags');
                    const tagElement = document.createElement('span');
                    tagElement.className = 'badge bg-primary me-2 mb-2';
                    tagElement.innerHTML = tag + ' <i class="fas fa-times ms-1" onclick="removeTag(this)"></i>';
                    tagsContainer.appendChild(tagElement);
                    
                    updateTagsInput();
                    tagInput.value = '';
                }
            }
            
            function removeTag(element) {
                element.parentElement.remove();
                updateTagsInput();
            }
            
            function updateTagsInput() {
                const tags = [];
                document.querySelectorAll('#selectedTags .badge').forEach(badge => {
                    tags.push(badge.childNodes[0].textContent.trim());
                });
                document.getElementById('tags').value = JSON.stringify(tags);
            }
            
            function updateConfirmation() {
                document.getElementById('confirmFullName').textContent = document.getElementById('full_name').value;
                document.getElementById('confirmUsername').textContent = document.getElementById('username').value;
                document.getElementById('confirmEmail').textContent = document.getElementById('email').value;
                document.getElementById('confirmPhone').textContent = document.getElementById('phone').value || 'غير مضاف';
                document.getElementById('confirmTarget').textContent = document.getElementById('daily_target').value;
                document.getElementById('confirmHours').textContent = document.getElementById('work_hours').value;
                document.getElementById('confirmStart').textContent = document.getElementById('work_start').options[document.getElementById('work_start').selectedIndex].text;
                document.getElementById('confirmStrategy').textContent = document.getElementById('optimization').options[document.getElementById('optimization').selectedIndex].text;
                
                const tags = JSON.parse(document.getElementById('tags').value || '[]');
                document.getElementById('confirmTags').textContent = tags.length > 0 ? tags.join(', ') : 'غير مضاف';
            }
            
            function showVerificationModal() {
                const modal = new bootstrap.Modal(document.getElementById('verificationModal'));
                modal.show();
                
                // تهيئة حقول إدخال الرمز
                const inputs = document.querySelectorAll('.verification-digit');
                inputs.forEach((input, index) => {
                    input.addEventListener('input', function() {
                        if (this.value.length === 1 && index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        }
                    });
                    
                    input.addEventListener('keydown', function(e) {
                        if (e.key === 'Backspace' && !this.value && index > 0) {
                            inputs[index - 1].focus();
                        }
                    });
                });
            }
            
            // تهيئة شهادات العملاء
            initTestimonialsSlider();
        });
        
        // شريط شهادات العملاء المنزلق
        function initTestimonialsSlider() {
            let currentSlide = 0;
            const slides = document.querySelectorAll('.testimonial-slide');
            
            function showSlide(index) {
                slides.forEach(slide => slide.classList.remove('active'));
                slides[index].classList.add('active');
            }
            
            // التبديل التلقائي كل 5 ثواني
            setInterval(() => {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            }, 5000);
        }
    </script>
</body>
</html>