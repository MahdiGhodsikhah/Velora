# 📘 مستندات جامع پروژه Velora Shop

**پروژه:** فروشگاه آنلاین پوشاک با سیستم تم فصلی خودکار  
**تاریخ تهیه مستندات:** 28 اوت 2026  
**نسخه:** 1.0.0  
**زبان برنامه‌نویسی:** PHP 8.3 (Pure PHP - بدون Framework)  
**پایگاه داده:** MySQL 8.4 با MySQLi  
**معماری:** MVC سنتی (Model-View-Controller)

---

## 📑 فهرست مطالب

1. [معرفی پروژه و نمای کلی](#1-معرفی-پروژه-و-نمای-کلی)
2. [معماری و ساختار کلی پروژه](#2-معماری-و-ساختار-کلی-پروژه)
3. [سیستم روتینگ و مسیریابی](#3-سیستم-روتینگ-و-مسیریابی)
4. [پیکربندی‌های پروژه](#4-پیکربندی‌های-پروژه)
5. [ساختار Frontend](#5-ساختار-frontend)
6. [ساختار Backend](#6-ساختار-backend)
7. [ویژگی‌های عملکردی](#7-ویژگی‌های-عملکردی)
8. [امنیت پروژه](#8-امنیت-پروژه)
9. [مدیریت خطاها](#9-مدیریت-خطاها)
10. [پنل مدیریت](#10-پنل-مدیریت)
11. [ساختار دیتابیس](#11-ساختار-دیتابیس)
12. [تحلیل نقاط قوت و ضعف](#12-تحلیل-نقاط-قوت-و-ضعف)
13. [راهنمای توسعه](#13-راهنمای-توسعه)

---

## 1. معرفی پروژه و نمای کلی

### 1.1 هدف پروژه

**Velora Shop** یک فروشگاه آنلاین پوشاک است که با ویژگی منحصر‌به‌فرد **تم‌بندی فصلی خودکار** طراحی شده است. این پروژه با هدف ارائه تجربه کاربری متمایز و بصری جذاب، رابط کاربری را بر اساس فصل جاری (بهار، تابستان، پاییز، زمستان) تغییر می‌دهد.

### 1.2 ویژگی‌های اصلی

#### ویژگی‌های کاربری
- ✅ سیستم احراز هویت کامل (ثبت‌نام، ورود، مدیریت پروفایل)
- ✅ مدیریت سبد خرید Session-based با AJAX
- ✅ سیستم Wishlist (علاقه‌مندی‌ها)
- ✅ فرآیند Checkout کامل با مدیریت سفارشات
- ✅ سیستم نظرات و امتیازدهی محصولات
- ✅ جستجوی پیشرفته با pagination
- ✅ داشبورد کاربری شخصی
- ✅ مدیریت آدرس و کد پستی

#### ویژگی‌های مدیریتی
- ✅ پنل Admin کامل با کنترل دسترسی
- ✅ مدیریت محصولات (CRUD)
- ✅ مدیریت سفارشات و تغییر وضعیت
- ✅ مدیریت کاربران
- ✅ تأیید/رد نظرات کاربران
- ✅ تنظیمات تم سراسری

#### ویژگی‌های فنی منحصر‌به‌فرد
- 🎨 **Theme Manager**: سیستم تم‌بندی فصلی با 6 اولویت انتخاب (انتخاب کاربر، تم محصول، تم دیتابیس، تم ادمین، فصل خودکار)
- 🔒 **Security System**: کلاس امنیتی جامع با پوشش CSRF, XSS, SQL Injection, Rate Limiting, Brute Force Protection
- 🖼️ **Image Uploader**: سیستم آپلود امن با بررسی MIME واقعی
- 📱 **Responsive Design**: طراحی کاملاً واکنش‌گرا با Bootstrap 5 RTL

### 1.3 تکنولوژی‌ها و کتابخانه‌های استفاده شده

#### Backend
| تکنولوژی | نسخه | علت استفاده |
|---------|------|-------------|
| PHP | 8.3.28 | زبان اصلی - پشتیبانی از Type Hints و امکانات مدرن |
| MySQL | 8.4.7 | پایگاه داده - پشتیبانی از UTF8MB4 برای فارسی و emoji |
| MySQLi | Built-in | لایه دیتابیس - Connection pooling و Singleton pattern |

**علت عدم استفاده از PDO:** پروژه از mysqli برای اتصال سنتی استفاده می‌کند. این انتخاب برای سادگی در پروژه‌های کوچک منطقی است، اما برای مقیاس‌پذیری بهتر است از PDO با Prepared Statements استفاده شود.

#### Frontend
| کتابخانه/فریم‌ورک | نسخه | علت استفاده |
|-------------------|------|-------------|
| Bootstrap | 5.3 RTL | Grid system و components آماده با پشتیبانی راست‌چین |
| jQuery | 3.7.1 | AJAX calls و DOM manipulation ساده |
| Font Awesome | 6.5.1 | آیکون‌های وکتور با تنوع بالا |
| Slick Carousel | 1.8.1 | اسلایدرهای تصاویر محصولات |
| Vazirmatn Font | Google Fonts | فونت فارسی استاندارد و خوانا |

**توضیح انتخاب jQuery:** با وجود اینکه Vanilla JavaScript مدرن‌تر است، jQuery برای پروژه‌های با نیاز به سازگاری مرورگر و سرعت توسعه مناسب است.

#### Tools و Utilities
- **jdf.php**: کتابخانه تاریخ جلالی برای نمایش تاریخ‌های فارسی
- **ImageUploader**: کلاس سفارشی برای مدیریت آپلود امن تصاویر
- **Security Helper**: کلاس جامع برای امنیت (300+ خطوط کد)
- **ThemeManager Singleton**: مدیریت تم با الگوی Singleton

---

## 2. معماری و ساختار کلی پروژه

### 2.1 معماری MVC

پروژه از معماری **MVC سنتی** استفاده می‌کند:

```
Velora/
├── config/              # پیکربندی‌ها
│   ├── database.php     # اتصال mysqli
│   ├── routes.php       # تعریف مسیرها
│   └── theme.php        # تنظیمات تم
│
├── public/              # نقطه ورود وب (Document Root)
│   ├── index.php        # Front Controller
│   ├── .htaccess        # تنظیمات Apache
│   └── assets/          # فایل‌های استاتیک
│       ├── css/
│       ├── js/
│       └── images/
│
├── src/                 # کد اصلی برنامه
│   ├── Controllers/     # کنترلرها (10 کنترلر)
│   ├── Models/          # مدل‌ها (4 مدل)
│   ├── Views/           # قالب‌های HTML
│   └── Libs/            # کلاس‌های کمکی
│
├── logs/                # لاگ خطاها
│   └── error.log
│
├── velora_shop.sql      # فایل دیتابیس
└── .htaccess            # Redirect به public/
```

### 2.2 توضیح ساختار پوشه‌ها

#### A) `config/` - پیکربندی‌های پروژه

**نقش:** تمرکز تنظیمات در یک مکان برای مدیریت آسان

**فایل‌های موجود:**

1. **`database.php`** (138 خط)
   - **مکان:** `config/database.php`
   - **هدف:** ایجاد اتصال mysqli و ارائه helper functions
   - **محتوا:**
     - تعریف ثوابت دیتابیس (HOST, USER, PASS, NAME)
     - تابع `db_connect()`: Singleton connection
     - تابع `db_escape()`: جلوگیری از SQL Injection
     - تابع `db_query()`: اجرای کوئری با error logging
     - تابع `db_fetch_one()`: دریافت یک رکورد
     - تابع `db_fetch_all()`: دریافت تمام رکوردها
     - تابع `db_insert()`: INSERT و برگشت ID
   - **چرا mysqli؟** برای پروژه‌های کوچک و متوسط، mysqli با pattern matching سنتی سریع‌تر از PDO است
   - **نقطه ضعف:** عدم استفاده از Prepared Statements واقعی

2. **`routes.php`** (171 خط)
   - **مکان:** `config/routes.php`
   - **هدف:** تعریف نگاشت URL به Controller+Action
   - **الگو:** `'METHOD:PATH' => ['ControllerName', 'methodName']`
   - **ویژگی کلیدی:** پشتیبانی از dynamic parameters مانند `{id}` و `{slug}`
   - **مثال:**
     ```php
     'GET:/products/{slug}' => ['ProductController', 'show']
     'POST:/cart/add' => ['CartController', 'add']
     'GET:/admin/products/edit/{id}' => ['AdminController', 'editProduct']
     ```
   - **تابع اصلی:** `dispatch_route($method, $uri)` که با regex matching مسیر مناسب را پیدا می‌کند

3. **`theme.php`** (کوچک، ~10 خط)
   - **مکان:** `config/theme.php`
   - **هدف:** تنظیمات تم سراسری توسط ادمین
   - **ساختار:**
     ```php
     return [
         'mode' => 'automatic',  // automatic یا manual
         'admin_selected_theme' => 'autumn'  // spring, summer, autumn, winter
     ];
     ```

#### B) `public/` - Document Root

**نقش:** تنها پوشه قابل دسترس از وب برای امنیت بالاتر

**محتوا:**

1. **`index.php`** (144 خط) - **Front Controller Pattern**
   - **مکان:** `public/index.php`
   - **نقش:** نقطه ورود واحد برای تمام درخواست‌ها
   - **جریان اجرا:**
     ```
     1. تنظیمات Session امنیتی (httponly, samesite, strict)
     2. تعریف مسیرهای پایه (BASE_PATH, PUBLIC_PATH, BASE_URL)
     3. تنظیمات نمایش خطا (APP_DEBUG)
     4. بارگذاری تمام کلاس‌ها (require_once)
     5. دریافت REQUEST_METHOD و URI
     6. Dispatch به controller مناسب
     7. اجرای action با parameters
     8. Fallback به 404 در صورت عدم یافتن route
     ```
   - **امنیت Session:**
     ```php
     ini_set('session.cookie_httponly', 1);  // جلوگیری از XSS
     ini_set('session.cookie_samesite', 'Strict');  // CSRF protection
     ini_set('session.use_strict_mode', 1);
     ini_set('session.gc_maxlifetime', 3600);  // 1 ساعت
     ```

2. **`.htaccess`** (امنیتی و routing)
   - **مکان:** `public/.htaccess`
   - **هدف:** تنظیمات Apache برای امنیت و routing
   - **تنظیمات کلیدی:**
     - غیرفعال کردن Directory Listing: `Options -Indexes`
     - Custom Error Pages: `ErrorDocument 404 /index.php`
     - Rewrite تمام URL‌ها به index.php
     - محدود کردن دسترسی به فایل‌های .env، .log، .sql، .sh
     - Security Headers: X-Content-Type-Options, X-Frame-Options
   - **چرا .htaccess؟** برای پروژه‌های shared hosting، .htaccess تنها راه تنظیمات Apache است

3. **`assets/`** - فایل‌های استاتیک
   - **ساختار:**
     ```
     assets/
     ├── css/
     │   ├── base/          # لایه پایه (variables, reset, typography)
     │   ├── components/    # کامپوننت‌ها (navbar, footer, card)
     │   └── themes/        # تم‌های فصلی (spring.css, summer.css, ...)
     ├── js/
     │   ├── main.js        # اسکریپت اصلی
     │   ├── cart.js        # مدیریت سبد خرید
     │   └── ...
     └── images/
         ├── products/      # تصاویر محصولات
         ├── auth/          # تصاویر صفحات ورود (فصلی)
         └── banners/       # بنرهای تبلیغاتی
     ```

#### C) `src/` - کد اصلی برنامه

**نقش:** جداسازی منطق تجاری از فایل‌های عمومی

**زیرپوشه‌ها:**

1. **`Controllers/`** (10 کنترلر، ~3500 خط مجموع)
   - **HomeController.php**: صفحه اصلی با محصولات featured
   - **ProductController.php**: لیست محصولات، جزئیات، جستجو، نظرات
   - **AuthController.php**: ورود، ثبت‌نام، خروج با امنیت کامل
   - **CartController.php**: CRUD سبد خرید
   - **CheckoutController.php**: تکمیل خرید و ثبت سفارش
   - **UserController.php**: پروفایل، داشبورد، سفارشات
   - **WishlistController.php**: مدیریت علاقه‌مندی‌ها
   - **AdminController.php**: پنل مدیریت (300+ خط)
   - **AboutController.php**: صفحه درباره ما
   - **ErrorController.php**: مدیریت 404 و 500

2. **`Models/`** (4 مدل، ~1800 خط مجموع)
   - **UserModel.php**: کاربران، احراز هویت، پروفایل (150+ خط)
   - **ProductModel.php**: محصولات، جستجو، فیلتر (250+ خط)
   - **OrderModel.php**: سفارشات، آیتم‌ها، کاهش موجودی (200+ خط)
   - **ReviewModel.php**: نظرات، امتیازدهی، تأیید (~100 خط)

3. **`Views/`** - قالب‌های HTML
   - **layouts/**: header, footer, navbar (مشترک)
   - **pages/**: صفحات اصلی (home, products, cart, checkout)
   - **partials/**: اجزای کوچک قابل استفاده مجدد
   - **admin/**: قالب‌های پنل مدیریت

4. **`Libs/`** - کتابخانه‌های کمکی
   - **Security.php**: کلاس جامع امنیتی (320+ خط) ⭐
   - **ThemeManager.php**: مدیریت تم فصلی (211 خط) ⭐
   - **ImageUploader.php**: آپلود امن تصاویر
   - **jdf.php**: تبدیل تاریخ میلادی به شمسی

---

## 3. سیستم روتینگ و مسیریابی

### 3.1 الگوی Routing

**فایل:** `config/routes.php`

**ساختار:**

```php
$routes = [
    'GET:/'  => ['HomeController', 'index'],
    'GET:/products' => ['ProductController', 'index'],
    'GET:/products/{slug}' => ['ProductController', 'show'],
    'POST:/cart/add' => ['CartController', 'add'],
    // ... 40+ route دیگر
];
```

### 3.2 تابع `dispatch_route()`

**مکان:** `config/routes.php` (خطوط 150-171)

**ورودی:**
- `$method`: GET, POST
- `$uri`: مسیر درخواست (مثل `/products/autumn-jacket`)

**خروجی:**
```php
[
    'controller' => 'ProductController',
    'action' => 'show',
    'params' => ['autumn-jacket']
]
```

**الگوریتم:**

1. **پاکسازی URI:**
   ```php
   $uri = strtok($uri, '?');  // حذف query string
   $uri = '/' . trim($uri, '/');  // نرمال‌سازی
   ```

2. **تطبیق مستقیم:**
   ```php
   $key = strtoupper($method) . ':' . $uri;
   if (isset($routes[$key])) {
       return ['controller' => ..., 'action' => ..., 'params' => []];
   }
   ```

3. **تطبیق با Regex (برای پارامترهای دینامیک):**
   ```php
   $pattern = preg_replace('/\{[a-z_]+\}/', '([^/]+)', $route_path);
   if (preg_match($pattern, $uri, $matches)) {
       array_shift($matches);  // حذف match کامل
       return ['controller' => ..., 'params' => $matches];
   }
   ```

4. **Fallback به 404:**
   ```php
   return ['controller' => 'ErrorController', 'action' => 'notFound'];
   ```

### 3.3 مثال‌های واقعی Routing

#### مثال 1: صفحه محصول با Slug
**URL:** `/products/autumn-jacket`

**Route Definition:**
```php
'GET:/products/{slug}' => ['ProductController', 'show']
```

**Dispatch Result:**
```php
[
    'controller' => 'ProductController',
    'action' => 'show',
    'params' => ['autumn-jacket']
]
```

**اجرا در index.php:**
```php
$controller = new ProductController();
$controller->show('autumn-jacket');
```

#### مثال 2: ویرایش محصول در Admin
**URL:** `/admin/products/edit/15`

**Route:**
```php
'GET:/admin/products/edit/{id}' => ['AdminController', 'editProduct']
```

**Result:**
```php
['controller' => 'AdminController', 'action' => 'editProduct', 'params' => ['15']]
```

### 3.4 چرا این سیستم Routing؟

**مزایا:**
✅ ساده و قابل فهم برای پروژه‌های کوچک  
✅ بدون وابستگی به framework  
✅ پشتیبانی از پارامترهای دینامیک  
✅ جداسازی کامل route‌ها از منطق کنترلر

**معایب:**
❌ عدم پشتیبانی از Middleware  
❌ عدم پشتیبانی از Route Groups  
❌ نیاز به تعریف دستی همه route‌ها  
❌ عدم پشتیبانی از Route Caching

**بهبود پیشنهادی:**  
استفاده از کتابخانه‌هایی مانند `nikic/fast-route` یا `AltoRouter` برای routing پیشرفته‌تر.

---

## 4. پیکربندی‌های پروژه

### 4.1 پیکربندی دیتابیس

**فایل:** `config/database.php`

#### تنظیمات اتصال

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'velora_shop');
define('DB_CHARSET', 'utf8mb4');
```

**علت انتخاب UTF8MB4:**  
پشتیبانی کامل از کاراکترهای فارسی، عربی، emoji و سایر کاراکترهای 4-byte Unicode.

#### تابع `db_connect()`

**مکان:** `config/database.php` (خطوط 14-26)

**الگوی طراحی:** Singleton Pattern

```php
function db_connect() {
    static $conn = null;
    if ($conn === null) {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$conn) {
            error_log('DB Connection failed: ' . mysqli_connect_error());
            die(json_encode(['error' => 'خطا در اتصال به پایگاه داده']));
        }
        mysqli_set_charset($conn, DB_CHARSET);
    }
    return $conn;
}
```

**توضیح:**
- **`static $conn`**: Connection فقط یک بار ایجاد می‌شود
- **Error Handling**: لاگ خطا + نمایش پیام کاربرپسند
- **Character Set**: تنظیم دوباره برای اطمینان از UTF8MB4

#### توابع کمکی دیتابیس

**1. `db_escape($value)` - جلوگیری از SQL Injection**

```php
function db_escape($value) {
    $conn = db_connect();
    return mysqli_real_escape_string($conn, trim((string)$value));
}
```

**کاربرد:**
```php
$username = db_escape($_POST['username']);
$sql = "SELECT * FROM users WHERE username = '$username'";
```

**چرا نه PDO Prepared Statements؟**  
در mysqli سنتی، این روش ساده‌تر است اما کمتر امن. برای پروژه‌های بزرگ، PDO با `bindParam` بهتر است.

**2. `db_query($sql)` - اجرای کوئری**

```php
function db_query($sql) {
    $conn = db_connect();
    $result = mysqli_query($conn, $sql);
    if ($result === false) {
        error_log('DB Query Error: ' . mysqli_error($conn) . ' | SQL: ' . $sql);
        return false;
    }
    return $result;
}
```

**ویژگی:**  
✅ Error logging خودکار  
✅ برگشت `false` در صورت خطا

**3. `db_fetch_one($sql)` - دریافت یک رکورد**

```php
function db_fetch_one($sql) {
    $result = db_query($sql);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}
```

**مثال استفاده:**
```php
$user = db_fetch_one("SELECT * FROM users WHERE id = 5 LIMIT 1");
echo $user['username'];
```

**4. `db_fetch_all($sql)` - دریافت تمام رکوردها**

```php
function db_fetch_all($sql) {
    $result = db_query($sql);
    $rows = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        mysqli_free_result($result);
    }
    return $rows;
}
```

**5. `db_insert($sql)` - INSERT و برگشت ID**

```php
function db_insert($sql) {
    $conn = db_connect();
    if (db_query($sql)) {
        return mysqli_insert_id($conn);
    }
    return false;
}
```

**مثال:**
```php
$sql = "INSERT INTO products (name, price) VALUES ('Jacket', 250000)";
$productId = db_insert($sql);
echo "Product ID: $productId";
```

### 4.2 پیکربندی Theme

**فایل:** `config/theme.php`

**ساختار:**
```php
return [
    'mode' => 'automatic',  // or 'manual'
    'admin_selected_theme' => 'autumn'  // spring, summer, autumn, winter, null
];
```

**توضیح:**
- **`mode`**: 
  - `automatic`: تم بر اساس فصل جاری (ماه میلادی)
  - `manual`: تم توسط ادمین انتخاب شده
- **`admin_selected_theme`**: 
  - تم پیش‌فرض سراسری که ادمین تنظیم می‌کند
  - اولویت 4 در الگوریتم Theme Manager دارد

---

## 5. ساختار Frontend

### 5.1 معماری CSS

پروژه از معماری **Modular CSS** با سه لایه استفاده می‌کند:

```
assets/css/
├── base/           # لایه پایه
│   ├── variables.css      # CSS Custom Properties
│   ├── reset.css          # Normalize و reset
│   ├── typography.css     # فونت و تایپوگرافی
│   ├── layout.css         # Grid و Container
│   ├── animations.css     # Transitions و Keyframes
│   └── responsive.css     # Media Queries
│
├── components/     # کامپوننت‌های UI
│   ├── navbar.css
│   ├── footer.css
│   ├── card.css
│   ├── button.css
│   ├── hero.css
│   ├── products.css
│   └── ...
│
└── themes/         # تم‌های فصلی
    ├── spring.css
    ├── summer.css
    ├── autumn.css
    └── winter.css
```

### 5.2 لایه Base - Variables

**فایل:** `assets/css/base/variables.css`

**مکان:** بالای تمام استایل‌ها load می‌شود

**محتوا:**
```css
:root {
    /* رنگ‌های اصلی */
    --primary-color: #8B4513;
    --secondary-color: #D2691E;
    --accent-color: #CD853F;
    
    /* فونت‌ها */
    --font-primary: 'Vazirmatn', sans-serif;
    --font-size-base: 16px;
    --font-size-h1: 2.5rem;
    
    /* Spacing */
    --spacing-xs: 0.5rem;
    --spacing-sm: 1rem;
    --spacing-md: 1.5rem;
    --spacing-lg: 2rem;
    
    /* Shadows */
    --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
    --shadow-md: 0 4px 8px rgba(0,0,0,0.15);
    
    /* Transitions */
    --transition-fast: 0.2s ease;
    --transition-normal: 0.3s ease;
}
```

**علت استفاده از CSS Variables:**
✅ تغییر آسان رنگ‌ها در تم‌های مختلف  
✅ کاهش تکرار کد  
✅ پشتیبانی از Dark Mode در آینده  
✅ سازگاری با JavaScript برای تغییر دینامیک

### 5.3 لایه Components

#### A) Navbar Component

**فایل:** `assets/css/components/navbar.css`

**ویژگی‌ها:**
- Sticky header با background blur
- Dropdown menu با hover + click
- Responsive hamburger menu
- Search bar با animation
- Theme switcher dropdown
- Cart badge با نمایش تعداد

**کد نمونه از navbar.css:**
```css
.navbar {
    position: fixed;
    top: 0;
    width: 100%;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    box-shadow: var(--shadow-md);
    z-index: 1000;
    transition: var(--transition-normal);
}

.navbar.scrolled {
    background: rgba(255, 255, 255, 0.98);
    box-shadow: var(--shadow-lg);
}
```

#### B) Card Component

**فایل:** `assets/css/components/card.css`

**استفاده:** کارت محصولات

**ویژگی‌ها:**
- Hover effect با scale و shadow
- Badge برای تخفیف
- Rating stars
- Wishlist heart icon
- Quick view button

### 5.4 لایه Themes - سیستم تم‌بندی فصلی

**مکان:** `assets/css/themes/`

#### تم پاییزی (autumn.css)

```css
:root {
    /* رنگ‌های پاییزی */
    --theme-primary: #8B4513;      /* قهوه‌ای */
    --theme-secondary: #D2691E;    /* نارنجی */
    --theme-accent: #CD853F;       /* عسلی */
    --theme-bg: #FFF8DC;           /* کرم */
    
    /* Gradients */
    --gradient-hero: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);
    
    /* Leaves animation */
    --leaf-color-1: #8B4513;
    --leaf-color-2: #D2691E;
}
```

**تصاویر فصلی:**
```css
.auth-visual {
    background-image: url('/assets/images/auth/autumn/bg.png');
}

.falling-leaf {
    color: var(--leaf-color-1);
}
```

#### چگونگی تعویض تم

**در Header:**
```php
<?php 
$themeManager = ThemeManager::getInstance();
$themeCss = $themeManager->getThemeCssPath();
?>
<link rel="stylesheet" href="<?= $themeCss ?>">
```

**ThemeManager به صورت خودکار فایل CSS مناسب را برمی‌گرداند:**
- `/assets/css/themes/spring.css`
- `/assets/css/themes/summer.css`
- `/assets/css/themes/autumn.css`
- `/assets/css/themes/winter.css`

### 5.5 JavaScript Architecture

#### A) main.js - اسکریپت اصلی

**فایل:** `public/assets/js/main.js` (200+ خط)

**مسئولیت‌ها:**

1. **Navbar Functionality**
   ```javascript
   // Sticky navbar on scroll
   $(window).on('scroll', function () {
       if ($(this).scrollTop() > 50) {
           $navbar.addClass('scrolled');
       } else {
           $navbar.removeClass('scrolled');
       }
   });
   ```

2. **Dropdown Menus**
   ```javascript
   // Hover برای دسکتاپ، Click برای موبایل
   let isDesktop = $(window).width() > 1024;
   
   $('.has-dropdown').on('mouseenter', function() {
       if (isDesktop && !$(this).hasClass('clicked')) {
           $(this).addClass('keep-open');
       }
   });
   ```

3. **Theme Switcher**
   ```javascript
   $('.dropdown-theme a').on('click', function(e) {
       e.preventDefault();
       const themeUrl = $(this).attr('href');
       
       // نمایش loading
       $btn.find('i').first().attr('class', 'fas fa-spinner fa-spin');
       
       // انتقال به URL جدید (reload صفحه)
       window.location.href = themeUrl;
   });
   ```

4. **Wishlist Toggle**
   ```javascript
   $('.wishlist-toggle').on('click', function(e) {
       e.preventDefault();
       const productId = $(this).data('product-id');
       
       $.ajax({
           url: BASE_URL + '/wishlist/toggle',
           method: 'POST',
           data: { product_id: productId },
           success: function(response) {
               if (response.success) {
                   // به‌روزرسانی UI
                   updateWishlistIcon(productId, response.is_wishlisted);
               }
           }
       });
   });
   ```

5. **Add to Cart**
   ```javascript
   $('.add-to-cart-btn').on('click', function() {
       const productId = $(this).data('product-id');
       const quantity = $('#quantity').val() || 1;
       
       $.ajax({
           url: BASE_URL + '/cart/add',
           method: 'POST',
           data: { product_id: productId, quantity: quantity },
           success: function(response) {
               if (response.success) {
                   showNotification('محصول به سبد خرید اضافه شد', 'success');
                   updateCartBadge(response.cart_count);
               }
           }
       });
   });
   ```

#### B) cart.js - مدیریت سبد خرید

**مسئولیت‌ها:**

1. **افزایش/کاهش تعداد**
   ```javascript
   $('.qty-btn').on('click', function() {
       const $input = $(this).siblings('.qty-input');
       const currentQty = parseInt($input.val());
       const action = $(this).data('action');
       
       let newQty = action === 'increase' ? currentQty + 1 : currentQty - 1;
       if (newQty < 1) newQty = 1;
       
       $input.val(newQty);
       updateCartItem(productId, newQty);
   });
   ```

2. **حذف از سبد**
3. **اعمال کد تخفیف**
4. **محاسبه مجدد مبلغ**

---

*(ادامه در بخش بعد...)*

## 6. ساختار Backend

### 6.1 کنترلرها (Controllers)

پروژه شامل **10 کنترلر** است که هر کدام مسئول بخش خاصی از عملکرد هستند.

#### A) HomeController

**فایل:** `src/Controllers/HomeController.php`

**مسئولیت:** نمایش صفحه اصلی با محصولات ویژه و بنرها

**متد اصلی:** `index()`

**جریان اجرا:**
```php
public function index(): void {
    // 1. تنظیم Security Headers
    Security::set_security_headers();
    
    // 2. پاک کردن تم محصول (برگشت به تم انتخابی کاربر)
    $themeManager = ThemeManager::getInstance();
    $themeManager->clearProductTheme();
    
    // 3. دریافت محصولات featured بر اساس فصل جاری
    $productModel = new ProductModel();
    $featuredProducts = $productModel->getFeaturedBySeason($currentSeason, 8);
    
    // 4. دریافت بنرهای hero
    $heroBanners = $productModel->getBanners('hero');
    
    // 5. رندر view
    require BASE_PATH . '/src/Views/layouts/header.php';
    require BASE_PATH . '/src/Views/layouts/navbar.php';
    require BASE_PATH . '/src/Views/pages/home.php';
    require BASE_PATH . '/src/Views/layouts/footer.php';
}
```

**نقاط قوت:**
✅ تفکیک واضح منطق و نمایش  
✅ استفاده از ThemeManager برای مدیریت تم  
✅ Security headers در ابتدای هر صفحه

#### B) ProductController

**فایل:** `src/Controllers/ProductController.php`

**مسئولیت‌ها:**
1. نمایش لیست محصولات با pagination و فیلتر
2. نمایش جزئیات محصول
3. جستجو در محصولات
4. افزودن نظرات کاربران

**متدهای کلیدی:**

**1. `index()` - لیست محصولات**

```php
public function index(): void {
    // پارامترهای فیلتر
    $page = max(1, (int)($_GET['page'] ?? 1));
    $perPage = 12;
    $category = $_GET['category'] ?? null;
    $season = $_GET['season'] ?? null;
    $search = $_GET['q'] ?? null;
    
    // فیلتر بر اساس نوع جستجو
    if ($search) {
        $products = $this->productModel->searchWithPagination($search, $perPage, ($page-1)*$perPage);
        $total = $this->productModel->countSearch($search);
    } elseif ($season) {
        $products = $this->productModel->getBySeason($season, $perPage, ($page-1)*$perPage);
        $total = $this->productModel->countBySeason($season);
    } else {
        $products = $this->productModel->getAll($perPage, ($page-1)*$perPage);
        $total = $this->productModel->count();
    }
    
    // محاسبه pagination
    $totalPages = ceil($total / $perPage);
    
    // رندر view
    require BASE_PATH . '/src/Views/pages/products.php';
}
```

**علت این پیاده‌سازی:**  
✅ پشتیبانی همزمان از فیلترهای مختلف (category, season, search)  
✅ Pagination برای عملکرد بهتر  
✅ استفاده از الگوی Query Object در آینده بهتر است

**2. `show($slug)` - جزئیات محصول**

**مکان:** `ProductController::show()` (خطوط 60-110)

```php
public function show(string $slug): void {
    // 1. دریافت محصول
    $product = $this->productModel->getBySlug($slug);
    
    if (!$product) {
        $this->notFound();
        return;
    }
    
    // 2. افزایش تعداد بازدید
    $this->productModel->incrementViews($product['id']);
    
    // 3. تغییر تم به تم محصول
    $themeManager = ThemeManager::getInstance();
    if (!empty($product['season'])) {
        $themeManager->setProductTheme($product['season']);
    }
    
    // 4. دریافت گالری تصاویر
    $galleryImages = json_decode($product['gallery'] ?? '[]', true);
    
    // 5. دریافت نظرات
    $reviews = $this->productModel->getReviews($product['id']);
    
    // 6. محاسبه میانگین امتیاز
    $avgRating = $product['rating_avg'] ?? 0;
    
    // 7. بررسی وضعیت wishlist
    $isWishlisted = false;
    if (isset($_SESSION['user_id'])) {
        // بررسی از دیتابیس
    }
    
    // 8. رندر view
    require BASE_PATH . '/src/Views/pages/product-single.php';
}
```

**ویژگی منحصربفرد:**  
🎨 **تغییر خودکار تم به تم محصول** - اگر محصول فصل خاصی دارد، تم صفحه به همان فصل تغییر می‌کند.

**مثال:**  
- محصول: "ژاکت پاییزی" با `season='autumn'`
- تم صفحه: خودکار به autumn.css تغییر می‌کند
- بازگشت به لیست: تم به انتخاب کاربر برمی‌گردد

**3. `addReview()` - افزودن نظر**

```php
public function addReview(): void {
    header('Content-Type: application/json');
    
    // 1. بررسی ورود کاربر
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'لطفا ابتدا وارد شوید']);
        exit;
    }
    
    // 2. اعتبارسنجی
    $productId = (int)($_POST['product_id'] ?? 0);
    $rating = max(1, min(5, (int)($_POST['rating'] ?? 0)));
    $title = Security::sanitize_input($_POST['title'] ?? '');
    $body = Security::sanitize_input($_POST['body'] ?? '');
    
    if (empty($title) || empty($body) || $rating < 1) {
        echo json_encode(['success' => false, 'message' => 'لطفا تمام فیلدها را پر کنید']);
        exit;
    }
    
    // 3. افزودن به دیتابیس
    $result = $this->productModel->addReview($productId, $_SESSION['user_id'], $rating, $title, $body);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'نظر شما با موفقیت ثبت شد و پس از تأیید نمایش داده می‌شود']);
    } else {
        echo json_encode(['success' => false, 'message' => 'خطا در ثبت نظر']);
    }
    exit;
}
```

**امنیت:**
✅ بررسی ورود کاربر  
✅ Sanitization ورودی‌ها  
✅ محدود کردن rating به 1-5  
✅ نظرات پس از تأیید ادمین نمایش داده می‌شوند

#### C) AuthController - احراز هویت

**فایل:** `src/Controllers/AuthController.php`

**مسئولیت‌ها:**
- ورود کاربران (Login)
- ثبت‌نام (Register)
- خروج (Logout)
- مدیریت امنیت (Rate Limiting, Account Locking)

**متد کلیدی: `login()`**

**مکان:** `AuthController::login()` (خطوط 40-120)

```php
public function login(): void {
    header('Content-Type: application/json; charset=utf-8');
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'درخواست نامعتبر']);
        exit;
    }
    
    // 1. اعتبارسنجی CSRF
    if (!Security::verify_csrf($_POST['csrf_token'] ?? '')) {
        echo json_encode(['success' => false, 'message' => 'توکن امنیتی نامعتبر است']);
        exit;
    }
    
    // 2. دریافت ورودی
    $identifier = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($identifier) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'لطفا تمام فیلدها را پر کنید']);
        exit;
    }
    
    // 3. Rate Limiting (5 تلاش در 15 دقیقه)
    $ip = $_SERVER['REMOTE_ADDR'];
    if (!Security::rate_limit($ip, 5, 900)) {
        echo json_encode(['success' => false, 'message' => 'تعداد تلاش‌های شما بیش از حد مجاز است']);
        exit;
    }
    
    // 4. یافتن کاربر (username, email یا phone)
    $user = $this->userModel->findByUsername($identifier);
    if (!$user) {
        $user = $this->userModel->findByEmail($identifier);
    }
    if (!$user) {
        $user = $this->userModel->findByPhone($identifier);
    }
    
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'نام کاربری یا رمز عبور اشتباه است']);
        exit;
    }
    
    // 5. بررسی قفل بودن اکانت
    if ($this->userModel->isLocked($user)) {
        echo json_encode(['success' => false, 'message' => 'حساب شما به دلیل تلاش‌های ناموفق قفل شده است']);
        exit;
    }
    
    // 6. تأیید رمز عبور
    if (!Security::verify_password($password, $user['password_hash'])) {
        // افزایش تعداد تلاش‌های ناموفق
        $this->userModel->incrementLoginAttempts($user['id']);
        
        // قفل اکانت بعد از 5 تلاش
        if ((int)$user['login_attempts'] >= 4) {
            $this->userModel->lockAccount($user['id'], 15);
        }
        
        echo json_encode(['success' => false, 'message' => 'نام کاربری یا رمز عبور اشتباه است']);
        exit;
    }
    
    // 7. ورود موفق - Session Regeneration برای امنیت
    session_regenerate_id(true);
    
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_role'] = $user['role'];
    
    // 8. ریست تلاش‌های ورود
    $this->userModel->resetLoginAttempts($user['id']);
    Security::rate_limit_reset($ip);
    
    // 9. Redirect
    $redirectUrl = $_SESSION['redirect_after_login'] ?? BASE_URL . '/dashboard';
    unset($_SESSION['redirect_after_login']);
    
    echo json_encode([
        'success' => true,
        'message' => 'ورود موفقیت‌آمیز بود',
        'redirect' => $redirectUrl
    ]);
    exit;
}
```

**تحلیل امنیت:**

1. **CSRF Protection:**  
   ✅ توکن CSRF در فرم  
   ✅ اعتبارسنجی با `Security::verify_csrf()`

2. **Rate Limiting:**  
   ✅ محدودیت 5 تلاش در 15 دقیقه بر اساس IP  
   ✅ ذخیره در Session (در پروژه‌های بزرگ: Redis یا Database)

3. **Account Locking:**  
   ✅ قفل خودکار اکانت بعد از 5 تلاش ناموفق  
   ✅ مدت قفل: 15 دقیقه (قابل تنظیم)

4. **Password Security:**  
   ✅ هش با bcrypt (cost 12)  
   ✅ تأیید با `password_verify()`  
   ✅ عدم نمایش دلیل دقیق عدم ورود (username-enumeration protection)

5. **Session Security:**  
   ✅ Session Regeneration بعد از ورود  
   ✅ httponly و samesite cookies  
   ✅ زمان انقضای session: 1 ساعت

**مثال فرآیند:**

```
کاربر → فرم ورود
    ↓
