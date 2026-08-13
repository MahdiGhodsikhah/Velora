<?php
// نمایش پیام‌های موفقیت/خطا
if (isset($_SESSION['admin_success'])): ?>
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

<!-- آمار کلی -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <h3>کل کاربران</h3>
            <div class="stat-value"><?= number_format($stats['total_users'] ?? 0) ?></div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon green">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <h3>کل محصولات</h3>
            <div class="stat-value"><?= number_format($stats['total_products'] ?? 0) ?></div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon purple">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h3>کل سفارشات</h3>
            <div class="stat-value"><?= number_format($stats['total_orders'] ?? 0) ?></div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon orange">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3>سفارشات در انتظار</h3>
            <div class="stat-value"><?= number_format($stats['pending_orders'] ?? 0) ?></div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- آخرین سفارشات -->
    <div class="col-lg-7">
        <div class="admin-table">
            <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0;">
                <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: #1e293b;">آخرین سفارشات</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>شماره سفارش</th>
                        <th>مشتری</th>
                        <th>مبلغ</th>
                        <th>وضعیت</th>
                        <th>تاریخ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($stats['recent_orders'])): ?>
                        <?php foreach ($stats['recent_orders'] as $order): ?>
                            <tr>
                                <td style="font-weight: 600; color: #4f46e5;">#<?= Security::e($order['order_number']) ?></td>
                                <td><?= Security::e($order['full_name'] ?? $order['username'] ?? 'ناشناس') ?></td>
                                <td><?= number_format($order['total_amount']) ?> تومان</td>
                                <td>
                                    <?php
                                    $statusLabels = [
                                        'pending' => ['label' => 'در انتظار', 'class' => 'warning'],
                                        'processing' => ['label' => 'در حال پردازش', 'class' => 'info'],
                                        'completed' => ['label' => 'تکمیل شده', 'class' => 'success'],
                                        'cancelled' => ['label' => 'لغو شده', 'class' => 'danger']
                                    ];
                                    $status = $statusLabels[$order['status']] ?? ['label' => $order['status'], 'class' => 'info'];
                                    ?>
                                    <span class="badge badge-<?= $status['class'] ?>"><?= $status['label'] ?></span>
                                </td>
                                <td style="font-size: 0.875rem; color: #64748b;">
                                    <?= jdate('Y/m/d', strtotime($order['created_at'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #94a3b8; padding: 2rem;">
                                هیچ سفارشی ثبت نشده است
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php if (!empty($stats['recent_orders'])): ?>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid #f1f5f9; text-align: center;">
                    <a href="<?= BASE_URL ?>/admin/orders" style="color: #4f46e5; text-decoration: none; font-size: 0.875rem; font-weight: 600;">
                        مشاهده همه سفارشات →
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- آخرین کاربران -->
    <div class="col-lg-5">
        <div class="admin-table">
            <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0;">
                <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: #1e293b;">آخرین کاربران</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>نام کاربری</th>
                        <th>نقش</th>
                        <th>تاریخ عضویت</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($stats['recent_users'])): ?>
                        <?php foreach ($stats['recent_users'] as $user): ?>
                            <tr>
                                <td>
                                    <div style="font-weight: 600; color: #1e293b;">
                                        <?= Security::e($user['username']) ?>
                                    </div>
                                    <?php if (!empty($user['full_name'])): ?>
                                        <div style="font-size: 0.75rem; color: #94a3b8;">
                                            <?= Security::e($user['full_name']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $roleLabels = [
                                        'admin' => ['label' => 'مدیر', 'class' => 'danger'],
                                        'moderator' => ['label' => 'ناظر', 'class' => 'warning'],
                                        'customer' => ['label' => 'مشتری', 'class' => 'info']
                                    ];
                                    $role = $roleLabels[$user['role']] ?? ['label' => $user['role'], 'class' => 'info'];
                                    ?>
                                    <span class="badge badge-<?= $role['class'] ?>"><?= $role['label'] ?></span>
                                </td>
                                <td style="font-size: 0.875rem; color: #64748b;">
                                    <?= jdate('Y/m/d', strtotime($user['created_at'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center; color: #94a3b8; padding: 2rem;">
                                هیچ کاربری ثبت نشده است
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php if (!empty($stats['recent_users'])): ?>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid #f1f5f9; text-align: center;">
                    <a href="<?= BASE_URL ?>/admin/users" style="color: #4f46e5; text-decoration: none; font-size: 0.875rem; font-weight: 600;">
                        مشاهده همه کاربران →
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
