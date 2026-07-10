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
            <p><?= count($wishlistProducts) ?> محصول در لیست علاقه‌مندی‌های شما</p>
        </div>

        <?php if (!empty($wishlistProducts)): ?>
        
        <!-- گرید محصولات -->
        <div class="products-grid">
            <?php foreach ($wishlistProducts as $product): ?>
                <?php require BASE_PATH . '/src/Views/partials/product-card.php'; ?>
            <?php endforeach; ?>
        </div>

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

<style>
/* استایل‌های اضافی برای صفحه wishlist */
.wishlist-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 40px 20px 80px;
}

.wishlist-container {
    max-width: 1400px;
    margin: 0 auto;
}

.page-header {
    text-align: center;
    margin-bottom: 50px;
    padding: 30px 20px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.page-header h1 {
    font-size: 2.5rem;
    font-weight: 800;
    color: #2c3e50;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.page-header h1 i {
    color: #e53e3e;
}

.page-header p {
    color: #7f8c8d;
    font-size: 1.1rem;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.empty-wishlist {
    text-align: center;
    padding: 80px 20px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.empty-wishlist i {
    font-size: 6rem;
    color: #e53e3e;
    margin-bottom: 25px;
    opacity: 0.3;
}

.empty-wishlist h2 {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 15px;
}

.empty-wishlist p {
    font-size: 1.1rem;
    color: #7f8c8d;
    margin-bottom: 30px;
}

.btn-browse-products {
    display: inline-block;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 40px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-browse-products:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    color: white;
}

@media (max-width: 768px) {
    .wishlist-page {
        padding: 20px 15px 60px;
    }
    
    .page-header h1 {
        font-size: 1.8rem;
    }
    
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
}
</style>

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