بررسی CSRF Token ✓
    ↓
بررسی Rate Limit (IP) ✓
    ↓
جستجوی کاربر در DB ✓
    ↓
بررسی قفل اکانت ✓
    ↓
تأیید رمز عبور ✓
    ↓
Session Regeneration
    ↓
ثبت اطلاعات در Session
    ↓
Redirect به Dashboard
```

**متد `register()` - ثبت‌نام**

**ویژگی‌های کلیدی:**

1. **اعتبارسنجی چندلایه:**
   ```php
   // Username: 3-50 کاراکتر، فقط حروف، اعداد، _, -, .
   if (!Security::validate_username($username)) {
       // خطا
   }
   
   // Email: فرمت معتبر و حداکثر 150 کاراکتر
   if (!Security::validate_email($email)) {
       // خطا
   }
   
   // Phone: 09xxxxxxxxx
   if (!Security::validate_phone($phone)) {
       // خطا
   }
   
   // Password: حداقل 8 کاراکتر، یک عدد، یک حرف
   if (!Security::validate_password($password)) {
       // خطا
   }
   ```

2. **بررسی تکراری نبودن:**
   ```php
   if ($this->userModel->findByUsername($username)) {
       echo json_encode(['success' => false, 'message' => 'نام کاربری قبلا ثبت شده']);
       exit;
   }
   
   // همین طور برای email و phone
   ```

3. **تولید username یکتا:**
   ```php
   // اگر username تکراری بود، عدد اضافه کن
   $originalUsername = $username;
   $counter = 1;
   while ($this->userModel->findByUsername($username)) {
       $username = $originalUsername . $counter;
       $counter++;
   }
   ```

4. **ورود خودکار بعد از ثبت‌نام:**
   ```php
   $userId = $this->userModel->create($username, $email, $phone, $password);
   
   if ($userId) {
       // ورود خودکار
       $_SESSION['logged_in'] = true;
       $_SESSION['user_id'] = $userId;
       // ...
   }
   ```

#### D) CartController - سبد خرید

**فایل:** `src/Controllers/CartController.php` (300 خط)

**مسئولیت‌ها:**
- نمایش سبد خرید
- افزودن محصول به سبد
- حذف محصول از سبد
- به‌روزرسانی تعداد
- محاسبه مبلغ (subtotal, tax, shipping)

**ذخیره‌سازی:** Session-based (نه دیتابیس)

**چرا Session؟**
✅ سرعت بالاتر (بدون query به DB)  
✅ سبد موقت تا قبل از checkout  
✅ عدم نیاز به cleanup سبدهای قدیمی  
❌ از دست رفتن سبد با پاک شدن session  
❌ عدم امکان همگام‌سازی بین دستگاه‌ها

**ساختار Session:**
```php
$_SESSION['cart'] = [
    15 => 2,   // product_id => quantity
    23 => 1,
    47 => 3
];
```

**متد `add()` - افزودن به سبد**

```php
public function add(): void {
    header('Content-Type: application/json');
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit;
    }
    
    $productId = (int)($_POST['product_id'] ?? 0);
    $quantity  = (int)($_POST['quantity'] ?? 1);
    
    if ($productId <= 0 || $quantity <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        exit;
    }
    
    // بررسی موجودی محصول
    $product = $this->productModel->getById($productId);
    
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'محصول یافت نشد']);
        exit;
    }
    
    if ((int)$product['stock_qty'] < $quantity) {
        echo json_encode(['success' => false, 'message' => 'موجودی کافی نیست']);
        exit;
    }
    
    // افزودن به سبد
    $this->addToCart($productId, $quantity);
    
    // شمارش تعداد کل آیتم‌ها
    $cartCount = $this->getCartCount();
    
    echo json_encode([
        'success' => true,
        'message' => 'محصول به سبد خرید اضافه شد',
        'cart_count' => $cartCount
    ]);
    exit;
}

