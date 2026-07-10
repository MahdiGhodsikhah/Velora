<?php
/**
 * صفحه پنل کاربری - داشبورد
 */
$base = defined('BASE_URL') ? BASE_URL : '';
?>

<link rel="stylesheet" href="<?= $base ?>/assets/css/dashboard.css">

<main id="main-content" class="dashboard-page">
    
    <!-- پیام‌های سیستم -->
    <?php if (isset($_SESSION['success'])): ?>
    <div class="container pt-3">
        <div class="alert alert-success" role="status">
            <i class="fas fa-check-circle"></i>
            <?= Security::e($_SESSION['success']) ?>
            <button class="alert-close">&times;</button>
        </div>
    </div>
    <?php unset($_SESSION['success']); endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
    <div class="container pt-3">
        <div class="alert alert-error" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <?= $_SESSION['error'] ?>
            <button class="alert-close">&times;</button>
        </div>
    </div>
    <?php unset($_SESSION['error']); endif; ?>

    <div class="dashboard-container">
        
        <!-- هدر پنل -->
        <div class="dashboard-header">
            <div class="welcome-box">
                <i class="fas fa-user-circle welcome-icon"></i>
                <div>
                    <h1>سلام، <?= Security::e($user['full_name'] ?? $user['username']) ?>!</h1>
                    <p>خوش آمدید به پنل کاربری خود</p>
                </div>
            </div>
        </div>

        <!-- آمار سریع -->
        <div class="stats-grid">
            <div class="stat-card stat-orders">
                <div class="stat-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-content">
                    <h3><?= (int)$stats['total_orders'] ?></h3>
                    <p>سفارش ثبت شده</p>
                </div>
            </div>

            <div class="stat-card stat-reviews">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-content">
                    <h3><?= (int)$stats['total_reviews'] ?></h3>
                    <p>نظر ثبت شده</p>
                </div>
            </div>

            <div class="stat-card stat-wishlist">
                <div class="stat-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="stat-content">
                    <h3><?= (int)$stats['wishlist_count'] ?></h3>
                    <p>محصول مورد علاقه</p>
                </div>
            </div>

            <div class="stat-card stat-cart">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-content">
                    <h3><?= (int)$stats['cart_count'] ?></h3>
                    <p>در سبد خرید</p>
                </div>
            </div>
        </div>

        <!-- دسترسی سریع -->
        <div class="quick-actions">
            <h2 class="section-title">
                <i class="fas fa-bolt"></i> دسترسی سریع
            </h2>
            <div class="actions-grid">
                <a href="<?= $base ?>/profile" class="action-card">
                    <i class="fas fa-user-edit"></i>
                    <span>ویرایش حساب</span>
                </a>
                <a href="<?= $base ?>/wishlist" class="action-card">
                    <i class="fas fa-heart"></i>
                    <span>علاقه‌مندی‌ها</span>
                </a>
                <a href="<?= $base ?>/orders" class="action-card">
                    <i class="fas fa-shopping-bag"></i>
                    <span>سفارش‌ها</span>
                </a>
                <a href="<?= $base ?>/cart" class="action-card">
                    <i class="fas fa-shopping-cart"></i>
                    <span>سبد خرید</span>
                </a>
                <a href="<?= $base ?>/products" class="action-card">
                    <i class="fas fa-store"></i>
                    <span>فروشگاه</span>
                </a>
                <a href="<?= $base ?>/logout" class="action-card action-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>خروج از حساب</span>
                </a>
            </div>
        </div>

        <!-- آخرین فعالیت‌ها -->
        <div class="dashboard-row">
            
            <!-- آخرین سفارشات -->
            <div class="dashboard-col">
                <div class="activity-card">
                    <h2 class="card-title">
                        <i class="fas fa-shopping-bag"></i> آخرین سفارشات
                    </h2>
                    <?php if (!empty($recentOrders)): ?>
                    <div class="orders-list">
                        <?php foreach ($recentOrders as $order): ?>
                        <div class="order-item">
                            <div class="order-info">
                                <span class="order-id">#<?= (int)$order['id'] ?></span>
                                <span class="order-date"><?= Security::e(date('Y/m/d', strtotime($order['created_at']))) ?></span>
                            </div>
                            <div class="order-status status-<?= Security::e($order['status']) ?>">
                                <?php
                                $statusLabels = [
                                    'pending'    => 'در انتظار پرداخت',
                                    'paid'       => 'پرداخت شده',
                                    'processing' => 'در حال پردازش',
                                    'shipped'    => 'ارسال شده',
                                    'delivered'  => 'تحویل داده شده',
                                    'cancelled'  => 'لغو شده'
                                ];
                                echo $statusLabels[$order['status']] ?? $order['status'];
                                ?>
                            </div>
                            <div class="order-total">
                                <?= number_format((int)$order['total_amount']) ?> تومان
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="<?= $base ?>/orders" class="view-all-link">
                        مشاهده همه سفارشات <i class="fas fa-arrow-left"></i>
                    </a>
                    <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-shopping-bag"></i>
                        <p>هنوز سفارشی ثبت نکرده‌اید</p>
                        <a href="<?= $base ?>/products" class="btn-start-shopping">شروع خرید</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- آخرین نظرات -->
            <div class="dashboard-col">
                <div class="activity-card">
                    <h2 class="card-title">
                        <i class="fas fa-star"></i> آخرین نظرات شما
                    </h2>
                    <?php if (!empty($recentReviews)): ?>
                    <div class="reviews-list">
                        <?php foreach ($recentReviews as $review): ?>
                        <div class="review-item">
                            <div class="review-product">
                                <?php if (!empty($review['main_image'])): ?>
                                <img src="<?= Security::e($review['main_image']) ?>" alt="<?= Security::e($review['product_name']) ?>">
                                <?php endif; ?>
                                <div class="review-details">
                                    <a href="<?= $base ?>/products/<?= Security::e($review['product_slug']) ?>" class="product-name">
                                        <?= Security::e($review['product_name']) ?>
                                    </a>
                                    <div class="review-rating">
                                        <?php for ($s = 1; $s <= 5; $s++): ?>
                                        <i class="<?= $s <= (int)$review['rating'] ? 'fas' : 'far' ?> fa-star"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="review-status status-<?= $review['is_approved'] ? 'approved' : 'pending' ?>">
                                <?= $review['is_approved'] ? 'تأیید شده' : 'در انتظار تأیید' ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-star"></i>
                        <p>هنوز نظری ثبت نکرده‌اید</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </div>

</main>
