<div class="admin-table">
    <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0;">
        <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: #1e293b;">لیست سفارشات</h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>شماره سفارش</th>
                <th>مشتری</th>
                <th>شماره تماس</th>
                <th>مبلغ کل</th>
                <th>تخفیف</th>
                <th>هزینه ارسال</th>
                <th>وضعیت سفارش</th>
                <th>وضعیت پرداخت</th>
                <th>تاریخ</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td style="font-weight: 600; color: #4f46e5;">#<?= Security::e($order['order_number'] ?? 'N/A') ?></td>
                        <td>
                            <div style="font-weight: 600; color: #1e293b;">
                                <?= Security::e($order['full_name'] ?? $order['username'] ?? 'ناشناس') ?>
                            </div>
                            <?php if (!empty($order['username'])): ?>
                                <div style="font-size: 0.75rem; color: #94a3b8;">
                                    @<?= Security::e($order['username']) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td style="direction: ltr; text-align: right; font-family: monospace;">
                            <?= Security::e($order['phone'] ?? '-') ?>
                        </td>
                        <td style="font-weight: 600;">
                            <?= number_format($order['total_amount'] ?? 0) ?> تومان
                        </td>
                        <td>
                            <?php 
                            $discountAmt = $order['discount_amt'] ?? 0;
                            if ($discountAmt > 0): ?>
                                <span style="color: #dc2626;"><?= number_format($discountAmt) ?> تومان</span>
                            <?php else: ?>
                                <span style="color: #94a3b8;">-</span>
                            <?php endif; ?>
                        </td>
                        <td><?= number_format($order['shipping_cost'] ?? 0) ?> تومان</td>
                        <td>
                            <?php
                            $statusLabels = [
                                'pending' => ['label' => 'در انتظار', 'class' => 'warning'],
                                'processing' => ['label' => 'در حال پردازش', 'class' => 'info'],
                                'completed' => ['label' => 'تکمیل شده', 'class' => 'success'],
                                'cancelled' => ['label' => 'لغو شده', 'class' => 'danger']
                            ];
                            $orderStatus = $order['status'] ?? 'pending';
                            $status = $statusLabels[$orderStatus] ?? ['label' => $orderStatus, 'class' => 'info'];
                            ?>
                            <span class="badge badge-<?= $status['class'] ?>"><?= $status['label'] ?></span>
                        </td>
                        <td>
                            <?php
                            $paymentLabels = [
                                'paid' => ['label' => 'پرداخت شده', 'class' => 'success'],
                                'unpaid' => ['label' => 'پرداخت نشده', 'class' => 'danger'],
                                'refunded' => ['label' => 'بازگشت داده شده', 'class' => 'warning']
                            ];
                            $paymentStatus = $order['payment_status'] ?? 'unpaid';
                            $payment = $paymentLabels[$paymentStatus] ?? ['label' => $paymentStatus, 'class' => 'info'];
                            ?>
                            <span class="badge badge-<?= $payment['class'] ?>"><?= $payment['label'] ?></span>
                        </td>
                        <td style="font-size: 0.875rem; color: #64748b;">
                            <?= jdate('Y/m/d H:i', strtotime($order['created_at'])) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" style="text-align: center; color: #94a3b8; padding: 3rem;">
                        هیچ سفارشی ثبت نشده است
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
