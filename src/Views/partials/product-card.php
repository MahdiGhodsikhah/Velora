<?php
/**
 * کامپوننت کارت محصول
 * متغیرهای مورد نیاز: $product (array)
 * $product = [
 *   'id', 'name', 'slug', 'price', 'sale_price', 'discount_pct',
 *   'main_image', 'gallery_arr', 'rating_avg', 'rating_count',
 *   'short_desc', 'stock_qty', 'category_name'
 * ]
 */

$base          = defined('BASE_URL') ? BASE_URL : '';
$name          = Security::e($product['name'] ?? '');
$slug          = Security::e($product['slug'] ?? '');
$mainImg       = Security::e($product['main_image'] ?? '/assets/images/products/no-image.jpg');
$galleryArr    = $product['gallery_arr'] ?? [];
$price         = number_format((int)($product['price'] ?? 0));
$salePrice     = $product['sale_price'] ? number_format((int)$product['sale_price']) : null;
$discountPct   = (int)($product['discount_pct'] ?? 0);
$ratingAvg     = round((float)($product['rating_avg'] ?? 0), 1);
$ratingCount   = (int)($product['rating_count'] ?? 0);
$shortDesc     = Security::e($product['short_desc'] ?? '');
$stockQty      = (int)($product['stock_qty'] ?? 0);
$catName       = Security::e($product['category_name'] ?? '');
$productId     = (int)($product['id'] ?? 0);
?>

<div class="product-card" data-product-id="<?= $productId ?>" role="article" aria-label="<?= $name ?>">

    <!-- بج تخفیف -->
    <?php if ($discountPct > 0): ?>
    <div class="badge" aria-label="تخفیف <?= $discountPct ?> درصد">تخفیف <?= $discountPct ?>%</div>
    <?php endif; ?>

    <!-- آیکون‌های کنار -->
    <div class="side-icons" role="toolbar" aria-label="عملیات محصول">
        <button class="icon-item wishlist-btn" data-id="<?= $productId ?>" aria-label="افزودن به علاقه‌مندی" title="علاقه‌مندی">
            <i class="far fa-heart"></i>
        </button>
        <button class="icon-item share-btn" data-url="<?= $base ?>/products/<?= $slug ?>" aria-label="اشتراک‌گذاری" title="اشتراک‌گذاری">
            <i class="fas fa-share-alt"></i>
        </button>
        <a href="<?= $base ?>/products/<?= $slug ?>" class="icon-item" aria-label="مشاهده جزئیات" title="جزئیات">
            <i class="fas fa-eye"></i>
        </a>
    </div>

    <!-- گالری تصاویر محصول (اسلایدر داخلی) -->
    <div class="product-slider" aria-label="تصاویر <?= $name ?>">
        <div class="img-wrap">
            <a href="<?= $base ?>/products/<?= $slug ?>" tabindex="-1" aria-hidden="true">
                <img src="<?= $mainImg ?>" alt="<?= $name ?>" loading="lazy">
            </a>
        </div>
        <?php foreach ($galleryArr as $imgUrl): ?>
        <div class="img-wrap">
            <a href="<?= $base ?>/products/<?= $slug ?>" tabindex="-1" aria-hidden="true">
                <img src="<?= Security::e($imgUrl) ?>" alt="<?= $name ?>" loading="lazy">
            </a>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- اطلاعات محصول -->
    <div class="card-body-custome">
        <div class="product-category-label"><?= $catName ?></div>
        <a href="<?= $base ?>/products/<?= $slug ?>" class="product-title" aria-label="مشاهده <?= $name ?>">
            <?= $name ?>
        </a>

        <?php if ($shortDesc): ?>
        <p class="product-short-desc"><?= $shortDesc ?></p>
        <?php endif; ?>

        <div class="product-price" aria-label="قیمت محصول">
            <?php if ($salePrice): ?>
            <span class="old-price" aria-label="قیمت قبل از تخفیف"><?= $price ?> تومان</span>
            <span class="discount-badge" aria-label="درصد تخفیف"><?= $discountPct ?>٪</span>
            <span class="current-price" aria-label="قیمت فعلی"><?= $salePrice ?> تومان</span>
            <?php else: ?>
            <span class="current-price"><?= $price ?> تومان</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- ستاره‌ها -->
    <div class="stars" aria-label="امتیاز: <?= $ratingAvg ?> از ۵ - <?= $ratingCount ?> نظر">
        <?= Security::renderStars($ratingAvg) ?>
        <span class="rating-count">(<?= $ratingCount ?>)</span>
    </div>

    <!-- وضعیت موجودی -->
    <?php if ($stockQty <= 0): ?>
    <span class="out-of-stock-badge" aria-label="ناموجود">ناموجود</span>
    <?php endif; ?>

    <!-- دکمه افزودن به سبد -->
    <button
        class="btn btn-add <?= $stockQty <= 0 ? 'disabled' : '' ?>"
        data-product-id="<?= $productId ?>"
        <?= $stockQty <= 0 ? 'disabled aria-disabled="true"' : '' ?>
        aria-label="افزودن <?= $name ?> به سبد خرید"
    >
        <?= $stockQty <= 0 ? 'ناموجود' : 'افزودن به سبد خرید' ?>
    </button>

</div>
