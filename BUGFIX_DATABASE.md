# 🐛 رفع مشکل Database در CheckoutController و OrderModel

## مشکل

```
Fatal error: Class "Database" not found in CheckoutController.php
Fatal error: Class "Database" not found in OrderModel.php
```

## علت مشکل

این پروژه از **mysqli** و توابع helper استفاده می‌کند، نه از کلاس **PDO/Database**.

### توابع helper موجود در `config/database.php`:
- `db_connect()` - اتصال به دیتابیس
- `db_query($sql)` - اجرای کوئری
- `db_fetch_one($sql)` - دریافت یک رکورد
- `db_fetch_all($sql)` - دریافت همه رکوردها
- `db_insert($sql)` - درج و برگرداندن ID
- `db_escape($value)` - فرار از رشته

## راه‌حل ✅

فایل‌های `CheckoutController.php` و `OrderModel.php` بازنویسی شدند تا از توابع helper استفاده کنند.

### تغییرات در CheckoutController.php

**قبل (اشتباه):**
```php
private PDO $db;

public function __construct() {
    $this->db = Database::getInstance()->getConnection();
}
```

**بعد (صحیح):**
```php
// بدون constructor و بدون property $db
// مستقیماً از توابع helper استفاده می‌شود
```

### تغییرات در OrderModel.php

**قبل (اشتباه):**
```php
private PDO $db;

public function __construct() {
    $this->db = Database::getInstance()->getConnection();
}

// و استفاده از PDO:
$stmt = $this->db->prepare($sql);
$stmt->execute([...]);
```

**بعد (صحیح):**
```php
// بدون constructor و بدون PDO
// استفاده از توابع helper:

public function createOrder(array $orderData): ?int {
    $userId = (int)$orderData['user_id'];
    $orderNumber = db_escape($orderData['order_number']);
    // ...
    
    $sql = "INSERT INTO orders (...) VALUES (...)";
    return db_insert($sql);
}
```

## فایل‌های به‌روزرسانی شده ✅

1. ✅ `src/Controllers/CheckoutController.php` - حذف PDO و استفاده از mysqli
2. ✅ `src/Models/OrderModel.php` - حذف PDO و استفاده از mysqli

## تست رفع مشکل

برای اطمینان از رفع مشکل، این فایل را در مرورگر باز کنید:

```
http://localhost/Velora/test_checkout_fix.php
```

این فایل موارد زیر را بررسی می‌کند:
- ✅ وجود توابع database
- ✅ اتصال به دیتابیس
- ✅ بارگذاری OrderModel
- ✅ بارگذاری CheckoutController
- ✅ وجود فیلدهای جدول orders
- ✅ تعداد سفارشات

## تست کامل سیستم

### مرحله 1: ورود به سیستم
```
http://localhost/Velora/public/login
```

### مرحله 2: افزودن محصول به سبد
```
http://localhost/Velora/public/products
```
روی یک محصول کلیک کنید و "افزودن به سبد خرید" را بزنید.

### مرحله 3: مشاهده سبد خرید
```
http://localhost/Velora/public/cart
```

### مرحله 4: تکمیل خرید
روی دکمه "تکمیل خرید" کلیک کنید:
```
http://localhost/Velora/public/checkout
```

باید فرم آدرس و کد پستی نمایش داده شود بدون خطا.

### مرحله 5: ثبت سفارش
فرم را پر کنید و "ثبت نهایی سفارش" را بزنید.

### مرحله 6: مشاهده سفارشات
```
http://localhost/Velora/public/orders
```

باید لیست سفارشات نمایش داده شود بدون خطا.

## نتیجه ✅

مشکل رفع شد! همه فایل‌ها اکنون از mysqli و توابع helper استفاده می‌کنند.

---

**تاریخ رفع مشکل:** 21 ژوئیه 2026  
**وضعیت:** ✅ رفع شده
