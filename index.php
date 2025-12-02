<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRMS - نظام إدارة إيرادات المهام الذكي</title>
    
    <!-- Bootstrap 5 مع تحسينات -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Animate.css للرسوم المتحركة -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- AOS للتمريرات المتحركة -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
    <!-- شريط التنقل المحسّن -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNavbar">
        <div class="container">
            <!-- الشعار مع تأثيرات -->
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <div class="logo-icon me-2">
                    <i class="fas fa-chart-line"></i>
                </div>
                <span class="brand-text">
                    <span class="fw-bold">TRMS</span>
                    <small class="d-block fw-light">نظام الإدارة الذكي</small>
                </span>
            </a>
            
            <!-- زر القائمة للموبايل -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- عناصر التنقل -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="#hero">
                            <i class="fas fa-home me-1"></i> الرئيسية
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">
                            <i class="fas fa-star me-1"></i> المميزات
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#dashboard-preview">
                            <i class="fas fa-tachometer-alt me-1"></i> لوحة التحكم
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">
                            <i class="fas fa-play-circle me-1"></i> طريقة العمل
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pricing">
                            <i class="fas fa-tags me-1"></i> الأسعار
                        </a>
                    </li>
                </ul>
                
                <!-- أزرار الدخول والتسجيل -->
                <div class="d-flex gap-2 ms-lg-3">
                    <a href="login.php" class="btn btn-outline-light">
                        <i class="fas fa-sign-in-alt me-1"></i> تسجيل الدخول
                    </a>
                    <a href="register.php" class="btn btn-primary">
                        <i class="fas fa-user-plus me-1"></i> حساب جديد
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- قسم البطل (Hero Section) -->
    <section class="hero-section" id="hero">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6" data-aos="fade-right">
                    <h1 class="display-4 fw-bold mb-4">
                        حوّل <span class="text-primary">مهامك</span> إلى 
                        <span class="text-success">إيرادات</span>
                    </h1>
                    <p class="lead mb-4">
                        نظام متكامل لإدارة المهام اليومية وتتبع الإيرادات. 
                        خطط، نفذ، وحقق أهدافك المالية بذكاء وكفاءة.
                    </p>
                    
                    <div class="d-flex flex-wrap gap-3 mb-5">
                        <a href="#cta" class="btn btn-primary btn-lg px-4 py-3">
                            <i class="fas fa-rocket me-2"></i> ابدأ مجانًا الآن
                        </a>
                        <a href="#features" class="btn btn-outline-light btn-lg px-4 py-3">
                            <i class="fas fa-play-circle me-2"></i> شاهد الفيديو التوضيحي
                        </a>
                    </div>
                    
                    <!-- إحصائيات سريعة -->
                    <div class="row stats-preview">
                        <div class="col-4">
                            <div class="stat-box">
                                <h3 class="stat-number">+15%</h3>
                                <p class="stat-label">زيادة في الإنتاجية</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-box">
                                <h3 class="stat-number">+30%</h3>
                                <p class="stat-label">زيادة في الإيرادات</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-box">
                                <h3 class="stat-number">-25%</h3>
                                <p class="stat-label">توفير في الوقت</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6" data-aos="fade-left">
                    <!-- نموذج حيوي للوحة التحكم -->
                    <div class="dashboard-preview-card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم</h6>
                                <span class="badge bg-success">مباشر</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- مؤشرات حية -->
                            <div class="live-indicators">
                                <div class="indicator success">
                                    <div class="indicator-label">الهدف اليومي</div>
                                    <div class="indicator-value">2,500 ريال</div>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 75%"></div>
                                    </div>
                                </div>
                                <div class="indicator warning">
                                    <div class="indicator-label">المحقق</div>
                                    <div class="indicator-value">1,875 ريال</div>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 75%"></div>
                                    </div>
                                </div>
                                <div class="indicator info">
                                    <div class="indicator-label">الوقت المتبقي</div>
                                    <div class="indicator-value">3:45 ساعة</div>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 60%"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- قائمة مهام مصغرة -->
                            <div class="mini-tasks-list">
                                <h6 class="section-title">المهام النشطة</h6>
                                <div class="task-item completed">
                                    <i class="fas fa-check-circle"></i>
                                    <span>تصميم واجهة المستخدم</span>
                                    <span class="task-time">+150 ريال</span>
                                </div>
                                <div class="task-item in-progress">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    <span>تطوير API</span>
                                    <span class="task-time">جاري التنفيذ</span>
                                </div>
                                <div class="task-item pending">
                                    <i class="far fa-clock"></i>
                                    <span>كتابة التقارير</span>
                                    <span class="task-time">+200 ريال</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- موجة تصميمية في الأسفل -->
        <div class="wave-divider">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"></path>
            </svg>
        </div>
    </section>

    <!-- قسم المميزات -->
    <section class="features-section py-5" id="features">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">مميزات النظام المتقدمة</h2>
                <p class="section-subtitle">كل ما تحتاجه لتحقيق أقصى استفادة من وقتك وأعمالك</p>
            </div>
            
            <div class="row g-4">
                <!-- بطاقة الميزة 1 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card h-100">
                        <div class="feature-icon bg-primary-gradient">
                            <i class="fas fa-brain"></i>
                        </div>
                        <h4 class="feature-title">تخطيط ذكي</h4>
                        <p class="feature-description">
                            خوارزميات ذكية لتحسين جدولة المهام بناءً على الإيرادات والأولوية والوقت
                        </p>
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle text-success me-2"></i>تحليل تلقائي للمهام</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>توليد خطط مخصصة</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>تكييف ديناميكي مع التغييرات</li>
                        </ul>
                    </div>
                </div>
                
                <!-- بطاقة الميزة 2 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card h-100">
                        <div class="feature-icon bg-success-gradient">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h4 class="feature-title">تحليل متقدم</h4>
                        <p class="feature-description">
                            لوحة تحكم شاملة مع رسوم بيانية وتقارير مفصلة عن الأداء والإيرادات
                        </p>
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle text-success me-2"></i>تقارير أداء فورية</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>مقارنات تاريخية</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>تنبؤات ذكية</li>
                        </ul>
                    </div>
                </div>
                
                <!-- بطاقة الميزة 3 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card h-100">
                        <div class="feature-icon bg-warning-gradient">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h4 class="feature-title">تتبع دقيق</h4>
                        <p class="feature-description">
                            نظام تتبع الوقت الفعلي مع مقارنة الأداء الفعلي بالمخطط
                        </p>
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle text-success me-2"></i>مؤقتات ذكية</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>تتبع الفروقات الزمنية</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>تنبيهات الأداء</li>
                        </ul>
                    </div>
                </div>
                
                <!-- بطاقة الميزة 4 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card h-100">
                        <div class="feature-icon bg-info-gradient">
                            <i class="fas fa-sync-alt"></i>
                        </div>
                        <h4 class="feature-title">مزامنة متعددة</h4>
                        <p class="feature-description">
                            عمل متكامل على جميع الأجهزة مع مزامنة فورية للبيانات
                        </p>
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle text-success me-2"></i>تطبيق ويب متجاوب</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>مزامنة فورية</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>نسخ احتياطي تلقائي</li>
                        </ul>
                    </div>
                </div>
                
                <!-- بطاقة الميزة 5 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-card h-100">
                        <div class="feature-icon bg-danger-gradient">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4 class="feature-title">أمان متكامل</h4>
                        <p class="feature-description">
                            حماية كاملة للبيانات مع تشفير متقدم وأنظمة أمان متعددة الطبقات
                        </p>
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle text-success me-2"></i>تشفير AES-256</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>مصادقة ثنائية</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>سجلات أمنية مفصلة</li>
                        </ul>
                    </div>
                </div>
                
                <!-- بطاقة الميزة 6 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-card h-100">
                        <div class="feature-icon bg-purple-gradient">
                            <i class="fas fa-gamepad"></i>
                        </div>
                        <h4 class="feature-title">محاكاة وتحليل</h4>
                        <p class="feature-description">
                            أداة محاكاة لاختبار السيناريوهات المختلفة وتحليل النتائج المتوقعة
                        </p>
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle text-success me-2"></i>تحليل "ماذا لو"</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>مقارنة الخطط البديلة</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>توصيات ذكية</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- معاينة لوحة التحكم -->
    <section class="dashboard-preview-section py-5" id="dashboard-preview">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">لوحة تحكم قوية وشاملة</h2>
                <p class="section-subtitle">تحكم كامل في عملك من خلال واجهة بديهية وسهلة الاستخدام</p>
            </div>
            
            <div class="dashboard-mockup" data-aos="zoom-in">
                <!-- شريط التحكم العلوي -->
                <div class="dashboard-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="dashboard-title">
                            <h5><i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم الرئيسية</h5>
                            <small class="text-muted">آخر تحديث: الآن</small>
                        </div>
                        <div class="dashboard-actions">
                            <button class="btn btn-sm btn-outline-light me-2">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-light">
                                <i class="fas fa-cog"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- محتوى لوحة التحكم -->
                <div class="dashboard-content">
                    <div class="row g-3">
                        <!-- بطاقات الإحصائيات -->
                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card">
                                <div class="stat-icon bg-primary">
                                    <i class="fas fa-bullseye"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>2,500 <small>ريال</small></h3>
                                    <p>الهدف اليومي</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card">
                                <div class="stat-icon bg-success">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>1,875 <small>ريال</small></h3>
                                    <p>المحقق حتى الآن</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card">
                                <div class="stat-icon bg-warning">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>3:45 <small>ساعة</small></h3>
                                    <p>الوقت المتبقي</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card">
                                <div class="stat-icon bg-info">
                                    <i class="fas fa-tasks"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>8/12</h3>
                                    <p>المهام المكتملة</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- الرسوم البيانية -->
                        <div class="col-lg-8">
                            <div class="chart-container">
                                <div class="chart-header">
                                    <h6>التقدم اليومي</h6>
                                    <select class="form-select form-select-sm w-auto">
                                        <option>اليوم</option>
                                        <option>الأسبوع</option>
                                        <option>الشهر</option>
                                    </select>
                                </div>
                                <div class="chart-placeholder">
                                    <!-- هنا سيتم عرض الرسم البياني -->
                                    <div class="fake-chart">
                                        <div class="chart-bar" style="height: 80%"></div>
                                        <div class="chart-bar" style="height: 60%"></div>
                                        <div class="chart-bar" style="height: 90%"></div>
                                        <div class="chart-bar" style="height: 75%"></div>
                                        <div class="chart-bar" style="height: 85%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- قائمة المهام العاجلة -->
                        <div class="col-lg-4">
                            <div class="tasks-container">
                                <div class="tasks-header">
                                    <h6>المهام العاجلة</h6>
                                    <span class="badge bg-danger">3 جديدة</span>
                                </div>
                                <div class="tasks-list">
                                    <div class="task-item urgent">
                                        <div class="task-check">
                                            <input type="checkbox" checked>
                                        </div>
                                        <div class="task-details">
                                            <h6>اجتماع مع العميل</h6>
                                            <small>بعد 30 دقيقة - +500 ريال</small>
                                        </div>
                                    </div>
                                    <div class="task-item">
                                        <div class="task-check">
                                            <input type="checkbox">
                                        </div>
                                        <div class="task-details">
                                            <h6>مراجعة التصميم</h6>
                                            <small>ساعة - +300 ريال</small>
                                        </div>
                                    </div>
                                    <div class="task-item">
                                        <div class="task-check">
                                            <input type="checkbox">
                                        </div>
                                        <div class="task-details">
                                            <h6>إرسال التقارير</h6>
                                            <small>ساعتين - +450 ريال</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- كيف يعمل النظام -->
    <section class="how-it-works py-5 bg-light" id="how-it-works">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">كيف يعمل النظام؟</h2>
                <p class="section-subtitle">4 خطوات بسيطة لتحقيق أهدافك المالية</p>
            </div>
            
            <div class="row">
                <!-- الخطوة 1 -->
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <div class="step-icon">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <h4 class="step-title">أضف مهامك</h4>
                        <p class="step-description">
                            قم بإضافة جميع مهامك مع تحديد الوقت المقدر والقيمة المالية لكل مهمة
                        </p>
                    </div>
                </div>
                
                <!-- الخطوة 2 -->
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <div class="step-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h4 class="step-title">خطط ليومك</h4>
                        <p class="step-description">
                            دع النظام يولد خطة ذكية أو قم بإنشاء خطتك الخاصة
                        </p>
                    </div>
                </div>
                
                <!-- الخطوة 3 -->
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <div class="step-icon">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <h4 class="step-title">نفذ واتبع</h4>
                        <p class="step-description">
                            ابدأ التنفيذ، تتبع الوقت الفعلي، واحصل على تحديثات فورية
                        </p>
                    </div>
                </div>
                
                <!-- الخطوة 4 -->
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <div class="step-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4 class="step-title">حلل وطور</h4>
                        <p class="step-description">
                            تابع أداءك، احصل على تقارير مفصلة، وحسن من إنتاجيتك
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- فيديو توضيحي -->
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto" data-aos="zoom-in">
                    <div class="video-container">
                        <div class="video-placeholder">
                            <button class="play-button">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- دعوة للعمل -->
    <section class="cta-section py-5" id="cta">
        <div class="container">
            <div class="cta-card">
                <div class="row align-items-center">
                    <div class="col-lg-8" data-aos="fade-right">
                        <h2 class="cta-title">جاهز لزيادة إنتاجيتك وإيراداتك؟</h2>
                        <p class="cta-description">
                            انضم إلى آلاف المحترفين الذين حولوا TRMS إلى شريكهم الذكي في إدارة الأعمال. 
                            ابدأ مجانًا اليوم واستمتع بجميع المميزات لمدة 14 يومًا.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end" data-aos="fade-left">
                        <a href="register.php" class="btn btn-light btn-lg px-5 py-3">
                            <i class="fas fa-rocket me-2"></i> ابدأ التجربة المجانية
                        </a>
                        <p class="mt-2 text-light opacity-75">
                            لا حاجة لبطاقة ائتمان - تجربة كاملة لمدة 14 يومًا
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- تذييل الصفحة -->
    <footer class="footer py-5">
        <div class="container">
            <div class="row">
                <!-- معلومات الشركة -->
                <div class="col-lg-4 mb-4">
                    <div class="footer-brand">
                        <div class="logo-icon me-2">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4>TRMS</h4>
                    </div>
                    <p class="mt-3">
                        نظام متكامل لإدارة المهام وتتبع الإيرادات، 
                        مصمم لمساعدة المحترفين على تحقيق أقصى استفادة من وقتهم وأعمالهم.
                    </p>
                    <div class="social-links mt-4">
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <!-- روابط سريعة -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="footer-title">روابط سريعة</h5>
                    <ul class="footer-links">
                        <li><a href="#hero">الرئيسية</a></li>
                        <li><a href="#features">المميزات</a></li>
                        <li><a href="#how-it-works">كيف يعمل</a></li>
                        <li><a href="#pricing">الأسعار</a></li>
                        <li><a href="blog.php">المدونة</a></li>
                    </ul>
                </div>
                
                <!-- الدعم -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="footer-title">الدعم</h5>
                    <ul class="footer-links">
                        <li><a href="help.php">المركز المساعد</a></li>
                        <li><a href="docs.php">التوثيق</a></li>
                        <li><a href="contact.php">اتصل بنا</a></li>
                        <li><a href="privacy.php">الخصوصية</a></li>
                        <li><a href="terms.php">الشروط</a></li>
                    </ul>
                </div>
                
                <!-- اتصل بنا -->
                <div class="col-lg-4 mb-4">
                    <h5 class="footer-title">ابقَ على تواصل</h5>
                    <p class="mb-3">
                        اشترك في نشرتنا البريدية للحصول على آخر التحديثات والنصائح.
                    </p>
                    <form class="newsletter-form">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="بريدك الإلكتروني" required>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- حقوق النشر -->
            <div class="footer-bottom text-center pt-4 mt-4 border-top">
                <p class="mb-0">
                    © 2024 TRMS. جميع الحقوق محفوظة. 
                    <span class="d-block d-md-inline mt-2 mt-md-0">
                        صمم بـ <i class="fas fa-heart text-danger"></i> للمحترفين العرب
                    </span>
                </p>
            </div>
        </div>
    </footer>

    <!-- الأزرار العائمة -->
    <div class="floating-buttons">
        <button class="floating-button whatsapp" data-bs-toggle="tooltip" title="تواصل عبر واتساب">
            <i class="fab fa-whatsapp"></i>
        </button>
        <button class="floating-button chat" data-bs-toggle="tooltip" title="الدردشة المباشرة">
            <i class="fas fa-comment-alt"></i>
        </button>
        <button class="floating-button scroll-top" data-bs-toggle="tooltip" title="انتقل للأعلى">
            <i class="fas fa-arrow-up"></i>
        </button>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="assets/js/main.js"></script>
    
    <script>
        // تهيئة AOS للرسوم المتحركة
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
        
        // إضافة تأثير التمرير لشريط التنقل
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNavbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // إضافة تأثيرات التفاعل
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.classList.add('hovered');
            });
            
            card.addEventListener('mouseleave', function() {
                this.classList.remove('hovered');
            });
        });
    </script>
</body>
</html>