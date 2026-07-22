<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>راهنمای نصب سیستم تم</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Tahoma, Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #d97706 0%, #ea580c 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        .content {
            padding: 40px;
        }
        .step {
            background: #f8f9fa;
            padding: 25px;
            margin: 20px 0;
            border-radius: 12px;
            border-right: 5px solid #d97706;
            transition: all 0.3s ease;
        }
        .step:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateX(-5px);
        }
        .step-number {
            display: inline-block;
            background: #d97706;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            font-weight: bold;
            font-size: 1.2rem;
            margin-left: 15px;
        }
        .step h3 {
            display: inline-block;
            color: #333;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }
        .step p {
            color: #666;
            line-height: 1.8;
            margin-top: 10px;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #d97706, #ea580c);
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            margin: 10px 5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(217, 119, 6, 0.3);
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(217, 119, 6, 0.4);
        }
        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }
        .alert {
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .alert-info {
            background: #d1ecf1;
            border-right: 5px solid #0dcaf0;
            color: #055160;
        }
        .alert-success {
            background: #d4edda;
            border-right: 5px solid #28a745;
            color: #155724;
        }
        .icon {
            font-size: 1.5rem;
            margin-left: 10px;
        }
        .links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 30px 0;
        }
        .link-card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }
        .link-card:hover {
            border-color: #d97706;
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .link-card .icon {
            font-size: 3rem;
            margin-bottom: 10px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎨 راهنمای نصب سیستم تم دینامیک</h1>
            <p>سیستم مدیریت تم چندفصلی برای فروشگاه Velora</p>
        </div>

        <div class="content">
            
            <div class="alert alert-info">
                <strong>ℹ️ توجه:</strong> لطفاً مراحل زیر را به ترتیب انجام دهید تا سیستم تم به درستی نصب شود.
            </div>

            <!-- مرحله 1 -->
            <div class="step">
                <span class="step-number">1</span>
                <h3>نصب پایگاه داده</h3>
                <p>
                    ابتدا باید جدول تنظیمات و ستون فصل محصولات را در پایگاه داده ایجاد کنید.
                    این مرحله جدول <code>site_settings</code> را ایجاد و تنظیمات اولیه را اعمال می‌کند.
                </p>
                <a href="setup-theme.php" class="btn">
                    <span class="icon">⚙️</span> اجرای نصب
                </a>
            </div>

            <!-- مرحله 2 -->
            <div class="step">
                <span class="step-number">2</span>
                <h3>تست سیستم</h3>
                <p>
                    بعد از نصب، برای اطمینان از عملکرد صحیح، تمام بخش‌های سیستم را تست کنید.
                    این صفحه وضعیت تمام کامپوننت‌ها را بررسی می‌کند.
                </p>
                <a href="test-theme.php" class="btn btn-secondary">
                    <span class="icon">🧪</span> تست سیستم
                </a>
            </div>

            <!-- مرحله 3 -->
            <div class="step">
                <span class="step-number">3</span>
                <h3>مدیریت فصل محصولات</h3>
                <p>
                    برای محصولات موجود، فصل مرتبط را تنظیم کنید. این ابزار به شما امکان می‌دهد
                    فصل تمام محصولات را به صورت دسته‌جمعی یا تک‌تک تنظیم کنید.
                </p>
                <a href="update-products-season.php" class="btn btn-secondary">
                    <span class="icon">📦</span> مدیریت محصولات
                </a>
            </div>

            <!-- مرحله 4 -->
            <div class="step">
                <span class="step-number">4</span>
                <h3>دسترسی به پنل ادمین</h3>
                <p>
                    با حساب ادمین وارد شوید و به تنظیمات تم دسترسی پیدا کنید.
                    از این پنل می‌توانید تم پیش‌فرض، تشخیص خودکار و سایر تنظیمات را مدیریت کنید.
                </p>
                <a href="login" class="btn">
                    <span class="icon">🔐</span> ورود به پنل
                </a>
                <a href="admin/theme-settings" class="btn btn-secondary">
                    <span class="icon">⚙️</span> تنظیمات تم
                </a>
            </div>

            <div class="alert alert-success">
                <strong>✅ پس از تکمیل مراحل:</strong><br>
                سیستم تم آماده استفاده است! کاربران می‌توانند از منوی هدر تم خود را انتخاب کنند
                و محصولات با توجه به فصل تعریف شده، تم مناسب را نمایش می‌دهند.
            </div>

            <h2 style="margin: 40px 0 20px; color: #333; border-bottom: 2px solid #d97706; padding-bottom: 10px;">
                🔗 لینک‌های مفید
            </h2>

            <div class="links">
                <a href="./" class="link-card">
                    <div class="icon">🏠</div>
                    <strong>صفحه اصلی</strong>
                </a>
                <a href="products" class="link-card">
                    <div class="icon">🛍️</div>
                    <strong>محصولات</strong>
                </a>
                <a href="products?season=winter" class="link-card">
                    <div class="icon">❄️</div>
                    <strong>محصولات زمستانی</strong>
                </a>
                <a href="products?season=spring" class="link-card">
                    <div class="icon">🌸</div>
                    <strong>محصولات بهاری</strong>
                </a>
            </div>

            <h2 style="margin: 40px 0 20px; color: #333; border-bottom: 2px solid #d97706; padding-bottom: 10px;">
                📚 مستندات
            </h2>

            <p style="line-height: 2; color: #666;">
                برای اطلاعات بیشتر، فایل‌های زیر را در ریشه پروژه مطالعه کنید:<br>
                • <code>THEME_SYSTEM_README.md</code> - مستندات کامل<br>
                • <code>QUICK_START.md</code> - راهنمای سریع<br>
                • <code>IMPLEMENTATION_SUMMARY.md</code> - خلاصه پیاده‌سازی
            </p>

        </div>

        <div class="footer">
            <p>سیستم تم دینامیک چندفصلی - نسخه 1.0.0</p>
            <p>توسعه داده شده برای Velora Shop</p>
        </div>
    </div>
</body>
</html>
