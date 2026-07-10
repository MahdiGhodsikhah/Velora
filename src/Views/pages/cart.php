<?php
/**
 * صفحه سبد خرید
 */
$base = defined('BASE_URL') ? BASE_URL : '';
?>

<link rel="stylesheet" href="<?= $base ?>/assets/css/cart.css">

<main id="main-content" class="cart-page">
    
    <div class="cart-container">
        
        <!-- هدر صفحه -->
        <div class="page-header">
            <h1>
                <i class="fas fa-shopping-cart"></i>
                سبد خرید شما
            </h1>
            <?php if (!empty($cartItems)): ?>
            <p><?= count($cartItems) ?> محصول در سبد خرید شما</p>
            <?php endif; ?>
        </div>

        <?php if (!empty($cartItems)): ?>
        
        <div class="cart-layout">
            
            <!-- لیست محصولات -->
            <div class="cart-items">
                <?php foreach ($cartItems as $item): ?>
                <div class="cart-item" data-product-id="<?= (int)$item['id'] ?>">
                    
                    <!-- تصویر محصول -->
                    <div class="item-image">
                        <a href="<?= $base ?>/products/<?= Security::e($item['slug']) ?>">
                            <img src="<?= $base ?><?= Security::e($item['main_image'] ?? '/assets/images/products/no-image.jpg') ?>" 
                                 alt="<?= Security::e($item['name']) ?>">
                        </a>
                    </div>

                    <!-- اطلاعات محصول -->
                    <div class="item-details">
                        <a href="<?= $base ?>/products/<?= Security::e($item['slug']) ?>" class="item-name">
                            <?= Security::e($item['name']) ?>
                        </a>
                        <div class="item-category">
                            <?= Security::e($item['category_name']) ?>
                        </div>
                        
                        <!-- موجودی -->
                        <?php if ((int)$item['stock_qty'] < $item['quantity']): ?>
                        <div class="stock-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            فقط <?= (int)$item['stock_qty'] ?> عدد موجود است
                        </div>
                        <?php else: ?>
                        <div class="stock-available">
                            <i class="fas fa-check-circle"></i>
                            موجود در انبار
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- قیمت واحد -->
                    <div class="item-price">
                        <?php 
                        $price = $item['sale_price'] ?: $item['price'];
                        ?>
                        <?php if ($item['sale_price']): ?>
                        <span class="old-price"><?= number_format((int)$item['price']) ?></span>
                        <span class="current-price"><?= number_format((int)$price) ?> تومان</span>
                        <?php else: ?>
                        <span class="current-price"><?= number_format((int)$price) ?> تومان</span>
                        <?php endif; ?>
                    </div>

                    <!-- تعداد -->
                    <div class="item-quantity">
                        <button class="qty-btn qty-minus" data-id="<?= (int)$item['id'] ?>">-</button>
                        <input type="number" 
                               class="qty-input" 
                               value="<?= (int)$item['quantity'] ?>" 
                               min="1" 
                               max="<?= (int)$item['stock_qty'] ?>"
                               data-id="<?= (int)$item['id'] ?>"
                               readonly>
                        <button class="qty-btn qty-plus" data-id="<?= (int)$item['id'] ?>">+</button>
                    </div>

                    <!-- قیمت کل -->
                    <div class="item-total">
                        <span class="total-price" data-price="<?= $price ?>">
                            <?= number_format($price * $item['quantity']) ?>
                        </span>
                        <span class="currency">تومان</span>
                    </div>

                    <!-- حذف -->
                    <button class="btn-remove-item" data-id="<?= (int)$item['id'] ?>" title="حذف">
                        <i class="fas fa-trash-alt"></i>
                    </button>

                </div>
                <?php endforeach; ?>
            </div>

            <!-- خلاصه سبد خرید -->
            <div class="cart-summary">
                <h2>خلاصه سبد خرید</h2>
                
                <div class="summary-row">
                    <span>جمع کل:</span>
                    <span class="subtotal-amount"><?= number_format((int)$subtotal) ?> تومان</span>
                </div>

                <div class="summary-row">
                    <span>هزینه ارسال:</span>
                    <span class="shipping-amount">
                        <?php if ($shipping > 0): ?>
                        <?= number_format((int)$shipping) ?> تومان
                        <?php else: ?>
                        <span class="free-shipping">رایگان</span>
                        <?php endif; ?>
                    </span>
                </div>

                <?php if ($subtotal < 500000): ?>
                <div class="free-shipping-notice">
                    <i class="fas fa-info-circle"></i>
                    با خرید <?= number_format(500000 - $subtotal) ?> تومان دیگر، ارسال رایگان!
                </div>
                <?php endif; ?>

                <div class="summary-row">
                    <span>مالیات (9%):</span>
                    <span class="tax-amount"><?= number_format((int)$tax) ?> تومان</span>
                </div>

                <div class="summary-divider"></div>

                <div class="summary-row summary-total">
                    <span>مجموع نهایی:</span>
                    <span class="total-amount"><?= number_format((int)$total) ?> تومان</span>
                </div>

                <a href="<?= $base ?>/checkout" class="btn-checkout">
                    <i class="fas fa-credit-card"></i>
                    تکمیل خرید
                </a>

                <a href="<?= $base ?>/products" class="btn-continue-shopping">
                    <i class="fas fa-arrow-right"></i>
                    ادامه خرید
                </a>

                <!-- کد تخفیف -->
                <div class="coupon-section">
                    <h3>کد تخفیف</h3>
                    <form class="coupon-form">
                        <input type="text" placeholder="کد تخفیف را وارد کنید" class="coupon-input">
                        <button type="submit" class="btn-apply-coupon">اعمال</button>
                    </form>
                </div>
            </div>

        </div>

        <?php else: ?>
        
        <!-- حالت خالی -->
        <div class="empty-cart">
            <i class="fas fa-shopping-cart"></i>
            <h2>سبد خرید شما خالی است</h2>
            <p>هنوز هیچ محصولی به سبد خرید اضافه نکرده‌اید</p>
            <a href="<?= $base ?>/products" class="btn-start-shopping">
                <i class="fas fa-store"></i>
                شروع خرید
            </a>
        </div>

        <?php endif; ?>

    </div>

</main>

<!-- بارگذاری اسکریپت اختصاصی صفحه cart -->
<script>
// این اسکریپت بعد از footer و jQuery لود می‌شود
document.addEventListener('DOMContentLoaded', function() {
    // بارگذاری اسکریپت cart.js
    var script = document.createElement('script');
    script.src = (window.BASE_URL || '') + '/assets/js/cart.js';
    script.onload = function() {
        console.log('✅ cart.js loaded successfully');
    };
    script.onerror = function() {
        console.error('❌ Failed to load cart.js');
    };
    document.body.appendChild(script);
});
</script>
