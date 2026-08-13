<?php if (isset($_SESSION['admin_success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['admin_success'] ?>
        <?php unset($_SESSION['admin_success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['admin_error'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['admin_error'] ?>
        <?php unset($_SESSION['admin_error']); ?>
    </div>
<?php endif; ?>

<div style="margin-bottom: 1.5rem;">
    <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-secondary">
        ← بازگشت به لیست سفارشات
    </a>
</div>

<div class="admin-table" style="margin-bottom: 1.5rem;">
    <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0;">
        <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #1e293b;">
            📦 جزئیات سفارش #<?= Security::e($order['order_number']) ?>
        </h3>
        <p style="margin: 0.5rem 0 0; font-size: 0.875rem; color: #64748b;">
            تاریخ ثبت: <?= jdate('l، d F Y - ساعت H:i', strtotime($order['created_at'])) ?>
        </p>
    </div>
    
    <div style="padding: 2rem;">
        <div class="row g-4">
            <!-- اطلاعات مشتری -->
            <div class="col-md-6">
                <div style="padding: 1.5rem; background: #f8fafc; border-radius: 12px;">
                    <h4 style="font-size: 1rem; font-weight: 600; color: #475569; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        👤 اطلاعات مشتری
                    </h4>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.875rem;">
                        <div>
                            <strong>نام:</strong>
                            <?= Security::e($order['full_name'] ?? $order['username'] ?? 'ناشناس') ?>
                        </div>
                        <?php if (!empty($order['phone'])): ?>
                            <div>
                                <strong>تلفن:</strong>
                                <span style="direction: ltr; display: inline-block;"><?= Security::e($order['phone']) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($order['email'])): ?>
                            <div>
                                <strong>ایمیل:</strong>
                                <span style="direction: ltr; display: inline-block;"><?= Security::e($order['email']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- اطلاعات ارسال -->
            <div class="col-md-6">
                <div style="padding: 1.5rem; background: #f8fafc; border-radius: 12px;">
                    <h4 style="font-size: 1rem; font-weight: 600; color: #475569; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        📍 آدرس ارسال
                    </h4>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.875rem;">
                        <div>
                            <strong>آدرس:</strong><br>
                            <?= nl2br(Security::e($order['shipping_address'] ?? '-')) ?>
                        </div>
                        <?php if (!empty($order['postal_code'])): ?>
                            <div>
                                <strong>کد پستی:</strong>
                                <span style="font-family: monospace;"><?= Security::e($order['postal_code']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- وضعیت سفارش -->
            <div class="col-md-4">
                <div style="padding: 1.5rem; background: #f8fafc; border-radius: 12px;">
                    <h4 style="font-size: 1rem; font-weight: 600; color: #475569; margin-bottom: 1rem;">
                        📊 وضعیت سفارش
                    </h4>
                    <form method="POST" action="<?= BASE_URL ?>/admin/orders/update-status">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <input type="hidden" name="action" value="status">
                        <input type="hidden" name="return_url" value="/admin/orders/view/<?= $order['id'] ?>">
                        <select name="value" 
                                onchange="this.form.submit()"
                                style="width: 100%; padding: 0.75rem; border-radius: 8px; font-size: 0.875rem; cursor: pointer; border: 2px solid #e2e8f0;">
                            <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>⏳ در انتظار</option>
                            <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>⚙️ در حال پردازش</option>
                            <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : '' ?>>🚚 ارسال شده</option>
                            <option value="delivered" <?= $order['status'] == 'delivered' ? 'selected' : '' ?>>✅ تحویل داده شده</option>
                            <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>❌ لغو شده</option>
                            <option value="refunded" <?= $order['status'] == 'refunded' ? 'selected' : '' ?>>↩️ مرجوع شده</option>
                        </select>
                    </form>
                </div>
            </div>
            
            <!-- وضعیت پرداخت -->
            <div class="col-md-4">
                <div style="padding: 1.5rem; background: #f8fafc; border-radius: 12px;">
                    <h4 style="font-size: 1rem; font-weight: 600; color: #475569; margin-bottom: 1rem;">
                        💳 وضعیت پرداخت
                    </h4>
                    <form method="POST" action="<?= BASE_URL ?>/admin/orders/update-status">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <input type="hidden" name="action" value="payment">
                        <input type="hidden" name="return_url" value="/admin/orders/view/<?= $order['id'] ?>">
                        <select name="value" 
                                onchange="this.form.submit()"
                                style="width: 100%; padding: 0.75rem; border-radius: 8px; font-size: 0.875rem; cursor: pointer; border: 2px solid #e2e8f0;">
                            <option value="unpaid" <?= $order['payment_status'] == 'unpaid' ? 'selected' : '' ?>>❌ پرداخت نشده</option>
                            <option value="paid" <?= $order['payment_status'] == 'paid' ? 'selected' : '' ?>>✅ پرداخت شده</option>
                            <option value="refunded" <?= $order['payment_status'] == 'refunded' ? 'selected' : '' ?>>↩️ بازگشت داده شده</option>
                        </select>
                    </form>
                </div>
            </div>
            
            <!-- روش پرداخت -->
            <div class="col-md-4">
                <div style="padding: 1.5rem; background: #f8fafc; border-radius: 12px;">
                    <h4 style="font-size: 1rem; font-weight: 600; color: #475569; margin-bottom: 1rem;">
                        💰 روش پرداخت
                    </h4>
                    <div style="font-size: 0.875rem; padding: 0.75rem; background: white; border-radius: 8px; text-align: center; font-weight: 600;">
                        <?php
                        $methods = [
                            'online' => '💳 پرداخت آنلاین',
                            'cash' => '💵 پرداخت نقدی در محل',
                            'card' => '💳 کارت به کارت'
                        ];
                        echo $methods[$order['payment_method'] ?? 'online'] ?? $order['payment_method'];
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- آیتم‌های سفارش -->
<div class="admin-table">
    <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0;">
        <h4 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: #1e293b;">
            🛒 آیتم‌های سفارش
        </h4>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>تصویر</th>
                <th>نام محصول</th>
                <th>قیمت واحد</th>
                <th>تعداد</th>
                <th>جمع</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($order['items'])): ?>
                <?php foreach ($order['items'] as $item): ?>
                    <tr>
                        <td>
                            <?php if (!empty($item['main_image'])): ?>
                                <img src="<?= BASE_URL . $item['main_image'] ?>" 
                                     alt="<?= Security::e($item['product_name']) ?>" 
                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                            <?php else: ?>
                                <div style="width: 60px; height: 60px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    📦
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #1e293b;">
                                <?= Security::e($item['product_name']) ?>
                            </div>
                            <?php if (!empty($item['slug'])): ?>
                                <div style="font-size: 0.75rem; color: #94a3b8;">
                                    <?= Security::e($item['slug']) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td style="font-weight: 600;">
                            <?= number_format($item['unit_price']) ?> تومان
                        </td>
                        <td>
                            <span class="badge badge-info"><?= $item['quantity'] ?> عدد</span>
                        </td>
                        <td style="font-weight: 700; color: #4f46e5;">
                            <?= number_format($item['subtotal']) ?> تومان
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 2rem; color: #94a3b8;">
                        هیچ آیتمی یافت نشد
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <!-- خلاصه مالی -->
    <div style="padding: 1.5rem; background: #f8fafc; border-top: 2px solid #e2e8f0;">
        <div style="max-width: 400px; margin-right: auto; display: flex; flex-direction: column; gap: 0.75rem;">
            <div style="display: flex; justify-content: space-between; font-size: 0.875rem;">
                <span>جمع آیتم‌ها:</span>
                <span style="font-weight: 600;"><?= number_format($order['total_amount'] - $order['shipping_cost'] + $order['discount_amt']) ?> تومان</span>
            </div>
            
            <?php if ($order['discount_amt'] > 0): ?>
                <div style="display: flex; justify-content: space-between; font-size: 0.875rem; color: #dc2626;">
                    <span>تخفیف:</span>
                    <span style="font-weight: 600;">- <?= number_format($order['discount_amt']) ?> تومان</span>
                </div>
            <?php endif; ?>
            
            <div style="display: flex; justify-content: space-between; font-size: 0.875rem;">
                <span>هزینه ارسال:</span>
                <span style="font-weight: 600;">
                    <?= $order['shipping_cost'] > 0 ? number_format($order['shipping_cost']) . ' تومان' : 'رایگان' ?>
                </span>
            </div>
            
            <div style="display: flex; justify-content: space-between; padding-top: 0.75rem; border-top: 2px solid #e2e8f0; font-size: 1.125rem;">
                <span style="font-weight: 700;">مبلغ کل:</span>
                <span style="font-weight: 700; color: #4f46e5;"><?= number_format($order['total_amount']) ?> تومان</span>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($order['notes'])): ?>
    <div class="admin-table" style="margin-top: 1.5rem;">
        <div style="padding: 1.5rem;">
            <h4 style="font-size: 1rem; font-weight: 600; color: #475569; margin-bottom: 0.75rem;">
                📝 یادداشت
            </h4>
            <p style="margin: 0; color: #64748b; line-height: 1.6;">
                <?= nl2br(Security::e($order['notes'])) ?>
            </p>
        </div>
    </div>
<?php endif; ?>