private function addToCart(int $productId, int $quantity): void {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $quantity;  // افزودن به تعداد موجود
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}
```

**AJAX Call در Frontend:**
```javascript
$('.add-to-cart-btn').on('click', function() {
    const productId = $(this).data('product-id');
    const quantity = $('#quantity').val() || 1;
    
    $.ajax({
        url: BASE_URL + '/cart/add',
        method: 'POST',
        dataType: 'json',
        data: { product_id: productId, quantity: quantity },
        success: function(response) {
            if (response.success) {
                showNotification(response.message, 'success');
                $('.cart-count').text(response.cart_count);
            } else {
                showNotification(response.message, 'error');
            }
        }
    });
});
```

**متد `index()` - نمایش سبد**

```php
public function index(): void {
    // 1. پاک کردن تم محصول
    $themeManager = ThemeManager::getInstance();
    $themeManager->clearProductTheme();
    
    // 2. دریافت آیتم‌های سبد
    $cartItems = $this->getCartItems();
    
    // 3. محاسبه مجموع
    $subtotal = 0;
    foreach ($cartItems as $item) {
        $price = $item['sale_price'] ?: $item['price'];
        $subtotal += $price * $item['quantity'];
    }
    
    // 4. محاسبه هزینه ارسال
    $shipping = $subtotal > 1000000 ? 0 : 30000;  // رایگان برای بالای 1 میلیون
    
    // 5. محاسبه مالیات (9%)
    $tax = $subtotal * 0.09;
    
    // 6. مجموع نهایی
    $total = $subtotal + $shipping + $tax;
    
    // 7. رندر view
    require BASE_PATH . '/src/Views/pages/cart.php';
}

