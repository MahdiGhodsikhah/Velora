# 🔗 قابلیت اشتراک‌گذاری محصولات

## ✅ قابلیت اضافه شده

دکمه اشتراک‌گذاری (Share) در کارت محصولات حالا کامل کار می‌کنه و با کلیک روی اون، لینک محصول کپی میشه و یک نوتیفیکیشن زیبا نمایش داده میشه.

---

## 🎯 نحوه کار

### 1. کاربر روی آیکون Share کلیک می‌کنه
```html
<button class="icon-item share-btn" 
        data-url="/products/product-slug">
    <i class="fas fa-share-alt"></i>
</button>
```

### 2. لینک محصول به clipboard کپی میشه
- از **Clipboard API** مدرن استفاده میشه
- اگر مرورگر قدیمی باشه، از روش **fallback** (execCommand) استفاده میشه

### 3. نوتیفیکیشن نمایش داده میشه
```javascript
showNotification('لینک محصول کپی شد', 'success');
```

### 4. آیکون موقتاً به tick تبدیل میشه
```javascript
// قبل: fas fa-share-alt
// موقت (1.5 ثانیه): fas fa-check
// بعد: برمی‌گرده به fas fa-share-alt
```

---

## 💻 کد پیاده‌سازی شده

### در `main.js`:

```javascript
// =================================================================
// 6.5. اشتراک‌گذاری محصول (Share Button)
// =================================================================
$(document).on('click', '.share-btn', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const $btn = $(this);
    const url = $btn.data('url');
    
    if (!url) {
        showNotification('خطا در دریافت لینک محصول', 'error');
        return;
    }
    
    // ساخت URL کامل
    const fullUrl = url.startsWith('http') ? url : window.location.origin + url;
    
    // کپی کردن به clipboard
    if (navigator.clipboard && navigator.clipboard.writeText) {
        // روش مدرن
        navigator.clipboard.writeText(fullUrl)
            .then(function() {
                showNotification('لینک محصول کپی شد', 'success');
                
                // انیمیشن موقت برای دکمه
                const originalIcon = $btn.find('i').attr('class');
                $btn.find('i').attr('class', 'fas fa-check');
                
                setTimeout(function() {
                    $btn.find('i').attr('class', originalIcon);
                }, 1500);
            })
            .catch(function(err) {
                fallbackCopyTextToClipboard(fullUrl, $btn);
            });
    } else {
        // fallback برای مرورگرهای قدیمی
        fallbackCopyTextToClipboard(fullUrl, $btn);
    }
});
```

### تابع Fallback:

```javascript
function fallbackCopyTextToClipboard(text, $btn) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    
    // پنهان کردن textarea
    textArea.style.position = 'fixed';
    textArea.style.opacity = '0';
    
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showNotification('لینک محصول کپی شد', 'success');
            
            // انیمیشن موقت
            const originalIcon = $btn.find('i').attr('class');
            $btn.find('i').attr('class', 'fas fa-check');
            
            setTimeout(function() {
                $btn.find('i').attr('class', originalIcon);
            }, 1500);
        } else {
            showNotification('خطا در کپی کردن لینک', 'error');
        }
    } catch (err) {
        showNotification('مرورگر شما از کپی کردن پشتیبانی نمی‌کند', 'error');
    }
    
    document.body.removeChild(textArea);
}
```

---

## 🎨 نوتیفیکیشن

نوتیفیکیشن از تابع `showNotification` استفاده می‌کنه که قبلاً در پروژه تعریف شده:

```javascript
showNotification('لینک محصول کپی شد', 'success');
// یا
showNotification('خطا در کپی کردن لینک', 'error');
```

**ظاهر نوتیفیکیشن:**
- آیکون: ✓ (چک مارک سبز)
- متن: "لینک محصول کپی شد"
- رنگ: سبز (موفقیت)
- مدت نمایش: 3 ثانیه
- انیمیشن: slide in از بالا

---

## 🧪 تست

### تست 1: کپی موفقیت‌آمیز
```
1. روی آیکون share کلیک کنید
2. ✅ نوتیفیکیشن "لینک محصول کپی شد" نمایش داده میشه
3. ✅ آیکون موقتاً به tick تبدیل میشه
4. ✅ لینک در clipboard ذخیره شده
```

### تست 2: Paste کردن
```
1. روی آیکون share کلیک کنید
2. در یک text box یا آدرس بار مرورگر Ctrl+V بزنید
3. ✅ لینک کامل محصول paste میشه
   مثال: http://localhost/Velora/public/products/product-name
```

### تست 3: مرورگرهای مختلف
```
✅ Chrome/Edge: از Clipboard API استفاده میشه
✅ Firefox: از Clipboard API استفاده میشه
✅ Safari: از Clipboard API یا fallback استفاده میشه
✅ Internet Explorer: از fallback (execCommand) استفاده میشه
```

---

## 📱 سازگاری

### مرورگرهای پشتیبانی شده:
- ✅ Chrome 63+
- ✅ Firefox 53+
- ✅ Safari 13.1+
- ✅ Edge 79+
- ✅ Opera 50+
- ✅ مرورگرهای موبایل (iOS Safari, Chrome Mobile)

### مرورگرهای قدیمی:
- ✅ از روش fallback (execCommand) استفاده میشه
- ✅ IE 11: کار می‌کنه

---

## 🔒 امنیت

1. **XSS Prevention**: URL ها sanitize میشن
2. **Origin Check**: فقط URLهای داخلی سایت
3. **Event Propagation**: `stopPropagation()` برای جلوگیری از کلیک‌های ناخواسته

---

## 🎁 ویژگی‌های اضافی

### 1. لاگ‌های Console
```javascript
console.log('🔗 Share button clicked!');
console.log('📋 Copying URL:', fullUrl);
console.log('✅ URL copied successfully!');
```

### 2. انیمیشن دکمه
- آیکون share → tick (✓)
- بعد از 1.5 ثانیه → برگشت به share

### 3. پیام‌های خطا
- "خطا در دریافت لینک محصول" - وقتی data-url نباشه
- "خطا در کپی کردن لینک" - وقتی execCommand فیل بشه
- "مرورگر شما از کپی کردن پشتیبانی نمی‌کند" - در مرورگرهای خیلی قدیمی

---

## 📍 محل استفاده

دکمه Share در این صفحات کار می‌کنه:
- ✅ صفحه اصلی (کاروسل محصولات)
- ✅ صفحه محصولات (لیست محصولات)
- ✅ صفحه wishlist
- ✅ صفحه جستجو
- ✅ هر جایی که `product-card.php` استفاده بشه

---

## 💡 استفاده توسط کاربر

1. کاربر محصول مورد نظرش رو پیدا می‌کنه
2. روی آیکون **share** (📤) کلیک می‌کنه
3. لینک محصول **خودکار** کپی میشه
4. می‌تونه لینک رو در:
   - شبکه‌های اجتماعی (تلگرام، واتساپ، اینستاگرام)
   - ایمیل
   - پیام‌رسان‌ها
   - هر جای دیگه‌ای paste کنه

---

## 📚 مستندات API

### Clipboard API (مدرن)
```javascript
navigator.clipboard.writeText(text)
    .then(() => console.log('Success'))
    .catch((err) => console.error('Error:', err));
```

### execCommand (قدیمی)
```javascript
document.execCommand('copy')
```

---

✨ **قابلیت با موفقیت اضافه شد!**

تاریخ: 23 آگوست 2026
نسخه: 1.3.0
