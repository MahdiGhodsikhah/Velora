# سیستم Theme - مستندات فنی

## معماری کلی

سیستم Theme به سه بخش اصلی تقسیم شده است:

### 1. Base CSS (مستقل از Theme)
فایل‌های داخل `public/assets/css/base/`:
- `variables.css` - متغیرهای عمومی (spacing, radius, transition)
- `reset.css` - Reset استایل‌های مرورگر
- `typography.css` - تایپوگرافی پایه
- `layout.css` - Layout و ساختار کلی
- `animations.css` - انیمیشن‌های مشترک

### 2. Components CSS (ساختار بدون Theme)
فایل‌های داخل `public/assets/css/components/`:
- `alert.css` - کامپوننت Alert
- `button.css` - دکمه‌ها
- `card.css` - کارت‌ها
- `features.css` - نوار ویژگی‌ها
- `banner.css` - بنرها
- `hero.css` - Hero Section
- `section.css` - بخش‌های مختلف صفحه
- `grid.css` - Grid Layout
- `filter.css` - فیلترها و Sidebar
- `blog.css` - کامپوننت‌های Blog
- `about.css` - کامپوننت‌های About

**نکته مهم**: این فایل‌ها فقط ساختار را تعریف می‌کنند و از CSS Variables برای رنگ‌ها استفاده می‌کنند.

### 3. Theme CSS (رنگ و ظاهر)
فایل‌های داخل `public/assets/css/themes/`:
- `autumn.css` - تم پاییزی
- `spring.css` - تم بهاری
- `summer.css` - تم تابستانی
- `winter.css` - تم زمستانی

هر Theme شامل:
- رنگ‌های اصلی (Primary, Secondary, Accent)
- پس‌زمینه‌ها و Gradient
- Shadow و Effect
- متغیرهای اختصاصی Theme

## ThemeManager Class

مسیر: `src/Libs/ThemeManager.php`

### وظایف:
1. تشخیص Theme فعال بر اساس اولویت
2. مدیریت Session و Cache
3. تشخیص فصل جاری برای حالت Automatic
4. مدیریت Theme محصولات
5. مدیریت Asset Path

### اولویت انتخاب Theme:
```
1. Query String (?theme=summer)
2. Product Theme (محصول دارای فصل مشخص)
3. User Theme (تنظیمات کاربر)
4. Admin Theme (تنظیمات مدیر)
5. Automatic (فصل جاری سال)
```

### متدهای کلیدی:
- `getInstance()` - Singleton instance
- `getActiveTheme()` - دریافت Theme فعال
- `setProductTheme()` - تنظیم Theme محصول
- `getThemeCssPath()` - دریافت مسیر فایل CSS
- `getThemeAssetsPath()` - دریافت مسیر Asset

## دیتابیس

### جدول users
```sql
ALTER TABLE users ADD COLUMN preferred_theme VARCHAR(20) DEFAULT 'automatic';
```

مقادیر مجاز:
- `automatic` - خودکار
- `spring` - بهار
- `summer` - تابستان
- `autumn` - پاییز
- `winter` - زمستان

### جدول products
فیلد `season` در جدول products از قبل وجود دارد:
- `spring`, `summer`, `autumn`, `winter`, `null`

## Configuration

فایل: `config/theme.php`

```php
return [
    'mode' => 'automatic',  // automatic | manual
    'admin_selected_theme' => null  // spring | summer | autumn | winter
];
```

## استفاده در Controller

```php
$themeManager = ThemeManager::getInstance();

// تنظیم Theme محصول
if (!empty($product['season'])) {
    $themeManager->setProductTheme($product['season']);
}

// دریافت Theme فعال
$activeTheme = $themeManager->getActiveTheme();

// دریافت Asset Path
$themePath = $themeManager->getThemeAssetsPath('images');
```

## استفاده در View

### در header.php:
```php
<?php 
$themeManager = ThemeManager::getInstance();
$themeCss = $themeManager->getThemeCssPath();
?>
<link rel="stylesheet" href="<?= $themeCss ?>">
```

### دریافت Asset مخصوص Theme:
```php
<?php
$themeManager = ThemeManager::getInstance();
$bannerImage = $themeManager->getThemeAssetsPath('images') . '/banner.jpg';
?>
<img src="<?= $bannerImage ?>" alt="Banner">
```

## مدیریت Theme توسط Admin

URL: `/admin/theme-settings`

Controller: `AdminController->themeSettings()`

ویو: `src/Views/admin/theme-settings.php`

