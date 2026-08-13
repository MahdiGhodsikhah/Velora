<?php
$isEdit = isset($product);
$formAction = $isEdit 
    ? BASE_URL . '/admin/products/edit/' . $product['id'] 
    : BASE_URL . '/admin/products/create';
?>

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
            <?= $isEdit ? '✏️ ویرایش محصول' : '➕ افزودن محصول جدید' ?>
        </h3>
    </div>
    
    <form method="POST" action="<?= $formAction ?>" style="padding: 2rem;">
        <div class="row g-3">
            <!-- نام محصول -->
            <div class="col-md-6">
                <label for="name" class="form-label fw-bold">نام محصول <span style="color: #dc2626;">*</span></label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="<?= Security::e($product['name'] ?? '') ?>" 
                       required 
                       style="padding: 0.75rem; border-radius: 8px; border: 2px solid #e2e8f0;">
            </div>
            
            <!-- اسلاگ (URL) -->
            <div class="col-md-6">
                <label for="slug" class="form-label fw-bold">اسلاگ (URL) <span style="color: #dc2626;">*</span></label>
                <input type="text" class="form-control" id="slug" name="slug" 
                       value="<?= Security::e($product['slug'] ?? '') ?>" 
                       required 
                       style="padding: 0.75rem; border-radius: 8px; border: 2px solid #e2e8f0;">
                <small style="color: #64748b;">مثال: nike-autumn-hoodie</small>
            </div>
            
            <!-- دسته‌بندی -->
            <div class="col-md-4">
                <label for="category_id" class="form-label fw-bold">دسته‌بندی <span style="color: #dc2626;">*</span></label>
                <select class="form-select" id="category_id" name="category_id" required 
                        style="padding: 0.75rem; border-radius: 8px; border: 2px solid #e2e8f0;">
                    <option value="">انتخاب کنید...</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" 
                                <?= isset($product) && $product['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                            <?= Security::e($cat['name']) ?> (دسته #<?= $cat['id'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- کد SKU -->
            <div class="col-md-4">
                <label for="sku" class="form-label fw-bold">کد محصول (SKU)</label>
                <input type="text" class="form-control" id="sku" name="sku" 
                       value="<?= Security::e($product['sku'] ?? '') ?>" 
                       style="padding: 0.75rem; border-radius: 8px; border: 2px solid #e2e8f0;">
                <small style="color: #64748b;">مثال: SKU-M-001</small>
            </div>
            
            <!-- فصل -->
            <div class="col-md-4">
                <label for="season" class="form-label fw-bold">فصل محصول</label>
                <select class="form-select" id="season" name="season" 
                        style="padding: 0.75rem; border-radius: 8px; border: 2px solid #e2e8f0;">
                    <option value="all" <?= isset($product) && $product['season'] == 'all' ? 'selected' : '' ?>>🌍 همه فصول</option>
                    <option value="spring" <?= isset($product) && $product['season'] == 'spring' ? 'selected' : '' ?>>🌸 بهار</option>
                    <option value="summer" <?= isset($product) && $product['season'] == 'summer' ? 'selected' : '' ?>>☀️ تابستان</option>
                    <option value="autumn" <?= isset($product) && $product['season'] == 'autumn' ? 'selected' : '' ?>>🍂 پاییز</option>
                    <option value="winter" <?= isset($product) && $product['season'] == 'winter' ? 'selected' : '' ?>>❄️ زمستان</option>
                </select>
            </div>
            
            <!-- قیمت اصلی -->
            <div class="col-md-4">
                <label for="price" class="form-label fw-bold">قیمت اصلی (تومان) <span style="color: #dc2626;">*</span></label>
                <input type="number" class="form-control" id="price" name="price" 
                       value="<?= $product['price'] ?? '' ?>" 
                       required min="0" 
                       style="padding: 0.75rem; border-radius: 8px; border: 2px solid #e2e8f0;">
            </div>
            
            <!-- قیمت فروش (با تخفیف) -->
            <div class="col-md-4">
                <label for="sale_price" class="form-label fw-bold">قیمت فروش (با تخفیف)</label>
                <input type="number" class="form-control" id="sale_price" name="sale_price" 
                       value="<?= $product['sale_price'] ?? '' ?>" 
                       min="0" 
                       style="padding: 0.75rem; border-radius: 8px; border: 2px solid #e2e8f0;">
                <small style="color: #64748b;">اگر تخفیف ندارد خالی بگذارید</small>
            </div>
            
            <!-- درصد تخفیف -->
            <div class="col-md-4">
                <label for="discount_pct" class="form-label fw-bold">درصد تخفیف (%)</label>
                <input type="number" class="form-control" id="discount_pct" name="discount_pct" 
                       value="<?= $product['discount_pct'] ?? 0 ?>" 
                       min="0" max="100" 
                       style="padding: 0.75rem; border-radius: 8px; border: 2px solid #e2e8f0;">
            </div>
            
            <!-- موجودی -->
            <div class="col-md-4">
                <label for="stock_qty" class="form-label fw-bold">موجودی (تعداد)</label>
                <input type="number" class="form-control" id="stock_qty" name="stock_qty" 
                       value="<?= $product['stock_qty'] ?? 0 ?>" 
                       min="0" 
                       style="padding: 0.75rem; border-radius: 8px; border: 2px solid #e2e8f0;">
            </div>
            
            <!-- آدرس تصویر اصلی -->
            <div class="col-md-8">
                <label for="main_image" class="form-label fw-bold">آدرس تصویر اصلی</label>
                <input type="text" class="form-control" id="main_image" name="main_image" 
                       value="<?= Security::e($product['main_image'] ?? '/assets/images/products/no-image.jpg') ?>" 
                       style="padding: 0.75rem; border-radius: 8px; border: 2px solid #e2e8f0;">
                <small style="color: #64748b;">مثال: /assets/images/products/product-1-main.jpg</small>
            </div>
            
            <!-- توضیحات کوتاه -->
            <div class="col-12">
                <label for="short_desc" class="form-label fw-bold">توضیحات کوتاه</label>
                <textarea class="form-control" id="short_desc" name="short_desc" rows="2" 
                          style="padding: 0.75rem; border-radius: 8px; border: 2px solid #e2e8f0;"><?= Security::e($product['short_desc'] ?? '') ?></textarea>
                <small style="color: #64748b;">یک خلاصه کوتاه از محصول (حداکثر 500 کاراکتر)</small>
            </div>
            
            <!-- توضیحات کامل -->
            <div class="col-12">
                <label for="description" class="form-label fw-bold">توضیحات کامل</label>
                <textarea class="form-control" id="description" name="description" rows="5" 
                          style="padding: 0.75rem; border-radius: 8px; border: 2px solid #e2e8f0;"><?= Security::e($product['description'] ?? '') ?></textarea>
            </div>
            
            <!-- چک‌باکس‌ها -->
            <div class="col-12">
                <div style="padding: 1rem; background: #f8fafc; border-radius: 8px;">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                               <?= isset($product) && $product['is_active'] ? 'checked' : (!isset($product) ? 'checked' : '') ?>>
                        <label class="form-check-label fw-bold" for="is_active">
                            ✅ محصول فعال است
                        </label>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                               <?= isset($product) && $product['is_featured'] ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold" for="is_featured">
                            ⭐ محصول ویژه
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- دکمه‌ها -->
            <div class="col-12" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; border-radius: 8px; font-weight: 600;">
                    💾 <?= $isEdit ? 'به‌روزرسانی محصول' : 'ایجاد محصول' ?>
                </button>
                <a href="<?= BASE_URL ?>/admin/products" class="btn btn-secondary" style="padding: 0.75rem 2rem; border-radius: 8px; font-weight: 600; text-decoration: none;">
                    ❌ انصراف
                </a>
            </div>
        </div>
    </form>
</div>

<script>
// تولید خودکار slug از نام محصول
document.getElementById('name').addEventListener('input', function() {
    if (!<?= $isEdit ? 'true' : 'false' ?>) {
        const slug = this.value
            .toLowerCase()
            .replace(/[^\u0600-\u06FFa-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
        document.getElementById('slug').value = slug;
    }
});

// محاسبه خودکار درصد تخفیف
document.getElementById('price').addEventListener('input', calculateDiscount);
document.getElementById('sale_price').addEventListener('input', calculateDiscount);

function calculateDiscount() {
    const price = parseFloat(document.getElementById('price').value) || 0;
    const salePrice = parseFloat(document.getElementById('sale_price').value) || 0;
    
    if (price > 0 && salePrice > 0 && salePrice < price) {
        const discount = Math.round(((price - salePrice) / price) * 100);
        document.getElementById('discount_pct').value = discount;
    }
}
</script>
