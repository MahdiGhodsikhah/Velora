<?php
$base    = defined('BASE_URL') ? BASE_URL : '';
$banners = $banners ?? [];

// اگر بنری از دیتابیس نیامد، بنرهای پیش‌فرض را استفاده کن
if (empty($banners)) {
    $banners = [
        [
            'id' => 1,
            'title' => 'کلکسیون پاییزی شگفت‌انگیز',
            'subtitle' => 'جدیدترین مدل‌های پوشاک با طراحی منحصر‌به‌فرد پاییزی - تخفیف ویژه تا ۵۰٪',
            'image_url' => $base . '/assets/images/banners/banner-autumn-1.png',
            'link_url' => $base . '/products',
            'btn_text' => 'مشاهده محصولات'
        ],
        [
            'id' => 2,
            'title' => 'استایل پاییزی خود را بسازید',
            'subtitle' => 'با بهترین برندهای پوشاک و اکسسوری - ارسال رایگان برای خریدهای بالای ۵۰۰ هزار تومان',
            'image_url' => $base . '/assets/images/banners/banner-normal-1.png',
            'link_url' => $base . '/products?cat=1',
            'btn_text' => 'خرید کنید'
        ]
    ];
}

// تصحیح مسیر تصاویر اگر BASE_URL ندارند
foreach ($banners as &$banner) {
    if (!empty($banner['image_url']) && strpos($banner['image_url'], 'http') !== 0 && strpos($banner['image_url'], $base) !== 0) {
        $banner['image_url'] = $base . $banner['image_url'];
    }
    if (!empty($banner['link_url']) && strpos($banner['link_url'], 'http') !== 0 && strpos($banner['link_url'], $base) !== 0) {
        $banner['link_url'] = $base . $banner['link_url'];
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
                            <span class="eyebrow-leaf" aria-hidden="true"><i class="fas fa-leaf"></i></span>
                            کلکسیون پاییز ۱۴۰۵
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
            <button class="hero-arrow hero-prev" aria-label="بنر قبلی">
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
            </button>
            <button class="hero-arrow hero-next" aria-label="بنر بعدی">
                <i class="fas fa-chevron-left" aria-hidden="true"></i>
            </button>
            <?php endif; ?>
        </div>

    </div>

    <!-- موج پایین هدر -->
    <div class="hero-wave" aria-hidden="true">
        <svg viewBox="0 0 1440 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z" fill="var(--body-bg,#fef9f0)"/>
        </svg>
    </div>

</header>
<!-- ===== پایان هدر انیمیشنی ===== -->