### دسترسی:
فقط کاربران با `role = 'admin'`

### قابلیت‌ها:
- انتخاب حالت Automatic یا Manual
- در حالت Manual: انتخاب یکی از 4 فصل
- ذخیره در `config/theme.php`

## مدیریت Theme توسط کاربر

در صفحه پروفایل (`/profile`):

```html
<select name="preferred_theme">
    <option value="automatic">خودکار</option>
    <option value="spring">بهار</option>
    <option value="summer">تابستان</option>
    <option value="autumn">پاییز</option>
    <option value="winter">زمستان</option>
</select>
```

ذخیره در: `UserController->updateProfile()`

## ساختار Directory

```
public/assets/
├── css/
│   ├── base/           # مستقل از Theme
│   ├── components/     # ساختار بدون رنگ
│   └── themes/         # Theme‌های مختلف
├── images/
│   └── themes/
│       ├── spring/
│       ├── summer/
│       ├── autumn/
│       └── winter/
└── js/
    └── themes/
        ├── spring/
        ├── summer/
        ├── autumn/
        └── winter/
```

## افزودن Theme جدید

### قدم 1: ایجاد فایل CSS
```bash
public/assets/css/themes/halloween.css
```

### قدم 2: تعریف متغیرها
```css
:root {
    --primary: #ff6b00;
    --secondary: #000000;
    /* ... */
}
```

### قدم 3: افزودن به ThemeManager
```php
private array $availableThemes = [
    'spring', 'summer', 'autumn', 'winter', 'halloween'
];
```

### قدم 4: ایجاد Asset Directory
```bash
mkdir public/assets/images/themes/halloween
```

## CSS Variables اصلی

### رنگ‌ها:
- `--primary` - رنگ اصلی
- `--primary-dark` - رنگ اصلی تیره
- `--secondary` - رنگ ثانویه
- `--accent` - رنگ تاکیدی
- `--text-dark` - متن تیره
- `--text-muted` - متن کم‌رنگ

### پس‌زمینه:
- `--body-bg` - پس‌زمینه Body
- `--body-overlay` - Overlay روی Body
- `--card-bg` - پس‌زمینه کارت

### Shadow و Effect:
- `--shadow-primary` - سایه اصلی
- `--shadow-icon` - سایه آیکون
- `--gradient-primary` - Gradient اصلی
- `--gradient-secondary` - Gradient ثانویه

### Border:
- `--border-color` - رنگ Border
- `--border-light` - Border کم‌رنگ

## Performance

### کش کردن:
```php
// در آینده می‌توان Cache اضافه کرد:
$cachedTheme = cache()->get('user_theme_' . $userId);
if (!$cachedTheme) {
    $cachedTheme = $this->resolveTheme();
    cache()->set('user_theme_' . $userId, $cachedTheme, 3600);
}
```

### Minify:
فایل‌های CSS در Production باید Minify شوند.

### CDN:
Asset‌های Theme را می‌توان روی CDN قرار داد.

## تست

### تست Manual:
1. تغییر `preferred_theme` در دیتابیس
2. تست `/admin/theme-settings`
3. تست محصول با `season` مشخص
4. تست Query String: `?theme=winter`

### تست Automatic:
1. تغییر تاریخ سرور
2. بررسی تشخیص فصل صحیح

## نکات امنیتی

1. اعتبارسنجی Theme Name:
```php
if (!in_array($theme, $this->availableThemes)) {
    return $this->defaultTheme;
}
```

2. محدودیت دسترسی Admin:
```php
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: /');
    exit;
}
```

3. Escape Output:
```php
echo Security::e($themeName);
```

## مشکلات رایج و راه‌حل

### Theme لود نمی‌شود:
- بررسی مسیر فایل CSS
- بررسی مجوز فایل
- بررسی Cache مرورگر

### رنگ‌ها اعمال نمی‌شود:
- بررسی ترتیب لود CSS (Theme باید بعد از Components لود شود)
- بررسی !important در CSS

### Session از بین می‌رود:
- بررسی تنظیمات Session در php.ini
- بررسی مسیر session_save_path

## توسعه آینده

### قابلیت‌های پیشنهادی:
1. Theme Customizer داخل پنل کاربری
2. پیش‌نمایش Theme قبل از انتخاب
3. Theme Scheduler (تغییر خودکار بر اساس زمان)
4. Dark Mode برای هر Theme
5. Custom Theme Builder برای Admin
6. Theme Marketplace
