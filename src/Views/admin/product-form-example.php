<?php
/**
 * مثال فرم ثبت/ویرایش محصول با فیلد فصل
 * این فایل یک نمونه است برای نحوه اضافه کردن فیلد season به فرم محصول
 */
?>

<!-- بخش انتخاب فصل در فرم محصول -->
<div class="form-group mb-3">
    <label for="season" class="form-label">
        <i class="fas fa-calendar-alt ms-1"></i>
        فصل مرتبط با محصول
        <span class="text-danger">*</span>
    </label>
    
    <select class="form-select" id="season" name="season" required>
        <option value="all" <?= (isset($product) && $product['season'] === 'all') ? 'selected' : '' ?>>
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
    
    <div class="form-text">
        <i class="fas fa-info-circle"></i>
        هنگام مشاهده این محصول، تم سایت با فصل انتخاب شده تطبیق داده می‌شود.
    </div>
</div>

<!-- پیش‌نمایش تم بر اساس فصل انتخابی -->
<div class="alert alert-info d-flex align-items-center" id="seasonPreview">
    <i class="fas fa-eye ms-2" style="font-size: 1.5rem;"></i>
    <div>
        <strong>پیش‌نمایش تم:</strong>
        <span id="seasonPreviewText">محصول در تم پاییزی نمایش داده می‌شود</span>
    </div>
</div>

<script>
// پیش‌نمایش زنده تم بر اساس انتخاب فصل
document.getElementById('season')?.addEventListener('change', function() {
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
        preview.style.borderLeft = `4px solid ${info.color}`;
        previewText.innerHTML = `${info.icon} محصول در تم <strong>${info.name}</strong> نمایش داده می‌شود`;
    }
    
    // اعمال تم برای پیش‌نمایش (اختیاری)
    if (window.themeManager && selected !== 'all') {
        window.themeManager.applyTheme(selected, false);
    }
});

// تنظیم اولیه
document.addEventListener('DOMContentLoaded', function() {
    const seasonSelect = document.getElementById('season');
    if (seasonSelect) {
        seasonSelect.dispatchEvent(new Event('change'));
    }
});
</script>

<style>
#seasonPreview {
    transition: all 0.3s ease;
    border-left: 4px solid #d97706;
}

#season option {
    padding: 10px;
    font-size: 1.1rem;
}
</style>

<?php
/**
 * نحوه استفاده در کنترلر (مثال):
 * 
 * // دریافت داده از فرم
 * $season = $_POST['season'] ?? 'all';
 * 
 * // اعتبارسنجی
 * $validSeasons = ['spring', 'summer', 'autumn', 'winter', 'all'];
 * if (!in_array($season, $validSeasons)) {
 *     $season = 'all';
 * }
 * 
 * // ذخیره در پایگاه داده
 * $sql = "INSERT INTO products (..., season, ...) 
 *         VALUES (..., '$season', ...)";
 * 
 * // یا برای update
 * $sql = "UPDATE products SET season = '$season' WHERE id = $id";
 */
?>
