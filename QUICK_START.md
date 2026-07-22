# راه‌اندازی سریع سیستم تم دینامیک

## مراحل نصب (به ترتیب)

### ✅ مرحله 1: صفحه راهنمای نصب

در مرورگر آدرس زیر را باز کنید:
```
http://localhost/Velora/public/theme-setup-guide.php
```

یا مستقیماً اسکریپت نصب را اجرا کنید:
```
http://localhost/Velora/public/setup-theme.php
```

این کار جدول `site_settings` را ایجاد و تنظیمات اولیه را اعمال می‌کند.

---

### ✅ مرحله 2: تست سیستم

برای اطمینان از نصب صحیح:
```
http://localhost/Velora/public/test-theme.php
```

---

### ✅ مرحله 3: ورود به پنل ادمین

با حساب ادمین وارد شوید:
```
نام کاربری: admin
رمز عبور: [رمز ادمین شما]
```

---

### ✅ مرحله 4: دسترسی به تنظیمات تم

به آدرس زیر بروید:
```
http://localhost/Velora/public/admin/theme-settings
```

یا از منوی پنل کاربری > مدیریت تم

---

### ✅ مرحله 5: تنظیمات پیشنهادی

**برای شروع:**
- ☑️ تشخیص خودکار تم: **فعال**
- ☑️ اجازه انتخاب توسط کاربر: **فعال**
- تم پیش‌فرض: **پاییز (autumn)**

---

## تست عملکرد

### 1️⃣ تست کلی سیستم
```
http://localhost/Velora/public/
```
باید تم پاییزی را ببینید (رنگ‌های نارنجی و قهوه‌ای)

---

### 2️⃣ تست انتخاب تم توسط کاربر
- در هدر سایت روی آیکون 🎨 کلیک کنید
- یک تم را انتخاب کنید
- باید بدون رفرش صفحه تم تغییر کند

---

### 3️⃣ تست تم محصول
```
http://localhost/Velora/public/products/nike-autumn-hoodie
```
باید تم بر اساس فصل محصول (پاییز) اعمال شود

---

### 4️⃣ تست فیلتر فصلی
```
http://localhost/Velora/public/products?season=winter
```
باید تم به زمستانی (آبی) تغییر کند

---

## تست API

از کنسول مرورگر (F12):

```javascript
// تست تشخیص فصل
fetch('/Velora/public/api/detect-season')
  .then(r => r.json())
  .then(d => console.log('فصل جاری:', d.season));

// تست تغییر تم
window.changeTheme('winter');
// منتظر 2 ثانیه بمانید
window.changeTheme('spring');
```

---

## مشکلات رایج

### ❌ تم اعمال نمی‌شود
**راه‌حل:**
1. کش مرورگر را پاک کنید (Ctrl+Shift+Del)
2. مطمئن شوید اسکریپت نصب اجرا شده
3. Console مرورگر را برای خطاها بررسی کنید

### ❌ ویجت انتخاب تم نمایش داده نمی‌شود
**راه‌حل:**
- به پنل ادمین بروید
- "اجازه انتخاب توسط کاربر" را فعال کنید

### ❌ صفحه 404 برای فایل‌های نصب
**راه‌حل:**
- از مسیر صحیح استفاده کنید:
  - `http://localhost/Velora/public/setup-theme.php`
  - `http://localhost/Velora/public/test-theme.php`
- یا از صفحه راهنما استفاده کنید:
  - `http://localhost/Velora/public/theme-setup-guide.php`

### ❌ صفحه 404 برای admin/theme-settings
**راه‌حل:**
- از ورود با حساب admin اطمینان حاصل کنید
- فایل `config/routes.php` را بررسی کنید

---

## افزودن محصول با فصل

برای تست کامل، یک محصول جدید با فصل زمستانی اضافه کنید:

```sql
INSERT INTO products (
  category_id, name, slug, description, 
  price, stock_qty, main_image, season, is_active
) VALUES (
  1, 'کاپشن زمستانی', 'winter-jacket',
  'کاپشن گرم زمستانی', 
  1500000, 10, '/assets/images/products/winter-jacket.jpg',
  'winter', 1
);
```

سپس محصول را مشاهده کنید - باید تم زمستانی اعمال شود.

---

## چک‌لیست نهایی

- ✅ اسکریپت setup_theme_system.php اجرا شد
- ✅ جدول site_settings ایجاد شد
- ✅ تنظیمات در پنل ادمین قابل دسترسی است
- ✅ ویجت انتخاب تم در هدر نمایش داده می‌شود
- ✅ تم محصولات بر اساس season عمل می‌کند
- ✅ فیلتر فصلی تم را تغییر می‌دهد
- ✅ API های تم پاسخ می‌دهند

---

## دستورات مفید

```javascript
// تغییر سریع تم از Console
changeTheme('spring');  // بهار
changeTheme('summer');  // تابستان
changeTheme('autumn');  // پاییز
changeTheme('winter');  // زمستان

// دریافت تم فعلی
themeManager.getTheme();

// ریست به حالت پیش‌فرض
themeManager.resetTheme();

// پیش‌بارگذاری همه تم‌ها
themeManager.preloadAllThemes();
```

---

## آماده برای تولید (Production)

قبل از انتقال به سرور:

1. ✅ تمام فایل‌های CSS تم را minify کنید
2. ✅ JavaScript را minify کنید
3. ✅ عکس‌های فصلی برای هر تم اضافه کنید
4. ✅ تنظیمات Cache را فعال کنید
5. ✅ HTTPS را فعال کنید
6. ✅ Debug mode را غیرفعال کنید

---

**همه چیز آماده است! 🎉**

اگر سوالی دارید، به فایل `THEME_SYSTEM_README.md` مراجعه کنید.
