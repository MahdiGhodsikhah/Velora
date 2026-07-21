<?php
/**
 * صفحه سفارشات کاربر
 */
$base = defined('BASE_URL') ? BASE_URL : '';

// نگاشت وضعیت‌ها به فارسی
$statusLabels = [
    'pending' => 'در انتظار بررسی',
    'processing' => 'در حال پردازش',
    'shipped' => 'ارسال شده',
    'delivered' => 'تحویل داده شده',
    'cancelled' => 'لغو شده',
    'refunded' => 'مرجوع شده'
];

$statusColors = [
    'pending' => '#f39c12',
    'processing' => '#3498db',
    'shipped' => '#9b59b6',
    'delivered' => '#27ae60',
    'cancelled' => '#e74c3c',
    'refunded' => '#95a5a6'
];

$paymentStatusLabels = [
    'unpaid' => 'پرداخت نشده',
    'paid' => 'پرداخت شده',
    'refunded' => 'بازپرداخت شده'
];

// دریافت محصولات هر سفارش
$orderModel = new OrderModel();
foreach ($orders as &$order) {
    $order['items'] = $orderModel->getOrderItems((int)$order['id']);
}
unset($order);
?>

<link rel="stylesheet" href="<?= $base ?>/assets/css/orders.css">

<main id="main-content" class="orders-page">
    <div class="orders-container">
        
        <!-- هدر صفحه -->
        <div class="page-header">
            <h1>
                <i class="fas fa-shopping-bag"></i>
                سفارشات من
            </h1>
            <?php if (!empty($orders)): ?>
            <p><?= count($orders) ?> سفارش ثبت شده</p>
            <?php endif; ?>
        </div>

        <?php if (!empty($orders)): ?>
        
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
            <div class="order-card">
                
                <!-- هدر سفارش -->
                <div class="order-header">
                    <div>
                        <div class="order-number">
                            <i class="fas fa-receipt"></i>
                            <?= Security::e($order['order_number']) ?>
                        </div>
                        <div class="order-date">
                            <?php
                            $date = new DateTime($order['created_at']);
                            echo $date->format('Y/m/d - H:i');
                            ?>
                        </div>
                    </div>
                    <div>
                        <span class="order-status" style="background-color: <?= $statusColors[$order['status']] ?>">
                            <?= $statusLabels[$order['status']] ?>
                        </span>
                    </div>
                </div>

                <!-- بدنه سفارش -->
                <div class="order-body">
                    <div class="order-info">
                        <div class="info-item">
                            <i class="fas fa-box"></i>
                            <span><?= (int)$order['total_items'] ?> محصول</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-credit-card"></i>
                            <span><?= $paymentStatusLabels[$order['payment_status']] ?></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-truck"></i>
                            <span>هزینه ارسال: <?= number_format((int)$order['shipping_cost']) ?> تومان</span>
                        </div>
                    </div>

                    <?php if (!empty($order['items'])): ?>
                    <div class="order-products">
                        <div class="order-products-title">
                            <i class="fas fa-shopping-bag"></i>
                            محصولات سفارش:
                        </div>
                        <div class="order-products-list">
                            <?php foreach ($order['items'] as $item): ?>
                            <div class="product-item">
                                <div class="product-item-image">
                                    <img src="<?= $base ?><?= Security::e($item['main_image'] ?? '/assets/images/products/no-image.jpg') ?>" 
                                         alt="<?= Security::e($item['product_name']) ?>">
                                </div>
                                <div class="product-item-info">
                                    <span class="product-item-name"><?= Security::e($item['product_name']) ?></span>
                                    <span class="product-item-quantity">
                                        تعداد: <?= (int)$item['quantity'] ?> × <?= number_format((int)$item['unit_price']) ?> تومان
                                    </span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($order['shipping_address'])): ?>
                    <div class="shipping-address">
                        <strong><i class="fas fa-map-marker-alt"></i> اطلاعات ارسال:</strong>
                        <div class="address-row">
                            <div class="address-item">
                                <strong>آدرس:</strong>
                                <span><?= Security::e($order['shipping_address']) ?></span>
                            </div>
                            <?php if (!empty($order['postal_code'])): ?>
                            <div class="address-item">
                                <strong>کد پستی:</strong>
                                <span><?= Security::e($order['postal_code']) ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- فوتر سفارش -->
                <div class="order-footer">
                    <div class="order-total">
                        مبلغ کل: <?= number_format((int)$order['total_amount']) ?> تومان
                    </div>
                    <div class="order-actions">
                        <a href="<?= $base ?>/orders/view/<?= (int)$order['id'] ?>" class="btn btn-primary">
                            <i class="fas fa-eye"></i>
                            مشاهده جزئیات
                        </a>
                    </div>
                </div>

            </div>
            <?php endforeach; ?>
        </div>

        <?php else: ?>
        
        <!-- حالت خالی -->
        <div class="empty-orders">
            <i class="fas fa-shopping-bag"></i>
            <h2>هنوز سفارشی ثبت نکرده‌اید</h2>
            <p>سفارشات شما در اینجا نمایش داده می‌شود</p>
            <a href="<?= $base ?>/products" class="btn-start-shopping">
                <i class="fas fa-store"></i>
                شروع خرید
            </a>
        </div>

        <?php endif; ?>

    </div>
</main>