private function getCartItems(): array {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    $cart = $_SESSION['cart'];
    if (empty($cart)) {
        return [];
    }
    
    $productModel = new ProductModel();
    $items = [];
    
    foreach ($cart as $productId => $quantity) {
        $product = $productModel->getById((int)$productId);
        if ($product) {
            $product['quantity'] = $quantity;
            $items[] = $product;
        }
    }
    
    return $items;
}
```

**توضیح محاسبات:**

| مبلغ | محاسبه | مثال |
|------|--------|------|
| Subtotal | مجموع قیمت × تعداد | 850,000 تومان |
| Shipping | 30,000 تومان (رایگان برای >1M) | 30,000 تومان |
| Tax (9%) | Subtotal × 0.09 | 76,500 تومان |
| **Total** | Subtotal + Shipping + Tax | **956,500 تومان** |

#### E) CheckoutController - تکمیل خرید

**فایل:** `src/Controllers/CheckoutController.php` (273 خط)

**مسئولیت‌ها:**
- نمایش صفحه checkout
- دریافت اطلاعات ارسال (آدرس، کد پستی)
- ثبت سفارش در دیتابیس
- کاهش موجودی محصولات
- پاک کردن سبد خرید

**متد `process()` - ثبت سفارش**

**فرآیند گام‌به‌گام:**

```php
public function process(): void {
    header('Content-Type: application/json; charset=utf-8');
    
    // === مرحله 1: بررسی‌های اولیه ===
    
    // بررسی ورود کاربر
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'لطفا ابتدا وارد شوید']);
        exit;
    }
    
    // بررسی سبد خرید
    if (empty($_SESSION['cart'])) {
        echo json_encode(['success' => false, 'message' => 'سبد خرید شما خالی است']);
        exit;
    }
    
    // === مرحله 2: اعتبارسنجی ورودی‌ها ===
    
    $address = trim($_POST['address'] ?? '');
    $postalCode = trim($_POST['postal_code'] ?? '');
    $paymentMethod = $_POST['payment_method'] ?? 'online';
    $notes = trim($_POST['notes'] ?? '');
    
    if (empty($address)) {
        echo json_encode(['success' => false, 'message' => 'لطفا آدرس را وارد کنید']);
        exit;
    }
    
    if (empty($postalCode) || !preg_match('/^\d{10}$/', $postalCode)) {
        echo json_encode(['success' => false, 'message' => 'کد پستی باید 10 رقم باشد']);
        exit;
    }
    
    // === مرحله 3: آماده‌سازی آیتم‌های سفارش ===
    
    try {
        $cartItems = $this->getCartItems();
        
        if (empty($cartItems)) {
            echo json_encode(['success' => false, 'message' => 'سبد خرید خالی است']);
            exit;
        }
        
        $subtotal = 0;
        $orderItems = [];
        
        foreach ($cartItems as $item) {
            $price = $item['sale_price'] ?: $item['price'];
            $quantity = $item['quantity'];
            
            // بررسی موجودی
            if ((int)$item['stock_qty'] < $quantity) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'موجودی محصول ' . $item['name'] . ' کافی نیست'
                ]);
                exit;
            }
            
            $itemSubtotal = $price * $quantity;
            $subtotal += $itemSubtotal;
            
            $orderItems[] = [
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'unit_price' => $price,
                'quantity' => $quantity,
                'subtotal' => $itemSubtotal
            ];
        }
        
        // === مرحله 4: محاسبه مبالغ ===
        
        $shipping = $subtotal > 500000 ? 0 : 30000;
        $tax = $subtotal * 0.09;
        $total = $subtotal + $shipping + $tax;
        
        // === مرحله 5: ایجاد سفارش ===
        
        $orderModel = new OrderModel();
        $orderNumber = $orderModel->generateOrderNumber();
        
        $orderData = [
            'user_id' => (int)$_SESSION['user_id'],
            'order_number' => $orderNumber,
            'status' => 'pending',
            'total_amount' => (int)$total,
            'discount_amt' => 0,
            'shipping_cost' => (int)$shipping,
            'shipping_address' => $address,
            'postal_code' => $postalCode,
            'payment_method' => $paymentMethod,
            'payment_status' => 'unpaid',
            'notes' => $notes
        ];
        
        $orderId = $orderModel->createOrder($orderData);
        
        if (!$orderId) {
            echo json_encode(['success' => false, 'message' => 'خطا در ایجاد سفارش']);
            exit;
        }
        
        // === مرحله 6: افزودن آیتم‌های سفارش ===
        
        if (!$orderModel->addOrderItems($orderId, $orderItems)) {
            echo json_encode(['success' => false, 'message' => 'خطا در افزودن آیتم‌های سفارش']);
            exit;
        }
        
        // === مرحله 7: کاهش موجودی محصولات ===
        
        foreach ($orderItems as $item) {
            $orderModel->decreaseProductStock($item['product_id'], $item['quantity']);
        }
        
        // === مرحله 8: به‌روزرسانی آدرس کاربر ===
        
        $userModel = new UserModel();
        $userModel->updateUserAddress((int)$_SESSION['user_id'], $address, $postalCode);
        
        // === مرحله 9: پاک کردن سبد خرید ===
        
        $_SESSION['cart'] = [];
        
        // === مرحله 10: پاسخ موفق ===
        
        echo json_encode([
            'success' => true,
            'message' => 'سفارش شما با موفقیت ثبت شد',
            'order_id' => $orderId,
            'order_number' => $orderNumber,
            'redirect' => BASE_URL . '/orders'
        ]);
        exit;
        
    } catch (Exception $e) {
        error_log("Checkout error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'خطا در ثبت سفارش']);
        exit;
    }
}
```

**نکات امنیتی و عملکردی:**

1. **Transaction Safety:**  
   ❌ عدم استفاده از Database Transaction  
   💡 **پیشنهاد:** استفاده از `BEGIN TRANSACTION` و `COMMIT/ROLLBACK` برای اطمینان از یکپارچگی داده‌ها

2. **Stock Management:**  
   ✅ بررسی موجودی قبل از ثبت سفارش  
   ✅ کاهش خودکار موجودی بعد از ثبت  
   ❌ Race condition در موجودی (دو کاربر همزمان)

3. **Payment Integration:**  
   ❌ عدم اتصال به درگاه پرداخت واقعی  
   💡 در حال حاضر فقط ساختار آماده است

#### F) AdminController - پنل مدیریت

**فایل:** `src/Controllers/AdminController.php` (300+ خط)

**بررسی دسترسی:**

```php
private function checkAdminAccess(): void {
    // بررسی لاگین بودن
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
        $_SESSION['auth_error'] = 'برای دسترسی به پنل ادمین ابتدا وارد شوید.';
        $this->redirect('/login');
        return;
    }
    
    // بررسی نقش ادمین
    if (($_SESSION['user_role'] ?? '') !== 'admin') {
        $_SESSION['auth_error'] = 'شما دسترسی به این بخش را ندارید.';
        $this->redirect('/');
        return;
    }
}
```

**تمام متدهای Admin:**

این متد در ابتدای هر action فراخوانی می‌شود:
```php
public function dashboard(): void {
    $this->checkAdminAccess();  // ← همیشه اول
    // ...
}
```

### 6.2 مدل‌ها (Models)

#### A) UserModel

**فایل:** `src/Models/UserModel.php` (150+ خط)

**مسئولیت‌ها:**
- احراز هویت (جستجوی کاربر)
- ایجاد کاربر جدید
- مدیریت تلاش‌های ورود و قفل اکانت
- به‌روزرسانی پروفایل
- مدیریت تصویر پروفایل

**متدهای کلیدی:**

**1. `findByUsername()` / `findByEmail()` / `findByPhone()`**

```php
public function findByUsername(string $username): ?array {
    $u = db_escape($username);
    return db_fetch_one("SELECT * FROM `users` WHERE `username` = '$u' LIMIT 1");
}
```

**علت 3 متد جداگانه:**  
✅ کاربر می‌تواند با username، email یا phone وارد شود  
✅ جداسازی concerns برای readability

**2. `create()` - ایجاد کاربر جدید**

```php
public function create(string $username, string $email, string $phone, string $password): int|false {
    $u    = db_escape($username);
    $e    = db_escape($email);
    $p    = db_escape($phone);
    $hash = db_escape(Security::hash_password($password));
    
    $sql = "INSERT INTO `users` (`username`,`email`,`phone`,`password_hash`,`role`,`is_active`)
            VALUES ('$u','$e','$p','$hash','customer',1)";
    return db_insert($sql);
}
```

**هش کردن رمز عبور:**
```php
// در Security.php
public static function hash_password(string $password): string {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}
```

**چرا cost 12؟**  
- Cost 10: سرعت بالا، امنیت متوسط
- Cost 12: ✅ تعادل بین امنیت و سرعت
- Cost 14+: امنیت بسیار بالا، کند

**3. `incrementLoginAttempts()` - افزایش تلاش‌های ناموفق**

```php
public function incrementLoginAttempts(int $userId): void {
    $id = (int)$userId;
    db_query("UPDATE `users` SET `login_attempts` = `login_attempts` + 1 WHERE `id` = $id");
}
```

**4. `lockAccount()` - قفل اکانت**

```php
public function lockAccount(int $userId, int $minutes = 15): void {
    $id      = (int)$userId;
    $until   = date('Y-m-d H:i:s', time() + ($minutes * 60));
    db_query("UPDATE `users` SET `locked_until` = '$until' WHERE `id` = $id");
}
```

**5. `isLocked()` - بررسی قفل بودن**

```php
public function isLocked(array $user): bool {
    if (empty($user['locked_until'])) return false;
    return strtotime($user['locked_until']) > time();
}
```

**6. `updateProfile()` - به‌روزرسانی پروفایل**

```php
public function updateProfile(int $userId, array $data): bool {
    $userId   = (int)$userId;
    $fullName = db_escape(trim($data['full_name'] ?? ''));
    $email    = db_escape(trim($data['email'] ?? ''));
    $phone    = db_escape(trim($data['phone'] ?? ''));
    $address  = db_escape(trim($data['address'] ?? ''));
    $job      = db_escape(trim($data['job'] ?? ''));
    $birthDate = !empty($data['birth_date']) ? "'" . db_escape($data['birth_date']) . "'" : 'NULL';
    $postalCode = db_escape(trim($data['postal_code'] ?? ''));
    
    $sql = "UPDATE `users` 
            SET `full_name` = '$fullName', 
                `email` = '$email', 
                `phone` = '$phone', 
                `address` = '$address',
                `job` = '$job',
                `birth_date` = $birthDate,
                `postal_code` = '$postalCode'";
    
    // فقط اگر profile_image در data باشد
    if (isset($data['profile_image'])) {
        $profileImage = $data['profile_image'] === null ? 'NULL' : "'" . db_escape($data['profile_image']) . "'";
        $sql .= ", `profile_image` = $profileImage";
    }
    
    $sql .= ", `updated_at` = NOW() WHERE `id` = $userId";
    
    return db_query($sql);
}
```

#### B) ProductModel

**فایل:** `src/Models/ProductModel.php` (250+ خط)

**متدهای کلیدی:**

**1. `getAll()` - لیست محصولات با pagination**

```php
public function getAll(int $limit = 12, int $offset = 0): array {
    $limit  = (int)$limit;
    $offset = (int)$offset;
    return db_fetch_all(
        "SELECT p.*, c.`name` AS category_name, c.`slug` AS category_slug
         FROM `products` p
         JOIN `categories` c ON p.`category_id` = c.`id`
         WHERE p.`is_active` = 1
         ORDER BY p.`created_at` DESC
         LIMIT $limit OFFSET $offset"
    );
}
```

**2. `getFeaturedBySeason()` - محصولات ویژه فصلی**

```php
public function getFeaturedBySeason(string $season, int $limit = 8): array {
    $season = db_escape($season);
    $limit = (int)$limit;
    return db_fetch_all(
        "SELECT p.*, c.`name` AS category_name
         FROM `products` p
         JOIN `categories` c ON p.`category_id` = c.`id`
         WHERE p.`is_active` = 1 
           AND p.`is_featured` = 1 
           AND (p.`season` = '$season' OR p.`season` = 'all')
         ORDER BY p.`rating_avg` DESC
         LIMIT $limit"
    );
}
```

**علت شرط `OR p.season = 'all'`:**  
✅ محصولاتی که `season='all'` دارند در همه فصل‌ها نمایش داده می‌شوند

**3. `searchWithPagination()` - جستجو**

```php
public function searchWithPagination(string $query, int $limit = 12, int $offset = 0): array {
    $q = db_escape($query);
    return db_fetch_all(
        "SELECT p.*, c.`name` AS category_name
         FROM `products` p
         JOIN `categories` c ON p.`category_id` = c.`id`
         WHERE p.`is_active` = 1
           AND (p.`name` LIKE '%$q%' OR p.`description` LIKE '%$q%')
         ORDER BY p.`is_featured` DESC, p.`rating_avg` DESC
         LIMIT $limit OFFSET $offset"
    );
}
```

**نقطه ضعف:**  
❌ استفاده از LIKE با wildcard در ابتدا (`%query%`) باعث Full Table Scan می‌شود  
💡 **پیشنهاد:** استفاده از Full-Text Search در MySQL یا Elasticsearch

#### C) OrderModel

**فایل:** `src/Models/OrderModel.php` (200+ خط)

**1. `generateOrderNumber()` - تولید شماره سفارش یکتا**

```php
public function generateOrderNumber(): string {
    return 'ORD-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(4)));
}
```

**مثال خروجی:** `ORD-20260828-A3F5C7D9`

**2. `createOrder()` - ایجاد سفارش**

```php
public function createOrder(array $orderData): ?int {
    $userId = (int)$orderData['user_id'];
    $orderNumber = db_escape($orderData['order_number']);
    $status = db_escape($orderData['status'] ?? 'pending');
    $totalAmount = (int)$orderData['total_amount'];
    // ... سایر فیلدها
    
    $sql = "INSERT INTO orders 
            (user_id, order_number, status, total_amount, ...) 
            VALUES 
            ($userId, '$orderNumber', '$status', $totalAmount, ...)";
    
    return db_insert($sql);
}
```

**3. `addOrderItems()` - افزودن آیتم‌های سفارش**

```php
public function addOrderItems(int $orderId, array $items): bool {
    foreach ($items as $item) {
        $productId = (int)$item['product_id'];
        $productName = db_escape($item['product_name']);
        $unitPrice = (int)$item['unit_price'];
        $quantity = (int)$item['quantity'];
        $subtotal = (int)$item['subtotal'];
        
        $sql = "INSERT INTO order_items 
                (order_id, product_id, product_name, unit_price, quantity, subtotal) 
                VALUES 
                ($orderId, $productId, '$productName', $unitPrice, $quantity, $subtotal)";
        
        if (!db_query($sql)) {
            return false;
        }
    }
    
    return true;
}
```

**4. `decreaseProductStock()` - کاهش موجودی**

```php
public function decreaseProductStock(int $productId, int $quantity): bool {
    $productId = (int)$productId;
    $quantity = (int)$quantity;
    
    $sql = "UPDATE products 
            SET stock_qty = stock_qty - $quantity 
            WHERE id = $productId AND stock_qty >= $quantity";
    
    return db_query($sql) !== false;
}
```

**نکته امنیتی:**  
✅ شرط `stock_qty >= $quantity` جلوی موجودی منفی را می‌گیرد  
❌ Race Condition در موجودی (نیاز به Locking)

---

*(ادامه دارد...)*

## 7. ویژگی‌های عملکردی

### 7.1 سیستم Theme Manager - ویژگی منحصربفرد پروژه

**کلاس:** `ThemeManager` (Singleton Pattern)  
**فایل:** `src/Libs/ThemeManager.php` (211 خط)

#### معماری سیستم

```php
class ThemeManager {
    private static ?ThemeManager $instance = null;
    private string $activeTheme;
    private array $themeConfig;
    private array $availableThemes = ['spring', 'summer', 'autumn', 'winter'];
    private string $defaultTheme = 'autumn';
    
