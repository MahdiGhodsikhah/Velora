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

<div class="admin-table">
    <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0;">
        <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: #1e293b;">
            ✏️ ویرایش نظر #<?= $review['id'] ?>
        </h3>
    </div>
    
    <form method="POST" action="<?= BASE_URL ?>/admin/reviews/edit/<?= $review['id'] ?>" style="padding: 2rem;">
        <div class="row g-3">
            <!-- اطلاعات نظر -->
            <div class="col-12" style="padding: 1rem; background: #f8fafc; border-radius: 8px; margin-bottom: 1rem;">
                <h4 style="font-size: 0.875rem; font-weight: 600; color: #475569; margin-bottom: 0.75rem;">
                    ℹ️ اطلاعات نظر
                </h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.75rem; font-size: 0.875rem; color: #64748b;">
                    <div>
                        <strong>نویسنده:</strong>
                        <?php if (!empty($review['username'])): ?>
                            @<?= Security::e($review['username']) ?>
                            <?php if (!empty($review['full_name'])): ?>
                                (<?= Security::e($review['full_name']) ?>)
                            <?php endif; ?>
                        <?php else: ?>
                            <?= Security::e($review['author_name'] ?? 'ناشناس') ?>
                        <?php endif; ?>
                    </div>
                    <div>
                        <strong>محصول:</strong>
                        <?= Security::e($review['product_name'] ?? 'محصول حذف شده') ?>
                    </div>
                    <div>
                        <strong>تاریخ:</strong>
                        <?= jdate('Y/m/d H:i', strtotime($review['created_at'])) ?>
                    </div>
                </div>
            </div>
            
            <!-- امتیاز -->
            <div class="col-md-6">
                <label for="rating" class="form-label fw-bold">امتیاز <span style="color: #dc2626;">*</span></label>
                <select class="form-select" id="rating" name="rating" required 
                        style="padding: 0.75rem; border-radius: 8px; border: 2px solid #e2e8f0;">
                    <option value="5" <?= $review['rating'] == 5 ? 'selected' : '' ?>>⭐⭐⭐⭐⭐ عالی (5)</option>
                    <option value="4" <?= $review['rating'] == 4 ? 'selected' : '' ?>>⭐⭐⭐⭐ خوب (4)</option>
                    <option value="3" <?= $review['rating'] == 3 ? 'selected' : '' ?>>⭐⭐⭐ متوسط (3)</option>
                    <option value="2" <?= $review['rating'] == 2 ? 'selected' : '' ?>>⭐⭐ ضعیف (2)</option>
                    <option value="1" <?= $review['rating'] == 1 ? 'selected' : '' ?>>⭐ خیلی ضعیف (1)</option>
                </select>
            </div>
            
            <!-- وضعیت تأیید -->
            <div class="col-md-6">
                <label class="form-label fw-bold">وضعیت</label>
                <div style="padding: 0.75rem; background: #f8fafc; border-radius: 8px;">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_approved" name="is_approved" 
                               <?= $review['is_approved'] ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold" for="is_approved">
                            ✅ نظر تأیید شده است
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- عنوان -->
            <div class="col-12">
                <label for="title" class="form-label fw-bold">عنوان نظر</label>
                <input type="text" class="form-control" id="title" name="title" 
                       value="<?= Security::e($review['title'] ?? '') ?>" 
                       style="padding: 0.75rem; border-radius: 8px; border: 2px solid #e2e8f0;">
            </div>
            
            <!-- متن نظر -->
            <div class="col-12">
                <label for="body" class="form-label fw-bold">متن نظر <span style="color: #dc2626;">*</span></label>
                <textarea class="form-control" id="body" name="body" rows="6" required
                          style="padding: 0.75rem; border-radius: 8px; border: 2px solid #e2e8f0;"><?= Security::e($review['body'] ?? '') ?></textarea>
            </div>
            
            <!-- دکمه‌ها -->
            <div class="col-12" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; border-radius: 8px; font-weight: 600;">
                    💾 به‌روزرسانی نظر
                </button>
                <a href="<?= BASE_URL ?>/admin/reviews" class="btn btn-secondary" style="padding: 0.75rem 2rem; border-radius: 8px; font-weight: 600; text-decoration: none;">
                    ❌ انصراف
                </a>
            </div>
        </div>
    </form>
</div>
