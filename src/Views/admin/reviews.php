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
                مدیریت نظرات
            </h3>
            <p style="margin: 0.25rem 0 0; font-size: 0.875rem; color: #64748b;">
                کل: <?= number_format($totalReviews) ?> نظر | 
                <?= $pendingCount ?> نظر در انتظار تأیید | 
                صفحه <?= $page ?> از <?= $totalPages ?>
            </p>
        </div>
        
        <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <!-- فیلتر وضعیت -->
            <form method="GET" action="<?= BASE_URL ?>/admin/reviews" style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="hidden" name="page" value="1">
                <input type="hidden" name="per_page" value="<?= $perPage ?>">
                <label for="filter" style="font-size: 0.875rem; color: #64748b;">فیلتر:</label>
                <select id="filter" 
                        name="filter" 
                        onchange="this.form.submit()"
                        style="padding: 0.375rem 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.875rem; cursor: pointer;">
                    <option value="all" <?= $filter == 'all' ? 'selected' : '' ?>>همه نظرات</option>
                    <option value="pending" <?= $filter == 'pending' ? 'selected' : '' ?>>در انتظار تأیید (<?= $pendingCount ?>)</option>
                    <option value="approved" <?= $filter == 'approved' ? 'selected' : '' ?>>تأیید شده</option>
                </select>
            </form>
            
            <!-- تعداد نمایش -->
            <form method="GET" action="<?= BASE_URL ?>/admin/reviews" style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="hidden" name="page" value="1">
                <input type="hidden" name="filter" value="<?= $filter ?>">
                <label for="per_page" style="font-size: 0.875rem; color: #64748b; white-space: nowrap;">نمایش:</label>
                <select id="per_page" 
                        name="per_page" 
                        onchange="this.form.submit()"
                        style="padding: 0.375rem 0.5rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.875rem; cursor: pointer;">
                    <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10</option>
                    <option value="20" <?= $perPage == 20 ? 'selected' : '' ?>>20</option>
                    <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50</option>
                </select>
            </form>
        </div>
    </div>
    
    <!-- جدول نظرات -->
    <table>
        <thead style="position: sticky; top: 0; z-index: 10; background: #f8fafc;">
            <tr>
                <th>شناسه</th>
                <th>نویسنده</th>
                <th>محصول</th>
                <th>امتیاز</th>
                <th>عنوان</th>
                <th>متن نظر</th>
                <th>وضعیت</th>
                <th>تاریخ</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td style="font-weight: 600; color: #4f46e5;">#<?= $review['id'] ?></td>
                        <td>
                            <?php if (!empty($review['username'])): ?>
                                <div style="font-weight: 600; color: #1e293b;">
                                    @<?= Security::e($review['username']) ?>
                                </div>
                                <?php if (!empty($review['full_name'])): ?>
                                    <div style="font-size: 0.75rem; color: #94a3b8;">
                                        <?= Security::e($review['full_name']) ?>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <span style="color: #64748b;">
                                    <?= Security::e($review['author_name'] ?? 'ناشناس') ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td style="max-width: 200px;">
                            <div style="font-weight: 600; color: #1e293b;">
                                <?= Security::e($review['product_name'] ?? 'محصول حذف شده') ?>
                            </div>
                            <?php if (!empty($review['product_slug'])): ?>
                                <div style="font-size: 0.75rem; color: #94a3b8;">
                                    <?= Security::e($review['product_slug']) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.25rem;">
                                <?php 
                                $rating = (int)($review['rating'] ?? 0);
                                for ($i = 1; $i <= 5; $i++): ?>
                                    <span style="color: <?= $i <= $rating ? '#fbbf24' : '#e2e8f0' ?>;">★</span>
                                <?php endfor; ?>
                                <span style="font-weight: 600; margin-right: 0.25rem;"><?= $rating ?></span>
                            </div>
                        </td>
                        <td style="max-width: 200px;">
                            <?= Security::e($review['title'] ?? '-') ?>
                        </td>
                        <td style="max-width: 300px;">
                            <div style="max-height: 60px; overflow-y: auto; font-size: 0.875rem; color: #64748b; line-height: 1.4;">
                                <?= nl2br(Security::e($review['body'] ?? '')) ?>
                            </div>
                        </td>
                        <td>
                            <?php if ($review['is_approved']): ?>
                                <span class="badge badge-success">✓ تأیید شده</span>
                            <?php else: ?>
                                <span class="badge badge-warning">⏳ در انتظار</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size: 0.875rem; color: #64748b; white-space: nowrap;">
                            <?= jdate('Y/m/d', strtotime($review['created_at'])) ?>
                        </td>
                        <td style="white-space: nowrap;">
                            <!-- دکمه تأیید/لغو تأیید -->
                            <?php if ($review['is_approved']): ?>
                                <form method="POST" action="<?= BASE_URL ?>/admin/reviews/approve" style="display: inline;">
                                    <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                    <input type="hidden" name="action" value="unapprove">
                                    <button type="submit" 
                                            class="btn btn-sm btn-warning" 
                                            style="padding: 0.375rem 0.75rem; font-size: 0.875rem; margin-left: 0.25rem;">
                                        ⏸️ لغو تأیید
                                    </button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="<?= BASE_URL ?>/admin/reviews/approve" style="display: inline;">
                                    <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" 
                                            class="btn btn-sm btn-success" 
                                            style="padding: 0.375rem 0.75rem; font-size: 0.875rem; margin-left: 0.25rem;">
                                        ✓ تأیید
                                    </button>
                                </form>
                            <?php endif; ?>
                            
                            <!-- دکمه ویرایش -->
                            <a href="<?= BASE_URL ?>/admin/reviews/edit/<?= $review['id'] ?>" 
                               class="btn btn-sm btn-primary" 
                               style="padding: 0.375rem 0.75rem; font-size: 0.875rem; text-decoration: none; display: inline-block; margin-left: 0.25rem;">
                                ✏️ ویرایش
                            </a>
                            
                            <!-- دکمه حذف -->
                            <button onclick="confirmDelete(<?= $review['id'] ?>, '<?= Security::e($review['title'] ?? 'این نظر') ?>');" 
                                    class="btn btn-sm btn-danger" 
                                    style="padding: 0.375rem 0.75rem; font-size: 0.875rem;">
                                🗑️ حذف
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" style="text-align: center; color: #94a3b8; padding: 3rem;">
                        <?php if ($filter === 'pending'): ?>
                            هیچ نظری در انتظار تأیید نیست 🎉
                        <?php else: ?>
                            هیچ نظری یافت نشد
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- صفحه‌بندی -->
<?php if ($totalPages > 1): ?>
    <div style="padding: 1.5rem; background: white; border-radius: 12px; margin-top: 1.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
        <nav aria-label="صفحه‌بندی نظرات">
            <ul style="display: flex; justify-content: center; align-items: center; gap: 0.5rem; list-style: none; padding: 0; margin: 0; flex-wrap: wrap;">
                
                <?php if ($page > 1): ?>
                    <li>
                        <a href="<?= BASE_URL ?>/admin/reviews?page=1&per_page=<?= $perPage ?>&filter=<?= $filter ?>" 
                           style="display: inline-block; padding: 0.5rem 0.875rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #475569; text-decoration: none; font-size: 0.875rem;">
                            ⏮️ اول
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>/admin/reviews?page=<?= $page - 1 ?>&per_page=<?= $perPage ?>&filter=<?= $filter ?>" 
                           style="display: inline-block; padding: 0.5rem 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #475569; text-decoration: none; font-size: 0.875rem; font-weight: 600;">
                            ← قبلی
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php
                $rangeStart = max(1, $page - 2);
                $rangeEnd = min($totalPages, $page + 2);
                
                for ($i = $rangeStart; $i <= $rangeEnd; $i++): ?>
                    <li>
                        <?php if ($i == $page): ?>
                            <span style="display: inline-block; padding: 0.5rem 0.875rem; background: #4f46e5; color: white; border-radius: 6px; font-weight: 600; font-size: 0.875rem;">
                                <?= $i ?>
                            </span>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/admin/reviews?page=<?= $i ?>&per_page=<?= $perPage ?>&filter=<?= $filter ?>" 
                               style="display: inline-block; padding: 0.5rem 0.875rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #475569; text-decoration: none; font-size: 0.875rem;">
                                <?= $i ?>
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                    <li>
                        <a href="<?= BASE_URL ?>/admin/reviews?page=<?= $page + 1 ?>&per_page=<?= $perPage ?>&filter=<?= $filter ?>" 
                           style="display: inline-block; padding: 0.5rem 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #475569; text-decoration: none; font-size: 0.875rem; font-weight: 600;">
                            بعدی →
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>/admin/reviews?page=<?= $totalPages ?>&per_page=<?= $perPage ?>&filter=<?= $filter ?>" 
                           style="display: inline-block; padding: 0.5rem 0.875rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #475569; text-decoration: none; font-size: 0.875rem;">
                            آخر ⏭️
                        </a>
                    </li>
                <?php endif; ?>
                
            </ul>
        </nav>
        
        <div style="text-align: center; margin-top: 1rem; color: #64748b; font-size: 0.875rem;">
            نمایش <?= ($page - 1) * $perPage + 1 ?> تا <?= min($page * $perPage, $totalReviews) ?> از <?= number_format($totalReviews) ?> نظر
        </div>
    </div>
<?php endif; ?>

<!-- فرم حذف مخفی -->
<form id="deleteForm" method="POST" action="<?= BASE_URL ?>/admin/reviews/delete" style="display: none;">
    <input type="hidden" name="review_id" id="deleteReviewId">
</form>

<script>
function confirmDelete(reviewId, reviewTitle) {
    if (confirm('آیا مطمئن هستید که می‌خواهید نظر "' + reviewTitle + '" را حذف کنید؟\n\nاین عمل قابل بازگشت نیست!')) {
        document.getElementById('deleteReviewId').value = reviewId;
        document.getElementById('deleteForm').submit();
    }
}
</script>

<style>
    nav ul li a:hover {
        background: #e2e8f0 !important;
        border-color: #cbd5e1 !important;
    }
</style>