    private function __construct() {
        $this->loadThemeConfig();
        $this->activeTheme = $this->resolveTheme();
    }
    
    public static function getInstance(): ThemeManager {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
```

**علت استفاده از Singleton:**  
✅ تنها یک نمونه از ThemeManager در کل application  
✅ جلوگیری از load مکرر config  
✅ حفظ state تم در طول درخواست

#### الگوریتم حل تم (resolveTheme)

**6 اولویت برای انتخاب تم:**

```php
private function resolveTheme(): string {
    // اولویت 1: تغییر تم از navbar (query string)
    if (isset($_GET['theme']) && in_array($_GET['theme'], $this->availableThemes)) {
        $_SESSION['user_selected_theme'] = $_GET['theme'];
        unset($_SESSION['product_theme']);  // پاک کردن تم محصول
        return $_GET['theme'];
    }
    
    // اولویت 2: انتخاب کاربر از navbar (session)
    if (isset($_SESSION['user_selected_theme'])) {
        $theme = $_SESSION['user_selected_theme'];
        if (in_array($theme, $this->availableThemes)) {
            return $theme;
        }
    }
    
    // اولویت 3: تم محصول (فقط در صفحه محصول)
    if (isset($_SESSION['product_theme'])) {
        $theme = $_SESSION['product_theme'];
        if (in_array($theme, $this->availableThemes)) {
            return $theme;
        }
    }
    
    // اولویت 4: تم ذخیره شده کاربر در دیتابیس
    if (isset($_SESSION['user_id'])) {
        $userTheme = $this->getUserTheme($_SESSION['user_id']);
        if ($userTheme && $userTheme !== 'automatic') {
            return $userTheme;
        }
    }
    
    // اولویت 5: تم انتخاب شده توسط ادمین
    if (!empty($this->themeConfig['admin_selected_theme'])) {
        return $this->themeConfig['admin_selected_theme'];
    }
    
    // اولویت 6: تم خودکار بر اساس فصل
    return $this->getCurrentSeasonTheme();
}
```

#### تشخیص فصل خودکار

```php
private function getCurrentSeasonTheme(): string {
    $month = (int)date('n');  // 1-12
    
    if ($month >= 3 && $month <= 5) {
        return 'spring';   // بهار: مارس-می
    } elseif ($month >= 6 && $month <= 8) {
        return 'summer';   // تابستان: ژوئن-اوت
    } elseif ($month >= 9 && $month <= 11) {
        return 'autumn';   // پاییز: سپتامبر-نوامبر
    } else {
        return 'winter';   // زمستان: دسامبر-فوریه
    }
}
```

#### تنظیم تم محصول

**در `ProductController::show()`:**

```php
// تغییر تم به تم محصول
$themeManager = ThemeManager::getInstance();
if (!empty($product['season'])) {
    $themeManager->setProductTheme($product['season']);
}
```

**در `ThemeManager`:**

```php
public function setProductTheme(?string $season): void {
    if ($season && in_array($season, $this->availableThemes)) {
        $_SESSION['product_theme'] = $season;
        $this->activeTheme = $season;
    }
}
```

**نکته مهم:** انتخاب کاربر حفظ می‌شود و فقط در صفحه محصول، تم موقتاً تغییر می‌کند.

#### پاک کردن تم محصول

**در کنترلرهایی که از صفحه محصول خارج می‌شوند:**

```php
$themeManager = ThemeManager::getInstance();
$themeManager->clearProductTheme();  // برگشت به تم انتخابی کاربر
```

#### استفاده در View

**در `header.php`:**

```php
<?php 
$themeManager = ThemeManager::getInstance();
$themeCss = $themeManager->getThemeCssPath();
?>
<link rel="stylesheet" href="<?= $themeCss ?>">
```

**متد `getThemeCssPath()`:**

```php
public function getThemeCssPath(): string {
    $theme = $this->activeTheme;
    $path = BASE_URL . '/assets/css/themes/' . $theme . '.css';
    $fullPath = PUBLIC_PATH . '/assets/css/themes/' . $theme . '.css';
    
    // بررسی وجود فایل
    if (!file_exists($fullPath)) {
        $theme = $this->defaultTheme;
        $path = BASE_URL . '/assets/css/themes/' . $theme . '.css';
    }
    
    return $path;
}
```

#### مثال سناریوی واقعی

**سناریو:** کاربر در پاییز وارد سایت می‌شود

```
1. ورود به سایت
   → resolveTheme() → فصل خودکار = autumn
   → تم: Autumn (رنگ‌های قهوه‌ای، نارنجی)

2. کاربر از navbar تم Spring را انتخاب می‌کند
   → URL: ?theme=spring
   → $_SESSION['user_selected_theme'] = 'spring'
   → تم: Spring (رنگ‌های سبز، صورتی)

3. کاربر وارد صفحه محصول "ژاکت زمستانی" می‌شود
   → محصول: season = 'winter'
   → setProductTheme('winter')
   → تم موقت: Winter (رنگ‌های آبی، سفید)

4. کاربر به صفحه لیست محصولات برمی‌گردد
   → clearProductTheme()
   → تم: Spring (انتخاب قبلی کاربر)

5. کاربر logout می‌کند
   → Session پاک می‌شود
   → تم: Autumn (فصل خودکار)
```

### 7.2 سیستم امنیتی (Security Class)

**کلاس:** `Security`  
**فایل:** `src/Libs/Security.php` (320+ خط)

این کلاس جامع‌ترین بخش امنیتی پروژه است.

#### A) CSRF Protection

**1. تولید توکن:**

```php
public static function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
```

**2. فیلد HTML:**

```php
public static function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . self::csrf_token() . '">';
}
```

**3. اعتبارسنجی:**

```php
public static function verify_csrf(string $token): bool {
    if (empty($_SESSION['csrf_token'])) return false;
    return hash_equals($_SESSION['csrf_token'], $token);
}
```

**استفاده در فرم:**

```html
<form method="POST" action="/login">
    <?= Security::csrf_field() ?>
    <!-- سایر فیلدها -->
</form>
```

**استفاده در کنترلر:**

```php
if (!Security::verify_csrf($_POST['csrf_token'] ?? '')) {
    echo json_encode(['success' => false, 'message' => 'توکن امنیتی نامعتبر']);
    exit;
}
```

#### B) XSS Protection

**1. Escape برای HTML Output:**

```php
public static function e(mixed $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}
```

**استفاده در View:**

```php
<h1><?= Security::e($product['name']) ?></h1>
<p><?= Security::e($user['bio']) ?></p>
```

**2. Sanitization ورودی:**

```php
public static function sanitize_input(string $input): string {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return $input;
}
```

**3. Slug امن:**

```php
public static function sanitize_slug(string $input): string {
    $input = strtolower(trim($input));
    $input = preg_replace('/[^a-z0-9\-]/', '-', $input);
    $input = preg_replace('/-+/', '-', $input);
    return trim($input, '-');
}
```

**مثال:**
```php
$slug = Security::sanitize_slug("پیراهن مردانه 2024!!");
// خروجی: "------2024"
```

#### C) Validation

**1. Email:**

```php
public static function validate_email(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false
        && strlen($email) <= 150;
}
```

**2. Username:**

```php
public static function validate_username(string $username): bool {
    return preg_match('/^[a-zA-Z0-9_\-\.]{3,50}$/', $username) === 1;
}
```

**قوانین:**
- 3-50 کاراکتر
- فقط حروف انگلیسی، اعداد، `_`, `-`, `.`

**3. Phone:**

```php
public static function validate_phone(string $phone): bool {
    return preg_match('/^09[0-9]{9}$/', $phone) === 1;
}
```

**فرمت:** 09xxxxxxxxx (11 رقم، شروع با 09)

**4. Password:**

```php
public static function validate_password(string $pass): bool {
    return strlen($pass) >= 8
        && preg_match('/[0-9]/', $pass)
        && preg_match('/[A-Za-z]/', $pass);
}
```

**قوانین:**
- حداقل 8 کاراکتر
- حداقل یک عدد
- حداقل یک حرف (بزرگ یا کوچک)

**5. Integer:**

```php
public static function validate_int(mixed $value, int $min = 1, int $max = PHP_INT_MAX): bool {
    $v = filter_var($value, FILTER_VALIDATE_INT);
    return $v !== false && $v >= $min && $v <= $max;
}
```

#### D) Rate Limiting

**پیاده‌سازی Session-based:**

```php
public static function rate_limit(string $key, int $max = 5, int $ttl = 900): bool {
    $session_key = 'rl_' . md5($key);
    $now = time();
    
    if (!isset($_SESSION[$session_key])) {
        $_SESSION[$session_key] = ['count' => 0, 'first' => $now];
    }
    
    $data = &$_SESSION[$session_key];
    
    // ریست بعد از TTL
    if ($now - $data['first'] > $ttl) {
        $data = ['count' => 0, 'first' => $now];
    }
    
    $data['count']++;
    
    if ($data['count'] > $max) {
        return false;  // مسدود
    }
    return true;
}
```

**استفاده:**

```php
$ip = $_SERVER['REMOTE_ADDR'];
if (!Security::rate_limit($ip, 5, 900)) {  // 5 تلاش در 15 دقیقه
    echo json_encode(['success' => false, 'message' => 'تعداد تلاش‌های شما زیاد است']);
    exit;
}
```

**ریست کردن:**

```php
public static function rate_limit_reset(string $key): void {
    $session_key = 'rl_' . md5($key);
    unset($_SESSION[$session_key]);
}
```

#### E) امنیت آپلود فایل

**1. Validation:**

```php
public static function validate_upload(array $file): array {
    $errors = [];
    
    // بررسی خطای آپلود
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'خطا در آپلود فایل';
        return $errors;
    }
    
