<?php
/**
 * اسکریپت به‌روزرسانی پایگاه داده - نسخه 4
 * اضافه کردن فیلد season به جدول محصولات
 * اجرا: http://localhost/Velora/config/setup_database_v4.php
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
// اضافه کردن ستون season به جدول products
// -------------------------------------------------------------------

// بررسی وجود ستون
$check_column = mysqli_query($conn, "SHOW COLUMNS FROM `products` LIKE 'season'");
$column_exists = mysqli_num_rows($check_column) > 0;

if (!$column_exists) {
    $sql_add_season = "ALTER TABLE `products` 
        ADD COLUMN `season` ENUM('spring', 'summer', 'autumn', 'winter', 'all') 
        DEFAULT 'all' 
        COMMENT 'فصل محصول: بهار، تابستان، پاییز، زمستان، همه فصول' 
        AFTER `category_id`";
    
    if (mysqli_query($conn, $sql_add_season)) {
        $updates[] = 'ستون season به جدول products اضافه شد';
    } else {
        $errors[] = 'خطا در اضافه کردن ستون season: ' . mysqli_error($conn);
    }
} else {
    $updates[] = 'ستون season قبلاً وجود داشت';
}

// -------------------------------------------------------------------
// اضافه کردن index برای بهبود عملکرد جستجو
// -------------------------------------------------------------------
$sql_add_index = "ALTER TABLE `products` ADD INDEX `idx_season` (`season`)";
$result = @mysqli_query($conn, $sql_add_index);
if ($result) {
    $updates[] = 'ایندکس برای ستون season اضافه شد';
} else {
    // اگر ایندکس قبلاً وجود داشت، خطا ندهیم
    if (strpos(mysqli_error($conn), 'Duplicate key name') === false) {
        $errors[] = 'خطا در اضافه کردن ایندکس: ' . mysqli_error($conn);
    } else {
        $updates[] = 'ایندکس season قبلاً وجود داشت';
    }
}

// -------------------------------------------------------------------
// به‌روزرسانی محصولات موجود با مقادیر نمونه
// -------------------------------------------------------------------
$sql_update_seasons = "UPDATE `products` SET 
    `season` = CASE 
        WHEN `id` % 4 = 0 THEN 'autumn'
        WHEN `id` % 4 = 1 THEN 'spring'
        WHEN `id` % 4 = 2 THEN 'summer'
        WHEN `id` % 4 = 3 THEN 'winter'
        ELSE 'all'
    END
    WHERE `season` = 'all' OR `season` IS NULL";

if (mysqli_query($conn, $sql_update_seasons)) {
    $affected = mysqli_affected_rows($conn);
    $updates[] = "محصولات موجود با فصل‌های نمونه به‌روز شدند ($affected محصول)";
} else {
    $errors[] = 'خطا در به‌روزرسانی محصولات: ' . mysqli_error($conn);
}

// -------------------------------------------------------------------
// آمار محصولات بر اساس فصل
// -------------------------------------------------------------------
$stats_query = "SELECT 
    `season`,
    COUNT(*) as count
    FROM `products`
    GROUP BY `season`
    ORDER BY `season`";

$stats_result = mysqli_query($conn, $stats_query);
$season_stats = [];
while ($row = mysqli_fetch_assoc($stats_result)) {
    $season_stats[] = $row;
}

mysqli_close($conn);

// نمایش نتایج
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>به‌روزرسانی پایگاه داده - نسخه 4</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Tahoma, Arial, sans-serif;
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
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
            max-width: 900px;
            width: 100%;
        }
        h1 {
            color: #16a34a;
            margin-bottom: 30px;
            font-size: 2rem;
            text-align: center;
        }
        h1::before {
            content: '🌸';
            margin-left: 10px;
        }
        .section {
            margin: 25px 0;
        }
        .section h3 {
            color: #059669;
            margin-bottom: 15px;
            font-size: 1.2rem;
            border-right: 4px solid #10b981;
            padding-right: 12px;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        ul li {
            padding: 12px 15px;
            margin: 8px 0;
            background: #d1fae5;
            border-right: 4px solid #10b981;
            border-radius: 8px;
            line-height: 1.6;
        }
        .error li {
            background: #fef2f2;
            border-right-color: #ef4444;
        }
        .warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
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
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            min-width: 150px;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
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
            background: #dbeafe;
            border: 2px solid #3b82f6;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .info-box strong {
            color: #1e40af;
            display: block;
            margin-bottom: 10px;
        }
        .season-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .season-card {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
        }
        .season-card.autumn { border-color: #d97706; background: #fef3c7; }
        .season-card.spring { border-color: #10b981; background: #d1fae5; }
        .season-card.summer { border-color: #f59e0b; background: #fef3c7; }
        .season-card.winter { border-color: #3b82f6; background: #dbeafe; }
        .season-card .icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .season-card .count {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 5px 0;
        }
        .season-card .name {
            font-size: 0.9rem;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>به‌روزرسانی پایگاه داده - نسخه 4</h1>
        
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
                <li>✓ <?= htmlspecialchars($update, ENT_QUOTES, 'UTF-8') ?></li>
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

        <?php if (!empty($season_stats)): ?>
        <div class="info-box">
            <strong>📊 آمار محصولات بر اساس فصل:</strong>
            <div class="season-stats">
                <?php 
                $season_names = [
                    'spring' => ['نام' => 'بهار', 'آیکون' => '🌸', 'کلاس' => 'spring'],
                    'summer' => ['نام' => 'تابستان', 'آیکون' => '☀️', 'کلاس' => 'summer'],
                    'autumn' => ['نام' => 'پاییز', 'آیکون' => '🍂', 'کلاس' => 'autumn'],
                    'winter' => ['نام' => 'زمستان', 'آیکون' => '❄️', 'کلاس' => 'winter'],
                    'all' => ['نام' => 'همه فصول', 'آیکون' => '🌍', 'کلاس' => 'all']
                ];
                
                foreach ($season_stats as $stat):
                    $season_info = $season_names[$stat['season']] ?? ['نام' => $stat['season'], 'آیکون' => '📦', 'کلاس' => ''];
                ?>
                <div class="season-card <?= $season_info['کلاس'] ?>">
                    <div class="icon"><?= $season_info['آیکون'] ?></div>
                    <div class="count"><?= $stat['count'] ?></div>
                    <div class="name"><?= $season_info['نام'] ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="info-box">
            <strong>📝 یادداشت:</strong>
            <ul style="list-style: disc; padding-right: 20px; margin-top: 10px;">
                <li>فیلد <code>season</code> برای دسته‌بندی محصولات بر اساس فصل اضافه شد</li>
                <li>مقادیر ممکن: spring (بهار), summer (تابستان), autumn (پاییز), winter (زمستان), all (همه فصول)</li>
                <li>محصولات موجود به صورت خودکار به فصل‌های مختلف تقسیم شدند</li>
                <li>حالا می‌توانید در صفحه محصولات فیلتر فصلی داشته باشید</li>
            </ul>
        </div>

        <div class="warning">
            <strong>⚠️ هشدار امنیتی:</strong><br>
            این فایل را پس از اجرا موفقیت‌آمیز از سرور حذف کنید<br>
            یا دسترسی به آن را محدود نمایید!
        </div>
    </div>
</body>
</html>
