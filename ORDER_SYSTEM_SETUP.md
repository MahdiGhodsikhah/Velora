# راهنمای سیستم سفارش‌گیری و تکمیل خرید

## نصب و راه‌اندازی ✅

### ۱. به‌روزرسانی دیتابیس

فیلدهای `shipping_address` و `postal_code` به جدول `orders` اضافه شده‌اند.

برای اعمال تغییرات:
```bash
php config/update_orders_table.php
```

✅ **این مرحله با موفقیت انجام شده است**

### ۲. فایل‌های ایجاد شده

#### کنترلرها:
- ✅ `src/Controllers/CheckoutController.php` - کنترلر تکمیل خرید و ثبت سفارش

#### مدل‌ها:
- ✅ `src/Models/OrderModel.php` - مدل سفارشات

#### ویوها:
- ✅ `src/Views/pages/checkout.php` - صفحه تکمیل خرید
- ✅ `src/Views/pages/orders.php` - صفحه لیست سفارشات

#### تغییرات:
- ✅ `config/routes.php` - افزودن روت‌های checkout و orders
- ✅ `public/index.php` - افزودن CheckoutController و OrderModel به autoloader
- ✅ `src/Models/UserModel.php` - افزودن متد updateUserAddress
- ✅ `src/Controllers/UserController.php` - افزودن متد orders

---

## نحوه استفاده 🛒

### فرآیند خرید

#### مرحله ۱: افزودن به سبد خرید
کاربر محصولات را به سبد خرید اضافه می‌کند
```
/cart
```

#### مرحله ۲: تکمیل خرید
کاربر روی دکمه "تکمیل خرید" کلیک می‌کند و به صفحه checkout می‌رود
```
/checkout
```

**در این صفحه:**
- اگر کاربر لاگین نکرده باشد → هدایت به صفحه ورود
- اگر سبد خرید خالی باشد → بازگشت به صفحه سبد خرید
- نمایش فرم آدرس و کد پستی:
  - اگر کاربر قبلاً آدرس ثبت کرده → نمایش آدرس قبلی
  - اگر ثبت نکرده → فیلدهای خالی برای ثبت
- انتخاب روش پرداخت (آنلاین / نقدی)
- امکان ثبت یادداشت سفارش

#### مرحله ۳: ثبت سفارش
با کلیک روی "ثبت نهایی سفارش":

1. اعتبارسنجی داده‌ها (آدرس و کد پستی ۱۰ رقمی)
2. ایجاد سفارش در جدول `orders`
3. ثبت آیتم‌های سفارش در جدول `order_items`
4. کاهش موجودی محصولات
5. به‌روزرسانی آدرس و کد پستی در پروفایل کاربر
6. پاک کردن سبد خرید
7. هدایت به صفحه سفارشات

#### مرحله ۴: مشاهده سفارشات
کاربر می‌تواند سفارشات خود را مشاهده کند
```
/orders
```

**اطلاعات نمایش داده شده:**
- شماره سفارش
- تاریخ و ساعت ثبت
- وضعیت سفارش (در انتظار، در حال پردازش، ارسال شده، تحویل داده شده، لغو شده، مرجوع شده)
- وضعیت پرداخت (پرداخت نشده، پرداخت شده، بازپرداخت شده)
- تعداد محصولات
- هزینه ارسال
- آدرس و کد پستی ارسال
- مبلغ کل

---

## ساختار دیتابیس 📊

### جدول orders

```sql
CREATE TABLE orders (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  order_number VARCHAR(30) UNIQUE NOT NULL,
  status ENUM('pending','processing','shipped','delivered','cancelled','refunded') DEFAULT 'pending',
  total_amount BIGINT UNSIGNED NOT NULL,
  discount_amt BIGINT UNSIGNED DEFAULT 0,
  shipping_cost BIGINT UNSIGNED DEFAULT 0,
  shipping_address TEXT NULL,              -- ✅ جدید
  postal_code VARCHAR(10) NULL,            -- ✅ جدید
  payment_method VARCHAR(50) NULL,
  payment_status ENUM('unpaid','paid','refunded') DEFAULT 'unpaid',
  notes TEXT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);
```

### جدول order_items

```sql
CREATE TABLE order_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NOT NULL,
  product_name VARCHAR(200) NOT NULL,
  unit_price BIGINT UNSIGNED NOT NULL,
  quantity SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  subtotal BIGINT UNSIGNED NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id)
);
```

