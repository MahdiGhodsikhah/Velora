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
    
    <form method="POST" action="<?= $formAction ?>" enctype="multipart/form-data" style="padding: 2rem;">
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
            
            <!-- آدرس تصویر اصلی یا آپلود -->
            <div class="col-12">
                <label class="form-label fw-bold">تصویر اصلی محصول</label>
                
                <!-- نمایش تصویر فعلی -->
                <?php if ($isEdit && !empty($product['main_image'])): ?>
                    <div style="margin-bottom: 1rem;">
                        <img src="<?= BASE_URL . $product['main_image'] ?>" 
                             alt="تصویر فعلی" 
                             style="max-width: 200px; max-height: 200px; object-fit: cover; border-radius: 8px; border: 2px solid #e2e8f0;">
                        <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #64748b;">
                            تصویر فعلی: <?= Security::e($product['main_image']) ?>
                        </p>
                    </div>
                <?php endif; ?>
                
                <!-- آپلود تصویر جدید -->
                <div style="margin-bottom: 1rem;">
                    <label for="main_image_upload" class="form-label">آپلود تصویر جدید:</label>
                    <input type="file" 
                           class="form-control" 
                           id="main_image_upload" 
                           name="main_image_upload" 
                           accept="image/jpeg,image/jpg,image/png,image/webp,image/gif"
                           style="padding: 0.75rem; border-radius: 8px; border: 2px solid #e2e8f0;">
                    <small style="color: #64748b;">فرمت‌های مجاز: JPG, PNG, WEBP, GIF | حداکثر حجم: 5MB</small>
                </div>
                
                <!-- یا وارد کردن آدرس دستی -->
                <div>
                    <label for="main_image" class="form-label">یا آدرس تصویر را وارد کنید:</label>
                    <input type="text" 
                           class="form-control" 
                           id="main_image" 
                           name="main_image" 
                           value="<?= Security::e($product['main_image'] ?? '/assets/images/products/no-image.jpg') ?>" 
                           style="padding: 0.75rem; border-radius: 8px; border: 2px solid #e2e8f0;">
                    <small style="color: #64748b;">مثال: /assets/images/products/product-1-main.jpg</small>
                </div>
            </div>
            
            <!-- گالری تصاویر (چند عکس) -->
            <div class="col-12">
                <label class="form-label fw-bold">گالری تصاویر (چندین عکس)</label>
                
                <!-- نمایش تصاویر گالری فعلی -->
                <?php if ($isEdit && !empty($product['gallery'])): 
                    $galleryImages = json_decode($product['gallery'], true);
                    if (is_array($galleryImages) && !empty($galleryImages)):
                ?>
                    <div style="margin-bottom: 1rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        <?php foreach ($galleryImages as $img): ?>
                            <div style="position: relative;">
                                <img src="<?= BASE_URL . $img ?>" 
                                     alt="تصویر گالری" 
                                     style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 2px solid #e2e8f0;">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <p style="font-size: 0.875rem; color: #64748b;">
                        تعداد تصاویر گالری فعلی: <?= count($galleryImages) ?>
                    </p>
                <?php endif; endif; ?>
                
                <!-- آپلود تصاویر جدید برای گالری -->
                <div style="margin-bottom: 1rem;">
                    <label for="gallery_images" class="form-label">آپلود تصاویر جدید برای گالری:</label>
                    <input type="file" 
                           class="form-control" 
                           id="gallery_images" 
                           name="gallery_images[]" 
                           accept="image/jpeg,image/jpg,image/png,image/webp,image/gif"
                           multiple
                           style="padding: 0.75rem; border-radius: 8px; border: 2px solid #e2e8f0;">
                    <small style="color: #64748b;">
                        می‌توانید یک یا چند تصویر انتخاب کنید | فرمت‌های مجاز: JPG, PNG, WEBP, GIF | حداکثر حجم هر فایل: 5MB
                    </small>
                </div>
                
                <div class="alert alert-info" style="font-size: 0.875rem; padding: 0.75rem; border-radius: 8px;">
                    💡 <strong>توجه:</strong> اگر تصاویر جدید آپلود کنید، تصاویر قبلی جایگزین خواهند شد.
                    <?= $isEdit ? ' اگر می‌خواهید تصاویر قبلی را حفظ کنید، هیچ فایلی انتخاب نکنید.' : '' ?>
                </div>
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

