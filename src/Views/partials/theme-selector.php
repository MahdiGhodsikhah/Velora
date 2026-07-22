<?php
/**
 * ویجت انتخاب تم برای کاربران
 * نمایش در هدر یا سایدبار
 */
?>

<div class="theme-selector-widget">
    <div class="dropdown">
        <button class="btn btn-sm btn-outline-secondary dropdown-toggle theme-toggle-btn" 
                type="button" 
                id="themeSelectorDropdown" 
                data-bs-toggle="dropdown" 
                aria-expanded="false"
                title="تغییر تم">
            <i class="fas fa-palette ms-1"></i>
            <span class="d-none d-md-inline">تم</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end theme-selector-menu" aria-labelledby="themeSelectorDropdown">
            <li class="dropdown-header">انتخاب تم</li>
            <?php foreach ($themes as $theme_key => $theme_info): ?>
                <li>
                    <a class="dropdown-item theme-option <?= $current_theme === $theme_key ? 'active' : '' ?>" 
                       href="#" 
                       data-theme="<?= $theme_key ?>"
                       onclick="changeTheme('<?= $theme_key ?>'); return false;">
                        <span class="theme-icon"><?= $theme_info['icon'] ?></span>
                        <span class="theme-name"><?= $theme_info['name'] ?></span>
                        <?php if ($current_theme === $theme_key): ?>
                            <i class="fas fa-check text-success me-1"></i>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item text-muted small" href="#" onclick="window.themeManager.resetTheme(); return false;">
                    <i class="fas fa-undo ms-1"></i>
                    بازگشت به پیش‌فرض
                </a>
            </li>
        </ul>
    </div>
</div>

<style>
.theme-selector-widget {
    display: inline-block;
}

.theme-toggle-btn {
    border-radius: 20px;
    padding: 0.375rem 1rem;
    transition: all 0.3s ease;
}

.theme-toggle-btn:hover {
    transform: scale(1.05);
}

.theme-selector-menu {
    min-width: 200px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.theme-selector-menu .dropdown-header {
    font-weight: bold;
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
}

.theme-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
}

.theme-option:hover {
    background-color: rgba(0,0,0,0.05);
    transform: translateX(-3px);
}

.theme-option.active {
    background-color: rgba(13, 110, 253, 0.1);
    font-weight: bold;
}

.theme-icon {
    font-size: 1.25rem;
}

.theme-name {
    flex: 1;
}
</style>

<script>
// تابع تغییر تم با انیمیشن
function changeTheme(theme) {
    // افکت انیمیشن
    document.body.style.transition = 'opacity 0.3s ease';
    document.body.style.opacity = '0.7';
    
    setTimeout(() => {
        if (window.themeManager) {
            window.themeManager.changeTheme(theme).then(() => {
                document.body.style.opacity = '1';
                
                // نمایش اعلان موفقیت
                const themeName = {
                    'spring': 'بهاری',
                    'summer': 'تابستانی',
                    'autumn': 'پاییزی',
                    'winter': 'زمستانی'
                }[theme];
                
                showNotification(`تم ${themeName} اعمال شد`);
            });
        }
    }, 150);
}

// نمایش نوتیفیکیشن
function showNotification(message) {
    // اگر سیستم نوتیفیکیشن وجود دارد
    if (typeof createToast === 'function') {
        createToast(message, 'success');
        return;
    }
    
    // در غیر این صورت نوتیفیکیشن ساده
    const notification = document.createElement('div');
    notification.className = 'alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transition = 'opacity 0.3s ease';
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 2000);
}
</script>
