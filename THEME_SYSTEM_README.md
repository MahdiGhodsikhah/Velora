# سیستم تم دینامیک چندفصلی

## نصب و راه‌اندازی

### مرحله 1: اجرای اسکریپت نصب

برای راه‌اندازی سیستم تم، از طریق مرورگر به آدرس زیر بروید:

```
http://localhost/Velora/config/setup_theme_system.php
```

این اسکریپت موارد زیر را انجام می‌دهد:
- ایجاد جدول `site_settings`
- درج تنظیمات پیش‌فرض تم
- اطمینان از وجود ستون `season` در جدول `products`
- ایجاد ایندکس‌های مورد نیاز

### مرحله 2: تنظیم دسترسی ادمین

برای دسترسی به پنل مدیریت تم، کاربری با نقش `admin` نیاز دارید.

آدرس پنل مدیریت تم:
```
http://localhost/Velora/public/admin/theme-settings
```

## ویژگی‌های سیستم

### 1. تشخیص خودکار فصل
سیستم به صورت خودکار بر اساس تقویم هجری شمسی، فصل جاری را تشخیص می‌دهد:
- **بهار (فروردین - خرداد - اردیبهشت)**: `spring`
- **تابستان (تیر - مرداد - شهریور)**: `summer`
- **پاییز (مهر - آبان - آذر)**: `autumn`
- **زمستان (دی - بهمن - اسفند)**: `winter`

### 2. مدیریت تم در پنل ادمین
- انتخاب تم پیش‌فرض سایت
- فعال/غیرفعال کردن تشخیص خودکار
- فعال/غیرفعال کردن انتخاب توسط کاربر
- پیش‌نمایش زنده تم‌ها

### 3. تم محصولات
هر محصول می‌تواند فصل اختصاصی خود را داشته باشد:
- `spring` - بهار
- `summer` - تابستان
- `autumn` - پاییز
- `winter` - زمستان
- `all` - همه فصول

هنگام مشاهده صفحه محصول، تم به صورت خودکار با فصل محصول تطبیق داده می‌شود.

### 4. فیلتر فصلی در لیست محصولات
در صفحه لیست محصولات، با فیلتر کردن بر اساس فصل:
```
/products?season=winter
```
تم کل صفحه به صورت خودکار تغییر می‌کند.

### 5. انتخاب تم توسط کاربران
کاربران می‌توانند از منوی انتخاب تم در هدر، تم مورد علاقه خود را انتخاب کنند.
این انتخاب در `localStorage` ذخیره می‌شود.

## ساختار فایل‌ها

```
├── config/
│   └── setup_theme_system.php          # اسکریپت نصب
├── src/
│   ├── Libs/
│   │   └── ThemeManager.php            # کلاس مدیریت تم
│   ├── Controllers/
│   │   └── ThemeController.php         # کنترلر تم
│   └── Views/
│       ├── admin/
│       │   └── theme-settings.php      # پنل مدیریت تم
│       └── partials/
│           └── theme-selector.php      # ویجت انتخاب تم
└── public/
    └── assets/
        ├── css/
        │   └── themes/
        │       ├── theme-autumn.css    # تم پاییزی
        │       ├── theme-winter.css    # تم زمستانی
        │       ├── theme-spring.css    # تم بهاری
        │       └── theme-summer.css    # تم تابستانی
        └── js/
            └── theme-manager.js        # مدیریت تم سمت کلاینت
```

## API

### 1. تنظیم تم (POST)
```javascript
fetch('/Velora/public/api/set-theme', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ theme: 'winter' })
});
```

### 2. تشخیص فصل (GET)
```javascript
fetch('/Velora/public/api/detect-season')
    .then(res => res.json())
    .then(data => console.log(data.season));
```

### 3. دریافت تم فعال (GET)
```javascript
fetch('/Velora/public/api/theme')
    .then(res => res.json())
    .then(data => console.log(data.theme));
```

## استفاده در JavaScript

### تغییر تم
```javascript
// تغییر به تم زمستانی
window.changeTheme('winter');

// یا
window.themeManager.changeTheme('winter');
```

### دریافت تم فعلی
```javascript
const currentTheme = window.themeManager.getTheme();
console.log(currentTheme); // 'autumn'
```

### تشخیص خودکار
```javascript
window.themeManager.autoDetectTheme();
```

