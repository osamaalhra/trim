// التحكم في شريط التنقل
class NavbarController {
    constructor() {
        this.navbar = document.getElementById('mainNavbar');
        this.lastScroll = 0;
        
        this.init();
    }
    
    init() {
        // تأثير التمرير
        window.addEventListener('scroll', () => this.handleScroll());
        
        // إغلاق القائمة عند النقر على رابط في الموبايل
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                const navbarCollapse = document.getElementById('navbarContent');
                if (navbarCollapse.classList.contains('show')) {
                    bootstrap.Collapse.getInstance(navbarCollapse).hide();
                }
            });
        });
    }
    
    handleScroll() {
        const currentScroll = window.pageYOffset;
        
        // إضافة/إزالة clase عند التمرير
        if (currentScroll > 50) {
            this.navbar.classList.add('scrolled');
        } else {
            this.navbar.classList.remove('scrolled');
        }
        
        // إخفاء/إظهار الشريط عند التمرير للأعلى/الأسفل
        if (currentScroll > this.lastScroll && currentScroll > 100) {
            this.navbar.style.transform = 'translateY(-100%)';
        } else {
            this.navbar.style.transform = 'translateY(0)';
        }
        
        this.lastScroll = currentScroll;
    }
}

// نظام التنقل السلس
class SmoothScroller {
    constructor() {
        this.init();
    }
    
    init() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => this.handleClick(e, anchor));
        });
    }
    
    handleClick(e, anchor) {
        const href = anchor.getAttribute('href');
        
        if (href === '#' || href === '#!') return;
        
        const target = document.querySelector(href);
        if (!target) return;
        
        e.preventDefault();
        
        window.scrollTo({
            top: target.offsetTop - 80,
            behavior: 'smooth'
        });
    }
}

// نظام الأزرار العائمة
class FloatingButtons {
    constructor() {
        this.buttons = document.querySelector('.floating-buttons');
        this.scrollTopBtn = document.querySelector('.floating-button.scroll-top');
        
        this.init();
    }
    
    init() {
        // إظهار/إخفاء زر الانتقال للأعلى
        window.addEventListener('scroll', () => this.toggleScrollTopButton());
        
        // النقر على زر الانتقال للأعلى
        if (this.scrollTopBtn) {
            this.scrollTopBtn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
        
        // تهيئة tooltips
        this.initTooltips();
    }
    
    toggleScrollTopButton() {
        if (window.scrollY > 300) {
            this.scrollTopBtn.style.opacity = '1';
            this.scrollTopBtn.style.visibility = 'visible';
        } else {
            this.scrollTopBtn.style.opacity = '0';
            this.scrollTopBtn.style.visibility = 'hidden';
        }
    }
    
    initTooltips() {
        const tooltipTriggerList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="tooltip"]')
        );
        tooltipTriggerList.map(tooltipTriggerEl => {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
}

// نظام التأثيرات التفاعلية
class InteractiveEffects {
    constructor() {
        this.init();
    }
    
    init() {
        // تأثيرات بطاقات الميزات
        this.initFeatureCards();
        
        // تأثيرات الإحصائيات
        this.initStatsAnimation();
        
        // تأثيرات قائمة المهام
        this.initTaskItems();
    }
    
    initFeatureCards() {
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-10px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0) scale(1)';
            });
        });
    }
    
    initStatsAnimation() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateStats(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        document.querySelectorAll('.stat-number').forEach(stat => {
            observer.observe(stat);
        });
    }
    
    animateStats(statElement) {
        const value = statElement.textContent;
        const numericValue = parseFloat(value);
        
        if (!isNaN(numericValue)) {
            let start = 0;
            const duration = 2000;
            const increment = numericValue / (duration / 16);
            
            const timer = setInterval(() => {
                start += increment;
                if (start >= numericValue) {
                    statElement.textContent = value;
                    clearInterval(timer);
                } else {
                    statElement.textContent = Math.floor(start) + (value.includes('%') ? '%' : '');
                }
            }, 16);
        }
    }
    
    initTaskItems() {
        document.querySelectorAll('.task-item').forEach(task => {
            task.addEventListener('click', (e) => {
                if (!e.target.closest('.task-check')) return;
                
                const checkbox = task.querySelector('input[type="checkbox"]');
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                    task.classList.toggle('completed', checkbox.checked);
                }
            });
        });
    }
}

// نظام الإشعارات
class NotificationSystem {
    constructor() {
        this.notificationContainer = null;
        this.createContainer();
    }
    
    createContainer() {
        this.notificationContainer = document.createElement('div');
        this.notificationContainer.className = 'notification-container';
        document.body.appendChild(this.notificationContainer);
    }
    
    show(message, type = 'info', duration = 5000) {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type} animate__animated animate__fadeInUp`;
        
        const icon = this.getIcon(type);
        
        notification.innerHTML = `
            <div class="notification-icon">${icon}</div>
            <div class="notification-content">
                <div class="notification-message">${message}</div>
            </div>
            <button class="notification-close">&times;</button>
        `;
        
        this.notificationContainer.appendChild(notification);
        
        // إغلاق الإشعار عند النقر على الزر
        const closeBtn = notification.querySelector('.notification-close');
        closeBtn.addEventListener('click', () => this.removeNotification(notification));
        
        // إغلاق تلقائي بعد المدة المحددة
        if (duration > 0) {
            setTimeout(() => this.removeNotification(notification), duration);
        }
    }
    
    getIcon(type) {
        const icons = {
            success: '<i class="fas fa-check-circle"></i>',
            error: '<i class="fas fa-times-circle"></i>',
            warning: '<i class="fas fa-exclamation-triangle"></i>',
            info: '<i class="fas fa-info-circle"></i>'
        };
        return icons[type] || icons.info;
    }
    
    removeNotification(notification) {
        notification.classList.add('animate__fadeOutUp');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }
}

// تهيئة التطبيق عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    // تهيئة AOS
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    }
    
    // تهيئة الأنظمة
    new NavbarController();
    new SmoothScroller();
    new FloatingButtons();
    new InteractiveEffects();
    window.notifications = new NotificationSystem();
    
    // تأثيرات إضافية
    initAdditionalEffects();
});

// تأثيرات إضافية
function initAdditionalEffects() {
    // تأثيرات النصوص
    const animatedTexts = document.querySelectorAll('.animate-text');
    animatedTexts.forEach(text => {
        text.style.animationDelay = Math.random() * 0.5 + 's';
    });
    
    // تأثيرات الأزرار
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('mousedown', function() {
            this.style.transform = 'scale(0.95)';
        });
        
        btn.addEventListener('mouseup', function() {
            this.style.transform = 'scale(1)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // تأثيرات نموذج النشرة البريدية
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            // محاكاة إرسال النشرة
            setTimeout(() => {
                if (window.notifications) {
                    window.notifications.show(
                        'تم الاشتراك بنجاح في النشرة البريدية',
                        'success'
                    );
                }
                this.reset();
            }, 500);
        });
    }
}

// تأثيرات Parallax للخلفية
function initParallax() {
    const heroSection = document.querySelector('.hero-section');
    if (!heroSection) return;
    
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const rate = scrolled * -0.5;
        
        heroSection.style.backgroundPosition = `center ${rate}px`;
    });
}

// تهيئة تأثيرات Parallax
if (window.innerWidth > 768) {
    initParallax();
}