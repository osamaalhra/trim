/**
 * مكتبة JavaScript لصفحات المصادقة في TRMS
 */

class AuthSystem {
    constructor() {
        this.init();
    }
    
    init() {
        this.initPasswordToggles();
        this.initFormValidation();
        this.initInputMasks();
        this.initAutoSave();
        this.initSessionCheck();
    }
    
    initPasswordToggles() {
        // إضافة أزرار إظهار/إخفاء كلمة المرور
        document.querySelectorAll('input[type="password"]').forEach(input => {
            const parent = input.parentElement;
            if (parent.classList.contains('input-group')) {
                const toggleBtn = document.createElement('button');
                toggleBtn.type = 'button';
                toggleBtn.className = 'btn btn-outline-secondary';
                toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
                toggleBtn.addEventListener('click', () => this.togglePassword(input, toggleBtn));
                parent.appendChild(toggleBtn);
            }
        });
    }
    
    togglePassword(input, button) {
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        button.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    }
    
    initFormValidation() {
        // التحقق من النماذج في الوقت الحقيقي
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => this.validateForm(e, form));
            
            // التحقق أثناء الكتابة
            form.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', () => this.validateInput(input));
                input.addEventListener('blur', () => this.validateInput(input));
            });
        });
    }
    
    validateForm(e, form) {
        let isValid = true;
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        
        inputs.forEach(input => {
            if (!this.validateInput(input)) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            this.showFormError(form, 'يرجى تصحيح الأخطاء في النموذج');
        }
        
        return isValid;
    }
    
    validateInput(input) {
        const value = input.value.trim();
        const errorElement = document.getElementById(input.id + 'Error') || 
                            input.parentElement.querySelector('.invalid-feedback');
        
        // إزالة رسالة الخطأ السابقة
        if (errorElement) {
            errorElement.classList.remove('d-block');
        }
        
        // التحقق من الحقول المطلوبة
        if (input.hasAttribute('required') && !value) {
            this.showInputError(input, 'هذا الحقل مطلوب');
            return false;
        }
        
        // التحقق من صيغة البريد الإلكتروني
        if (input.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                this.showInputError(input, 'البريد الإلكتروني غير صالح');
                return false;
            }
        }
        
        // التحقق من كلمة المرور
        if (input.type === 'password' && value) {
            if (value.length < 8) {
                this.showInputError(input, 'كلمة المرور يجب أن تكون 8 أحرف على الأقل');
                return false;
            }
            
            if (input.id === 'confirm_password' || input.id === 'confirm_new_password') {
                const passwordId = input.id.replace('confirm_', '');
                const password = document.getElementById(passwordId);
                if (password && password.value !== value) {
                    this.showInputError(input, 'كلمات المرور غير متطابقة');
                    return false;
                }
            }
        }
        
        // التحقق من اسم المستخدم
        if (input.name === 'username' && value) {
            const usernameRegex = /^[a-zA-Z0-9._]+$/;
            if (!usernameRegex.test(value)) {
                this.showInputError(input, 'اسم المستخدم يمكن أن يحتوي على أحرف إنجليزية وأرقام ونقاط فقط');
                return false;
            }
            
            if (value.length < 3) {
                this.showInputError(input, 'اسم المستخدم يجب أن يكون 3 أحرف على الأقل');
                return false;
            }
        }
        
        // إذا كانت جميع التحققات ناجحة
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        return true;
    }
    
    showInputError(input, message) {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        
        let errorElement = document.getElementById(input.id + 'Error');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'invalid-feedback';
            errorElement.id = input.id + 'Error';
            input.parentElement.appendChild(errorElement);
        }
        
        errorElement.textContent = message;
        errorElement.classList.add('d-block');
        
        // تأثير الاهتزاز
        input.classList.add('shake');
        setTimeout(() => input.classList.remove('shake'), 500);
    }
    
    showFormError(form, message) {
        // إنشاء أو تحديث رسالة الخطأ العامة
        let errorAlert = form.querySelector('.form-error-alert');
        if (!errorAlert) {
            errorAlert = document.createElement('div');
            errorAlert.className = 'alert alert-danger alert-dismissible fade show';
            errorAlert.innerHTML = `
                <i class="fas fa-exclamation-circle me-2"></i>
                <span class="form-error-message">${message}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            form.insertBefore(errorAlert, form.firstChild);
        } else {
            errorAlert.querySelector('.form-error-message').textContent = message;
            errorAlert.classList.remove('d-none');
        }
        
        // التمرير إلى أعلى النموذج
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    
    initInputMasks() {
        // قناع رقم الهاتف
        const phoneInputs = document.querySelectorAll('input[type="tel"]');
        phoneInputs.forEach(input => {
            input.addEventListener('input', (e) => {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 0) {
                    value = value.substring(0, 10);
                    e.target.value = value;
                }
            });
        });
        
        // قناع الرموز الرقمية
        const digitInputs = document.querySelectorAll('.verification-digit');
        digitInputs.forEach(input => {
            input.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/\D/g, '').substring(0, 1);
                if (e.target.value && e.target.nextElementSibling) {
                    e.target.nextElementSibling.focus();
                }
            });
            
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && e.target.previousElementSibling) {
                    e.target.previousElementSibling.focus();
                }
            });
        });
    }
    
    initAutoSave() {
        // حفظ البيانات تلقائياً أثناء الكتابة
        const forms = ['loginForm', 'registerForm', 'passwordForm'];
        forms.forEach(formId => {
            const form = document.getElementById(formId);
            if (form) {
                form.querySelectorAll('input, select, textarea').forEach(input => {
                    input.addEventListener('input', () => {
                        this.saveFormData(formId);
                    });
                });
                
                // تحميل البيانات المحفوظة عند تحميل الصفحة
                this.loadFormData(formId);
            }
        });
    }
    
    saveFormData(formId) {
        const form = document.getElementById(formId);
        if (!form) return;
        
        const data = {};
        form.querySelectorAll('input, select, textarea').forEach(input => {
            if (input.type !== 'password') { // لا نحفظ كلمات المرور
                data[input.name] = input.value;
            }
        });
        
        localStorage.setItem(`trms_form_${formId}`, JSON.stringify(data));
    }
    
    loadFormData(formId) {
        const savedData = localStorage.getItem(`trms_form_${formId}`);
        if (!savedData) return;
        
        const data = JSON.parse(savedData);
        const form = document.getElementById(formId);
        
        form.querySelectorAll('input, select, textarea').forEach(input => {
            if (data[input.name] !== undefined && input.type !== 'password') {
                input.value = data[input.name];
            }
        });
    }
    
    initSessionCheck() {
        // التحقق من انتهاء الجلسة
        const lastActivity = localStorage.getItem('trms_last_activity');
        const now = Date.now();
        
        if (lastActivity && (now - lastActivity) > 30 * 60 * 1000) { // 30 دقيقة
            this.clearSession();
        }
        
        // تحديث وقت النشاط الأخير
        document.addEventListener('mousemove', () => {
            localStorage.setItem('trms_last_activity', Date.now());
        });
        
        document.addEventListener('keypress', () => {
            localStorage.setItem('trms_last_activity', Date.now());
        });
    }
    
    clearSession() {
        // مسح بيانات النماذج المحفوظة
        ['loginForm', 'registerForm', 'passwordForm'].forEach(formId => {
            localStorage.removeItem(`trms_form_${formId}`);
        });
        
        localStorage.removeItem('trms_last_activity');
    }
    
    // وظائف مساعدة
    showNotification(message, type = 'info', duration = 5000) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = `
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            min-width: 300px;
            text-align: center;
        `;
        
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };
        
        notification.innerHTML = `
            <i class="fas ${icons[type] || icons.info} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // إزالة تلقائية بعد المدة المحددة
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, duration);
        
        // إزالة عند النقر
        notification.querySelector('.btn-close').addEventListener('click', () => {
            notification.remove();
        });
    }
    
    startCountdown(elementId, seconds, onComplete) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        let remaining = seconds;
        element.textContent = this.formatTime(remaining);
        
        const interval = setInterval(() => {
            remaining--;
            element.textContent = this.formatTime(remaining);
            
            if (remaining <= 0) {
                clearInterval(interval);
                if (onComplete) onComplete();
            }
        }, 1000);
        
        return interval;
    }
    
    formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }
    
    checkPasswordStrength(password) {
        let strength = 0;
        
        // طول كلمة المرور
        if (password.length >= 8) strength += 20;
        if (password.length >= 12) strength += 10;
        
        // تنوع الأحرف
        if (/[a-z]/.test(password)) strength += 20;
        if (/[A-Z]/.test(password)) strength += 20;
        if (/[0-9]/.test(password)) strength += 20;
        if (/[^A-Za-z0-9]/.test(password)) strength += 20;
        
        return Math.min(strength, 100);
    }
    
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

// تهيئة النظام عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    window.authSystem = new AuthSystem();
    
    // إضافة تأثيرات CSS إضافية
    const style = document.createElement('style');
    style.textContent = `
        .shake {
            animation: shake 0.5s ease;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .is-valid {
            border-color: #198754 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        
        .is-invalid {
            border-color: #dc3545 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
    `;
    document.head.appendChild(style);
});