### ریست تم
```javascript
window.themeManager.resetTheme();
```

### گوش دادن به تغییر تم
```javascript
document.addEventListener('themeChanged', (e) => {
    console.log('تم جدید:', e.detail.theme);
});
```

## اولویت اعمال تم

سیستم با اولویت زیر تم را اعمال می‌کند:

1. **تم صفحه محصول**: اگر در صفحه محصول هستید، تم بر اساس فصل محصول
2. **تم فیلتر**: اگر فیلتر فصل در لیست محصولات فعال است
3. **تم دستی ادمین**: اگر ادمین تم را به صورت دستی تنظیم کرده (و تشخیص خودکار غیرفعال است)
4. **تم کاربر**: اگر کاربر تم خود را انتخاب کرده
5. **تشخیص خودکار**: بر اساس فصل جاری تقویم شمسی
6. **پیش‌فرض**: تم پاییزی

## افزودن محصول با فصل

در فرم ثبت محصول در پنل ادمین، فیلد `season` را انتخاب کنید:

```php
<select name="season">
    <option value="all">همه فصول</option>
    <option value="spring">بهار</option>
    <option value="summer">تابستان</option>
    <option value="autumn">پاییز</option>
    <option value="winter">زمستان</option>
</select>
```

## سفارشی‌سازی تم‌ها

برای تغییر ظاهر هر تم، فایل CSS مربوطه را ویرایش کنید:

```css
/* مثال: تغییر رنگ اصلی تم پاییزی */
:root.theme-autumn {
  --primary-color: #d97706;
  --primary-hover: #b45309;
  /* ... */
}
```

## عملکرد و بهینه‌سازی

- **پیش‌بارگذاری**: تمام تم‌ها بعد از بارگذاری صفحه، پیش‌بارگذاری می‌شوند
- **کش مرورگر**: تم انتخابی کاربر در localStorage ذخیره می‌شود
- **تغییر بدون رفرش**: تم بدون نیاز به رفرش صفحه تغییر می‌کند
- **سرعت**: استفاده از CSS Variables برای تغییر سریع رنگ‌ها

## مشکلات رایج و راه‌حل

### 1. تم اعمال نمی‌شود
- اطمینان حاصل کنید که `setup_theme_system.php` اجرا شده است
- کش مرورگر را پاک کنید
- بررسی کنید که فایل‌های CSS تم در مسیر صحیح قرار دارند

### 2. ویجت انتخاب تم نمایش داده نمی‌شود
- از فعال بودن "اجازه انتخاب توسط کاربر" در پنل ادمین اطمینان حاصل کنید

### 3. تم محصول اعمال نمی‌شود
- بررسی کنید که فیلد `season` در جدول products وجود دارد
- مقدار season محصول را بررسی کنید

## تست سیستم

### 1. تست تشخیص خودکار
```
http://localhost/Velora/public/api/detect-season
```

### 2. تست تغییر تم
از کنسول مرورگر:
```javascript
window.changeTheme('winter');
window.changeTheme('spring');
window.changeTheme('summer');
window.changeTheme('autumn');
```

### 3. تست فیلتر فصلی
```
http://localhost/Velora/public/products?season=winter
http://localhost/Velora/public/products?season=spring
```

## امنیت

- تمام ورودی‌های کاربر Sanitize می‌شوند
- فقط تم‌های معتبر قابل انتخاب هستند
- CSRF Token در فرم‌های ادمین استفاده می‌شود
- دسترسی به پنل ادمین محدود به کاربران با نقش admin است

## پشتیبانی مرورگرها

- Chrome/Edge: ✅ کامل
- Firefox: ✅ کامل
- Safari: ✅ کامل
- مرورگرهای موبایل: ✅ کامل

## نکات مهم

1. همیشه قبل از اعمال تغییرات در پایگاه داده، backup بگیرید
2. تست کامل در محیط Development قبل از انتقال به Production
3. مطمئن شوید که همه فایل‌های CSS تم با درستی بارگذاری می‌شوند
4. برای عملکرد بهتر، از CDN برای فایل‌های استاتیک استفاده کنید

---

**توسعه دهنده**: سیستم تم دینامیک Velora Shop
**تاریخ**: 1403/05/02
**نسخه**: 1.0.0
