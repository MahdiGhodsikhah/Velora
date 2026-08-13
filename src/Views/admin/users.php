<div class="admin-table">
    <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: #1e293b;">لیست کاربران</h3>
        <button class="btn btn-primary btn-sm" disabled>افزودن کاربر جدید</button>
    </div>
    <table>
        <thead>
            <tr>
                <th>شناسه</th>
                <th>نام کاربری</th>
                <th>نام کامل</th>
                <th>شماره تماس</th>
                <th>ایمیل</th>
                <th>نقش</th>
                <th>وضعیت</th>
                <th>آخرین ورود</th>
                <th>تاریخ عضویت</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td style="font-weight: 600; color: #4f46e5;">#<?= $user['id'] ?></td>
                        <td>
                            <div style="font-weight: 600; color: #1e293b;">
                                <?= Security::e($user['username']) ?>
                            </div>
                            <?php if ($user['login_attempts'] > 0): ?>
                                <div style="font-size: 0.75rem; color: #dc2626;">
                                    <?= $user['login_attempts'] ?> تلاش ناموفق
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><?= Security::e($user['full_name'] ?? '-') ?></td>
                        <td style="direction: ltr; text-align: right; font-family: monospace;">
                            <?= Security::e($user['phone']) ?>
                        </td>
                        <td style="direction: ltr; text-align: right;">
                            <?= Security::e($user['email'] ?? '-') ?>
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
                        <td>
                            <?php if ($user['is_active']): ?>
                                <span class="badge badge-success">فعال</span>
                            <?php else: ?>
                                <span class="badge badge-danger">غیرفعال</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size: 0.875rem; color: #64748b;">
                            <?php if (!empty($user['last_login'])): ?>
                                <?= jdate('Y/m/d H:i', strtotime($user['last_login'])) ?>
                            <?php else: ?>
                                <span style="color: #94a3b8;">هرگز</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size: 0.875rem; color: #64748b;">
                            <?= jdate('Y/m/d', strtotime($user['created_at'])) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" style="text-align: center; color: #94a3b8; padding: 3rem;">
                        هیچ کاربری یافت نشد
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
