# 🎨 راهنمای مدیریت تم‌های فصلی - Velora

## نحوه کار سیستم تم

سیستم تم پروژه Velora به صورت خودکار بر اساس فصل سال عوض می‌شود، اما شما می‌تونید به صورت دستی هم اون رو تغییر بدید.

---

## 📋 روش‌های تغییر تم

### 1️⃣ **تغییر خودکار بر اساس فصل (پیش‌فرض)**
سیستم به صورت خودکار بر اساس ماه فعلی، تم رو انتخاب می‌کنه:

- **بهار (Spring)**: فروردین تا خرداد (ماه 3-5)
- **تابستان (Summer)**: تیر تا شهریور (ماه 6-8)
- **پاییز (Autumn)**: مهر تا آذر (ماه 9-11)
- **زمستان (Winter)**: دی تا اسفند (ماه 12-2)

---

### 2️⃣ **تغییر دستی توسط کاربر**
کاربران می‌تونن از منوی Navbar (بخش "تم") تم مورد نظر رو انتخاب کنند:

```
در Navbar کلیک کنید روی: تم 🎨
```

تم انتخاب شده در Session ذخیره می‌شه و تا زمان خروج یا تغییر دستی، ثابت می‌مونه.

---

### 3️⃣ **تغییر دستی از طریق URL**
می‌تونید مستقیماً در URL پارامتر `theme` رو اضافه کنید:

```
http://localhost/Velora/public/?theme=spring
http://localhost/Velora/public/?theme=summer
http://localhost/Velora/public/?theme=autumn
http://localhost/Velora/public/?theme=winter
```

این روش در همه صفحات کار می‌کنه (هم صفحات اصلی، هم صفحات Login/Register).

---

### 4️⃣ **تغییر دستی توسط ادمین (برای همه کاربران)**
ادمین می‌تونه از پنل مدیریت تم پیش‌فرض سایت رو تغییر بده:

1. به پنل ادمین برید:
   ```
   http://localhost/Velora/public/admin
   ```

2. از منوی سمت چپ، روی **"تنظیمات تم"** کلیک کنید

3. یکی از دو گزینه رو انتخاب کنید:
   - **Automatic Mode**: تم بر اساس فصل سال تعیین بشه
   - **Manual Mode**: یک تم خاص رو برای همه انتخاب کنید

4. تغییرات رو ذخیره کنید

این تنظیمات در فایل `config/theme.php` ذخیره می‌شه:

```php
<?php
return [
    'mode' => 'manual',              // automatic یا manual
    'admin_selected_theme' => 'winter'  // spring, summer, autumn, winter یا null
];
```

---

## 🎯 اولویت تم‌ها (از بالا به پایین)

سیستم تم با اولویت زیر کار می‌کنه:

1. **انتخاب کاربر از Navbar** (`$_SESSION['user_selected_theme']`)
2. **تم محصول** (فقط در صفحه محصول: `$_SESSION['product_theme']`)
3. **تم ذخیره شده کاربر در دیتابیس** (ستون `preferred_theme` در جدول `users`)
4. **تم انتخاب شده توسط ادمین** (`config/theme.php`)
5. **تم خودکار بر اساس فصل** (پیش‌فرض)

---

## 🖼️ تصاویر فصلی صفحات Login و Register

تصاویر پس‌زمینه و المان‌های بصری صفحات Login و Register به صورت خودکار بر اساس تم فعال تغییر می‌کنن.

### 📁 ساختار پوشه‌های تصویر:
```
public/assets/images/auth/
├── spring/
│   ├── bg.png
│   ├── trees.png
│   ├── girl.png
│   ├── spring_leaves_01.png
│   ├── spring_leaves_02.png
│   ├── spring_leaves_03.png
│   └── spring_leaves_04.png
├── summer/
│   ├── bg.png
│   ├── trees.png
│   ├── girl.png
│   ├── summer_leaves_01.png
│   ├── summer_leaves_02.png
│   ├── summer_leaves_03.png
│   └── summer_leaves_04.png
├── autumn/
│   ├── bg.png
│   ├── trees.png
│   ├── girl.png
│   ├── autumn_leaves_01.png
│   ├── autumn_leaves_02.png
│   ├── autumn_leaves_03.png
│   └── autumn_leaves_04.png
└── winter/
    ├── bg.png
    ├── trees.png
    ├── girl.png
    ├── winter_leaves_01.png
    ├── winter_leaves_02.png
    ├── winter_leaves_03.png
    └── winter_leaves_04.png
```

