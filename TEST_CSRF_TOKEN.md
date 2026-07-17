# تست CSRF Token

## مشکل فعلی:
هنگام ارسال فرم پروفایل، خطای "درخواست نامعتبر است" نمایش داده می‌شود.

## علت‌های احتمالی:

### 1. CSRF Token وجود ندارد
- بررسی کنید در فرم این خط وجود دارد:
```html
<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
```

### 2. Session از بین رفته
- احتمالاً session timeout شده
- یا session در حین کار پاک شده

### 3. چند تب باز است
- اگر در چند تب سایت را باز کرده باشید، token ممکن است تغییر کند

## راه‌حل:

### گزینه 1: دیباگ کردن (موقت)
در فایل `src/Controllers/UserController.php`، قبل از چک CSRF این خطوط را اضافه کن:

```php
// DEBUG - بعد از تست حذف کن!
error_log("POST csrf_token: " . ($_POST['csrf_token'] ?? 'NOT SET'));
error_log("SESSION csrf_token: " . ($_SESSION['csrf_token'] ?? 'NOT SET'));
error_log("Match: " . (($_POST['csrf_token'] ?? '') === ($_SESSION['csrf_token'] ?? '') ? 'YES' : 'NO'));
```

بعد لاگ‌ها را در `logs/error.log` چک کن.

### گزینه 2: تولید مجدد Token (راه حل قطعی)
در فایل `src/Views/pages/profile.php` تغییر بده به:

```php
// همیشه token جدید بساز
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
```

### گزینه 3: حذف چک CSRF (غیر امن - توصیه نمی‌شود!)
```php
// این کار را نکن! فقط برای تست
// if (empty($csrfToken) || ...) {
//     $_SESSION['error'] = 'درخواست نامعتبر است.';
//     header('Location: ' . BASE_URL . '/profile');
//     exit;
// }
```

## تست:

1. صفحه پروفایل را باز کن
2. در Console مرورگر این را بزن:
```javascript
console.log(document.querySelector('input[name="csrf_token"]').value);
```
3. اگر خالی بود → مشکل در تولید token
4. اگر پر بود → مشکل در session یا مقایسه

## حذف JavaScript (اگر می‌خواهی):

### فایل‌هایی که باید حذف کنی:
```
public/assets/js/profile-protection.js
```

### در profile.php حذف کن:
```html
<!-- بارگذاری اسکریپت محافظت از فرم -->
<script src="<?= BASE_URL ?>/assets/js/profile-protection.js"></script>
```

**نکته:** JavaScript فقط برای تجربه کاربری بهتر است (تایمر، لودینگ، پیام‌های فوری). امنیت واقعی در PHP است!
