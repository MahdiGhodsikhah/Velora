<?php
/**
 * اسکریپت به‌روزرسانی پایگاه داده - نسخه 2
 * اضافه کردن جدول بلاگ و بهبودهای دیگر
 * اجرا: http://localhost/Velora/public/../config/setup_database_v2.php
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
// جدول دسته‌بندی بلاگ
// -------------------------------------------------------------------
$sql_blog_categories = "CREATE TABLE IF NOT EXISTS `blog_categories` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(100) NOT NULL,
    `slug`        VARCHAR(120) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `sort_order`  TINYINT UNSIGNED DEFAULT 0,
    `is_active`   TINYINT(1) NOT NULL DEFAULT 1,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci";

if (mysqli_query($conn, $sql_blog_categories)) {
    $updates[] = 'جدول blog_categories ایجاد/بررسی شد';
} else {
    $errors[] = 'خطا در ساخت blog_categories: ' . mysqli_error($conn);
}

// -------------------------------------------------------------------
// جدول پست‌های بلاگ
// -------------------------------------------------------------------
$sql_blog_posts = "CREATE TABLE IF NOT EXISTS `blog_posts` (
    `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `category_id`     INT UNSIGNED DEFAULT NULL,
    `author_id`       INT UNSIGNED NOT NULL,
    `title`           VARCHAR(250) NOT NULL,
    `slug`            VARCHAR(270) NOT NULL UNIQUE,
    `excerpt`         VARCHAR(500) DEFAULT NULL COMMENT 'خلاصه مطلب',
    `content`         TEXT NOT NULL COMMENT 'محتوای اصلی',
    `featured_image`  VARCHAR(500) DEFAULT NULL,
    `meta_description` VARCHAR(300) DEFAULT NULL,
    `meta_keywords`   VARCHAR(500) DEFAULT NULL,
    `views_count`     INT UNSIGNED DEFAULT 0,
    `is_published`    TINYINT(1) NOT NULL DEFAULT 0,
    `published_at`    DATETIME DEFAULT NULL,
    `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_slug` (`slug`),
    INDEX `idx_published` (`is_published`, `published_at`),
    INDEX `idx_category` (`category_id`),
    INDEX `idx_author` (`author_id`),
    CONSTRAINT `fk_blog_cat` FOREIGN KEY (`category_id`) REFERENCES `blog_categories`(`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_blog_author` FOREIGN KEY (`author_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci";

if (mysqli_query($conn, $sql_blog_posts)) {
    $updates[] = 'جدول blog_posts ایجاد/بررسی شد';
} else {
    $errors[] = 'خطا در ساخت blog_posts: ' . mysqli_error($conn);
}

// -------------------------------------------------------------------
// جدول نظرات بلاگ
// -------------------------------------------------------------------
$sql_blog_comments = "CREATE TABLE IF NOT EXISTS `blog_comments` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `post_id`     INT UNSIGNED NOT NULL,
    `user_id`     INT UNSIGNED DEFAULT NULL,
    `author_name` VARCHAR(80) DEFAULT NULL,
    `author_email` VARCHAR(150) DEFAULT NULL,
    `comment`     TEXT NOT NULL,
    `parent_id`   INT UNSIGNED DEFAULT NULL COMMENT 'برای پاسخ به کامنت',
    `is_approved` TINYINT(1) DEFAULT 0,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_post` (`post_id`),
    INDEX `idx_parent` (`parent_id`),
    CONSTRAINT `fk_comment_post` FOREIGN KEY (`post_id`) REFERENCES `blog_posts`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_comment_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci";

if (mysqli_query($conn, $sql_blog_comments)) {
    $updates[] = 'جدول blog_comments ایجاد/بررسی شد';
} else {
    $errors[] = 'خطا در ساخت blog_comments: ' . mysqli_error($conn);
}

// -------------------------------------------------------------------
// جدول تگ‌ها
// -------------------------------------------------------------------
$sql_tags = "CREATE TABLE IF NOT EXISTS `tags` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(60) NOT NULL UNIQUE,
    `slug`       VARCHAR(70) NOT NULL UNIQUE,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci";

if (mysqli_query($conn, $sql_tags)) {
    $updates[] = 'جدول tags ایجاد/بررسی شد';
} else {
    $errors[] = 'خطا در ساخت tags: ' . mysqli_error($conn);
}

// -------------------------------------------------------------------
// جدول رابطه پست و تگ
// -------------------------------------------------------------------
$sql_post_tags = "CREATE TABLE IF NOT EXISTS `post_tags` (
    `post_id` INT UNSIGNED NOT NULL,
    `tag_id`  INT UNSIGNED NOT NULL,
    PRIMARY KEY (`post_id`, `tag_id`),
    CONSTRAINT `fk_pt_post` FOREIGN KEY (`post_id`) REFERENCES `blog_posts`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_pt_tag`  FOREIGN KEY (`tag_id`)  REFERENCES `tags`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci";

if (mysqli_query($conn, $sql_post_tags)) {
    $updates[] = 'جدول post_tags ایجاد/بررسی شد';
} else {
    $errors[] = 'خطا در ساخت post_tags: ' . mysqli_error($conn);
}

// ===================================================================
// درج داده‌های نمونه برای بلاگ
// ===================================================================

// دسته‌بندی‌های بلاگ
$sql_insert_blog_cats = "INSERT IGNORE INTO `blog_categories`
    (`id`, `name`, `slug`, `description`, `sort_order`, `is_active`) VALUES
    (1, 'مد و پوشاک', 'fashion', 'مقالات مرتبط با مد و پوشاک', 1, 1),
    (2, 'راهنمای خرید', 'shopping-guide', 'راهنماهای جامع خرید محصولات', 2, 1),
    (3, 'نگهداری', 'care-tips', 'نکات نگهداری از محصولات', 3, 1),
    (4, 'اخبار', 'news', 'آخرین اخبار دنیای مد', 4, 1)";

if (mysqli_query($conn, $sql_insert_blog_cats)) {
    $updates[] = 'دسته‌بندی‌های بلاگ درج شدند';
} else {
    $errors[] = 'خطا در درج blog_categories: ' . mysqli_error($conn);
}

// یافتن user_id ادمین
$admin_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM users WHERE role='admin' LIMIT 1"));
$admin_id = $admin_row ? (int)$admin_row['id'] : 1;

// پست‌های نمونه بلاگ
$sql_insert_blog_posts = "INSERT IGNORE INTO `blog_posts`
    (`id`, `category_id`, `author_id`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `views_count`, `is_published`, `published_at`) VALUES
    (1, 1, $admin_id,
     'جدیدترین ترندهای مد پاییزی ۱۴۰۳',
     'autumn-fashion-trends',
     'با شروع فصل پاییز، سبک‌های جدید و رنگ‌های گرم وارد دنیای مد شده‌اند. در این مقاله با جدیدترین ترندهای پاییزی آشنا می‌شوید...',
     '<p>فصل پاییز یکی از بهترین فصل‌ها برای تجربه سبک‌های متنوع لباس‌پوشی است. رنگ‌های گرم مانند قهوه‌ای، نارنجی سوخته، و زرشکی در این فصل بسیار محبوب هستند.</p><h3>رنگ‌های محبوب پاییز</h3><p>رنگ‌هایی مانند قهوه‌ای شکلاتی، نارنجی سوخته، و سبز ارتشی از محبوب‌ترین رنگ‌های این فصل هستند.</p><h3>لایه‌لایه پوشیدن</h3><p>استفاده از لایه‌های مختلف لباس هم استایل شما را جذاب‌تر می‌کند و هم در دمای متغیر پاییز راحتی بیشتری به شما می‌دهد.</p>',
     '/assets/images/blog/autumn-fashion.jpg', 245, 1, '2024-11-15 10:30:00'),

    (2, 2, $admin_id,
     '۱۰ نکته برای خرید هوشمندانه پوشاک',
     'shopping-tips',
     'خرید پوشاک می‌تواند چالش‌برانگیز باشد. با این ۱۰ نکته طلایی، خریدی هوشمندانه و رضایت‌بخش تجربه کنید...',
     '<p>خرید هوشمندانه یعنی انتخاب محصولات با کیفیت، متناسب با نیاز و بودجه. در اینجا ۱۰ نکته کلیدی را معرفی می‌کنیم:</p><ol><li><strong>کیفیت را به قیمت ترجیح دهید</strong> - یک لباس با کیفیت بالا بهتر از ۵ لباس ارزان است</li><li><strong>سایز مناسب را انتخاب کنید</strong> - لباس باید کاملاً به اندازه شما باشد</li><li><strong>به جنس پارچه توجه کنید</strong> - پارچه‌های طبیعی معمولاً دوام بیشتری دارند</li><li><strong>رنگ‌های همه‌کاره بخرید</strong> - رنگ‌هایی که با اکثر لباس‌هایتان ست می‌شوند</li><li><strong>از خریدهای هیجانی پرهیز کنید</strong> - فقط آنچه واقعاً نیاز دارید بخرید</li></ol>',
     '/assets/images/blog/shopping-guide.jpg', 189, 1, '2024-11-10 14:20:00'),

    (3, 3, $admin_id,
     'راهنمای نگهداری صحیح از لباس‌های پاییزی',
     'care-guide',
     'برای طولانی‌تر شدن عمر لباس‌هایتان، نکاتی را باید رعایت کنید. از شستشو گرفته تا نگهداری، همه چیز را در اینجا بخوانید...',
     '<p>نگهداری صحیح از لباس‌ها می‌تواند عمر آن‌ها را چند برابر کند.</p><h3>شستشو</h3><p>همیشه برچسب شستشوی لباس را مطالعه کنید. اکثر لباس‌های پاییزی با آب سرد یا ولرم شسته می‌شوند.</p><h3>خشک کردن</h3><p>از خشک‌کردن مستقیم زیر نور آفتاب پرهیز کنید. بهترین روش، خشک کردن در سایه است.</p><h3>نگهداری</h3><p>لباس‌ها را تمیز و خشک نگه دارید. از قرار دادن در محیط‌های مرطوب خودداری کنید.</p>',
     '/assets/images/blog/care-tips.jpg', 156, 1, '2024-11-05 09:15:00')";

if (mysqli_query($conn, $sql_insert_blog_posts)) {
    $updates[] = 'پست‌های نمونه بلاگ درج شدند';
} else {
    $errors[] = 'خطا در درج blog_posts: ' . mysqli_error($conn);
}

// تگ‌های نمونه
$sql_insert_tags = "INSERT IGNORE INTO `tags`
    (`id`, `name`, `slug`) VALUES
    (1, 'مد پاییزی', 'autumn-fashion'),
    (2, 'راهنمای خرید', 'shopping-guide'),
    (3, 'نگهداری', 'care-tips'),
    (4, 'استایل', 'style'),
    (5, 'لباس مردانه', 'mens-clothing'),
    (6, 'لباس زنانه', 'womens-clothing')";

if (mysqli_query($conn, $sql_insert_tags)) {
    $updates[] = 'تگ‌های نمونه درج شدند';
} else {
    $errors[] = 'خطا در درج tags: ' . mysqli_error($conn);
}

// ارتباط پست و تگ
$sql_insert_post_tags = "INSERT IGNORE INTO `post_tags`
    (`post_id`, `tag_id`) VALUES
    (1, 1), (1, 4),
    (2, 2), (2, 4),
    (3, 3), (3, 4)";

if (mysqli_query($conn, $sql_insert_post_tags)) {
    $updates[] = 'ارتباط پست‌ها و تگ‌ها ایجاد شد';
} else {
    $errors[] = 'خطا در درج post_tags: ' . mysqli_error($conn);
}

mysqli_close($conn);

// نمایش نتایج
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>به‌روزرسانی پایگاه داده</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Tahoma, Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            max-width: 700px;
            width: 100%;
        }
        h1 {
            color: #16a34a;
            margin-bottom: 30px;
            font-size: 2rem;
            text-align: center;
        }
        h1::before {
            content: '✅';
            margin-left: 10px;
        }
        .section {
            margin: 25px 0;
        }
        .section h3 {
            color: #1e40af;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        ul li {
            padding: 10px 15px;
            margin: 8px 0;
            background: #f0fdf4;
            border-right: 4px solid #22c55e;
            border-radius: 8px;
            line-height: 1.6;
        }
        .error li {
            background: #fef2f2;
            border-right-color: #ef4444;
        }
        .warning {
            background: #fef3c7;
            border: 2px solid #f59e0b;
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
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            min-width: 150px;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>به‌روزرسانی پایگاه داده - نسخه 2</h1>
        
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
                <li><?= htmlspecialchars($update, ENT_QUOTES, 'UTF-8') ?></li>
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

        <div class="warning">
            <strong>⚠️ هشدار امنیتی:</strong><br>
            این فایل را پس از اجرا موفقیت‌آمیز از سرور حذف کنید<br>
            یا دسترسی به آن را محدود نمایید!
        </div>
    </div>
</body>
</html>
