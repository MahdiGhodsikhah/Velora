# فروشگاه پاییزی شگفت‌انگیز

## راه‌اندازی

### ۱. پیکربندی پایگاه داده
فایل `config/database.php` را باز کنید و اطلاعات اتصال را تنظیم کنید:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'autumn_shop');
```

### ۲. ساخت پایگاه داده
در مرورگر باز کنید:
```
http://localhost/Project/project/config/setup_database.php
```
**پس از اجرا، این فایل را حذف کنید.**

### ۳. تنظیم BASE_URL
در `public/index.php` مقدار BASE_URL را بر اساس مسیر نصب تغییر دهید:
```php
define('BASE_URL', '/Project/project/public');
```

### ۴. دسترسی به سایت
```
http://localhost/Project/project/public/
```

## ساختار پروژه
```
project/
├── config/
│   ├── database.php      # اتصال به DB (mysqli خالص)
│   ├── routes.php        # روتینگ
│   └── setup_database.php # اسکریپت ساخت DB
├── public/
│   ├── .htaccess
│   ├── index.php         # نقطه ورود (Front Controller)
│   └── assets/
│       ├── css/          # استایل‌ها
│       ├── js/           # اسکریپت‌ها
│       ├── icons/        # آیکون‌های SVG
│       └── images/       # تصاویر
└── src/
    ├── Controllers/       # کنترلرها
    ├── Models/            # مدل‌ها (mysqli خالص)
    ├── Libs/              # کتابخانه‌ها (Security, jdf)
    └── Views/
        ├── layouts/       # header, navbar, footer
        ├── pages/         # صفحات اصلی
        └── partials/      # کامپوننت‌های قابل استفاده مجدد
```

## امنیت پیاده‌سازی شده
- CSRF Token در تمام فرم‌ها
- Rate Limiting برای ورود/ثبت‌نام
- Brute Force Protection (قفل حساب بعد از ۵ تلاش)
- SQL Injection Prevention (mysqli_real_escape_string)
- XSS Prevention (htmlspecialchars)
- Session Fixation Prevention (session_regenerate_id)
- Security Headers (X-Frame-Options, CSP, ...)
- Directory Traversal Prevention
- Password Hashing با bcrypt

## اعتبار ادمین پیش‌فرض
- نام کاربری: `admin`
- رمز عبور: `Admin@1234`