    // بررسی اندازه (5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        $errors[] = 'حجم فایل نباید بیشتر از 5 مگابایت باشد';
    }
    
    // بررسی پسوند
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
        $errors[] = 'فرمت فایل مجاز نیست';
    }
    
    // بررسی MIME Type واقعی
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $real_mime = $finfo->file($file['tmp_name']);
    if (!in_array($real_mime, ['image/jpeg', 'image/png', 'image/webp', 'image/gif'])) {
        $errors[] = 'نوع فایل مجاز نیست';
    }
    
    return $errors;
}
```

**نکته امنیتی:**  
✅ **بررسی MIME واقعی** (نه فقط extension) جلوی فایل‌های مخرب را می‌گیرد

**2. تولید نام فایل امن:**

```php
public static function safe_filename(string $original): string {
    $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
    return bin2hex(random_bytes(16)) . '.' . $ext;
}
```

**مثال:**
```php
$original = "profile photo (1).jpg";
$safe = Security::safe_filename($original);
// خروجی: "a3f5c7d9e2b4f6a8c1d3e5f7b9d1c3e5.jpg"
```

#### F) Security Headers

```php
public static function set_security_headers(): void {
    if (!headers_sent()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }
}
```

**توضیح هر Header:**

| Header | هدف |
|--------|-----|
| `X-Content-Type-Options: nosniff` | جلوگیری از MIME-type sniffing (تبدیل فایل HTML به JS) |
| `X-Frame-Options: SAMEORIGIN` | جلوگیری از Clickjacking (قرار گرفتن در iframe خارجی) |
| `X-XSS-Protection: 1; mode=block` | فعال‌سازی فیلتر XSS مرورگر |
| `Referrer-Policy: strict-origin-when-cross-origin` | محدود کردن اطلاعات Referrer |

**استفاده در کنترلرها:**

```php
public function index(): void {
    Security::set_security_headers();  // همیشه اول
    // ...
}
```

### 7.3 سیستم Wishlist

**جدول دیتابیس:** `user_wishlist`

```sql
CREATE TABLE user_wishlist (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_wish (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
```

**کنترلر:** `WishlistController`

**متد `toggle()` - افزودن/حذف:**

```php
public function toggle(): void {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'لطفا ابتدا وارد شوید']);
        exit;
    }
    
    $productId = (int)($_POST['product_id'] ?? 0);
    $userId = (int)$_SESSION['user_id'];
    
    if ($productId <= 0) {
        echo json_encode(['success' => false, 'message' => 'محصول نامعتبر']);
        exit;
    }
    
    // بررسی وجود در wishlist
    $exists = db_fetch_one(
        "SELECT id FROM user_wishlist 
         WHERE user_id = $userId AND product_id = $productId 
         LIMIT 1"
    );
    
    if ($exists) {
        // حذف از wishlist
        db_query("DELETE FROM user_wishlist WHERE user_id = $userId AND product_id = $productId");
        echo json_encode(['success' => true, 'is_wishlisted' => false, 'message' => 'محصول از علاقه‌مندی‌ها حذف شد']);
    } else {
        // افزودن به wishlist
        db_query("INSERT INTO user_wishlist (user_id, product_id) VALUES ($userId, $productId)");
        echo json_encode(['success' => true, 'is_wishlisted' => true, 'message' => 'محصول به علاقه‌مندی‌ها اضافه شد']);
    }
    exit;
}
```

**AJAX Call در Frontend:**

```javascript
$('.wishlist-toggle').on('click', function(e) {
    e.preventDefault();
    const $btn = $(this);
    const productId = $btn.data('product-id');
    
    $.ajax({
        url: BASE_URL + '/wishlist/toggle',
        method: 'POST',
        data: { product_id: productId },
        success: function(response) {
            if (response.success) {
                // به‌روزرسانی آیکون
                if (response.is_wishlisted) {
                    $btn.find('i').removeClass('far').addClass('fas').css('color', '#e74c3c');
                } else {
                    $btn.find('i').removeClass('fas').addClass('far').css('color', '');
                }
                showNotification(response.message, 'success');
            }
        }
    });
});
```

### 7.4 سیستم نظرات و امتیازدهی

**جدول:** `reviews`

```sql
CREATE TABLE reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL CHECK (rating BETWEEN 1 AND 5),
    title VARCHAR(200),
    body TEXT NOT NULL,
    is_approved TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

**مدل:** `ReviewModel`

**متد `addReview()`:**

```php
public function addReview(int $productId, int $userId, int $rating, string $title, string $body): bool {
    $productId = (int)$productId;
    $userId    = (int)$userId;
    $rating    = max(1, min(5, (int)$rating));
    $title     = db_escape(trim($title));
    $body      = db_escape(trim($body));
    
    // بررسی نظر قبلی
    $exists = db_fetch_one(
        "SELECT id FROM reviews 
         WHERE product_id = $productId AND user_id = $userId 
         LIMIT 1"
    );
    
    if ($exists) {
        return false;  // کاربر قبلا نظر داده
    }
    
    $sql = "INSERT INTO reviews (product_id, user_id, rating, title, body) 
            VALUES ($productId, $userId, $rating, '$title', '$body')";
    
    return db_query($sql) !== false;
}
```

**به‌روزرسانی میانگین امتیاز محصول:**

```php
public function updateProductRating(int $productId): void {
    $result = db_fetch_one(
        "SELECT AVG(rating) as avg_rating, COUNT(*) as count 
         FROM reviews 
         WHERE product_id = $productId AND is_approved = 1"
    );
    
    $avgRating = round($result['avg_rating'] ?? 0, 2);
    $reviewCount = (int)($result['count'] ?? 0);
    
    db_query(
        "UPDATE products 
         SET rating_avg = $avgRating, rating_count = $reviewCount 
         WHERE id = $productId"
    );
}
```

**نمایش ستاره‌ها:**

```php
public static function renderStars(float $avg): string {
    $out = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($avg >= $i) {
            $out .= '<i class="fas fa-star"></i>';
        } elseif ($avg >= $i - 0.5) {
            $out .= '<i class="fas fa-star-half-alt"></i>';
        } else {
            $out .= '<i class="far fa-star"></i>';
        }
    }
    return $out;
}
```

**مثال استفاده در View:**

```php
<div class="product-rating">
    <?= Security::renderStars($product['rating_avg']) ?>
    <span>(<?= $product['rating_count'] ?> نظر)</span>
</div>
```

---

## 8. امنیت پروژه

### 8.1 لایه‌های امنیتی

پروژه Velora از **7 لایه امنیتی** استفاده می‌کند:

```
┌─────────────────────────────────────────┐
│ 1. Apache .htaccess Security            │
│    - Directory Listing OFF              │
│    - File Access Control                │
│    - Security Headers                   │
└─────────────────────────────────────────┘
                   ↓
┌─────────────────────────────────────────┐
│ 2. Session Security                     │
│    - httponly, samesite, strict         │
│    - Session Regeneration               │
│    - 1-hour Timeout                     │
└─────────────────────────────────────────┘
                   ↓
┌─────────────────────────────────────────┐
│ 3. CSRF Protection                      │
│    - Token در تمام فرم‌ها              │
│    - hash_equals() برای مقایسه         │
└─────────────────────────────────────────┘
                   ↓
┌─────────────────────────────────────────┐
│ 4. XSS Protection                       │
│    - htmlspecialchars() در output       │
│    - sanitize_input() در input          │
└─────────────────────────────────────────┘
                   ↓
┌─────────────────────────────────────────┐
│ 5. SQL Injection Prevention             │
│    - db_escape() برای تمام inputs       │
│    - Type casting برای integers         │
│    - Validation قبل از query            │
└─────────────────────────────────────────┘
                   ↓
┌─────────────────────────────────────────┐
│ 6. Rate Limiting & Brute Force          │
│    - 5 تلاش در 15 دقیقه                │
│    - Account Locking                    │
│    - IP-based Limiting                  │
└─────────────────────────────────────────┘
                   ↓
┌─────────────────────────────────────────┐
│ 7. Upload Security                      │
│    - MIME Type Validation               │
│    - Extension Whitelist                │
│    - Safe Filename Generation           │
│    - Size Limit (5MB)                   │
└─────────────────────────────────────────┘
```

### 8.2 تنظیمات .htaccess

**فایل:** `public/.htaccess`

```apache
# غیرفعال کردن Directory Listing
Options -Indexes

# Custom Error Pages
ErrorDocument 404 /index.php
ErrorDocument 500 /index.php

# Security Headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# محدود کردن دسترسی به فایل‌های حساس
<FilesMatch "\.(env|log|sql|sh)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# URL Rewriting
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /Velora/public
    
    # هدایت تمام درخواست‌ها به index.php
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>
```

**توضیح:**

- `Options -Indexes`: جلوگیری از لیست شدن فایل‌ها
- `ErrorDocument 404`: هدایت خطاها به index.php
- Security Headers: محافظت در برابر حملات رایج
- `FilesMatch`: منع دسترسی مستقیم به فایل‌های .env، .log، .sql
- RewriteRule: تمام URL‌ها به index.php هدایت می‌شوند

### 8.3 Password Security

**هش کردن:**

```php
// در Security.php
public static function hash_password(string $password): string {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}
```

**تأیید:**

```php
public static function verify_password(string $password, string $hash): bool {
    return password_verify($password, $hash);
}
```

**چرا bcrypt با cost 12؟**

| Cost | زمان | امنیت | توصیه |
|------|------|-------|-------|
| 10 | ~80ms | متوسط | برای پروژه‌های سریع |
| 12 | ~300ms | بالا | ✅ **توصیه شده** |
| 14 | ~1.2s | بسیار بالا | برای داده‌های بسیار حساس |

**مثال hash:**
```php
$hash = Security::hash_password("MyP@ssw0rd123");
// خروجی: $2y$12$lQ3f5Y2nZ...  (60 کاراکتر)
```

### 8.4 Account Locking Mechanism

**فرآیند:**

```
ورود ناموفق 1 → login_attempts = 1
ورود ناموفق 2 → login_attempts = 2
ورود ناموفق 3 → login_attempts = 3
ورود ناموفق 4 → login_attempts = 4
ورود ناموفق 5 → login_attempts = 5 + locked_until = NOW() + 15 min

تلاش ورود در زمان قفل → خطا: "حساب شما قفل شده"

بعد از 15 دقیقه:
ورود موفق → login_attempts = 0, locked_until = NULL
```

**کد در `UserModel`:**

```php
public function incrementLoginAttempts(int $userId): void {
    $id = (int)$userId;
    db_query("UPDATE `users` SET `login_attempts` = `login_attempts` + 1 WHERE `id` = $id");
}

public function lockAccount(int $userId, int $minutes = 15): void {
    $id      = (int)$userId;
    $until   = date('Y-m-d H:i:s', time() + ($minutes * 60));
    db_query("UPDATE `users` SET `locked_until` = '$until' WHERE `id` = $id");
}

public function isLocked(array $user): bool {
    if (empty($user['locked_until'])) return false;
    return strtotime($user['locked_until']) > time();
}

public function resetLoginAttempts(int $userId): void {
    $id = (int)$userId;
    db_query("UPDATE `users` SET `login_attempts` = 0, `locked_until` = NULL WHERE `id` = $id");
}
```

---

*(ادامه در بخش بعد...)*


## 9. مدیریت خطاها

### 9.1 سیستم Error Handling

**تنظیمات در `index.php`:**

```php
define('APP_DEBUG', true);

if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', BASE_PATH . '/logs/error.log');
}
```

**توصیه:** در پروداکشن حتماً `APP_DEBUG = false` کنید.

### 9.2 Error Logging

**در توابع دیتابیس:**

```php
function db_query($sql) {
    $conn = db_connect();
    $result = mysqli_query($conn, $sql);
    if ($result === false) {
        error_log('DB Query Error: ' . mysqli_error($conn) . ' | SQL: ' . $sql);
        return false;
    }
    return $result;
}
```

**مثال لاگ:**
```
[28-Aug-2026 14:30:15] DB Query Error: Unknown column 'xyz' in 'field list' | SQL: SELECT xyz FROM users
[28-Aug-2026 14:32:42] DB Connection failed: Access denied for user 'root'@'localhost'
[28-Aug-2026 15:10:33] Checkout error: Division by zero
```

### 9.3 Custom Error Pages

**404 Not Found:**

```php
class ErrorController {
    public function notFound(): void {
        http_response_code(404);
        $pageTitle = '404 - صفحه یافت نشد';
        require BASE_PATH . '/src/Views/pages/404.php';
    }
}
```

---

## 10. پنل مدیریت

### 10.1 دسترسی به پنل

**URL:** `/admin` یا `/admin/dashboard`

**شرط ورود:**
1. کاربر باید login باشد (`$_SESSION['logged_in']`)
2. نقش کاربر باید `admin` باشد (`$_SESSION['user_role'] === 'admin'`)

**کد بررسی در `AdminController`:**

