# Changelog - سیستم Theme

## [2.0.0] - 2026-07-29

### Added ✨
- سیستم Theme کاملاً ماژولار و مقیاس‌پذیر
- پشتیبانی از 4 فصل: Spring, Summer, Autumn, Winter
- ThemeManager Class برای مدیریت متمرکز Theme
- Theme Priority System (Query String > Product > User > Admin > Automatic)
- Season Detection خودکار بر اساس ماه جاری
- Product-based Theme (هر محصول می‌تواند Theme اختصاصی داشته باشد)
- User Theme Preference در پروفایل کاربر
- Admin Theme Settings Panel
- پوشه‌های جداگانه برای Theme Assets (images, js)
- مستندات کامل فارسی و انگلیسی

### Changed 🔄
- معماری CSS به سه بخش تقسیم شد: Base, Components, Themes
- تمام رنگ‌ها به CSS Variables تبدیل شدند
- header.php و minimal-header.php برای استفاده از Theme جدید
- ProductController برای پشتیبانی از Product Theme
- UserController برای مدیریت preferred_theme

### Refactored ♻️
- جداسازی کامل ساختار از ظاهر در CSS
- حذف Code Duplication در استایل‌ها
- Component-based CSS Architecture
- بهبود Separation of Concerns

### Database 💾
- افزودن فیلد `preferred_theme` به جدول `users`

### Files Structure 📁
```
public/assets/css/
├── base/              # استایل‌های مستقل از Theme
├── components/        # ساختار کامپوننت‌ها
└── themes/           # Theme‌های مختلف

src/Libs/
└── ThemeManager.php  # مدیریت Theme

config/
└── theme.php         # تنظیمات Theme
```

### Developer Notes 👨‍💻
- تمام Controllerها و Viewها باید از ThemeManager برای دریافت Theme استفاده کنند
- افزودن Theme جدید تنها با ایجاد یک فایل CSS امکان‌پذیر است
- هیچ تغییری در ساختار پروژه لازم نیست
- Backward Compatibility حفظ شده است

### Performance ⚡
- آماده برای Cache
- قابلیت Minify
- آماده برای CDN

### Security 🔒
- اعتبارسنجی Theme Name
- محدودیت دسترسی Admin Panel
- Escape تمام Outputها

### Documentation 📚
- README_THEME_SYSTEM.md - مستندات فنی کامل
- IMPLEMENTATION_GUIDE.md - راهنمای پیاده‌سازی و نصب
- CHANGELOG.md - تغییرات و نسخه‌ها

### Breaking Changes ⚠️
- فایل `main.css` قدیمی دیگر استفاده نمی‌شود (اما حذف نشده)
- نیاز به اجرای Migration دیتابیس
- نیاز به آپدیت header.php در صفحات سفارشی

### Migration Guide 🔧
```sql
ALTER TABLE users ADD COLUMN preferred_theme VARCHAR(20) DEFAULT 'automatic';
```

### Known Issues 🐛
- هیچ مشکل شناخته‌شده‌ای وجود ندارد

### Future Plans 🚀
- Theme Customizer داخل پنل کاربری
- پیش‌نمایش Theme قبل از انتخاب
- Theme Scheduler (تغییر خودکار بر اساس زمان)
- Dark Mode برای هر Theme
- Custom Theme Builder
- Theme Marketplace
- Halloween Theme
- Christmas Theme
- Black Friday Theme
- Nowruz Theme
- Valentine Theme

### Credits 👏
- معماری SOLID و DRY
- Separation of Concerns
- Component-based Architecture
- CSS Variables
- Modern PHP Patterns

### Support 💬
برای مشکلات و سوالات، مستندات را مطالعه کنید یا Issue ایجاد کنید.

---

## [1.0.0] - قبل از 2026-07-29

### Initial Release
- سیستم Theme ساده با main.css
- فقط پشتیبانی از Autumn Theme
- استایل‌های Inline و غیر ماژولار
