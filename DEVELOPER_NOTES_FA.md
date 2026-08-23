# 🔧 یادداشت‌های توسعه‌دهنده - سیستم تم فصلی

## تغییرات انجام شده

### ✅ صفحات Login و Register
تصاویر فصلی به صورت دینامیک بر اساس `ThemeManager` بارگذاری می‌شوند.

**قبل:**
```php
// Hard-coded برای یک فصل خاص
<img src="/assets/images/auth/winter/winter_leaves_01.png">
```

**بعد:**
```php
// دینامیک بر اساس تم فعال
<?php
$themeManager = ThemeManager::getInstance();
$currentTheme = $themeManager->getActiveTheme();
?>
<img src="/assets/images/auth/<?= $currentTheme ?>/<?= $currentTheme ?>_leaves_01.png">
```

---

## 🎯 چگونگی عملکرد

### 1. دریافت تم فعال
```php
$themeManager = ThemeManager::getInstance();
$currentTheme = $themeManager->getActiveTheme();
// خروجی: 'spring', 'summer', 'autumn', یا 'winter'
```

### 2. لود تصاویر فصلی
```php
// پس‌زمینه
<img src="<?= BASE_URL ?>/assets/images/auth/<?= $currentTheme ?>/bg.png">

// درختان
<img src="<?= BASE_URL ?>/assets/images/auth/<?= $currentTheme ?>/trees.png">

// کاراکتر دختر
<img src="<?= BASE_URL ?>/assets/images/auth/<?= $currentTheme ?>/girl.png">

// برگ‌های متحرک
<img src="<?= BASE_URL ?>/assets/images/auth/<?= $currentTheme ?>/<?= $currentTheme ?>_leaves_01.png">
<img src="<?= BASE_URL ?>/assets/images/auth/<?= $currentTheme ?>/<?= $currentTheme ?>_leaves_02.png">
<img src="<?= BASE_URL ?>/assets/images/auth/<?= $currentTheme ?>/<?= $currentTheme ?>_leaves_03.png">
<img src="<?= BASE_URL ?>/assets/images/auth/<?= $currentTheme ?>/<?= $currentTheme ?>_leaves_04.png">
```

---

## 📁 ساختار فایل‌ها

### فایل‌های تغییر یافته:
- ✅ `src/Views/pages/login.php` - لود دینامیک تصاویر فصلی
- ✅ `src/Views/pages/register.php` - لود دینامیک تصاویر فصلی

### فایل‌های موجود (بدون تغییر):
- ✅ `src/Libs/ThemeManager.php` - مدیریت تم‌ها
- ✅ `config/theme.php` - تنظیمات تم
- ✅ `public/assets/css/themes/` - فایل‌های CSS تم‌ها
- ✅ `public/assets/images/auth/{season}/` - تصاویر فصلی

---

## 🧪 تست سیستم

### روش 1: تست با URL Parameter
```bash
http://localhost/Velora/public/login?theme=spring
http://localhost/Velora/public/login?theme=summer
http://localhost/Velora/public/login?theme=autumn
http://localhost/Velora/public/login?theme=winter
```

### روش 2: تغییر فصل سرور
برای تست خودکار، تاریخ سرور را تغییر دهید یا در `ThemeManager::getCurrentSeasonTheme()` ماه را دستی ست کنید.

### روش 3: تست از پنل ادمین
```
1. وارد پنل ادمین شوید
2. تنظیمات تم → Manual Mode
3. هر تم را انتخاب کنید و ذخیره کنید
4. صفحات Login/Register را رفرش کنید
```

---

## 🎨 افزودن تم جدید (در آینده)

اگر بخواهید فصل یا تم جدیدی اضافه کنید:

### مرحله 1: ایجاد پوشه تصاویر
```
public/assets/images/auth/new_season/
├── bg.png
├── trees.png
├── girl.png
├── new_season_leaves_01.png
├── new_season_leaves_02.png
├── new_season_leaves_03.png
└── new_season_leaves_04.png
```

### مرحله 2: ایجاد فایل CSS
```css
/* public/assets/css/themes/new_season.css */
:root {
    --primary: #YOUR_COLOR;
    --secondary: #YOUR_COLOR;
    /* ... */
}
```

