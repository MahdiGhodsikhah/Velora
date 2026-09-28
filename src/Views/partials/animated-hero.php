<?php
$base    = defined('BASE_URL') ? BASE_URL : '';
$banners = $banners ?? [];

// دریافت تم فعال
$themeManager = ThemeManager::getInstance();
$currentTheme = $themeManager->getActiveTheme();

// تصحیح مسیر تصاویر اگر BASE_URL ندارند
foreach ($banners as &$banner) {
    if (!empty($banner['image_url']) && strpos($banner['image_url'], 'http') !== 0 && strpos($banner['image_url'], $base) !== 0) {
        $banner['image_url'] = $base . $banner['image_url'];
    }
    if (!empty($banner['link_url']) && strpos($banner['link_url'], 'http') !== 0 && strpos($banner['link_url'], $base) !== 0) {
        $banner['link_url'] = $base . $banner['link_url'];
    }
    // اگر اطلاعات تم ندارد، از تم فعلی استفاده کن
    if (empty($banner['season_name'])) {
        $themeNames = ['autumn' => 'پاییز', 'winter' => 'زمستان', 'spring' => 'بهار', 'summer' => 'تابستان'];
        $themeIcons = ['autumn' => 'fa-leaf', 'winter' => 'fa-snowflake', 'spring' => 'fa-seedling', 'summer' => 'fa-sun'];
        $banner['season_name'] = $themeNames[$currentTheme] ?? 'پاییز';
        $banner['season_icon'] = $themeIcons[$currentTheme] ?? 'fa-leaf';
        $banner['season_year'] = '۱۴۰۵';
    }
}
unset($banner);
?>

<!-- ===== هدر انیمیشنی پاییزی ===== -->
<header class="animated-hero" role="banner" aria-label="بنر اصلی">

    <!-- پس‌زمینه پارالاکس -->
    <div class="hero-bg-layer" aria-hidden="true">
        <div class="hero-bg-gradient"></div>
        <!-- ذرات متحرک -->
        <div class="hero-particles" id="heroParticles"></div>
    </div>

    <!-- برگ‌های متحرک -->
    <div class="falling-leaves-container" aria-hidden="true" id="fallingLeaves"></div>

    <!-- محتوای اصلی -->
    <div class="hero-content-wrap">

        <!-- اسلایدر بنرها -->
        <div class="hero-banner-slider" role="region" aria-label="بنرهای اصلی" aria-roledescription="carousel">
            <?php foreach ($banners as $i => $banner): ?>
            <div class="hero-slide <?= $i === 0 ? 'active' : '' ?>"
                 role="group"
                 aria-roledescription="slide"
                 aria-label="بنر <?= $i + 1 ?> از <?= count($banners) ?>">

                <!-- تصویر پس‌زمینه بنر -->
                <div class="slide-bg" style="background-image:url('<?= Security::e($banner['image_url']) ?>')" aria-hidden="true"></div>
                <div class="slide-overlay" aria-hidden="true"></div>

                <div class="slide-content">
                    <div class="slide-text-wrap">
                        <div class="slide-eyebrow">
                            <span class="eyebrow-leaf" aria-hidden="true"><i class="fas <?= $banner['season_icon'] ?? 'fa-leaf' ?>"></i></span>
                            کلکسیون <?= Security::e($banner['season_name'] ?? 'پاییز') ?> <?= Security::e($banner['season_year'] ?? '۱۴۰۵') ?>
                        </div>
                        <h2 class="slide-title"><?= Security::e($banner['title'] ?? '') ?></h2>
                        <p class="slide-subtitle"><?= Security::e($banner['subtitle'] ?? '') ?></p>
                        <?php if (!empty($banner['link_url'])): ?>
                        <a href="<?= Security::e($banner['link_url']) ?>"
                           class="hero-cta-btn"
                           aria-label="<?= Security::e($banner['btn_text'] ?? 'مشاهده') ?>">
                            <span><?= Security::e($banner['btn_text'] ?? 'مشاهده محصولات') ?></span>
                            <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- دات‌های ناوبری بنر -->
            <?php if (count($banners) > 1): ?>
            <div class="hero-dots" role="tablist" aria-label="انتخاب بنر">
                <?php foreach ($banners as $i => $b): ?>
                <button class="hero-dot <?= $i === 0 ? 'active' : '' ?>"
                        data-slide="<?= $i ?>"
                        role="tab"
                        aria-selected="<?= $i === 0 ? 'true' : 'false' ?>"
                        aria-label="بنر <?= $i + 1 ?>"></button>
                <?php endforeach; ?>
            </div>
            <!-- فلش‌های ناوبری -->
            <button class="hero-arrow hero-arrow-prev" aria-label="بنر قبلی">
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
            </button>
            <button class="hero-arrow hero-arrow-next" aria-label="بنر بعدی">
                <i class="fas fa-chevron-left" aria-hidden="true"></i>
            </button>
            <?php endif; ?>
        </div>

    </div>

</header>
<!-- ===== پایان هدر انیمیشنی ===== -->