```php
private function checkAdminAccess(): void {
    if (!isset($_SESSION['user_id']) || !$_SESSION['logged_in']) {
        $_SESSION['auth_error'] = 'برای دسترسی به پنل ادمین ابتدا وارد شوید.';
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
    
    if (($_SESSION['user_role'] ?? '') !== 'admin') {
        $_SESSION['auth_error'] = 'شما دسترسی به این بخش را ندارید.';
        header('Location: ' . BASE_URL . '/');
        exit;
    }
}
```

### 10.2 بخش‌های پنل

#### A) Dashboard

**URL:** `/admin/dashboard`

**محتوا:**
- آمار کلی (تعداد کاربران، محصولات، سفارشات)
- تعداد سفارشات در انتظار
- 5 سفارش اخیر
- 5 کاربر جدید

```php
public function dashboard(): void {
    $this->checkAdminAccess();
    
    $stats = [
        'total_users' => $this->userModel->getTotalCount(),
        'total_products' => $this->productModel->getTotalCount(),
        'total_orders' => $this->orderModel->getTotalCount(),
        'pending_orders' => $this->orderModel->getPendingCount(),
        'recent_orders' => $this->orderModel->getRecentOrders(5),
        'recent_users' => $this->userModel->getRecentUsers(5),
    ];
    
    require BASE_PATH . '/src/Views/admin/dashboard.php';
}
```

#### B) مدیریت محصولات

**لیست محصولات:** `/admin/products`
- Pagination (5, 10, 20, 50 محصول در صفحه)
- دکمه‌های ویرایش و حذف

**افزودن محصول:** `/admin/products/create`
- فرم کامل با تمام فیلدها
- آپلود تصویر اصلی
- آپلود چندین تصویر برای گالری

**ویرایش محصول:** `/admin/products/edit/{id}`
- پر شدن فرم با اطلاعات موجود
- امکان تغییر تصویر اصلی
- مدیریت گالری تصاویر

**حذف محصول:** `POST /admin/products/delete`
```php
public function deleteProduct(): void {
    $this->checkAdminAccess();
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect('/admin/products');
        return;
    }
    
    $productId = (int)($_POST['product_id'] ?? 0);
    
    if ($productId > 0) {
        $result = $this->productModel->deleteProduct($productId);
        if ($result) {
            $_SESSION['admin_success'] = 'محصول با موفقیت حذف شد.';
        } else {
            $_SESSION['admin_error'] = 'خطا در حذف محصول.';
        }
    }
    
    $this->redirect('/admin/products');
}
```

#### C) مدیریت سفارشات

**لیست سفارشات:** `/admin/orders`
- Pagination
- فیلتر بر اساس وضعیت (pending, processing, shipped, delivered, cancelled)
- فیلتر بر اساس وضعیت پرداخت (unpaid, paid, refunded)

**جزئیات سفارش:** `/admin/orders/view/{id}`
- اطلاعات کامل سفارش
- لیست محصولات سفارش
- اطلاعات کاربر
- آدرس ارسال

**تغییر وضعیت:** `POST /admin/orders/update-status`
```php
public function updateOrderStatus(): void {
    $this->checkAdminAccess();
    
    $orderId = (int)($_POST['order_id'] ?? 0);
    $newStatus = $_POST['status'] ?? '';
    
    $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'];
    
    if (in_array($newStatus, $validStatuses)) {
        db_query("UPDATE orders SET status = '" . db_escape($newStatus) . "' WHERE id = $orderId");
        $_SESSION['admin_success'] = 'وضعیت سفارش به‌روز شد.';
    }
    
    $this->redirect('/admin/orders');
}
```

#### D) مدیریت کاربران

**لیست کاربران:** `/admin/users`
- نمایش username, email, role, وضعیت
- آمار هر کاربر (تعداد سفارشات، نظرات)

#### E) مدیریت نظرات

**لیست نظرات:** `/admin/reviews`
- نظرات تأیید شده و در انتظار تأیید
- ستون‌ها: محصول، کاربر، امتیاز، متن نظر

**تأیید نظر:** `POST /admin/reviews/approve`
```php
public function approveReview(): void {
    $this->checkAdminAccess();
    
    $reviewId = (int)($_POST['review_id'] ?? 0);
    
    if ($reviewId > 0) {
        db_query("UPDATE reviews SET is_approved = 1 WHERE id = $reviewId");
        $_SESSION['admin_success'] = 'نظر تأیید شد.';
    }
    
    $this->redirect('/admin/reviews');
}
```

**حذف نظر:** `POST /admin/reviews/delete`

#### F) تنظیمات تم

**URL:** `/admin/theme-settings`

**فرم:**
```html
<form method="POST">
    <select name="mode">
        <option value="automatic">خودکار (بر اساس فصل)</option>
        <option value="manual">دستی</option>
    </select>
    
    <select name="admin_selected_theme">
        <option value="">انتخاب کنید</option>
        <option value="spring">بهار</option>
        <option value="summer">تابستان</option>
        <option value="autumn">پاییز</option>
        <option value="winter">زمستان</option>
    </select>
    
    <button type="submit">ذخیره تنظیمات</button>
</form>
```

**پردازش:**
```php
public function themeSettings(): void {
    $this->checkAdminAccess();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $mode = $_POST['mode'] ?? 'automatic';
        $theme = $_POST['admin_selected_theme'] ?? null;
        
        $config = [
            'mode' => $mode,
            'admin_selected_theme' => $theme
        ];
        
        $content = "<?php\nreturn " . var_export($config, true) . ";\n";
        file_put_contents(BASE_PATH . '/config/theme.php', $content);
        
        $_SESSION['admin_success'] = 'تنظیمات تم ذخیره شد.';
    }
    
    require BASE_PATH . '/src/Views/admin/theme-settings.php';
}
```

---

## 11. ساختار دیتابیس

### 11.1 جداول اصلی

#### A) `users` - کاربران

```sql
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    phone VARCHAR(11) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    role ENUM('customer', 'admin') DEFAULT 'customer',
    profile_image VARCHAR(255),
    address TEXT,
    postal_code VARCHAR(10),
    job VARCHAR(100),
    birth_date DATE,
    preferred_theme ENUM('spring', 'summer', 'autumn', 'winter', 'automatic') DEFAULT 'automatic',
    is_active TINYINT(1) DEFAULT 1,
    login_attempts TINYINT UNSIGNED DEFAULT 0,
    locked_until DATETIME,
    last_login DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

**فیلدهای کلیدی:**
- `role`: نقش کاربر (customer یا admin)
- `login_attempts`: تعداد تلاش‌های ناموفق ورود
- `locked_until`: زمان قفل اکانت
- `preferred_theme`: تم انتخابی کاربر

#### B) `products` - محصولات

```sql
CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(250) UNIQUE NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    short_desc VARCHAR(500),
    description TEXT,
    price INT UNSIGNED NOT NULL,
    sale_price INT UNSIGNED,
    stock_qty INT UNSIGNED DEFAULT 0,
    main_image VARCHAR(500),
    gallery JSON,
    season ENUM('spring', 'summer', 'autumn', 'winter', 'all') DEFAULT 'all',
    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    rating_avg DECIMAL(3,2) DEFAULT 0.00,
    rating_count INT UNSIGNED DEFAULT 0,
    views INT UNSIGNED DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
);
```

**فیلدهای کلیدی:**
- `slug`: URL-friendly نام محصول
- `gallery`: JSON array از مسیرهای تصاویر
- `season`: فصل محصول (برای Theme Manager)
- `is_featured`: نمایش در صفحه اصلی
- `rating_avg`: میانگین امتیاز
- `rating_count`: تعداد نظرات

#### C) `categories` - دسته‌بندی‌ها

```sql
CREATE TABLE categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(150) UNIQUE NOT NULL,
    description TEXT,
    parent_id INT UNSIGNED,
    sort_order TINYINT UNSIGNED DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

#### D) `orders` - سفارشات

```sql
CREATE TABLE orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded') DEFAULT 'pending',
    total_amount INT UNSIGNED NOT NULL,
    discount_amt INT UNSIGNED DEFAULT 0,
    shipping_cost INT UNSIGNED DEFAULT 0,
    shipping_address TEXT NOT NULL,
    postal_code VARCHAR(10) NOT NULL,
    payment_method ENUM('online', 'cash') DEFAULT 'online',
    payment_status ENUM('unpaid', 'paid', 'refunded') DEFAULT 'unpaid',
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
);
```

**وضعیت‌های سفارش:**
- `pending`: در انتظار پردازش
- `processing`: در حال آماده‌سازی
- `shipped`: ارسال شده
- `delivered`: تحویل داده شده
- `cancelled`: لغو شده
- `refunded`: مبلغ برگشت داده شده

#### E) `order_items` - آیتم‌های سفارش

```sql
CREATE TABLE order_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    product_name VARCHAR(200) NOT NULL,
    unit_price INT UNSIGNED NOT NULL,
    quantity TINYINT UNSIGNED NOT NULL,
    subtotal INT UNSIGNED NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
);
```

**چرا `product_name` ذخیره می‌شود؟**  
✅ حفظ نام محصول در زمان خرید (حتی اگر بعداً تغییر کند)

#### F) `reviews` - نظرات

