<?php

/**
 * کامپوننت کاروسل/اسلایدر محصولات
 * متغیرهای مورد نیاز: 
 * - $featuredProducts یا $products (array)
 * متغیرهای اختیاری: 
 * - $sliderTitle (string)
 * - $sliderIcon (string) - آیکون کنار عنوان
 * - $seasonFilter (string) - فصل برای لینک مشاهده همه
 */

$sliderTitle = $sliderTitle ?? 'محصولات ویژه';
$sliderIcon  = $sliderIcon ?? 'fas fa-fire-alt';
$seasonFilter = $seasonFilter ?? ''; // فیلتر فصل برای لینک
$base        = defined('BASE_URL') ? BASE_URL : '';

// استفاده از متغیر صحیح
$products = $products ?? $featuredProducts ?? [];

// ساخت لینک مشاهده همه با فیلتر فصل
$viewAllLink = $base . '/products';
if (!empty($seasonFilter)) {
    $viewAllLink .= '?season=' . urlencode($seasonFilter);
}
?>

<section class="products-carousel-section" aria-labelledby="carousel-heading-<?= md5($sliderTitle) ?>">
    <div class="section-header">
        <h2 class="section-title" id="carousel-heading-<?= md5($sliderTitle) ?>">
            <span class="title-icon"><i class="<?= $sliderIcon ?>" aria-hidden="true"></i></span>
            <?= Security::e($sliderTitle) ?>
            <span class="title-leaf" aria-hidden="true"><i class="fas fa-leaf"></i></span>
        </h2>
        <a href="<?= $viewAllLink ?>" class="see-all-btn">
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
                <div role="group" aria-roledescription="slide" aria-label="محصول <?= Security::e($product['name']) ?>">
                    <?php require __DIR__ . '/product-card.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</section>