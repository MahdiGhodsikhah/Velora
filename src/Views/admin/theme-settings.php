<?php
/**
 * صفحه مدیریت تنظیمات تم در پنل ادمین
 */

$page_title = 'مدیریت تم سایت';
require_once BASE_PATH . '/src/Views/layouts/header.php';
?>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-palette ms-2"></i>
                        مدیریت تم دینامیک سایت
                    </h4>
                </div>
                <div class="card-body">
                    
                    <?php if (isset($_SESSION['alert'])): ?>
                        <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($_SESSION['alert']['message']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['alert']); ?>
                    <?php endif; ?>

                    <form method="POST" action="<?= BASE_URL ?>/admin/theme-settings/save">
                        
                        <!-- اطلاعات فصل جاری -->
                        <div class="alert alert-info mb-4">
                            <h5><i class="fas fa-info-circle ms-2"></i>اطلاعات فعلی</h5>
                            <p class="mb-1"><strong>فصل جاری (تشخیص خودکار):</strong> 
                                <span class="badge bg-secondary"><?= ThemeManager::SEASON_MAP[$settings['current_season']] ?? '' ?> (<?= $settings['current_season'] ?>)</span>
                            </p>
                            <p class="mb-0"><strong>تم فعال:</strong> 
                                <span class="badge bg-primary"><?= $themes[$settings['active_theme']]['name'] ?? '' ?> (<?= $settings['active_theme'] ?>)</span>
                            </p>
                        </div>

                        <!-- انتخاب تم دستی -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-brush ms-2"></i>
                                انتخاب تم پیش‌فرض سایت
                            </h5>
                            <p class="text-muted">تمی که می‌خواهید به صورت پیش‌فرض برای تمام کاربران اعمال شود را انتخاب کنید.</p>
                            
                            <div class="row g-3">
                                <?php foreach ($themes as $theme_key => $theme_info): ?>
                                    <div class="col-md-3 col-sm-6">
                                        <label class="theme-option-card">
                                            <input type="radio" 
                                                   name="active_theme" 
                                                   value="<?= $theme_key ?>"
                                                   <?= $settings['active_theme'] === $theme_key ? 'checked' : '' ?>>
                                            <div class="card h-100 theme-card" data-theme="<?= $theme_key ?>">
                                                <div class="card-body text-center">
                                                    <div class="theme-icon mb-2" style="font-size: 3rem;">
                                                        <?= $theme_info['icon'] ?>
                                                    </div>
                                                    <h5 class="card-title"><?= $theme_info['name'] ?></h5>
                                                    <p class="card-text text-muted small"><?= $theme_info['description'] ?></p>
                                                    <div class="color-preview" style="background: <?= $theme_info['primary_color'] ?>; height: 30px; border-radius: 5px;"></div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- تنظیمات پیشرفته -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-cog ms-2"></i>
                                تنظیمات پیشرفته
                            </h5>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="auto_detect" 
                                       name="auto_detect"
                                       <?= $settings['auto_detect'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="auto_detect">
                                    <strong>تشخیص خودکار تم بر اساس فصل</strong>
                                    <p class="text-muted small mb-0">با فعال کردن این گزینه، تم سایت به صورت خودکار بر اساس فصل جاری تقویم شمسی تغییر می‌کند.</p>
                                </label>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="allow_user_choice" 
                                       name="allow_user_choice"
                                       <?= $settings['allow_user_choice'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="allow_user_choice">
                                    <strong>اجازه انتخاب تم توسط کاربران</strong>
                                    <p class="text-muted small mb-0">کاربران می‌توانند تم مورد علاقه خود را انتخاب کنند.</p>
                                </label>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- راهنما -->
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-lightbulb ms-2"></i>نکات مهم:</h6>
                            <ul class="mb-0">
                                <li>اگر تشخیص خودکار فعال باشد، تم بر اساس فصل جاری تقویم شمسی تنظیم می‌شود.</li>
                                <li>تم دستی انتخاب شده زمانی اعمال می‌شود که تشخیص خودکار غیرفعال باشد.</li>
                                <li>اگر انتخاب توسط کاربر فعال باشد، کاربران می‌توانند تم خود را تغییر دهند.</li>
                                <li>در صفحه محصول، تم بر اساس فصل محصول تغییر می‌کند.</li>
                            </ul>
                        </div>

                        <!-- دکمه‌های عملیات -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save ms-1"></i>
                                ذخیره تنظیمات
                            </button>
                            <a href="<?= BASE_URL ?>/" class="btn btn-secondary">
                                <i class="fas fa-times ms-1"></i>
                                انصراف
                            </a>
                            <button type="button" class="btn btn-info" id="previewTheme">
                                <i class="fas fa-eye ms-1"></i>
                                پیش‌نمایش تم‌ها
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.theme-option-card {
    cursor: pointer;
    display: block;
}

.theme-option-card input[type="radio"] {
    display: none;
}

.theme-card {
    border: 3px solid #e0e0e0;
    transition: all 0.3s ease;
}

.theme-option-card input[type="radio"]:checked + .theme-card {
    border-color: #0d6efd;
    box-shadow: 0 0 15px rgba(13, 110, 253, 0.3);
    transform: scale(1.05);
}

.theme-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.theme-icon {
    filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.1));
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // پیش‌نمایش تم‌ها
    const previewBtn = document.getElementById('previewTheme');
    if (previewBtn) {
        previewBtn.addEventListener('click', function() {
            const themeCards = document.querySelectorAll('.theme-card');
            let currentIndex = 0;
            
            const cycleThemes = () => {
                themeCards.forEach((card, index) => {
                    if (index === currentIndex) {
                        const theme = card.dataset.theme;
                        if (window.themeManager) {
                            window.themeManager.applyTheme(theme, false);
                        }
                    }
                });
                
                currentIndex = (currentIndex + 1) % themeCards.length;
                
                if (currentIndex === 0) {
                    clearInterval(interval);
                    // بازگشت به تم فعلی
                    const activeTheme = document.querySelector('input[name="active_theme"]:checked');
                    if (activeTheme && window.themeManager) {
                        window.themeManager.applyTheme(activeTheme.value, false);
                    }
                }
            };
            
            const interval = setInterval(cycleThemes, 2000);
        });
    }
    
    // تغییر تم با کلیک روی کارت
    document.querySelectorAll('.theme-card').forEach(card => {
        card.addEventListener('click', function() {
            const theme = this.dataset.theme;
            if (window.themeManager) {
                window.themeManager.applyTheme(theme, false);
            }
        });
    });
});
</script>

<?php require_once BASE_PATH . '/src/Views/layouts/footer.php'; ?>