### مرحله 3: افزودن به ThemeManager
```php
// src/Libs/ThemeManager.php
private array $availableThemes = [
    'spring', 'summer', 'autumn', 'winter', 'new_season'
];
```

### مرحله 4: افزودن به Routes
```php
// اگر نیاز به روت خاص دارید
'GET:/theme/new_season' => ['ThemeController', 'newSeason']
```

---

## 🔍 نکات مهم

### 1. نام‌گذاری فایل‌ها
همه فایل‌های برگ باید به این فرمت باشند:
```
{season_name}_leaves_01.png
{season_name}_leaves_02.png
{season_name}_leaves_03.png
{season_name}_leaves_04.png
```

مثال:
```
spring_leaves_01.png  ✅
springLeaf01.png      ❌
leaf_spring_01.png    ❌
```

### 2. Cache مرورگر
اگر تغییرات اعمال نمی‌شوند:
```
Ctrl + F5       (Windows/Linux)
Cmd + Shift + R (Mac)
```

یا Cache مرورگر را پاک کنید.

### 3. Session Management
تم در Session ذخیره می‌شود:
```php
$_SESSION['user_selected_theme']  // انتخاب کاربر از Navbar
$_SESSION['product_theme']        // تم محصول (صفحه محصول)
```

برای پاک کردن:
```php
$themeManager->clearUserSelectedTheme();
$themeManager->clearProductTheme();
```

---

## 🐛 عیب‌یابی

### مشکل: تصاویر لود نمی‌شوند
**راه‌حل:**
1. مسیر فایل‌ها را بررسی کنید
2. دسترسی‌های پوشه را چک کنید
3. Console مرورگر را برای ارور 404 بررسی کنید

### مشکل: تم تغییر نمی‌کند
**راه‌حل:**
1. `ThemeManager::getActiveTheme()` را debug کنید
2. Session را پاک کنید: `session_destroy()`
3. `config/theme.php` را بررسی کنید

### مشکل: رنگ‌های CSS درست نیست
**راه‌حل:**
1. فایل CSS تم را بررسی کنید: `public/assets/css/themes/{season}.css`
2. متغیرهای CSS را در Developer Tools بررسی کنید
3. اولویت CSS را چک کنید (ممکن است override شده باشد)

---

## 📚 مستندات مرتبط

- `THEME_GUIDE.md` - راهنمای کامل کاربر
- `HOW_TO_CHANGE_THEME.txt` - راهنمای سریع
- `src/Libs/ThemeManager.php` - کد منبع ThemeManager
- `config/theme.php` - تنظیمات تم

---

## 🚀 عملکرد (Performance)

سیستم تم بر عملکرد تاثیر منفی ندارد:
- ThemeManager یک Singleton است
- تم فقط یک بار در هر درخواست resolve می‌شود
- تصاویر توسط مرورگر cache می‌شوند
- CSS فایل‌ها minified هستند

---

## ✅ Checklist قبل از Production

- [ ] همه تصاویر فصلی بهینه‌سازی شده‌اند (compression)
- [ ] فایل‌های CSS minified شده‌اند
- [ ] تمام تم‌ها تست شده‌اند
- [ ] مسیرها و نام‌گذاری‌ها صحیح هستند
- [ ] Cache strategy برای تصاویر تنظیم شده
- [ ] Error handling برای تصاویر گم‌شده اضافه شده
- [ ] تنظیمات تم در پنل ادمین کار می‌کند

---

## 💡 ایده‌های توسعه آینده

1. **Lazy Loading** برای تصاویر بزرگ
2. **WebP Format** برای بهبود عملکرد
3. **Transition Animation** هنگام تغییر تم
4. **Theme Preview** در پنل کاربری
5. **Custom Themes** برای کاربران Premium
6. **Dark Mode** برای هر فصل
7. **API Endpoint** برای تغییر تم از موبایل اپ

---

🎉 **پیاده‌سازی با موفقیت انجام شد!**

توسعه‌دهنده: Kiro AI Assistant
تاریخ: August 23, 2026
نسخه: 1.0.0
