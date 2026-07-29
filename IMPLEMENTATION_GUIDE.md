# راهنمای پیاده‌سازی سیستم Theme

## تغییرات انجام شده

### 1. فایل‌های جدید ایجاد شده

#### PHP Classes:
- `src/Libs/ThemeManager.php` - کلاس مدیریت Theme
- `src/Controllers/AdminController.php` - کنترلر مدیریت

#### Views:
- `src/Views/admin/theme-settings.php` - صفحه تنظیمات مدیر

#### Config:
- `config/theme.php` - تنظیمات Theme

#### CSS Base:
- `public/assets/css/base/variables.css`
- `public/assets/css/base/reset.css`
- `public/assets/css/base/typography.css`
- `public/assets/css/base/layout.css`
- `public/assets/css/base/animations.css`

#### CSS Components:
- `public/assets/css/components/alert.css`
- `public/assets/css/components/button.css`
- `public/assets/css/components/card.css`
- `public/assets/css/components/features.css`
- `public/assets/css/components/banner.css`
- `public/assets/css/components/hero.css`
- `public/assets/css/components/section.css`
- `public/assets/css/components/grid.css`
- `public/assets/css/components/filter.css`
- `public/assets/css/components/blog.css`
- `public/assets/css/components/about.css`

#### CSS Themes:
- `public/assets/css/themes/spring.css`
- `public/assets/css/themes/summer.css`
- `public/assets/css/themes/autumn.css`
- `public/assets/css/themes/winter.css`

### 2. فایل‌های تغییر یافته

#### Core Files:
- `public/index.php` - اضافه شدن ThemeManager و AdminController
- `config/routes.php` - افزودن route های admin

#### Controllers:
- `src/Controllers/ProductController.php` - اضافه شدن setProductTheme
- `src/Controllers/UserController.php` - اضافه شدن مدیریت preferred_theme

#### Views:
- `src/Views/layouts/header.php` - تغییر ساختار CSS
- `src/Views/layouts/minimal-header.php` - تغییر ساختار CSS
- `src/Views/pages/profile.php` - اضافه شدن انتخاب Theme

### 3. Database Migration

فایل: `database_migration.sql`

```sql
ALTER TABLE users ADD COLUMN preferred_theme VARCHAR(20) DEFAULT 'automatic' AFTER profile_image;
```

**نکته**: این Query را در phpMyAdmin یا MySQL Client اجرا کنید.

## مراحل نصب و راه‌اندازی

### مرحله 1: Database
```bash
# اجرای Migration
mysql -u root -p autumn_shop < database_migration.sql
```

یا مستقیماً در phpMyAdmin:
```sql
ALTER TABLE users ADD COLUMN preferred_theme VARCHAR(20) DEFAULT 'automatic' AFTER profile_image;
```

### مرحله 2: بررسی فایل‌ها
بررسی کنید که تمام فایل‌های زیر ایجاد شده باشند:
- تمام فایل‌های CSS در `public/assets/css/`
- `src/Libs/ThemeManager.php`
- `config/theme.php`

### مرحله 3: تست Theme System

#### تست 1: Theme پیش‌فرض
1. مرورگر را باز کنید: `http://localhost/Velora/public/`
2. باید Theme پاییزی (Autumn) نمایش داده شود

#### تست 2: تغییر Theme توسط کاربر
1. لاگین کنید
2. به Profile بروید: `/profile`
3. Theme را تغییر دهید (مثلاً Winter)
4. ذخیره کنید
5. بررسی کنید که Theme تغییر کرده باشد

#### تست 3: Theme Admin
1. با حساب Admin لاگین کنید
2. به `/admin/theme-settings` بروید
3. Mode را Manual کنید
4. Theme را انتخاب کنید
5. ذخیره کنید

#### تست 4: Product Theme
1. محصولی با `season = 'winter'` ایجاد کنید
2. صفحه محصول را باز کنید
3. باید Theme زمستانی نمایش داده شود

#### تست 5: Query String
1. به هر صفحه‌ای بروید
2. `?theme=spring` را به URL اضافه کنید
3. باید Theme بهاری نمایش داده شود

## نکات مهم

### 1. مجوزهای فایل
```bash
# اطمینان از Write Permission برای config
chmod 666 config/theme.php
```

### 2. Cache
اگر تغییرات نمایش داده نشد:
- Cache مرورگر را پاک کنید (Ctrl+F5)
- Session را پاک کنید