// پیش‌نمایش تصویر اصلی
document.getElementById('main_image_upload').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // بررسی نوع فایل
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            alert('فرمت فایل مجاز نیست. فقط JPG, PNG, WEBP, GIF');
            this.value = '';
            return;
        }
        
        // بررسی حجم فایل (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('حجم فایل نباید بیشتر از 5MB باشد');
            this.value = '';
            return;
        }
        
        // نمایش پیش‌نمایش
        const reader = new FileReader();
        reader.onload = function(event) {
            // حذف پیش‌نمایش قبلی اگر وجود داشته باشد
            const oldPreview = document.getElementById('main_image_preview');
            if (oldPreview) {
                oldPreview.remove();
            }
            
            // ایجاد پیش‌نمایش جدید
            const preview = document.createElement('div');
            preview.id = 'main_image_preview';
            preview.style.marginTop = '1rem';
            preview.innerHTML = `
                <p style="font-size: 0.875rem; color: #10b981; font-weight: 600;">✓ پیش‌نمایش تصویر جدید:</p>
                <img src="${event.target.result}" 
                     alt="پیش‌نمایش" 
                     style="max-width: 200px; max-height: 200px; object-fit: cover; border-radius: 8px; border: 2px solid #10b981;">
            `;
            document.getElementById('main_image_upload').parentElement.appendChild(preview);
        };
        reader.readAsDataURL(file);
    }
});

// پیش‌نمایش تصاویر گالری
document.getElementById('gallery_images').addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    
    if (files.length === 0) return;
    
    // بررسی تعداد فایل‌ها
    if (files.length > 10) {
        alert('حداکثر 10 تصویر می‌توانید آپلود کنید');
        this.value = '';
        return;
    }
    
    // حذف پیش‌نمایش قبلی
    const oldPreview = document.getElementById('gallery_preview');
    if (oldPreview) {
        oldPreview.remove();
    }
    
    // ایجاد کانتینر پیش‌نمایش
    const previewContainer = document.createElement('div');
    previewContainer.id = 'gallery_preview';
    previewContainer.style.marginTop = '1rem';
    previewContainer.innerHTML = `
        <p style="font-size: 0.875rem; color: #10b981; font-weight: 600;">✓ پیش‌نمایش تصاویر جدید گالری (${files.length} تصویر):</p>
        <div id="gallery_images_container" style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.5rem;"></div>
    `;
    
    this.parentElement.appendChild(previewContainer);
    
    const container = document.getElementById('gallery_images_container');
    
    // پردازش هر فایل
    files.forEach((file, index) => {
        // بررسی نوع فایل
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            alert(`فایل ${index + 1} (${file.name}) فرمت مجاز ندارد`);
            return;
        }
        
        // بررسی حجم فایل
        if (file.size > 5 * 1024 * 1024) {
            alert(`فایل ${index + 1} (${file.name}) بیشتر از 5MB است`);
            return;
        }
        
        // نمایش پیش‌نمایش
        const reader = new FileReader();
        reader.onload = function(event) {
            const imgDiv = document.createElement('div');
            imgDiv.style.position = 'relative';
            imgDiv.innerHTML = `
                <img src="${event.target.result}" 
                     alt="تصویر ${index + 1}" 
                     style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 2px solid #10b981;">
                <span style="position: absolute; top: -8px; right: -8px; background: #10b981; color: white; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 600;">${index + 1}</span>
            `;
            container.appendChild(imgDiv);
        };
        reader.readAsDataURL(file);
    });
});
</script>
