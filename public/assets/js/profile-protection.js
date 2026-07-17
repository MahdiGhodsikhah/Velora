/**
 * محافظت از فرم‌های پروفایل در برابر سوء استفاده
 */

// محافظت فرم پروفایل
(function() {
    const profileForm = document.getElementById('profileForm');
    if (!profileForm) return;

    let lastSubmitTime = 0;
    const SUBMIT_INTERVAL = 3000; // 3 ثانیه
    let attemptCount = 0;
    const MAX_ATTEMPTS = 3;
    const RESET_TIME = 5000; // 5 ثانیه
    const COOLDOWN_TIME = 60000; // 60 ثانیه
    let inCooldown = false;
    let cooldownEndTime = 0;

    // بازنشانی شمارنده تلاش‌ها
    setInterval(() => {
        if (attemptCount > 0 && !inCooldown) {
            attemptCount = 0;
        }
    }, RESET_TIME);

    // تایمر Cooldown
    function updateCooldownTimer() {
        if (!inCooldown) return;
        
        const now = Date.now();
        const remaining = Math.ceil((cooldownEndTime - now) / 1000);
        
        if (remaining <= 0) {
            inCooldown = false;
            attemptCount = 0;
            showNotification('اکنون می‌توانید دوباره تلاش کنید', 'success');
            return;
        }
        
        const submitBtn = profileForm.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<i class="bi bi-hourglass-split me-2"></i>لطفا ${remaining} ثانیه صبر کنید...`;
        }
        
        setTimeout(updateCooldownTimer, 1000);
    }

    profileForm.addEventListener('submit', function(e) {
        const now = Date.now();

        // بررسی Cooldown
        if (inCooldown) {
            e.preventDefault();
            const remainingTime = Math.ceil((cooldownEndTime - now) / 1000);
            showNotification(`تعداد تلاش‌های شما بیش از حد مجاز است. لطفا ${remainingTime} ثانیه صبر کنید`, 'error');
            return false;
        }

        // بررسی فاصله زمانی
        const timeSinceLastSubmit = now - lastSubmitTime;
        if (timeSinceLastSubmit < SUBMIT_INTERVAL) {
            e.preventDefault();
            showNotification('لطفا کمی صبر کنید و دوباره امتحان کنید', 'warning');
            return false;
        }

        // بررسی تعداد تلاش‌ها
        attemptCount++;
        if (attemptCount > MAX_ATTEMPTS) {
            e.preventDefault();
            inCooldown = true;
            cooldownEndTime = now + COOLDOWN_TIME;
            showNotification('تعداد تلاش‌های شما بیش از حد مجاز است. لطفا 60 ثانیه صبر کنید', 'error');
            updateCooldownTimer();
            return false;
        }

        lastSubmitTime = now;

        // غیرفعال کردن فیلدها و نمایش لودینگ
        const submitBtn = profileForm.querySelector('button[type="submit"]');
        const formFields = profileForm.querySelectorAll('input, textarea, select, button');

        formFields.forEach(field => {
            field.disabled = true;
        });

        if (submitBtn) {
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>در حال ذخیره...';
            
            // بازگرداندن دکمه بعد از 3 ثانیه (در صورت عدم موفقیت)
            setTimeout(() => {
                if (!inCooldown) {
                    formFields.forEach(field => {
                        field.disabled = false;
                    });
                    submitBtn.innerHTML = originalText;
                }
            }, 3000);
        }

        return true;
    });
})();

// محافظت فرم تغییر رمز عبور
(function() {
    const changePasswordBtn = document.getElementById('submitPasswordChange');
    if (!changePasswordBtn) return;

    let lastAttemptTime = 0;
    const ATTEMPT_INTERVAL = 3000; // 3 ثانیه
    let attemptCount = 0;
    const MAX_ATTEMPTS = 5;
    const COOLDOWN_TIME = 60000; // 60 ثانیه
    let inCooldown = false;

    changePasswordBtn.addEventListener('click', function(e) {
        const now = Date.now();

        // بررسی دوره انتظار
        if (inCooldown) {
            const remainingTime = Math.ceil((COOLDOWN_TIME - (now - lastAttemptTime)) / 1000);
            const messageDiv = document.getElementById('passwordChangeMessage');
            messageDiv.className = 'alert alert-warning';
            messageDiv.innerHTML = `<i class="bi bi-exclamation-triangle me-2"></i>لطفا ${remainingTime} ثانیه صبر کنید`;
            messageDiv.classList.remove('d-none');
            return;
        }

        // بررسی فاصله زمانی
        const timeSinceLastAttempt = now - lastAttemptTime;
        if (timeSinceLastAttempt < ATTEMPT_INTERVAL) {
            const messageDiv = document.getElementById('passwordChangeMessage');
            messageDiv.className = 'alert alert-warning';
            messageDiv.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>لطفا کمی صبر کنید و دوباره امتحان کنید';
            messageDiv.classList.remove('d-none');
            return;
        }

        // بررسی تعداد تلاش‌ها
        attemptCount++;
        if (attemptCount >= MAX_ATTEMPTS) {
            inCooldown = true;
            const messageDiv = document.getElementById('passwordChangeMessage');
            messageDiv.className = 'alert alert-danger';
            messageDiv.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>تعداد تلاش‌های شما بیش از حد مجاز است. لطفا 60 ثانیه صبر کنید';
            messageDiv.classList.remove('d-none');
            
            // بازنشانی بعد از 60 ثانیه
            setTimeout(() => {
                inCooldown = false;
                attemptCount = 0;
            }, COOLDOWN_TIME);
            return;
        }

        lastAttemptTime = now;

        // غیرفعال کردن فیلدها
        const form = document.getElementById('changePasswordForm');
        const formFields = form.querySelectorAll('input, button');
        formFields.forEach(field => {
            field.disabled = true;
        });
    });
})();

// تابع نمایش نوتیفیکیشن
function showNotification(message, type = 'success') {
    const notificationContainer = document.getElementById('notification-container');
    if (!notificationContainer) return;

    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    
    let icon = 'bi-check-circle-fill';
    if (type === 'error') icon = 'bi-x-circle-fill';
    else if (type === 'warning') icon = 'bi-exclamation-triangle-fill';
    
    notification.innerHTML = `<i class="bi ${icon}"></i>${message}`;
    
    notificationContainer.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}