### 3. فایل main.css قدیمی
فایل `public/assets/css/main.css` قدیمی دیگر استفاده نمی‌شود اما برای Backward Compatibility نگه داشته شده است. می‌توانید آن را به `main.css.backup` تغییر نام دهید.

### 4. استایل‌های سفارشی
اگر استایل‌های سفارشی دارید:
- آن‌ها را به Component مناسب منتقل کنید
- یا یک فایل `custom.css` جدید ایجاد کنید

## Debug

### مشکل: Theme لود نمی‌شود

1. بررسی Console مرورگر:
```javascript
console.log(ThemeManager::getInstance()->getActiveTheme());
```

2. بررسی مسیر فایل:
```php
var_dump($themeManager->getThemeCssPath());
```

3. بررسی Session:
```php
var_dump($_SESSION);
```

### مشکل: رنگ‌ها اعمال نمی‌شود

1. بررسی Developer Tools > Elements
2. جستجو برای `--primary` در Computed Styles
3. بررسی ترتیب لود CSS در Network Tab

### مشکل: Admin Theme کار نمی‌کند

1. بررسی `config/theme.php`:
```php
var_dump(require 'config/theme.php');
```

2. بررسی Write Permission:
```bash
ls -la config/theme.php
```

## افزودن Theme جدید (مثال: Halloween)

### 1. ایجاد فایل CSS
```css
/* public/assets/css/themes/halloween.css */
:root {
    --primary: #ff6b00;
    --primary-dark: #cc5500;
    --secondary: #1a0000;
    --accent: #ff9933;
    
    --body-bg: linear-gradient(135deg, #1a0000 0%, #330000 50%, #1a0000 100%);
    /* ... بقیه متغیرها */
}
```

### 2. آپدیت ThemeManager
```php
// src/Libs/ThemeManager.php
private array $availableThemes = [
    'spring', 'summer', 'autumn', 'winter', 'halloween'
];
```

### 3. آپدیت Season Detection (اختیاری)
```php
// برای نمایش خودکار در ماه اکتبر
private function getCurrentSeasonTheme(): string {
    $month = (int)date('n');
    
    if ($month === 10) { // اکتبر
        return 'halloween';
    }
    // ... بقیه فصل‌ها
}
```

### 4. ایجاد Asset Directory
```bash
mkdir public/assets/images/themes/halloween
```

### 5. آپدیت فرم‌های انتخاب Theme
```php
// در profile.php و theme-settings.php
<option value="halloween">هالووین</option>
```

## بهینه‌سازی Performance

### 1. Minify CSS
```bash
# استفاده از cssnano یا مشابه
npx cssnano public/assets/css/themes/autumn.css public/assets/css/themes/autumn.min.css
```

### 2. Combine Files
برای Production می‌توانید فایل‌ها را Combine کنید:
```bash
cat base/*.css components/*.css > combined.css
```

### 3. Cache Headers
```apache
# در .htaccess
<FilesMatch "\.(css)$">
    Header set Cache-Control "max-age=31536000, public"
</FilesMatch>
```

### 4. استفاده از CDN
فایل‌های Theme را می‌توانید روی CDN قرار دهید و URL را در ThemeManager تغییر دهید.

## سوالات متداول

### Q: آیا می‌توانم Theme‌های قدیمی را پاک کنم؟
A: بله، فقط فایل CSS Theme مربوطه را حذف کنید و از availableThemes حذف کنید.

### Q: چطور می‌توانم Theme پیش‌فرض را تغییر دهم؟
A: در ThemeManager.php خط زیر را تغییر دهید:
```php
private string $defaultTheme = 'autumn'; // به مثلاً 'spring'
```

### Q: آیا می‌توانم برای هر دسته‌بندی Theme مجزا داشته باشم؟
A: بله، می‌توانید منطق مشابه Product Theme را برای Category پیاده‌سازی کنید.

### Q: چطور Dark Mode اضافه کنم؟
A: برای هر Theme یک نسخه Dark ایجاد کنید (مثلاً autumn-dark.css) و یک Toggle در UI اضافه کنید.

## پشتیبانی

در صورت مشکل:
1. Log فایل‌های PHP را بررسی کنید
2. Console مرورگر را چک کنید
3. Network Tab را برای بررسی Load CSS بررسی کنید
4. مستندات `README_THEME_SYSTEM.md` را مطالعه کنید
