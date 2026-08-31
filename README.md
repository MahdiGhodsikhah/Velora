# مستندات فایل‌های CSS پروژه Velora

## فهرست
- [فایل‌های اصلی](#فایلهای-اصلی)
- [فایل‌های Base](#فایلهای-base)
- [فایل‌های Components](#فایلهای-components)
- [فایل‌های صفحات](#فایلهای-صفحات)
- [تم‌های فصلی](#تمهای-فصلی)

---

## فایل‌های اصلی

### **main.css**
- **وظیفه**: فایل استایل اصلی و جامع پروژه
- **محتوا**: 
  - متغیرهای رنگ پاییزی (primary, secondary, accent)
  - استایل صفحه اصلی و hero section
  - نوار ویژگی‌ها و دسته‌بندی محصولات
  - بنر میانی با انیمیشن
  - فوتر با افکت‌های پیشرفته
  - صفحه درباره ما (hero, stats, features)
- **ویژگی‌ها**: انیمیشن‌های پیشرفته، گرادینت‌های پویا، افکت‌های شناور

### **bootstrap.min.css**
- **وظیفه**: فریمورک CSS بوت‌استرپ (نسخه مینیفای شده)
- **محتوا**: استایل‌های پایه‌ای grid، button، form و...

---

## فایل‌های Base

### **variables.css**
- **وظیفه**: تعریف متغیرهای CSS سراسری
- **محتوا**:
  - Transition values
  - Border radius (sm, md, lg, xl, full)
  - Shadow levels (sm, md, lg)
  - Navbar height & container width
- **استفاده**: پایه برای تمام فایل‌های CSS دیگر

### **reset.css**
- **وظیفه**: ریست کردن استایل‌های پیش‌فرض مرورگر

### **layout.css**
- **وظیفه**: تعریف ساختار کلی صفحات (container, grid, flexbox)

### **typography.css**
- **وظیفه**: تایپوگرافی و فونت‌ها (Vazirmatn)

### **animations.css**
- **وظیفه**: انیمیشن‌های مورد استفاده در پروژه

### **responsive.css**
- **وظیفه**: Media queries برای موبایل و تبلت

---

## فایل‌های Components

### **navbar.css**
- **وظیفه**: استایل نوار ناوبری
- **ویژگی‌ها**: sticky header، dropdown menu، search bar

### **hero.css**
- **وظیفه**: بخش hero/banner صفحه اصلی

### **products.css**
- **وظیفه**: استایل کارت محصولات
- **محتوا**: product card، rating stars، badges، hover effects

### **card.css**
- **وظیفه**: استایل عمومی کارت‌ها

### **button.css**
- **وظیفه**: دکمه‌های سایت (primary, secondary, gradient)

### **footer.css**
- **وظیفه**: فوتر سایت با انیمیشن و لینک‌های اجتماعی

### **banner.css**
- **وظیفه**: بنرهای تبلیغاتی میانی

### **features.css**
- **وظیفه**: بخش نمایش ویژگی‌ها

### **notification.css**
- **وظیفه**: پیام‌های نوتیفیکیشن (success, error, info)

### **section.css**
- **وظیفه**: استایل عمومی section ها

### **auth.css** (در components)
- **وظیفه**: استایل مربوط به فرم‌های احراز هویت در کامپوننت‌ها

### **about.css**
- **وظیفه**: استایل‌های اختصاصی صفحه درباره ما

---

## فایل‌های صفحات

### **auth.css** (در root)
- **وظیفه**: صفحات ورود و ثبت‌نام
- **محتوا**:
  - Password toggle icon
  - Terms modal (شرایط و قوانین)
  - Form styling مخصوص صفحات احراز هویت
- **ویژگی‌ها**: glassmorphism effect، backdrop-filter

### **dashboard.css**
- **وظیفه**: پنل کاربری
- **محتوا**:
  - Header با gradient
  - Stats cards با آیکون
  - Quick actions grid
  - Activity lists (orders, reviews)
- **ویژگی‌ها**: hover effects، gradient icons

### **cart.css**
- **وظیفه**: صفحه سبد خرید
- **محتوا**:
  - Cart items grid
  - Quantity controls
  - Price summary
  - Coupon section
  - Empty cart state
- **ویژگی‌ها**: sticky summary، responsive layout

### **wishlist.css**
- **وظیفه**: صفحه لیست علاقه‌مندی‌ها
- **محتوا**:
  - Products grid
  - Pagination
  - Empty wishlist state
- **ویژگی‌ها**: heartbeat animation، responsive

### **checkout.css**
- **وظیفه**: صفحه تسویه حساب
- **محتوا**:
  - Checkout form (address, payment)
  - Order summary sticky box
  - Payment method selection
- **ویژگی‌ها**: form validation styling، sticky sidebar

### **orders.css**
- **وظیفه**: صفحه سفارشات کاربر
- **محتوا**:
  - Order cards
  - Status badges
  - Order details
  - Shipping address
  - Products list
- **ویژگی‌ها**: color-coded status، responsive cards

### **product-single.css**
- **وظیفه**: صفحه جزئیات محصول
- **محتوا**:
  - Image gallery با thumbnails
  - Product info (price, rating, stock)
  - Quantity selector
  - Add to cart button
  - Reviews section
  - Review form با star rating
  - Related products carousel
  - Animated leaves background
- **ویژگی‌ها**: sticky gallery، glassmorphism، تطبیق با تم فصلی

### **products-fix.css**
- **وظیفه**: اصلاحات و override های مخصوص صفحه محصولات

### **error.css**
- **وظیفه**: صفحه 404 و خطاها
- **محتوا**:
  - Aurora background effect
  - Glitch effect روی کد خطا
  - Action buttons
  - Suggestions section
  - Animated particles
- **ویژگی‌ها**: dark theme، neon effects، full-screen design

### **carousel.css**
- **وظیفه**: استایل carousel/slider محصولات

### **slick.css / slick-theme.css**
- **وظیفه**: کتابخانه Slick Carousel

---

## تم‌های فصلی

### **autumn.css** (پاییز)
- **رنگ اصلی**: نارنجی (#d97706)
- **رنگ ثانویه**: قهوه‌ای (#78350f)
- **رنگ accent**: قرمز پاییزی (#dc2626)

### **winter.css** (زمستان)
- **رنگ اصلی**: آبی یخی (#0ea5e9)
- **رنگ ثانویه**: آبی تیره (#1e3a8a)
- **رنگ accent**: سفید برفی

### **spring.css** (بهار)
- **رنگ اصلی**: سبز (#10b981)
- **رنگ ثانویه**: سبز تیره (#047857)
- **رنگ accent**: صورتی (#ec4899)

### **summer.css** (تابستان)
- **رنگ اصلی**: زرد (#fbbf24)
- **رنگ ثانویه**: نارنجی (#f97316)
- **رنگ accent**: آبی آسمانی (#3b82f6)

**نکته**: تم‌های فصلی متغیرهای CSS را override می‌کنند و ظاهر کل سایت را تغییر می‌دهند.

---

## ساختار کلی

```
css/
├── base/              # پایه و اصول
│   ├── variables.css  # متغیرها
│   ├── reset.css
│   ├── layout.css
│   ├── typography.css
│   ├── animations.css
│   └── responsive.css
│
├── components/        # کامپوننت‌های قابل استفاده مجدد
│   ├── navbar.css
│   ├── footer.css
│   ├── products.css
│   ├── card.css
│   ├── button.css
│   └── ...
│
├── themes/           # تم‌های فصلی
│   ├── autumn.css
│   ├── winter.css
│   ├── spring.css
│   └── summer.css
│
├── main.css          # فایل اصلی
├── auth.css          # احراز هویت
├── dashboard.css     # پنل کاربری
├── cart.css          # سبد خرید
├── checkout.css      # تسویه حساب
├── orders.css        # سفارشات
├── wishlist.css      # علاقه‌مندی‌ها
├── product-single.css # جزئیات محصول
├── error.css         # صفحات خطا
└── ...
```

---

## خلاصه سریع

| فایل | کاربرد اصلی |
|------|-------------|
| **variables.css** | متغیرهای سراسری (رنگ، اندازه، shadow) |
| **main.css** | صفحه اصلی + فوتر + درباره ما |
| **auth.css** | ورود و ثبت‌نام |
| **dashboard.css** | پنل کاربری |
| **cart.css** | سبد خرید |
| **wishlist.css** | علاقه‌مندی‌ها |
| **checkout.css** | پرداخت و تسویه |
| **orders.css** | لیست سفارشات |
| **product-single.css** | جزئیات هر محصول |
| **error.css** | صفحه 404 |
| **navbar.css** | نوار بالا |
| **footer.css** | فوتر |
| **products.css** | کارت محصولات |
| **themes/** | تم‌های فصلی (4 فصل) |

---

**تاریخ ایجاد**: 2026
**نسخه پروژه**: Velora E-Commerce
