<?php
/**
 * بررسی وجود فیلد profile_image
 */

define('BASE_PATH', dirname(__DIR__));
require_once __DIR__ . '/database.php';

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>";
echo "<html lang='fa' dir='rtl'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<title>بررسی فیلد عکس پروفایل</title>";
echo "<style>body{font-family:Tahoma,Arial;padding:20px;background:#f5f5f5}";
echo ".box{background:white;padding:20px;border-radius:8px;max-width:600px;margin:0 auto;box-shadow:0 2px 10px rgba(0,0,0,0.1)}";
echo ".success{color:green}.error{color:red}.warning{color:orange}</style>";
echo "</head><body>";

echo "<div class='box'>";
echo "<h2>🔍 بررسی فیلد عکس پروفایل</h2>";
echo "<hr>";

// بررسی وجود فیلد
$checkSql = "SHOW COLUMNS FROM `users` LIKE 'profile_image'";
$result = db_query($checkSql);

if ($result && mysqli_num_rows($result) > 0) {
    echo "<p class='success'>✅ فیلد <strong>profile_image</strong> در دیتابیس وجود دارد.</p>";
    
    // نمایش جزئیات فیلد
    $row = mysqli_fetch_assoc($result);
    echo "<h3>جزئیات فیلد:</h3>";
    echo "<table border='1' cellpadding='8' style='border-collapse:collapse;width:100%'>";
    echo "<tr><th>Property</th><th>Value</th></tr>";
    foreach ($row as $key => $value) {
        echo "<tr><td><strong>{$key}</strong></td><td>{$value}</td></tr>";
    }
    echo "</table>";
    
    echo "<p class='success'>✅ همه چیز آماده است! می‌توانید از قابلیت آپلود عکس پروفایل استفاده کنید.</p>";
    echo "<p><a href='/Velora/public/profile'>← رفتن به صفحه پروفایل</a></p>";
    
} else {
    echo "<p class='warning'>⚠️ فیلد <strong>profile_image</strong> در دیتابیس وجود ندارد.</p>";
    echo "<p>برای اضافه کردن این فیلد، <a href='setup_database_v7.php'>اینجا کلیک کنید</a></p>";
}

// بررسی پوشه uploads
echo "<hr>";
echo "<h3>بررسی پوشه‌های آپلود:</h3>";

$uploadsDir = BASE_PATH . '/public/uploads/profiles';
if (file_exists($uploadsDir)) {
    echo "<p class='success'>✅ پوشه uploads/profiles وجود دارد</p>";
    
    // بررسی دسترسی نوشتن
    if (is_writable($uploadsDir)) {
        echo "<p class='success'>✅ پوشه قابل نوشتن است</p>";
    } else {
        echo "<p class='error'>❌ پوشه قابل نوشتن نیست! دسترسی را تنظیم کنید (chmod 755)</p>";
    }
} else {
    echo "<p class='error'>❌ پوشه uploads/profiles وجود ندارد</p>";
    echo "<p>برای ایجاد پوشه، <a href='setup_database_v7.php'>اینجا کلیک کنید</a></p>";
}

// بررسی .htaccess
$htaccessPath = $uploadsDir . '/.htaccess';
if (file_exists($htaccessPath)) {
    echo "<p class='success'>✅ فایل .htaccess برای امنیت وجود دارد</p>";
} else {
    echo "<p class='warning'>⚠️ فایل .htaccess وجود ندارد</p>";
}

// بررسی GD Library
echo "<hr>";
echo "<h3>بررسی GD Library:</h3>";
if (extension_loaded('gd')) {
    echo "<p class='success'>✅ GD Library نصب است</p>";
    $gdInfo = gd_info();
    echo "<ul>";
    echo "<li>Version: " . ($gdInfo['GD Version'] ?? 'نامشخص') . "</li>";
    echo "<li>JPEG Support: " . ($gdInfo['JPEG Support'] ? '✅' : '❌') . "</li>";
    echo "<li>PNG Support: " . ($gdInfo['PNG Support'] ? '✅' : '❌') . "</li>";
    echo "<li>GIF Support: " . ($gdInfo['GIF Read Support'] ? '✅' : '❌') . "</li>";
    echo "<li>WebP Support: " . ($gdInfo['WebP Support'] ?? false ? '✅' : '❌') . "</li>";
    echo "</ul>";
} else {
    echo "<p class='error'>❌ GD Library نصب نیست! برای پردازش تصاویر نیاز است.</p>";
}

echo "</div>";
echo "</body></html>";
