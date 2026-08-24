<?php
/**
 * صفحه علاقه‌مندی‌های کاربر
 */
$base = defined('BASE_URL') ? BASE_URL : '';
?>

<link rel="stylesheet" href="<?= $base ?>/assets/css/wishlist.css">
<link rel="stylesheet" href="<?= $base ?>/assets/css/carousel.css">

<main id="main-content" class="wishlist-page">
    
    <div class="wishlist-container">
        
        <!-- هدر صفحه -->
        <div class="page-header">
            <h1>
                <i class="fas fa-heart"></i>
                علاقه‌مندی‌های من
            </h1>
            <p><?= $total ?? 0 ?> محصول در لیست علاقه‌مندی‌های شما</p>
        </div>

        <?php if (!empty($wishlistProducts)): ?>
        
        <!-- گرید محصولات -->
        <div class="products-grid">
            <?php foreach ($wishlistProducts as $product): ?>
            <div role="listitem">
                <?php require BASE_PATH . '/src/Views/partials/product-card.php'; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- صفحه‌بندی -->
        <?php if (($totalPages ?? 1) > 1): ?>
        <nav class="pagination-nav" aria-label="صفحه‌بندی">
            <?php for ($p = 1; $p <= ($totalPages ?? 1); $p++): ?>
            <a href="<?= $base ?>/wishlist?page=<?= $p ?>"
               class="page-btn <?= ($page ?? 1) === $p ? 'active' : '' ?>"
               aria-label="صفحه <?= $p ?>"
               <?= ($page ?? 1) === $p ? 'aria-current="page"' : '' ?>>
                <?= $p ?>
            </a>
            <?php endfor; ?>
        </nav>
        <?php endif; ?>

        <?php else: ?>
        
        <!-- حالت خالی -->
        <div class="empty-wishlist">
            <i class="fas fa-heart-broken"></i>
            <h2>لیست علاقه‌مندی‌های شما خالی است</h2>
            <p>محصولات مورد علاقه خود را به این لیست اضافه کنید</p>
            <a href="<?= $base ?>/products" class="btn-browse-products">
                <i class="fas fa-store"></i>
                مشاهده محصولات
            </a>
        </div>

        <?php endif; ?>

    </div>

</main>

<script>
$(document).ready(function() {
    console.log('🛒 Wishlist page loaded');
    
    // بارگذاری اسلایدرهای تصاویر محصولات
    setTimeout(function() {
        $('.product-slider').each(function() {
            const $slider = $(this);
            
            if ($slider.hasClass('slick-initialized')) {
                return;
            }
            
            const imageCount = $slider.find('.img-wrap').length;
            if (imageCount <= 1) {
                $slider.addClass('single-image');
                return;
            }
            
            $slider.slick({
                infinite: true,
                dots: true,
                arrows: false,
                speed: 400,
                autoplay: true,
                autoplaySpeed: 2800,
                rtl: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                fade: true,
                cssEase: 'cubic-bezier(0.4,0,0.2,1)',
                swipe: true,
                touchThreshold: 10,
                pauseOnHover: true,
                pauseOnFocus: true,
                accessibility: true,
                adaptiveHeight: false
            });
        });
    }, 300);
});
</script>