```sql
CREATE TABLE reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL CHECK (rating BETWEEN 1 AND 5),
    title VARCHAR(200),
    body TEXT NOT NULL,
    is_approved TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### G) `user_wishlist` - علاقه‌مندی‌ها

```sql
CREATE TABLE user_wishlist (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_wish (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
```

**UNIQUE KEY `unique_wish`:**  
✅ هر کاربر فقط یک بار می‌تواند یک محصول را به wishlist اضافه کند

#### H) `banners` - بنرهای تبلیغاتی

```sql
CREATE TABLE banners (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200),
    subtitle VARCHAR(300),
    image_url VARCHAR(500) NOT NULL,
    link_url VARCHAR(500),
    btn_text VARCHAR(80),
    position ENUM('hero', 'mid', 'sidebar') DEFAULT 'hero',
    sort_order TINYINT UNSIGNED DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### 11.2 روابط جداول

```
users (1) ───┬─→ (N) orders
             ├─→ (N) reviews
             └─→ (N) user_wishlist

products (1) ─┬─→ (N) order_items
              ├─→ (N) reviews
              └─→ (N) user_wishlist

orders (1) ───→ (N) order_items

categories (1) ─→ (N) products
```

---

## 12. تحلیل نقاط قوت و ضعف

### 12.1 نقاط قوت ⭐

#### A) معماری و ساختار

✅ **MVC منظم و تمیز**
- جداسازی کامل منطق تجاری، نمایش، و کنترل
- ساختار پوشه‌ها قابل فهم و maintainable
- نام‌گذاری استاندارد (PascalCase برای کلاس‌ها، camelCase برای متدها)

✅ **Front Controller Pattern**
- تک نقطه ورود (index.php)
- Routing متمرکز
- امنیت بالاتر

✅ **Singleton Pattern برای ThemeManager**
- جلوگیری از load مکرر config
- کاهش مصرف حافظه

#### B) امنیت

✅ **7 لایه امنیتی کامل**
- CSRF Protection
- XSS Prevention
- SQL Injection Prevention
- Rate Limiting
- Account Locking
- Upload Security
- Security Headers

✅ **Password Security**
- bcrypt با cost 12
- نگهداری hash (نه plaintext)
- Session Regeneration بعد از ورود

✅ **Session Security**
- httponly, samesite, strict
- timeout 1 ساعت
- regenerate_id بعد از authentication

#### C) ویژگی‌های منحصربفرد

✅ **Theme Manager**
- سیستم 6-priority برای انتخاب تم
- تشخیص خودکار فصل
- تم موقت برای صفحه محصول
- پنل ادمین برای تنظیم تم سراسری

✅ **Brute Force Protection**
- Rate limiting IP-based
- Account locking خودکار
- ذخیره تلاش‌های ناموفق در دیتابیس

#### D) تجربه کاربری

✅ **Responsive Design**
- Bootstrap 5 RTL
- موبایل-اول
- Hamburger menu

✅ **AJAX Operations**
- Add to Cart بدون refresh
- Wishlist toggle
- Real-time updates

✅ **جستجوی پیشرفته**
- Pagination
- فیلتر بر اساس دسته‌بندی
- فیلتر بر اساس فصل
- مرتب‌سازی

#### E) پنل مدیریت

✅ **CRUD کامل محصولات**
- آپلود تصویر با validation
- مدیریت گالری
- Pagination

✅ **مدیریت سفارشات**
- تغییر وضعیت
- فیلترها
- جزئیات کامل

✅ **تأیید نظرات**
- جلوگیری از spam
- کنترل محتوا

### 12.2 نقاط ضعف و چالش‌ها ⚠️

#### A) دیتابیس

❌ **عدم استفاده از PDO**
- mysqli سنتی کمتر امن از PDO
- نبود Prepared Statements واقعی
- `db_escape()` در برابر حملات پیشرفته کافی نیست

💡 **راه‌حل:**
```php
// استفاده از PDO با Prepared Statements
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
```

❌ **عدم Transaction Management**
- در checkout، اگر یکی از queryها fail شود، ناسازگاری داده رخ می‌دهد
- مثلاً: سفارش ثبت شود اما موجودی کم نشود

💡 **راه‌حل:**
```php
mysqli_begin_transaction($conn);
try {
    // ایجاد سفارش
    // افزودن آیتم‌ها
    // کاهش موجودی
    mysqli_commit($conn);
} catch (Exception $e) {
    mysqli_rollback($conn);
}
```

❌ **Race Condition در موجودی**
- دو کاربر همزمان ممکن است موجودی را خریداری کنند
- نیاز به Row Locking

💡 **راه‌حل:**
```sql
SELECT stock_qty FROM products WHERE id = 15 FOR UPDATE;
-- بررسی و کاهش
UPDATE products SET stock_qty = stock_qty - 1 WHERE id = 15;
```

#### B) عملکرد (Performance)

❌ **N+1 Query Problem**
- در لیست سبد خرید، برای هر محصول یک query جداگانه

💡 **راه‌حل:**
```php
// به جای N query، 1 query با IN
$ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$sql = "SELECT * FROM products WHERE id IN ($placeholders)";
```

❌ **عدم Caching**
- محصولات featured هر بار از DB خوانده می‌شوند
- تنظیمات تم هر بار load می‌شوند

💡 **راه‌حل:**
- استفاده از Redis یا Memcached
- یا حداقل File-based caching

```php
$cacheFile = BASE_PATH . '/cache/featured_products.json';
if (file_exists($cacheFile) && time() - filemtime($cacheFile) < 3600) {
    $products = json_decode(file_get_contents($cacheFile), true);
} else {
    $products = $productModel->getFeatured();
    file_put_contents($cacheFile, json_encode($products));
}
```

❌ **Full Table Scan در جستجو**
- استفاده از `LIKE '%query%'` بدون index

💡 **راه‌حل:**
```sql
-- افزودن Full-Text Index
ALTER TABLE products ADD FULLTEXT(name, description);

-- جستجو با MATCH AGAINST
SELECT * FROM products 
WHERE MATCH(name, description) AGAINST('جستجو' IN NATURAL LANGUAGE MODE);
```

#### C) ساختار کد

❌ **عدم استفاده از Autoloader**
- 14 خط `require_once` در index.php
- خطا در صورت فراموشی یک require

💡 **راه‌حل:**
```php
spl_autoload_register(function ($class) {
    $paths = [
        BASE_PATH . '/src/Controllers/' . $class . '.php',
        BASE_PATH . '/src/Models/' . $class . '.php',
        BASE_PATH . '/src/Libs/' . $class . '.php',
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});
```

❌ **کلاس‌های بزرگ**
- `AdminController` بیش از 300 خط
- `Security` بیش از 320 خط
- نقض Single Responsibility Principle

💡 **راه‌حل:**
- تقسیم `AdminController` به: `AdminProductController`, `AdminOrderController`, `AdminUserController`
- تقسیم `Security` به: `CsrfProtection`, `InputValidator`, `FileUploadValidator`

❌ **عدم Dependency Injection**
- Models مستقیماً در Controllers instantiate می‌شوند
- سخت شدن unit testing

💡 **راه‌حل:**
```php
class ProductController {
    private ProductModel $productModel;
    
    public function __construct(ProductModel $productModel) {
        $this->productModel = $productModel;
    }
}

// در index.php
$productModel = new ProductModel();
$controller = new ProductController($productModel);
```

#### D) ویژگی‌های ناقص

❌ **سیستم کد تخفیف**
- فقط TODO در کد
- هیچ جدولی در دیتابیس نیست

💡 **راه‌حل:**
```sql
CREATE TABLE coupons (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    discount_type ENUM('percentage', 'fixed') NOT NULL,
    discount_value INT UNSIGNED NOT NULL,
    min_purchase INT UNSIGNED DEFAULT 0,
    max_uses INT UNSIGNED DEFAULT 0,
    used_count INT UNSIGNED DEFAULT 0,
    expires_at DATETIME,
    is_active TINYINT(1) DEFAULT 1
);
```

❌ **عدم درگاه پرداخت**
- فقط ساختار آماده است
- کاربر نمی‌تواند واقعاً پرداخت کند

💡 **راه‌حل:**
- اتصال به Zarinpal, Zibal, یا Saman
- ثبت transaction در جدول `payments`

❌ **عدم Email Notification**
- کاربر بعد از خرید ایمیل نمی‌گیرد
- ادمین از سفارشات جدید مطلع نمی‌شود

💡 **راه‌حل:**
```php
// استفاده از PHPMailer یا SwiftMailer
use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer();
$mail->setFrom('shop@velora.com', 'Velora Shop');
$mail->addAddress($user['email']);
$mail->Subject = 'سفارش شما ثبت شد';
$mail->Body = "سفارش شماره {$orderNumber} با موفقیت ثبت شد.";
$mail->send();
```

#### E) تست

❌ **عدم Unit Tests**
- هیچ تستی برای توابع امنیتی نیست
- خطا در production شناسایی می‌شود

💡 **راه‌حل:**
```php
// با PHPUnit
class SecurityTest extends TestCase {
    public function testCsrfTokenGeneration() {
        $token = Security::csrf_token();
        $this->assertEquals(64, strlen($token)); // 32 bytes hex = 64 chars
    }
    
    public function testPasswordValidation() {
        $this->assertTrue(Security::validate_password('MyP@ss123'));
        $this->assertFalse(Security::validate_password('short'));
    }
}
```

### 12.3 نقاط بهبود پیشنهادی 💡

#### اولویت بالا (High Priority)

1. **اضافه کردن PDO و Prepared Statements**
2. **پیاده‌سازی Transaction Management**
3. **ایجاد سیستم کد تخفیف**
4. **اتصال به درگاه پرداخت**
5. **افزودن Unit Tests**

#### اولویت متوسط (Medium Priority)

6. **پیاده‌سازی Caching (Redis/Memcached)**
7. **بهبود جستجو با Full-Text Search**
8. **افزودن Email Notifications**
9. **Refactoring کلاس‌های بزرگ**
10. **استفاده از Autoloader**

#### اولویت پایین (Low Priority)

11. **اضافه کردن API Endpoints (REST/GraphQL)**
12. **پیاده‌سازی WebSocket برای Notifications Real-time**
13. **افزودن Multi-language Support**
14. **سیستم Wishlist Sharing**
15. **پیشنهاد محصولات مشابه با Machine Learning**

---

## 13. راهنمای توسعه

### 13.1 نصب و راه‌اندازی

#### الزامات

- PHP 8.3+
- MySQL 8.0+
- Apache 2.4+ (با mod_rewrite)
- Composer (اختیاری، برای آینده)

#### مراحل نصب

**1. Clone پروژه:**
```bash
git clone https://github.com/your-repo/velora.git
cd velora
```

**2. ایجاد دیتابیس:**
```sql
CREATE DATABASE velora_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci;
USE velora_shop;
SOURCE velora_shop.sql;
```

**3. تنظیم پیکربندی:**

`config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_user');
define('DB_PASS', 'your_password');
define('DB_NAME', 'velora_shop');
```

**4. تنظیم BASE_URL:**

`public/index.php`:
```php
define('BASE_URL', '/velora/public');
// یا برای root:
define('BASE_URL', '');
```

**5. تنظیم Apache Virtual Host:**
```apache
<VirtualHost *:80>
    ServerName velora.local
    DocumentRoot "C:/wamp64/www/Velora/public"
    
    <Directory "C:/wamp64/www/Velora/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**6. دسترسی‌ها:**
```bash
chmod 755 public/assets/images/products
chmod 755 public/assets/images/profiles
chmod 755 logs
```

**7. ایجاد کاربر Admin:**
```sql
INSERT INTO users (username, email, phone, password_hash, role, is_active)
VALUES (
    'admin',
    'admin@velora.com',
    '09123456789',
    '$2y$12$...',  -- hash از "Admin@123"
    'admin',
    1
);
```

### 13.2 افزودن ویژگی جدید

#### مثال: افزودن سیستم کد تخفیف

**مرحله 1: ایجاد جدول**

```sql
CREATE TABLE coupons (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    discount_type ENUM('percentage', 'fixed') NOT NULL,
    discount_value INT UNSIGNED NOT NULL,
    min_purchase INT UNSIGNED DEFAULT 0,
    max_uses INT UNSIGNED DEFAULT 0,
    used_count INT UNSIGNED DEFAULT 0,
    expires_at DATETIME,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

**مرحله 2: ایجاد Model**

`src/Models/CouponModel.php`:
```php
<?php
class CouponModel {
    
    public function findByCode(string $code): ?array {
        $code = db_escape($code);
        return db_fetch_one("SELECT * FROM coupons WHERE code = '$code' AND is_active = 1 LIMIT 1");
    }
    
    public function isValid(array $coupon, int $subtotal): bool {
        // بررسی انقضا
        if (!empty($coupon['expires_at']) && strtotime($coupon['expires_at']) < time()) {
            return false;
        }
        
        // بررسی حداقل خرید
        if ($subtotal < $coupon['min_purchase']) {
            return false;
        }
        
        // بررسی تعداد استفاده
        if ($coupon['max_uses'] > 0 && $coupon['used_count'] >= $coupon['max_uses']) {
            return false;
        }
        
        return true;
    }
    
    public function calculateDiscount(array $coupon, int $subtotal): int {
        if ($coupon['discount_type'] === 'percentage') {
            return (int)($subtotal * $coupon['discount_value'] / 100);
        } else {
            return min($coupon['discount_value'], $subtotal);
        }
    }
    
    public function incrementUsage(int $couponId): void {
        $id = (int)$couponId;
        db_query("UPDATE coupons SET used_count = used_count + 1 WHERE id = $id");
    }
}
```

**مرحله 3: به‌روزرسانی CartController**

```php
public function applyCoupon(): void {
    header('Content-Type: application/json; charset=utf-8');
    
    $couponCode = trim($_POST['coupon_code'] ?? '');
    
    if (empty($couponCode)) {
        echo json_encode(['success' => false, 'message' => 'لطفا کد تخفیف را وارد کنید']);
        exit;
    }
    
    $couponModel = new CouponModel();
    $coupon = $couponModel->findByCode($couponCode);
    
    if (!$coupon) {
        echo json_encode(['success' => false, 'message' => 'کد تخفیف نامعتبر است']);
        exit;
    }
    
    $cartItems = $this->getCartItems();
    $subtotal = 0;
    foreach ($cartItems as $item) {
        $price = $item['sale_price'] ?: $item['price'];
        $subtotal += $price * $item['quantity'];
    }
    
    if (!$couponModel->isValid($coupon, $subtotal)) {
        echo json_encode(['success' => false, 'message' => 'کد تخفیف منقضی شده یا شرایط آن برآورده نشده']);
        exit;
    }
    
    $discount = $couponModel->calculateDiscount($coupon, $subtotal);
    
    $_SESSION['applied_coupon'] = $coupon;
    
    echo json_encode([
        'success' => true,
        'message' => 'کد تخفیف با موفقیت اعمال شد',
        'discount_amount' => $discount,
        'final_total' => $subtotal - $discount
    ]);
    exit;
}
```

**مرحله 4: به‌روزرسانی CheckoutController**

```php
// در متد process()
$discountAmt = 0;
if (isset($_SESSION['applied_coupon'])) {
    $coupon = $_SESSION['applied_coupon'];
    $couponModel = new CouponModel();
    $discountAmt = $couponModel->calculateDiscount($coupon, $subtotal);
    $couponModel->incrementUsage($coupon['id']);
}

$total = $subtotal + $shipping + $tax - $discountAmt;
```

**مرحله 5: افزودن Route**

`config/routes.php`:
```php
'POST:/cart/apply-coupon' => ['CartController', 'applyCoupon'],
```

**مرحله 6: به‌روزرسانی View**

`src/Views/pages/cart.php`:
```html
<div class="coupon-section">
    <input type="text" id="couponCode" placeholder="کد تخفیف">
    <button id="applyCouponBtn">اعمال</button>
    <div id="couponMessage"></div>
</div>

<script>
$('#applyCouponBtn').on('click', function() {
    const code = $('#couponCode').val();
    
    $.ajax({
        url: BASE_URL + '/cart/apply-coupon',
        method: 'POST',
        data: { coupon_code: code },
        success: function(response) {
            if (response.success) {
                $('#couponMessage').html('<span class="success">' + response.message + '</span>');
                // به‌روزرسانی مبلغ
                $('.discount-amount').text(response.discount_amount.toLocaleString() + ' تومان');
                $('.final-total').text(response.final_total.toLocaleString() + ' تومان');
            } else {
                $('#couponMessage').html('<span class="error">' + response.message + '</span>');
            }
        }
    });
});
</script>
```

### 13.3 Debugging Tips

**1. فعال کردن APP_DEBUG:**
```php
define('APP_DEBUG', true);
```

**2. مشاهده Query Log:**
```php
function db_query($sql) {
    error_log('Query: ' . $sql);  // لاگ همه query‌ها
    // ...
}
```

**3. Dump و Die:**
```php
function dd($var) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    die();
}

// استفاده
dd($_SESSION);
dd($product);
```

**4. بررسی Session:**
```php
// در هر کنترلر
error_log('Session: ' . print_r($_SESSION, true));
```

---

## 📋 خلاصه نهایی

پروژه **Velora Shop** یک فروشگاه آنلاین مدرن با ویژگی منحصربفرد **تم‌بندی فصلی خودکار** است که با PHP خالص و معماری MVC پیاده‌سازی شده است.

### نکات کلیدی:

✅ **امنیت بالا** با 7 لایه محافظتی  
✅ **Theme Manager هوشمند** با 6 اولویت انتخاب  
✅ **Session-based Cart** برای سرعت  
✅ **پنل Admin کامل** با CRUD همه بخش‌ها  
✅ **Responsive Design** با Bootstrap 5 RTL  
✅ **AJAX Operations** برای UX بهتر  

### بهبودهای پیشنهادی:

🔸 جایگزینی mysqli با PDO  
🔸 افزودن Transaction Management  
🔸 پیاده‌سازی سیستم کد تخفیف  
🔸 اتصال به درگاه پرداخت واقعی  
🔸 Caching برای بهبود Performance  
🔸 Unit Testing با PHPUnit  

---

**تهیه‌کننده:** AI Assistant (Kiro)  
**تاریخ:** 28 اوت 2026  
**نسخه مستندات:** 1.0.0  

---

© 2026 Velora Shop - تمامی حقوق محفوظ است