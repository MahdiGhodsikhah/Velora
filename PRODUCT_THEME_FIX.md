# 🔧 رفع مشکل تم برگ‌های متحرک در صفحه محصولات

## 🐛 مشکل قبلی

وقتی کاربر تم دستی (مثلاً زمستان) انتخاب می‌کرد و به صفحه محصولی که برای **همه فصل‌ها** (`all` یا `all_seasons`) بود می‌رفت، برگ‌های متحرک هنوز با رنگ‌های پاییزی (نارنجی و قهوه‌ای) نمایش داده می‌شدند.

### علت مشکل:
```php
// کد قبلی - فقط از فصل محصول استفاده می‌کرد
$season = $product['season'] ?? 'autumn';
$colors = $leafColors[$season];
```

---

## ✅ راه‌حل پیاده‌سازی شده

### 1️⃣ تغییرات در `ProductController.php`

**قبل:**
```php
if (!empty($product['season'])) {
    $themeManager = ThemeManager::getInstance();
    $themeManager->setProductTheme($product['season']);
}
```

**بعد:**
```php
// فقط برای محصولات با فصل خاص (نه all یا all_seasons)
$themeManager = ThemeManager::getInstance();
$validSeasons = ['spring', 'summer', 'autumn', 'winter'];

if (!empty($product['season']) && in_array($product['season'], $validSeasons)) {
    $themeManager->setProductTheme($product['season']);
} else {
    // برای محصولات all_seasons، تم کاربر حفظ می‌شه
    $themeManager->clearProductTheme();
}
```

**چرا این تغییر؟**
- اگر محصول فصل خاصی دارد (`spring`, `summer`, `autumn`, `winter`) → تم محصول فعال می‌شه
- اگر محصول فصل `all` یا `all_seasons` دارد → تم انتخابی کاربر حفظ می‌شه
- اگر محصول فصلی نداره → تم انتخابی کاربر حفظ می‌شه

---

### 2️⃣ تغییرات در `product-single.php`

**قبل:**
```php
// همیشه از فصل محصول استفاده می‌کرد
$season = $product['season'] ?? 'autumn';
$colors = $leafColors[$season] ?? $leafColors['autumn'];
```

**بعد:**
```php
// استفاده از تم فعال کاربر برای برگ‌ها
$themeManager = ThemeManager::getInstance();
$activeTheme = $themeManager->getActiveTheme(); // تم انتخابی کاربر
$productSeason = $product['season'] ?? 'autumn';

// از تم فعال استفاده می‌کنیم (که ترکیبی از انتخاب کاربر و فصل محصول است)
$displaySeason = $activeTheme;

$leafColors = [
    'autumn' => ['#d97706', '#dc2626', '#fbbf24', '#ea580c', '#f59e0b'],
    'winter' => ['#3b82f6', '#60a5fa', '#93c5fd', '#2563eb', '#dbeafe'],
    'spring' => ['#10b981', '#34d399', '#6ee7b7', '#059669', '#a7f3d0'],
    'summer' => ['#f59e0b', '#fbbf24', '#fde047', '#d97706', '#fef3c7']
];
$colors = $leafColors[$displaySeason] ?? $leafColors['autumn'];
```

**چرا این تغییر؟**
- `getActiveTheme()` همیشه تم نهایی رو برمی‌گردونه (با در نظر گرفتن اولویت‌ها)
- اگر محصول فصل خاص داره، `setProductTheme()` صدا زده شده و `activeTheme` همون میشه
- اگر محصول `all_seasons` باشه، `clearProductTheme()` صدا زده شده و تم کاربر حفظ میشه

---

## 📊 جدول رفتار سیستم

| شرایط | رفتار قبلی | رفتار جدید |
|------|-----------|-----------|
| محصول فصل **autumn** + کاربر تم **winter** انتخاب کرده | برگ‌های نارنجی ❌ | برگ‌های نارنجی ✅ (تم محصول) |
| محصول فصل **all** + کاربر تم **winter** انتخاب کرده | برگ‌های نارنجی ❌ | برگ‌های آبی ✅ (تم کاربر) |
| محصول فصل **spring** + تم خودکار (ماه فعلی: آگوست) | برگ‌های سبز ✅ | برگ‌های سبز ✅ (تم محصول) |
| محصول فصل **all** + تم خودکار (ماه فعلی: آگوست) | برگ‌های نارنجی ❌ | برگ‌های زرد ✅ (تم تابستان) |

---

