<?php if (isset($_SESSION['admin_success'])): ?>
    <div class="alert alert-success" style="margin-bottom: 1.5rem;">
        <?= $_SESSION['admin_success'] ?>
        <?php unset($_SESSION['admin_success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['admin_error'])): ?>
    <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
        <?= $_SESSION['admin_error'] ?>
        <?php unset($_SESSION['admin_error']); ?>
    </div>
<?php endif; ?>

<div class="admin-table">
    <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: #1e293b;">
                مدیریت سفارشات
            </h3>
            <p style="margin: 0.25rem 0 0; font-size: 0.875rem; color: #64748b;">
                کل: <?= number_format($totalOrders) ?> سفارش | 
                <?= $pendingCount ?> سفارش در انتظار | 
                صفحه <?= $page ?> از <?= $totalPages ?>
            </p>
        </div>
        
        <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <!-- فیلتر وضعیت سفارش -->
            <form method="GET" action="<?= BASE_URL ?>/admin/orders" style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="hidden" name="page" value="1">
                <input type="hidden" name="per_page" value="<?= $perPage ?>">
                <input type="hidden" name="payment" value="<?= $paymentFilter ?>">
                <label for="status" style="font-size: 0.875rem; color: #64748b;">وضعیت:</label>
                <select id="status" 
                        name="status" 
                        onchange="this.form.submit()"
                        style="padding: 0.375rem 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.875rem; cursor: pointer;">
                    <option value="all" <?= $statusFilter == 'all' ? 'selected' : '' ?>>همه</option>
                    <option value="pending" <?= $statusFilter == 'pending' ? 'selected' : '' ?>>در انتظار</option>
                    <option value="processing" <?= $statusFilter == 'processing' ? 'selected' : '' ?>>در حال پردازش</option>
                    <option value="shipped" <?= $statusFilter == 'shipped' ? 'selected' : '' ?>>ارسال شده</option>
                    <option value="delivered" <?= $statusFilter == 'delivered' ? 'selected' : '' ?>>تحویل داده شده</option>
                    <option value="cancelled" <?= $statusFilter == 'cancelled' ? 'selected' : '' ?>>لغو شده</option>
                    <option value="refunded" <?= $statusFilter == 'refunded' ? 'selected' : '' ?>>مرجوع شده</option>
                </select>
            </form>
            
            <!-- فیلتر وضعیت پرداخت -->
            <form method="GET" action="<?= BASE_URL ?>/admin/orders" style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="hidden" name="page" value="1">
                <input type="hidden" name="per_page" value="<?= $perPage ?>">
                <input type="hidden" name="status" value="<?= $statusFilter ?>">
                <label for="payment" style="font-size: 0.875rem; color: #64748b;">پرداخت:</label>
                <select id="payment" 
                        name="payment" 
                        onchange="this.form.submit()"
                        style="padding: 0.375rem 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.875rem; cursor: pointer;">
                    <option value="all" <?= $paymentFilter == 'all' ? 'selected' : '' ?>>همه</option>
                    <option value="unpaid" <?= $paymentFilter == 'unpaid' ? 'selected' : '' ?>>پرداخت نشده</option>
                    <option value="paid" <?= $paymentFilter == 'paid' ? 'selected' : '' ?>>پرداخت شده</option>
                    <option value="refunded" <?= $paymentFilter == 'refunded' ? 'selected' : '' ?>>بازگشت داده شده</option>
                </select>
            </form>
            
            <!-- تعداد نمایش -->
            <form method="GET" action="<?= BASE_URL ?>/admin/orders" style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="hidden" name="page" value="1">
                <input type="hidden" name="status" value="<?= $statusFilter ?>">
                <input type="hidden" name="payment" value="<?= $paymentFilter ?>">
                <label for="per_page" style="font-size: 0.875rem; color: #64748b; white-space: nowrap;">نمایش:</label>
                <select id="per_page" 
                        name="per_page" 
                        onchange="this.form.submit()"
                        style="padding: 0.375rem 0.5rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.875rem; cursor: pointer;">
                    <option value="5" <?= $perPage == 5 ? 'selected' : '' ?>>5</option>
                    <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10</option>
                    <option value="20" <?= $perPage == 20 ? 'selected' : '' ?>>20</option>
                    <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50</option>
                </select>
            </form>
        </div>
    </div>
    
    <!-- جدول سفارشات -->
    <div style="overflow-x: auto;">
        <table style="min-width: 1400px;">
            <thead style="position: sticky; top: 0; z-index: 10; background: #f8fafc;">
                <tr>
                    <th>شناسه</th>
                    <th>شماره سفارش</th>
                    <th>مشتری</th>
                    <th>مبلغ کل</th>
                    <th>تخفیف</th>
                    <th>هزینه ارسال</th>
                    <th>روش پرداخت</th>
                    <th>وضعیت سفارش</th>
                    <th>وضعیت پرداخت</th>
                    <th>تاریخ</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td style="font-weight: 600; color: #4f46e5;">#<?= $order['id'] ?></td>
                            <td style="font-family: monospace; font-weight: 600;">
                                <?= Security::e($order['order_number']) ?>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #1e293b;">
                                    <?= Security::e($order['full_name'] ?? $order['username'] ?? 'ناشناس') ?>
                                </div>
                                <?php if (!empty($order['phone'])): ?>
                                    <div style="font-size: 0.75rem; color: #94a3b8; direction: ltr; text-align: right;">
                                        <?= Security::e($order['phone']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td style="font-weight: 600;">
                                <?= number_format($order['total_amount']) ?> ت
                            </td>
                            <td>
                                <?php if ($order['discount_amt'] > 0): ?>
                                    <span style="color: #dc2626; font-weight: 600;">
                                        <?= number_format($order['discount_amt']) ?> ت
                                    </span>
                                <?php else: ?>
                                    <span style="color: #94a3b8;">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= number_format($order['shipping_cost']) ?> ت</td>
                            <td>
                                <?php
                                $paymentMethods = [
                                    'online' => ['label' => '💳 آنلاین', 'class' => 'info'],
                                    'cash' => ['label' => '💵 نقدی', 'class' => 'warning'],
                                    'card' => ['label' => '💳 کارت', 'class' => 'info']
                                ];
                                $method = $paymentMethods[$order['payment_method'] ?? 'online'] ?? ['label' => $order['payment_method'], 'class' => 'info'];
                                ?>
                                <span class="badge badge-<?= $method['class'] ?>"><?= $method['label'] ?></span>
                            </td>
                            <td>
                                <!-- دراپ‌داون تغییر وضعیت -->
                                <form method="POST" action="<?= BASE_URL ?>/admin/orders/update-status" style="display: inline;">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <input type="hidden" name="action" value="status">
                                    <input type="hidden" name="return_url" value="/admin/orders?page=<?= $page ?>&per_page=<?= $perPage ?>&status=<?= $statusFilter ?>&payment=<?= $paymentFilter ?>">
                                    <select name="value" 
                                            onchange="this.form.submit()"
                                            style="padding: 0.375rem 0.5rem; border-radius: 6px; font-size: 0.875rem; cursor: pointer; border: 1px solid #e2e8f0;"
                                            class="status-<?= $order['status'] ?>">
                                        <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>⏳ در انتظار</option>
                                        <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>⚙️ در حال پردازش</option>
                                        <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : '' ?>>🚚 ارسال شده</option>
                                        <option value="delivered" <?= $order['status'] == 'delivered' ? 'selected' : '' ?>>✅ تحویل داده شده</option>
                                        <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>❌ لغو شده</option>
                                        <option value="refunded" <?= $order['status'] == 'refunded' ? 'selected' : '' ?>>↩️ مرجوع شده</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <!-- دراپ‌داون تغییر وضعیت پرداخت -->
                                <form method="POST" action="<?= BASE_URL ?>/admin/orders/update-status" style="display: inline;">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <input type="hidden" name="action" value="payment">
                                    <input type="hidden" name="return_url" value="/admin/orders?page=<?= $page ?>&per_page=<?= $perPage ?>&status=<?= $statusFilter ?>&payment=<?= $paymentFilter ?>">
                                    <select name="value" 
                                            onchange="this.form.submit()"
                                            style="padding: 0.375rem 0.5rem; border-radius: 6px; font-size: 0.875rem; cursor: pointer; border: 1px solid #e2e8f0;"
                                            class="payment-<?= $order['payment_status'] ?>">
                                        <option value="unpaid" <?= $order['payment_status'] == 'unpaid' ? 'selected' : '' ?>>❌ پرداخت نشده</option>
                                        <option value="paid" <?= $order['payment_status'] == 'paid' ? 'selected' : '' ?>>✅ پرداخت شده</option>
                                        <option value="refunded" <?= $order['payment_status'] == 'refunded' ? 'selected' : '' ?>>↩️ بازگشت داده شده</option>
                                    </select>
                                </form>
                            </td>
                            <td style="font-size: 0.875rem; color: #64748b; white-space: nowrap;">
                                <?= jdate('Y/m/d H:i', strtotime($order['created_at'])) ?>
                            </td>
                            <td style="white-space: nowrap;">
                                <a href="<?= BASE_URL ?>/admin/orders/view/<?= $order['id'] ?>" 
                                   class="btn btn-sm btn-primary" 
                                   style="padding: 0.375rem 0.75rem; font-size: 0.875rem; text-decoration: none; display: inline-block; margin-left: 0.25rem;">
                                    👁️ جزئیات
                                </a>
                                <button onclick="confirmDelete(<?= $order['id'] ?>, '<?= Security::e($order['order_number']) ?>');" 
                                        class="btn btn-sm btn-danger" 
                                        style="padding: 0.375rem 0.75rem; font-size: 0.875rem;">
                                    🗑️ حذف
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" style="text-align: center; color: #94a3b8; padding: 3rem;">
                            هیچ سفارشی یافت نشد
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- صفحه‌بندی -->
<?php if ($totalPages > 1): ?>
    <div style="padding: 1.5rem; background: white; border-radius: 12px; margin-top: 1.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
        <nav>
            <ul style="display: flex; justify-content: center; align-items: center; gap: 0.5rem; list-style: none; padding: 0; margin: 0; flex-wrap: wrap;">
                <?php
                $baseParams = "per_page=$perPage&status=$statusFilter&payment=$paymentFilter";
                
                if ($page > 1): ?>
                    <li><a href="<?= BASE_URL ?>/admin/orders?page=1&<?= $baseParams ?>" style="display: inline-block; padding: 0.5rem 0.875rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #475569; text-decoration: none; font-size: 0.875rem;">⏮️ اول</a></li>
                    <li><a href="<?= BASE_URL ?>/admin/orders?page=<?= $page - 1 ?>&<?= $baseParams ?>" style="display: inline-block; padding: 0.5rem 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #475569; text-decoration: none; font-size: 0.875rem; font-weight: 600;">← قبلی</a></li>
                <?php endif;
                
                $rangeStart = max(1, $page - 2);
                $rangeEnd = min($totalPages, $page + 2);
                
                for ($i = $rangeStart; $i <= $rangeEnd; $i++): ?>
                    <li>
                        <?php if ($i == $page): ?>
                            <span style="display: inline-block; padding: 0.5rem 0.875rem; background: #4f46e5; color: white; border-radius: 6px; font-weight: 600; font-size: 0.875rem;"><?= $i ?></span>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/admin/orders?page=<?= $i ?>&<?= $baseParams ?>" style="display: inline-block; padding: 0.5rem 0.875rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #475569; text-decoration: none; font-size: 0.875rem;"><?= $i ?></a>
                        <?php endif; ?>
                    </li>
                <?php endfor;
                
                if ($page < $totalPages): ?>
                    <li><a href="<?= BASE_URL ?>/admin/orders?page=<?= $page + 1 ?>&<?= $baseParams ?>" style="display: inline-block; padding: 0.5rem 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #475569; text-decoration: none; font-size: 0.875rem; font-weight: 600;">بعدی →</a></li>
                    <li><a href="<?= BASE_URL ?>/admin/orders?page=<?= $totalPages ?>&<?= $baseParams ?>" style="display: inline-block; padding: 0.5rem 0.875rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #475569; text-decoration: none; font-size: 0.875rem;">آخر ⏭️</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div style="text-align: center; margin-top: 1rem; color: #64748b; font-size: 0.875rem;">
            نمایش <?= ($page - 1) * $perPage + 1 ?> تا <?= min($page * $perPage, $totalOrders) ?> از <?= number_format($totalOrders) ?> سفارش
        </div>
    </div>
<?php endif; ?>

<form id="deleteForm" method="POST" action="<?= BASE_URL ?>/admin/orders/delete" style="display: none;">
    <input type="hidden" name="order_id" id="deleteOrderId">
</form>

<script>
function confirmDelete(orderId, orderNumber) {
    if (confirm('آیا مطمئن هستید که می‌خواهید سفارش "' + orderNumber + '" را حذف کنید؟\n\nاین عمل قابل بازگشت نیست و تمام آیتم‌های سفارش نیز حذف خواهند شد!')) {
        document.getElementById('deleteOrderId').value = orderId;
        document.getElementById('deleteForm').submit();
    }
}
</script>

<style>
    select.status-pending { background-color: #fef3c7; }
    select.status-processing { background-color: #dbeafe; }
    select.status-shipped { background-color: #e0e7ff; }
    select.status-delivered { background-color: #d1fae5; }
    select.status-cancelled { background-color: #fee2e2; }
    select.status-refunded { background-color: #fef3c7; }
    
    select.payment-unpaid { background-color: #fee2e2; }
    select.payment-paid { background-color: #d1fae5; }
    select.payment-refunded { background-color: #fef3c7; }
    
    nav ul li a:hover {
        background: #e2e8f0 !important;
        border-color: #cbd5e1 !important;
    }
</style>
