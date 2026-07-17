<?php
/**
 * اسکریپت راه‌اندازی دیتابیس - نسخه 7
 * اضافه کردن فیلد profile_image به جدول users
 */

// تعریف BASE_PATH
define('BASE_PATH', dirname(__DIR__));

require_once __DIR__ . '/database.php';

echo "<h2>🔧 راه‌اندازی دیتابیس - نسخه 7</h2>";
echo "<p>اضافه کردن فیلد عکس پروفایل به جدول users...</p>";
echo "<hr>";

// 1. اضافه کردن فیلد profile_image
echo "<h3>1️⃣ اضافه کردن فیلد profile_image</h3>";

// ابتدا بررسی کنیم که فیلد وجود دارد یا نه
$checkSql = "SHOW COLUMNS FROM `users` LIKE 'profile_image'";
$result = db_query($checkSql);

if ($result && mysqli_num_rows($result) > 0) {
    echo "<p style='color:orange;'>⚠️ فیلد profile_image از قبل وجود دارد.</p>";
} else {
    $sql = "ALTER TABLE `users` 
            ADD COLUMN `profile_image` VARCHAR(255) DEFAULT NULL 
            COMMENT 'مسیر عکس پروفایل' 
            AFTER `full_name`";
    
    if (db_query($sql)) {
        echo "<p style='color:green;'>✅ فیلد profile_image با موفقیت اضافه شد.</p>";
    } else {
        echo "<p style='color:red;'>❌ خطا در اضافه کردن فیلد profile_image</p>";
    }
}

// 2. ایجاد پوشه uploads اگر وجود ندارد
echo "<h3>2️⃣ ایجاد پوشه‌های مورد نیاز</h3>";

$uploadsDir = BASE_PATH . '/public/uploads';
$profilesDir = $uploadsDir . '/profiles';

if (!file_exists($uploadsDir)) {
    if (mkdir($uploadsDir, 0755, true)) {
        echo "<p style='color:green;'>✅ پوشه uploads ایجاد شد.</p>";
    } else {
        echo "<p style='color:red;'>❌ خطا در ایجاد پوشه uploads</p>";
    }
} else {
    echo "<p style='color:orange;'>⚠️ پوشه uploads از قبل وجود دارد.</p>";
}

if (!file_exists($profilesDir)) {
    if (mkdir($profilesDir, 0755, true)) {
        echo "<p style='color:green;'>✅ پوشه profiles ایجاد شد.</p>";
    } else {
        echo "<p style='color:red;'>❌ خطا در ایجاد پوشه profiles</p>";
    }
} else {
    echo "<p style='color:orange;'>⚠️ پوشه profiles از قبل وجود دارد.</p>";
}

// 3. ایجاد فایل .htaccess برای امنیت
$htaccessContent = "# Prevent PHP execution in uploads folder
<FilesMatch \"\.(php|php3|php4|php5|phtml)$\">
    Order Deny,Allow
    Deny from All
</FilesMatch>

# Allow images only
<FilesMatch \"\.(jpg|jpeg|png|gif|webp)$\">
    Order Allow,Deny
    Allow from All
</FilesMatch>
";

$htaccessPath = $uploadsDir . '/.htaccess';
if (file_put_contents($htaccessPath, $htaccessContent)) {
    echo "<p style='color:green;'>✅ فایل .htaccess برای امنیت ایجاد شد.</p>";
} else {
    echo "<p style='color:orange;'>⚠️ خطا در ایجاد .htaccess</p>";
}

// 4. بررسی نهایی
echo "<h3>3️⃣ بررسی نهایی ساختار</h3>";

$result = db_query("SHOW COLUMNS FROM `users`");
if ($result) {
    echo "<table border='1' cellpadding='5' style='border-collapse:collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        $highlight = ($row['Field'] === 'profile_image') ? "style='background-color:#d4edda;'" : "";
        echo "<tr $highlight>";
        echo "<td>{$row['Field']}</td>";
        echo "<td>{$row['Type']}</td>";
        echo "<td>{$row['Null']}</td>";
        echo "<td>{$row['Key']}</td>";
        echo "<td>{$row['Default']}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<hr>";
echo "<h3>✨ به‌روزرسانی با موفقیت انجام شد!</h3>";
echo "<p><strong>تغییرات:</strong></p>";
echo "<ul>";
echo "<li>✅ فیلد profile_image اضافه شد (VARCHAR 255)</li>";
echo "<li>✅ پوشه uploads/profiles ایجاد شد</li>";
echo "<li>✅ فایل .htaccess برای امنیت ایجاد شد</li>";
echo "</ul>";
echo "<p>حداکثر حجم مجاز: 2MB | فرمت‌های مجاز: JPG, PNG, GIF, WEBP</p>";
echo "<p><a href='/profile'>← بازگشت به صفحه پروفایل</a></p>";