---

## وضعیت‌های سفارش 📦

| وضعیت | توضیح | رنگ |
|------|------|-----|
| `pending` | در انتظار بررسی | نارنجی |
| `processing` | در حال پردازش | آبی |
| `shipped` | ارسال شده | بنفش |
| `delivered` | تحویل داده شده | سبز |
| `cancelled` | لغو شده | قرمز |
| `refunded` | مرجوع شده | خاکستری |

---

## وضعیت‌های پرداخت 💳

| وضعیت | توضیح |
|------|------|
| `unpaid` | پرداخت نشده |
| `paid` | پرداخت شده |
| `refunded` | بازپرداخت شده |

---

## محاسبات قیمت 💰

```php
$subtotal = مجموع قیمت محصولات
$shipping = ($subtotal > 500000) ? 0 : 30000  // ارسال رایگان برای خرید بالای ۵۰۰ هزار تومان
$tax = $subtotal * 0.09  // مالیات ۹٪
$total = $subtotal + $shipping + $tax
```

---

## API Endpoints 🔗

### صفحات

```
GET  /cart          - سبد خرید
GET  /checkout      - تکمیل خرید (نیاز به احراز هویت)
GET  /orders        - لیست سفارشات (نیاز به احراز هویت)
```

### عملیات AJAX

```
POST /checkout/process  - ثبت سفارش
  Parameters:
    - address (required)
    - postal_code (required, 10 digits)
    - payment_method (online/cash)
    - notes (optional)
  
  Response:
    {
      "success": true,
      "message": "سفارش شما با موفقیت ثبت شد",
      "order_id": 123,
      "order_number": "ORD-20260721-A1B2C3D4",
      "redirect": "/orders"
    }
```

---

## امنیت 🔒

### اقدامات امنیتی اعمال شده:

1. **احراز هویت**: فقط کاربران لاگین‌شده می‌توانند checkout کنند
2. **اعتبارسنجی ورودی**:
   - آدرس نباید خالی باشد
   - کد پستی باید دقیقاً ۱۰ رقم باشد
3. **Transaction**: تمام عملیات ثبت سفارش در یک Transaction انجام می‌شود
4. **بررسی موجودی**: قبل از ثبت سفارش، موجودی محصولات بررسی می‌شود
5. **Escape خروجی**: تمام داده‌های نمایشی با Security::e() محافظت شده‌اند

---

## توسعه‌های آینده 🚀

### پیشنهادات برای بهبود:

1. **درگاه پرداخت**: اتصال به درگاه‌های پرداخت آنلاین (زرین‌پال، پی‌دات‌آی‌آر)
2. **پیگیری سفارش**: سیستم مراحل ارسال با بارکد پیگیری
3. **فاکتور PDF**: دانلود فاکتور سفارش به صورت PDF
4. **ایمیل نوتیفیکیشن**: ارسال ایمیل پس از ثبت و تغییر وضعیت سفارش
5. **پنل ادمین**: مدیریت سفارشات از پنل ادمین
6. **آدرس‌های متعدد**: امکان ذخیره چندین آدرس
7. **کد تخفیف**: سیستم کوپن و تخفیف
8. **بازگشت وجه**: سیستم درخواست مرجوعی کالا

---

## تست عملکرد ✔️

### سناریوی تست:

1. ✅ افزودن محصول به سبد خرید
2. ✅ کلیک روی "تکمیل خرید"
3. ✅ بررسی redirect به login (در صورت عدم ورود)
4. ✅ ورود به سیستم
5. ✅ مشاهده فرم checkout با آدرس قبلی (در صورت وجود)
6. ✅ ثبت/ویرایش آدرس و کد پستی
7. ✅ انتخاب روش پرداخت
8. ✅ کلیک روی "ثبت نهایی سفارش"
9. ✅ مشاهده پیام موفقیت
10. ✅ redirect به صفحه سفارشات
11. ✅ مشاهده سفارش ثبت شده با تمام جزئیات

---

## پشتیبانی 📞

در صورت بروز مشکل:
- بررسی logs پروژه
- بررسی console مرورگر برای خطاهای JavaScript
- اطمینان از اجرای اسکریپت به‌روزرسانی دیتابیس

---

**تاریخ تکمیل:** ۲۱ ژوئیه ۲۰۲۶  
**نسخه:** 1.0.0  
**وضعیت:** ✅ آماده به استفاده
