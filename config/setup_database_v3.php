<?php
/**
 * اسکریپت به‌روزرسانی پایگاه داده - نسخه 3
 * اضافه کردن جدول بنرها و تصاویر اسلایدر
 * اجرا: http://localhost/Velora/config/setup_database_v3.php
 */

// جلوگیری از دسترسی خارجی
if (php_sapi_name() !== 'cli') {
    $allowed_ips = ['127.0.0.1', '::1'];
    if (!isset($_SERVER['REMOTE_ADDR']) || !in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
        http_response_code(403);
        die('دسترسی مجاز نیست');
    }
}

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'autumn_shop');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die('خطا در اتصال: ' . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8mb4');

$updates = [];
$errors = [];

// -------------------------------------------------------------------
// جدول بنرها
// -------------------------------------------------------------------
$sql_banners = "CREATE TABLE IF NOT EXISTS `banners` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`       VARCHAR(200) NOT NULL COMMENT 'عنوان اصلی بنر',
    `subtitle`    VARCHAR(300) DEFAULT NULL COMMENT 'زیرعنوان بنر',
    `image_url`   VARCHAR(500) NOT NULL COMMENT 'مسیر تصویر بنر',
    `link_url`    VARCHAR(500) DEFAULT NULL COMMENT 'لینک هدف بنر',
    `btn_text`    VARCHAR(100) DEFAULT 'مشاهده محصولات' COMMENT 'متن دکمه',
    `position`    VARCHAR(50) NOT NULL DEFAULT 'hero' COMMENT 'موقعیت نمایش: hero, sidebar, footer',
    `sort_order`  TINYINT UNSIGNED DEFAULT 0 COMMENT 'ترتیب نمایش',
    `is_active`   TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'فعال/غیرفعال',
    `start_date`  DATETIME DEFAULT NULL COMMENT 'تاریخ شروع نمایش',
    `end_date`    DATETIME DEFAULT NULL COMMENT 'تاریخ پایان نمایش',
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_position` (`position`, `is_active`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci";

if (mysqli_query($conn, $sql_banners)) {
    $updates[] = 'جدول banners ایجاد/بررسی شد';
} else {
    $errors[] = 'خطا در ساخت banners: ' . mysqli_error($conn);
}

// -------------------------------------------------------------------
// درج بنرهای نمونه
// -------------------------------------------------------------------
$sql_insert_banners = "INSERT INTO `banners`
    (`title`, `subtitle`, `image_url`, `link_url`, `btn_text`, `position`, `sort_order`, `is_active`) VALUES
    
    ('کلکسیون پاییزی شگفت‌انگیز', 
     'جدیدترین مدل‌های پوشاک با طراحی منحصر‌به‌فرد پاییزی - تخفیف ویژه تا ۵۰٪',
     '/assets/images/banners/banner-autumn-1.png',
     '/products',
     'مشاهده محصولات',
     'hero', 1, 1),
    
    ('استایل پاییزی خود را بسازید',
     'با بهترین برندهای پوشاک و اکسسوری - ارسال رایگان برای خریدهای بالای ۵۰۰ هزار تومان',
     '/assets/images/banners/banner-normal-1.png',
     '/products?cat=1',
     'خرید کنید',
     'hero', 2, 1),
    
    ('تخفیف‌های فصلی',
     'تا ۷۰٪ تخفیف روی محصولات منتخب - فقط تا پایان هفته',
     '/assets/images/banners/banner-autumn-1.png',
     '/products?sale=1',
     'خرید با تخفیف',
     'hero', 3, 1)
     
ON DUPLICATE KEY UPDATE
    `title` = VALUES(`title`),
    `subtitle` = VALUES(`subtitle`),
    `is_active` = VALUES(`is_active`)";

if (mysqli_query($conn, $sql_insert_banners)) {
    $affected = mysqli_affected_rows($conn);
    $updates[] = "بنرهای نمونه درج شدند ($affected ردیف)";
} else {
    $errors[] = 'خطا در درج banners: ' . mysqli_error($conn);
}

// -------------------------------------------------------------------
// بررسی وجود تصاویر بنر
// -------------------------------------------------------------------
$base_path = dirname(__DIR__) . '/public';
$banner_images = [
    '/assets/images/banners/banner-autumn-1.png',
    '/assets/images/banners/banner-normal-1.png'
];

$missing_images = [];
foreach ($banner_images as $img) {
    if (!file_exists($base_path . $img)) {
        $missing_images[] = $img;
    }
}

if (!empty($missing_images)) {
    $errors[] = '⚠️ تصاویر زیر یافت نشدند: ' . implode(', ', $missing_images);
} else {
    $updates[] = '✓ همه تصاویر بنر موجود هستند';
}

mysqli_close($conn);

// نمایش نتایج
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>به‌روزرسانی پایگاه داده - نسخه 3</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Tahoma, Arial, sans-serif;
            background: linear-gradient(135deg, #d97706 0%, #92400e 50%, #b91c1c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 800px;
            width: 100%;
        }
        h1 {
            color: #16a34a;
            margin-bottom: 30px;
            font-size: 2rem;
            text-align: center;
        }
        h1::before {
            content: '🍂';
            margin-left: 10px;
        }
        .section {
            margin: 25px 0;
        }
        .section h3 {
            color: #d97706;
            margin-bottom: 15px;
            font-size: 1.2rem;
            border-right: 4px solid #d97706;
            padding-right: 12px;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        ul li {
            padding: 12px 15px;
            margin: 8px 0;
            background: #fef3c7;
            border-right: 4px solid #d97706;
            border-radius: 8px;
            line-height: 1.6;
        }
        .error li {
            background: #fef2f2;
            border-right-color: #ef4444;
        }
        .warning {
            background: linear-gradient(135deg, #fef3c7, #fed7aa);
            border: 2px solid #d97706;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
            text-align: center;
        }
        .warning strong {
            color: #dc2626;
            font-size: 1.1rem;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin: 30px 0;
            flex-wrap: wrap;
            gap: 15px;
        }
        .stat-box {
            background: linear-gradient(135deg, #d97706, #b91c1c);
            color: #fff;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            min-width: 150px;
            box-shadow: 0 8px 20px rgba(217, 119, 6, 0.4);
        }
        .stat-box .number {
            font-size: 2.5rem;
            font-weight: bold;
            display: block;
        }
        .stat-box .label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-top: 5px;
        }
        .info-box {
            background: #e0f2fe;
            border: 2px solid #0ea5e9;
            border-radius: 10px;
            padding: 15px;
            margin: 20px 0;
        }
        .info-box strong {
            color: #0369a1;
        }
        .success-icon {
            display: inline-block;
            color: #16a34a;
            font-size: 1.2rem;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>به‌روزرسانی پایگاه داده - نسخه 3</h1>
        
        <div class="stats">
            <div class="stat-box">
                <span class="number"><?= count($updates) ?></span>
                <span class="label">به‌روزرسانی موفق</span>
            </div>
            <div class="stat-box">
                <span class="number"><?= count($errors) ?></span>
                <span class="label">خطا</span>
            </div>
        </div>

        <?php if (!empty($updates)): ?>
        <div class="section">
            <h3>✔ تغییرات انجام شده:</h3>
            <ul>
                <?php foreach ($updates as $update): ?>
                <li><span class="success-icon">✓</span> <?= htmlspecialchars($update, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
        <div class="section error">
            <h3>❌ خطاها:</h3>
            <ul>
                <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="info-box">
            <strong>📝 یادداشت:</strong><br>
            • جدول <code>banners</code> برای مدیریت بنرهای اسلایدر ایجاد شد<br>
            • تصاویر بنر باید در مسیر <code>/public/assets/images/banners/</code> قرار بگیرند<br>
            • بنرهای نمونه با تصاویر موجود ایجاد شدند
        </div>

        <div class="warning">
            <strong>⚠️ هشدار امنیتی:</strong><br>
            این فایل را پس از اجرا موفقیت‌آمیز از سرور حذف کنید<br>
            یا دسترسی به آن را محدود نمایید!
        </div>
    </div>
</body>
</html>
