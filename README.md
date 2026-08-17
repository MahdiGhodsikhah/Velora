# 📊 تحلیل جامع پروژه Velora - فروشگاه پاییزی شگفت‌انگیز

## 📑 فهرست مطالب
1. [معرفی کلی](#معرفی-کلی)
2. [معماری و ساختار](#معماری-و-ساختار)
3. [لایه دیتابیس](#لایه-دیتابیس)
4. [لایه Backend](#لایه-backend)
5. [لایه Frontend](#لایه-frontend)
6. [سیستم امنیتی](#سیستم-امنیتی)
7. [ویژگی‌های کلیدی](#ویژگی‌های-کلیدی)
8. [نقاط قوت](#نقاط-قوت)
9. [نقاط ضعف و محدودیت‌ها](#نقاط-ضعف-و-محدودیت‌ها)
10. [پیشنهادات بهبود](#پیشنهادات-بهبود)

---

## 🎯 معرفی کلی

**Velora** یک فروشگاه آنلاین مد و پوشاک با تم فصلی است که به صورت کامل با PHP خالص (بدون فریمورک) توسعه یافته است.

### ویژگی منحصر به فرد
- **سیستم تم دینامیک چهار فصل**: پاییز (Autumn)، زمستان (Winter)، بهار (Spring)، تابستان (Summer)
- تغییر خودکار تم بر اساس فصل سال جاری
- امکان انتخاب دستی تم توسط کاربر
- محصولات دسته‌بندی شده بر اساس فصل

### تکنولوژی‌های استفاده شده
**Backend:**
- PHP 8.3+ (Pure PHP - بدون فریمورک)
- MySQL 8.4 با MySQLi
- Session-based Authentication

**Frontend:**
- HTML5, CSS3, JavaScript (ES6+)
- jQuery 3.x
- Bootstrap 5 (Grid System)
- Slick Carousel
- Font Awesome Icons

**Server:**
- Apache/WAMP
- mod_rewrite برای URL Rewriting

---

## 🏗️ معماری و ساختار

### الگوی معماری: MVC (Model-View-Controller) سفارشی

```
Velora/
├── config/               # تنظیمات پروژه
│   ├── database.php     # اتصال DB و توابع helper
│   ├── routes.php       # سیستم روتینگ
│   └── theme.php        # تنظیمات تم
├── public/              # فایل‌های عمومی (Document Root)
│   ├── index.php        # Front Controller
│   ├── .htaccess        # Security & Routing
│   ├── assets/
│   │   ├── css/        # Stylesheets
│   │   ├── js/         # JavaScript
│   │   ├── images/     # تصاویر
│   │   └── icons/      # SVG Icons
│   └── uploads/        # آپلودهای کاربر
└── src/                # کد منبع اصلی
    ├── Controllers/    # کنترلرها (Logic)
    ├── Models/         # مدل‌ها (Data Layer)
    ├── Libs/           # کتابخانه‌های کمکی
    └── Views/          # نماها (Presentation)
        ├── layouts/    # قالب‌های اصلی
        ├── pages/      # صفحات
        ├── partials/   # کامپوننت‌های تکرارشونده
        └── admin/      # پنل ادمین
```

### جریان درخواست (Request Flow)

1. **ورودی**: همه درخواست‌ها به `public/index.php` هدایت می‌شوند (via .htaccess)
2. **Routing**: سیستم روتینگ URL را پارس کرده و کنترلر/اکشن مناسب را پیدا می‌کند
3. **Controller**: کنترلر منطق کسب‌وکار را اجرا می‌کند
4. **Model**: در صورت نیاز با دیتابیس ارتباط برقرار می‌کند
5. **View**: نمای HTML رندر شده و به کاربر برمی‌گردد

---

## 💾 لایه دیتابیس

### ساختار پایگاه داده

پروژه شامل **11 جدول اصلی** است:

#### 1. **users** - کاربران
```sql
- id, username, email, phone, password_hash
- full_name, address, postal_code, job, birth_date
- profile_image, preferred_theme
- role (customer/admin/moderator)
- login_attempts, locked_until (Brute Force Protection)
- created_at, updated_at, last_login
```
**هدف**: مدیریت اطلاعات کاربران، احراز هویت، پروفایل

#### 2. **products** - محصولات
```sql
- id, category_id, name, slug, sku
- description, short_desc
- price, sale_price, discount_pct
- stock_qty, main_image, gallery (JSON)
- rating_avg, rating_count, views
- is_featured, is_active
- season (spring/summer/autumn/winter/all) ⭐ کلیدی
```
**هدف**: ذخیره اطلاعات محصولات با پشتیبانی از فصل‌بندی

#### 3. **categories** - دسته‌بندی‌ها
```sql
- id, name, slug, description, image_url
- parent_id (برای دسته‌های چندسطحی)
- sort_order, is_active
```
**هدف**: سازماندهی محصولات در دسته‌های مختلف

#### 4. **orders** - سفارشات
```sql
- id, user_id, order_number
- status (pending/processing/shipped/delivered/cancelled/refunded)
- total_amount, discount_amt, shipping_cost
- shipping_address, postal_code
- payment_method, payment_status
- created_at, updated_at
```
**هدف**: ثبت و پیگیری سفارشات

#### 5. **order_items** - اقلام سفارش
```sql
- id, order_id, product_id
- product_name, unit_price, quantity, subtotal
```
**هدف**: جزئیات محصولات داخل هر سفارش

#### 6. **cart** - سبد خرید
```sql
- id, user_id, product_id, quantity, added_at
- UNIQUE KEY (user_id, product_id)
```
**هدف**: سبد خرید موقت کاربران

#### 7. **wishlist** - علاقه‌مندی‌ها
```sql
- id, user_id, product_id, added_at
- UNIQUE KEY (user_id, product_id)
```
**هدف**: لیست محصولات مورد علاقه کاربر

#### 8. **reviews** - نظرات و امتیازدهی
```sql
- id, product_id, user_id
- author_name, rating (1-5), title, body
- is_approved (نظارت ادمین)
- created_at
```
**هدف**: سیستم ریویو و امتیازدهی محصولات

#### 9. **banners** - بنرهای تبلیغاتی
```sql
- id, title, subtitle, image_url, link_url, btn_text
- position (hero/mid/sidebar)
- sort_order, is_active
```
**هدف**: مدیریت بنرهای اسلایدر و تبلیغات

#### 10. **site_settings** - تنظیمات سایت
```sql
- id, setting_key, setting_value
- setting_type (text/number/boolean/json)
- description
```
**نمونه تنظیمات**:
- `active_theme`: تم فعال (autumn/winter/spring/summer)
- `theme_auto_detect`: تشخیص خودکار تم بر اساس فصل
- `theme_allow_user_choice`: اجازه انتخاب تم به کاربر

#### 11. **user_sessions** - نشست‌های کاربر
```sql
- id (token SHA-256), user_id
- ip_address, user_agent
```
- expires_at, created_at
```
**هدف**: مدیریت امن session‌ها (اختیاری - در حال حاضر از PHP session استفاده می‌شود)

### لایه انتزاعی دیتابیس (database.php)

**توابع Helper برای MySQLi:**
```php
db_connect()         // اتصال singleton به DB
db_escape($value)    // فرار از رشته (SQL Injection Prevention)
db_query($sql)       // اجرای کوئری
db_fetch_one($sql)   // دریافت یک رکورد
db_fetch_all($sql)   // دریافت همه رکوردها
db_insert($sql)      // درج و برگرداندن ID
db_affected_rows()   // تعداد ردیف‌های تاثیر یافته
```

**نکات امنیتی:**
- استفاده از `mysqli_real_escape_string()` برای جلوگیری از SQL Injection
- مدیریت خطا با `error_log()` به جای نمایش مستقیم
- Connection Pooling با استفاده از static variable

---

## 🔧 لایه Backend

### Controllers (کنترلرها)

پروژه شامل **10 کنترلر** است:

#### 1. **HomeController** - صفحه اصلی
**مسئولیت‌ها:**
- نمایش محصولات ویژه به تفکیک فصل (8 محصول از هر فصل)
- دریافت دسته‌بندی‌ها
- نمایش بنرهای اسلایدر
- پردازش گالری JSON محصولات

**متدها:**
- `index()`: صفحه اصلی با محصولات فصلی

#### 2. **ProductController** - محصولات
**مسئولیت‌ها:**
- لیست محصولات با فیلتر (دسته‌بندی، فصل، جستجو)
- نمایش جزئیات محصول
- سیستم نظرات و امتیازدهی
- افزایش شمارنده بازدید

**متدها:**
- `index()`: لیست محصولات
- `show($slug)`: جزئیات محصول
- `addReview()`: افزودن نظر (نیاز به احراز هویت)

#### 3. **AuthController** - احراز هویت
**مسئولیت‌ها:**
- ورود/ثبت‌نام کاربران
- مدیریت session
- Brute Force Protection
- تولید نام کاربری یکتا برای موبایل

**متدها:**
- `loginForm()`, `login()`: ورود
- `registerForm()`, `register()`: ثبت‌نام
- `logout()`: خروج
- Rate Limiting: حداکثر 5 تلاش در 15 دقیقه

**امنیت:**
- CSRF Token validation
- Password Hashing (bcrypt cost=12)
- Session Regeneration بعد از ورود
- قفل حساب بعد از 5 تلاش ناموفق

#### 4. **UserController** - پنل کاربری
**مسئولیت‌ها:**
- داشبورد کاربر (آمار سفارشات، نظرات، علاقه‌مندی‌ها)
- ویرایش پروفایل
- آپلود/حذف عکس پروفایل
- تغییر رمز عبور
- مشاهده لیست سفارشات
- مدیریت علاقه‌مندی‌ها

**متدها:**
- `dashboard()`: صفحه اصلی پنل
- `profile()`: نمایش/ویرایش پروفایل
- `updateProfile()`: ذخیره تغییرات
- `changePassword()`: تغییر رمز
- `uploadProfileImage()`: آپلود عکس
- `orders()`: لیست سفارشات
- `wishlist()`: لیست علاقه‌مندی‌ها

#### 5. **CartController** - سبد خرید
**مسئولیت‌ها:**
- افزودن/حذف/ویرایش محصولات سبد
- محاسبه جمع کل
- اعمال کد تخفیف (آماده - پیاده‌سازی ناقص)

**متدها:**
- `index()`: نمایش سبد
- `add()`: افزودن محصول (AJAX)
- `remove()`: حذف محصول (AJAX)
- `update()`: به‌روزرسانی تعداد
- `applyCoupon()`: اعمال کوپن تخفیف

**نکته**: سبد خرید به صورت session-based برای کاربران مهمان و database-based برای کاربران لاگین شده

#### 6. **CheckoutController** - تکمیل خرید
**مسئولیت‌ها:**
- نمایش صفحه پرداخت
- دریافت اطلاعات ارسال
- ثبت سفارش
- اتصال به درگاه پرداخت (آماده - نیاز به پیاده‌سازی API)

**متدها:**
- `index()`: صفحه checkout
- `process()`: پردازش سفارش

**جریان کار:**
1. بررسی لاگین کاربر
2. دریافت اطلاعات آدرس و روش پرداخت
3. تولید شماره سفارش یکتا
4. ثبت در جدول orders و order_items
5. خالی کردن سبد خرید

#### 7. **WishlistController** - علاقه‌مندی‌ها
**مسئولیت‌ها:**
- افزودن/حذف محصول از لیست علاقه‌مندی‌ها
- دریافت وضعیت wishlist برای sync با UI

**متدها:**
- `toggle()`: toggle کردن محصول (AJAX)
- `getStatus()`: دریافت لیست ID محصولات در wishlist

#### 8. **AdminController** - پنل مدیریت
**مسئولیت‌ها:**
- داشبورد ادمین (آمار کلی)
- CRUD محصولات
- مدیریت سفارشات (تغییر وضعیت، حذف)
- مدیریت نظرات (تایید، ویرایش، حذف)
- تنظیمات تم
- مشاهده کاربران

**متدها:**
- `dashboard()`: صفحه اصلی ادمین
- محصولات: `products()`, `createProduct()`, `editProduct()`, `updateProduct()`, `deleteProduct()`
- سفارشات: `orders()`, `viewOrder()`, `updateOrderStatus()`, `deleteOrder()`
- نظرات: `reviews()`, `editReview()`, `approveReview()`, `deleteReview()`
- تنظیمات: `themeSettings()` (GET/POST)
- کاربران: `users()`

**امنیت**: همه اکشن‌های ادمین نیاز به بررسی role='admin' دارند

#### 9. **AboutController** - درباره ما
**مسئولیت‌ها:**
- نمایش صفحه درباره ما با آمار شرکت

#### 10. **ErrorController** - مدیریت خطاها
**مسئولیت‌ها:**
- نمایش صفحه 404

---

### Models (مدل‌ها)

#### 1. **UserModel**
**توابع کلیدی:**
```php
findByUsername(), findByEmail(), findByPhone(), findById()
create() // ثبت‌نام
incrementLoginAttempts(), lockAccount(), resetLoginAttempts()
isLocked() // بررسی قفل بودن حساب
updateProfile(), changePassword(), changeUsername()
getTotalOrders(), getTotalReviews(), getWishlistCount()
getRecentOrders(), getRecentReviews(), getWishlist()
```

#### 2. **ProductModel**
**توابع کلیدی:**
```php
getAll(), getFeatured(), getFeaturedBySeason() // ⭐ کلیدی
getBySlug(), getById()
getByCategory(), getBySeason()
getImages(), getReviews()
```
