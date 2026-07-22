# 🎨 راهنمای نصب سیستم تم دینامیک

## ⚠️ آدرس‌های صحیح نصب

همه فایل‌های نصب از طریق پوشه **public** در دسترس هستند:

---

## 🚀 مراحل نصب

### 📱 روش 1: استفاده از صفحه راهنمای تعاملی (پیشنهادی)

بهترین و آسان‌ترین روش:

```
http://localhost/Velora/public/theme-setup-guide.php
```

این صفحه تمام مراحل را به صورت تعاملی و گام‌به‌گام راهنمایی می‌کند.

---

### 🔧 روش 2: نصب دستی

#### مرحله 1: نصب پایگاه داده
```
http://localhost/Velora/public/setup-theme.php
```
✅ جدول site_settings ایجاد می‌شود  
✅ ستون season به جدول products اضافه می‌شود  
✅ تنظیمات پیش‌فرض اعمال می‌شوند  

#### مرحله 2: تست سیستم
```
http://localhost/Velora/public/test-theme.php
```
✅ بررسی تمام کامپوننت‌ها  
✅ تست فایل‌های CSS  
✅ تست کلاس‌های PHP  
✅ نمایش آمار کامل  

#### مرحله 3: پنل مدیریت تم
```
http://localhost/Velora/public/admin/theme-settings
```
⚠️ نیاز به ورود با حساب ادمین

#### مرحله 4: به‌روزرسانی محصولات (اختیاری)
```
http://localhost/Velora/public/update-products-season.php
```
✅ تنظیم فصل برای محصولات موجود  
✅ رابط کاربری ساده  

---

## 📋 چک‌لیست پس از نصب

- [ ] اسکریپت نصب با موفقیت اجرا شد
- [ ] تست سیستم بدون خطا انجام شد (100% موفق)
- [ ] ویجت انتخاب تم در هدر نمایش داده می‌شود
- [ ] تغییر تم از هدر کار می‌کند
- [ ] تم محصول بر اساس فصل تغییر می‌کند
- [ ] فیلتر فصلی در لیست محصولات کار می‌کند
- [ ] پنل ادمین قابل دسترسی است

---

## 🧪 تست سریع

### تست 1: تغییر تم از Console
باز کنید: `F12` > Console
```javascript
changeTheme('winter');  // آبی
changeTheme('spring');  // سبز
changeTheme('summer');  // زرد
changeTheme('autumn');  // نارنجی
```

### تست 2: فیلتر فصلی
```
http://localhost/Velora/public/products?season=winter
http://localhost/Velora/public/products?season=spring
http://localhost/Velora/public/products?season=summer
http://localhost/Velora/public/products?season=autumn
```

### تست 3: API
```javascript
fetch('/Velora/public/api/detect-season')
  .then(r => r.json())
  .then(d => console.log('فصل جاری:', d.season));
```

---

## ❌ مشکلات رایج

### خطای 404 برای فایل‌های نصب
**علت**: مسیر اشتباه  
**راه‌حل**: از مسیرهای بالا استفاده کنید (با `public/`)

### ویجت تم نمایش داده نمی‌شود
**علت**: تنظیمات ادمین  
**راه‌حل**: "اجازه انتخاب توسط کاربر" را فعال کنید

### تم اعمال نمی‌شود
**راه‌حل**:
1. کش مرورگر را پاک کنید (Ctrl+Shift+Del)
2. Console را برای خطا بررسی کنید
3. تست سیستم را اجرا کنید

---

## 📚 مستندات کامل

- **THEME_SYSTEM_README.md** - راهنمای کامل فنی
- **QUICK_START.md** - شروع سریع
- **IMPLEMENTATION_SUMMARY.md** - خلاصه پیاده‌سازی

---

## 🎯 تنظیمات پیشنهادی

برای شروع:
- ✅ تشخیص خودکار: **فعال**
- ✅ انتخاب توسط کاربر: **فعال**
- تم پیش‌فرض: **پاییز (autumn)**

---

## 🔗 لینک‌های سریع

| نام | آدرس |
|-----|------|
| 🏠 صفحه اصلی | `http://localhost/Velora/public/` |
| 📋 راهنمای نصب | `http://localhost/Velora/public/theme-setup-guide.php` |
| ⚙️ نصب | `http://localhost/Velora/public/setup-theme.php` |
| 🧪 تست | `http://localhost/Velora/public/test-theme.php` |
| 🎨 پنل تم | `http://localhost/Velora/public/admin/theme-settings` |
| 📦 محصولات | `http://localhost/Velora/public/update-products-season.php` |

---

## 💡 نکات مهم

1. **همیشه** ابتدا نصب را اجرا کنید
2. **حتماً** تست سیستم را انجام دهید
3. برای تغییرات، کش مرورگر را پاک کنید
4. Console مرورگر را برای خطایابی بررسی کنید

---

**نسخه:** 1.0.0  
**آخرین به‌روزرسانی:** 1403/05/02  
**وضعیت:** ✅ آماده استفاده