## 🎯 منطق اولویت‌بندی ThemeManager

```
1. انتخاب کاربر از Navbar ($_SESSION['user_selected_theme'])
   ↓ (اگر نبود)
2. تم محصول ($_SESSION['product_theme']) ← فقط برای فصل‌های خاص
   ↓ (اگر نبود)
3. تم ذخیره شده کاربر در دیتابیس
   ↓ (اگر نبود)
4. تم انتخاب شده توسط ادمین
   ↓ (اگر نبود)
5. تم خودکار بر اساس فصل (ماه فعلی)
```

---

## 🧪 سناریوهای تست

### تست 1: محصول با فصل خاص
```
1. کاربر تم winter را از Navbar انتخاب می‌کند
2. به صفحه محصول autumn می‌رود
3. ✅ برگ‌ها باید نارنجی باشند (تم محصول override می‌کنه)
4. از صفحه محصول خارج می‌شود
5. ✅ تم برمی‌گرده به winter (انتخاب کاربر)
```

### تست 2: محصول all_seasons
```
1. کاربر تم winter را از Navbar انتخاب می‌کند
2. به صفحه محصول all_seasons می‌رود
3. ✅ برگ‌ها باید آبی باشند (تم کاربر حفظ میشه)
4. از صفحه محصول خارج می‌شود
5. ✅ تم winter باقی می‌ماند
```

### تست 3: بدون انتخاب کاربر (خودکار)
```
1. هیچ تمی انتخاب نشده (ماه فعلی: آگوست = تابستان)
2. به صفحه محصول all_seasons می‌رود
3. ✅ برگ‌ها باید زرد (تم تابستان خودکار)
4. به صفحه محصول autumn می‌رود
5. ✅ برگ‌ها باید نارنجی (تم محصول)
```

---

## 📝 نکات مهم

### 1. فصل‌های معتبر
```php
$validSeasons = ['spring', 'summer', 'autumn', 'winter'];
// توجه: 'all' یا 'all_seasons' فصل معتبر نیست برای set کردن تم
```

### 2. رنگ‌های برگ به تفکیک فصل
```php
'autumn' => نارنجی، قرمز، زرد، قهوه‌ای
'winter' => آبی، آبی روشن، سفید
'spring' => سبز، سبز روشن
'summer' => زرد، طلایی، کرم
```

### 3. همگام‌سازی رنگ ستاره‌ها
```php
// ستاره‌ها هم از displaySeason استفاده می‌کنن نه season
$starColor = match($displaySeason) {
    'autumn' => '#fbbf24',
    'winter' => '#93c5fd',
    'spring' => '#a7f3d0',
    'summer' => '#fde047',
    default => '#fbbf24'
};
```

---

## 🚀 مزایای این رفع مشکل

1. ✅ **سازگاری با تم کاربر**: وقتی محصول `all_seasons` هست، برگ‌ها با تم کاربر هماهنگ میشن
2. ✅ **حفظ تجربه کاربری**: محصولات با فصل خاص همچنان تم خودشون رو دارن
3. ✅ **منطقی و قابل پیش‌بینی**: رفتار سیستم برای کاربر واضح و قابل فهمه
4. ✅ **انعطاف‌پذیری**: کاربر کنترل کامل روی تم داره (مگر محصول فصل خاص داشته باشه)
5. ✅ **سازگار با سیستم موجود**: هیچ تغییر breaking change نداره

---

## 🔍 عیب‌یابی

### مشکل: برگ‌ها هنوز رنگ اشتباه دارن
**راه‌حل:**
1. Cache مرورگر را پاک کنید (`Ctrl + F5`)
2. `var_dump($displaySeason)` برای debug اضافه کنید
3. بررسی کنید که `product['season']` چه مقداری دارد

### مشکل: تم بعد از خروج از صفحه محصول برنمی‌گرده
**راه‌حل:**
1. در `ProductController::index()` بررسی کنید که `clearProductTheme()` صدا زده میشه
2. Session را چک کنید: `var_dump($_SESSION['product_theme'])`

---

## 📚 فایل‌های تغییر یافته

- ✅ `src/Controllers/ProductController.php` - لاجیک تنظیم تم محصول
- ✅ `src/Views/pages/product-single.php` - نمایش برگ‌ها و ستاره‌ها
- ✅ این مستندات: `PRODUCT_THEME_FIX.md`

---

**تاریخ رفع مشکل**: 23 آگوست 2026  
**نسخه**: 1.1.0  
**Status**: ✅ Resolved