---

## 🎨 رنگ‌بندی هر تم

هر تم رنگ‌بندی و استایل خاص خودش رو داره که در فایل‌های زیر تعریف شده:

```
public/assets/css/themes/
├── spring.css    (سبز، صورتی، رنگ‌های بهاری)
├── summer.css    (آبی، زرد، رنگ‌های تابستانی)
├── autumn.css    (نارنجی، قهوه‌ای، رنگ‌های پاییزی)
└── winter.css    (آبی، سفید، رنگ‌های زمستانی)
```

---

## 🛠️ نکات فنی

### چطور ThemeManager کار می‌کنه؟

کلاس `ThemeManager` (فایل: `src/Libs/ThemeManager.php`) مسئول مدیریت تم‌هاست:

```php
// دریافت نمونه ThemeManager (Singleton)
$themeManager = ThemeManager::getInstance();

// دریافت تم فعال
$currentTheme = $themeManager->getActiveTheme(); // 'spring', 'summer', 'autumn', 'winter'

// دریافت مسیر فایل CSS تم
$themeCssPath = $themeManager->getThemeCssPath();

// بررسی معتبر بودن تم
if ($themeManager->isValidTheme('spring')) {
    // تم معتبر است
}

// پاک کردن تم انتخابی کاربر
$themeManager->clearUserSelectedTheme();
```

---

## 📝 مثال: استفاده در View ها

برای استفاده از تم فعال در صفحات، از کد زیر استفاده کنید:

```php
<?php
// دریافت تم فعال
$themeManager = ThemeManager::getInstance();
$currentTheme = $themeManager->getActiveTheme();
?>

<!-- استفاده از تصاویر فصلی -->
<img src="<?= BASE_URL ?>/assets/images/auth/<?= $currentTheme ?>/bg.png" alt="Background">
<img src="<?= BASE_URL ?>/assets/images/auth/<?= $currentTheme ?>/<?= $currentTheme ?>_leaves_01.png" alt="Leaf">
```

---

## 🚀 تست کردن تم‌ها

برای تست کردن سریع همه تم‌ها، URL های زیر رو امتحان کنید:

1. **بهار**: `http://localhost/Velora/public/login?theme=spring`
2. **تابستان**: `http://localhost/Velora/public/login?theme=summer`
3. **پاییز**: `http://localhost/Velora/public/login?theme=autumn`
4. **زمستان**: `http://localhost/Velora/public/login?theme=winter`

همین کار رو برای صفحه Register هم می‌تونید انجام بدید:

```
http://localhost/Velora/public/register?theme=spring
```

---

## ❓ سوالات متداول

### Q: چرا تم عوض نمیشه؟
A: مطمئن شوید که:
1. فایل‌های CSS تم در پوشه `public/assets/css/themes/` وجود دارن
2. تصاویر فصلی در پوشه `public/assets/images/auth/{season}/` موجودن
3. Cache مرورگر رو پاک کنید (`Ctrl + F5`)

### Q: میخوام برای یک کاربر خاص تم رو تغییر بدم؟
A: کاربر باید از منوی Navbar خودش تم رو انتخاب کنه یا میتونید در جدول `users` ستون `preferred_theme` رو تغییر بدید.

### Q: میخوام تم خودکار رو غیرفعال کنم؟
A: از پنل ادمین، وارد "تنظیمات تم" شوید و حالت Manual رو با یک تم مشخص انتخاب کنید.

---

## 📞 پشتیبانی

در صورت بروز مشکل یا سوال، به فایل‌های زیر مراجعه کنید:

- **کلاس مدیریت تم**: `src/Libs/ThemeManager.php`
- **تنظیمات تم**: `config/theme.php`
- **صفحه Login**: `src/Views/pages/login.php`
- **صفحه Register**: `src/Views/pages/register.php`

---

✨ **ساخته شده با عشق برای Velora** ✨
