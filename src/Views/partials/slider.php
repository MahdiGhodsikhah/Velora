<?php
/**
 * کامپوننت کاروسل/اسلایدر محصولات
 * متغیرهای مورد نیاز: 
 * - $featuredProducts یا $products (array)
 * متغیرهای اختیاری: 
 * - $sliderTitle (string)
 * - $sliderIcon (string) - آیکون کنار عنوان
 */

$sliderTitle = $sliderTitle ?? 'محصولات ویژه';
$sliderIcon  = $sliderIcon ?? 'fas fa-fire-alt';
$base        = defined('BASE_URL') ? BASE_URL : '';

// استفاده از متغیر صحیح
$products = $products ?? $featuredProducts ?? [];
?>

<section class="products-carousel-section" aria-labelledby="carousel-heading">
    <div class="section-header">
        <h2 class="section-title" id="carousel-heading">
            <span class="title-icon"><i class="<?= $sliderIcon ?>" aria-hidden="true"></i></span>
            <?= Security::e($sliderTitle) ?>
            <span class="title-leaf" aria-hidden="true"><i class="fas fa-leaf"></i></span>
        </h2>
        <a href="<?= $base ?>/products" class="see-all-btn">
            مشاهده همه <i class="fas fa-arrow-left" aria-hidden="true"></i>
        </a>
    </div>

    <?php if (empty($products)): ?>
    <div class="no-products" role="status">
        <i class="fas fa-box-open" aria-hidden="true"></i>
        <p>محصولی برای نمایش وجود ندارد.</p>
    </div>
    <?php else: ?>

    <div class="main-product-slider" role="region" aria-label="<?= Security::e($sliderTitle) ?>" aria-roledescription="carousel">
        <?php foreach ($products as $product): ?>
        <div role="group" aria-roledescription="slide">
            <?php require __DIR__ . '/product-card.php'; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <?php endif; ?>
</section>
