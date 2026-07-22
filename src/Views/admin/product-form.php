<?php
/**
 * فرم افزودن/ویرایش محصول
 */

$isEdit = isset($product);
require_once BASE_PATH . '/src/Views/layouts/header.php';
?>

<style>
.admin-form {
    padding: 30px;
    background: #f5f7fa;
    min-height: 100vh;
}
.form-container {
    max-width: 900px;
    margin: 0 auto;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.form-header {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #e9ecef;
}
.season-preview {
    padding: 15px;
    border-radius: 8px;
    margin-top: 10px;
    border-right: 4px solid #d97706;
    background: #fef3c7;
    transition: all 0.3s ease;
}
</style>

<div class="admin-form">
    <div class="form-container">
        
        <div class="form-header">
            <h2><?= $isEdit ? 'ویرایش محصول' : 'افزودن محصول جدید' ?></h2>
            <p style="color: #666;">فیلدهای مشخص شده با <span style="color: red;">*</span> الزامی هستند</p>
        </div>

        <form method="POST" action="">
            
            <!-- اطلاعات اصلی -->
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label">نام محصول <span style="color: red;">*</span></label>
                    <input type="text" class="form-control" name="name" required
                           value="<?= htmlspecialchars($product['name'] ?? '') ?>"
                           placeholder="مثال: هودی پاییزی مردانه">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">کد محصول (SKU)</label>
                    <input type="text" class="form-control" name="sku"
                           value="<?= htmlspecialchars($product['sku'] ?? '') ?>"
                           placeholder="SKU-001">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">نامک (Slug) <span style="color: red;">*</span></label>
                <input type="text" class="form-control" name="slug" required
                       value="<?= htmlspecialchars($product['slug'] ?? '') ?>"
                       placeholder="nike-autumn-hoodie">
                <small class="form-text text-muted">فقط حروف انگلیسی، اعداد و خط تیره مجاز است</small>
            </div>

            <!-- دسته‌بندی و فصل -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">دسته‌بندی <span style="color: red;">*</span></label>
                    <select class="form-select" name="category_id" required>
                        <option value="">انتخاب کنید...</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" 
                                    <?= (isset($product) && $product['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-calendar-alt ms-1"></i>
                        فصل محصول <span style="color: red;">*</span>
                    </label>
                    <select class="form-select" name="season" id="seasonSelect" required>
                        <option value="all" <?= (!isset($product) || $product['season'] === 'all') ? 'selected' : '' ?>>
                            🌍 همه فصول
                        </option>
                        <option value="spring" <?= (isset($product) && $product['season'] === 'spring') ? 'selected' : '' ?>>
                            🌸 بهار
                        </option>
                        <option value="summer" <?= (isset($product) && $product['season'] === 'summer') ? 'selected' : '' ?>>
                            ☀️ تابستان
                        </option>
                        <option value="autumn" <?= (isset($product) && $product['season'] === 'autumn') ? 'selected' : '' ?>>
                            🍂 پاییز
                        </option>
                        <option value="winter" <?= (isset($product) && $product['season'] === 'winter') ? 'selected' : '' ?>>
                            ❄️ زمستان
                        </option>
                    </select>
                    
                    <div class="season-preview" id="seasonPreview">
                        <i class="fas fa-eye ms-2"></i>
                        <strong>پیش‌نمایش تم:</strong>
                        <span id="seasonPreviewText">محصول در تم پاییزی نمایش داده می‌شود</span>
                    </div>
                </div>
            </div>

            <!-- قیمت -->
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">قیمت (تومان) <span style="color: red;">*</span></label>
                    <input type="number" class="form-control" name="price" required min="0"
                           value="<?= $product['price'] ?? '' ?>"
                           placeholder="1000000">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">قیمت با تخفیف (تومان)</label>
                    <input type="number" class="form-control" name="sale_price" min="0"
                           value="<?= $product['sale_price'] ?? '' ?>"
                           placeholder="800000">
                    <small class="form-text text-muted">در صورت خالی بودن، تخفیف اعمال نمی‌شود</small>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">موجودی <span style="color: red;">*</span></label>
                    <input type="number" class="form-control" name="stock_qty" required min="0"
                           value="<?= $product['stock_qty'] ?? 0 ?>">
                </div>
            </div>

            <!-- توضیحات -->
            <div class="mb-3">
                <label class="form-label">توضیحات کوتاه</label>
                <input type="text" class="form-control" name="short_desc" maxlength="500"
                       value="<?= htmlspecialchars($product['short_desc'] ?? '') ?>"
                       placeholder="توضیح کوتاه و جذاب محصول">
            </div>

            <div class="mb-3">
                <label class="form-label">توضیحات کامل</label>
                <textarea class="form-control" name="description" rows="5"
                          placeholder="توضیحات کامل محصول..."><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
            </div>

            <!-- گزینه‌ها -->
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured"
                           <?= (isset($product) && $product['is_featured']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_featured">
                        <i class="fas fa-star ms-1"></i> محصول ویژه
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                           <?= (!isset($product) || $product['is_active']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_active">
                        <i class="fas fa-check-circle ms-1"></i> فعال
                    </label>
                </div>
            </div>

            <!-- دکمه‌ها -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save ms-1"></i>
                    <?= $isEdit ? 'به‌روزرسانی' : 'ذخیره' ?> محصول
                </button>
                <a href="<?= BASE_URL ?>/admin/products" class="btn btn-secondary">
                    <i class="fas fa-times ms-1"></i>
                    انصراف
                </a>
                <?php if ($isEdit): ?>
                <a href="<?= BASE_URL ?>/products/<?= $product['slug'] ?>" 
                   class="btn btn-info" target="_blank">
                    <i class="fas fa-eye ms-1"></i>
                    مشاهده محصول
                </a>
                <?php endif; ?>
            </div>

        </form>
    </div>
</div>

<script>
// پیش‌نمایش تم بر اساس فصل
document.getElementById('seasonSelect').addEventListener('change', function() {
    const seasonMap = {
        'all': { name: 'پیش‌فرض سایت', icon: '🌍', color: '#6c757d' },
        'spring': { name: 'بهاری', icon: '🌸', color: '#10b981' },
        'summer': { name: 'تابستانی', icon: '☀️', color: '#f59e0b' },
        'autumn': { name: 'پاییزی', icon: '🍂', color: '#d97706' },
        'winter': { name: 'زمستانی', icon: '❄️', color: '#3b82f6' }
    };
    
    const selected = this.value;
    const info = seasonMap[selected];
    const preview = document.getElementById('seasonPreview');
    const previewText = document.getElementById('seasonPreviewText');
    
    if (info) {
        preview.style.borderRightColor = info.color;
        previewText.innerHTML = `${info.icon} محصول در تم <strong>${info.name}</strong> نمایش داده می‌شود`;
    }
    
    // اعمال تم برای پیش‌نمایش (اختیاری)
    if (window.themeManager && selected !== 'all') {
        window.themeManager.applyTheme(selected, false);
    }
});

// اجرای اولیه
document.getElementById('seasonSelect').dispatchEvent(new Event('change'));

// تولید خودکار slug از نام محصول
document.querySelector('input[name="name"]').addEventListener('input', function() {
    const slugInput = document.querySelector('input[name="slug"]');
    if (!slugInput.value || slugInput.dataset.auto !== 'false') {
        const slug = this.value
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        slugInput.value = slug;
    }
});

// جلوگیری از تولید خودکار اگر کاربر دستی تغییر داد
document.querySelector('input[name="slug"]').addEventListener('input', function() {
    this.dataset.auto = 'false';
});
</script>

<?php require_once BASE_PATH . '/src/Views/layouts/footer.php'; ?>
