<?php
/**
 * اسکریپت راه‌اندازی دیتابیس - نسخه 5
 * اصلاح ساختار جدول برای رفع خطاهای پنل کاربری
 * 
 * اصلاحات:
 * - اضافه کردن فیلد address به جدول users
 * - اصلاح نام فیلد created_at به added_at در جدول wishlist
 */

require_once __DIR__ . '/database.php';

echo "<h2>🔧 راه‌اندازی دیتابیس - نسخه 5</h2>";
echo "<p>اصلاح ساختار جداول برای پنل کاربری...</p>";
echo "<hr>";

// 1. اضافه کردن فیلد address به جدول users
echo "<h3>1️⃣ اضافه کردن فیلد address به جدول users</h3>";
$sql = "ALTER TABLE `users` 
        ADD COLUMN `address` TEXT COLLATE utf8mb4_persian_ci DEFAULT NULL 
        COMMENT 'آدرس کاربر' 
        AFTER `phone`";

if (db_query($sql)) {
    echo "<p style='color:green;'>✅ فیلد address با موفقیت به جدول users اضافه شد.</p>";
} else {
    // بررسی اینکه آیا فیلد از قبل وجود دارد
    $checkSql = "SHOW COLUMNS FROM `users` LIKE 'address'";
    $result = db_query($checkSql);
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<p style='color:orange;'>⚠️ فیلد address از قبل وجود دارد.</p>";
    } else {
        echo "<p style='color:red;'>❌ خطا در اضافه کردن فیلد address</p>";
    }
}

// 2. بررسی و اصلاح ساختار جدول wishlist
echo "<h3>2️⃣ بررسی و اصلاح جدول wishlist</h3>";

// ابتدا بررسی می‌کنیم که کدام فیلد وجود دارد
$checkCreatedAt = "SHOW COLUMNS FROM `wishlist` LIKE 'created_at'";
$checkAddedAt = "SHOW COLUMNS FROM `wishlist` LIKE 'added_at'";

$hasCreatedAt = false;
$hasAddedAt = false;

$result = db_query($checkCreatedAt);
if ($result && mysqli_num_rows($result) > 0) {
    $hasCreatedAt = true;
}

$result = db_query($checkAddedAt);
if ($result && mysqli_num_rows($result) > 0) {
    $hasAddedAt = true;
}

if ($hasCreatedAt && !$hasAddedAt) {
    // اگر created_at وجود دارد و added_at وجود ندارد، نام را تغییر می‌دهیم
    $sql = "ALTER TABLE `wishlist` CHANGE `created_at` `added_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP";
    if (db_query($sql)) {
        echo "<p style='color:green;'>✅ نام فیلد created_at به added_at تغییر یافت.</p>";
    } else {
        echo "<p style='color:red;'>❌ خطا در تغییر نام فیلد.</p>";
    }
} elseif (!$hasCreatedAt && !$hasAddedAt) {
    // اگر هیچکدام وجود ندارد، added_at را اضافه می‌کنیم
    $sql = "ALTER TABLE `wishlist` ADD COLUMN `added_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP";
    if (db_query($sql)) {
        echo "<p style='color:green;'>✅ فیلد added_at به جدول wishlist اضافه شد.</p>";
    } else {
        echo "<p style='color:red;'>❌ خطا در اضافه کردن فیلد added_at</p>";
    }
} elseif ($hasAddedAt) {
    echo "<p style='color:orange;'>⚠️ فیلد added_at از قبل وجود دارد.</p>";
} else {
    echo "<p style='color:orange;'>⚠️ هر دو فیلد وجود دارند. لطفا manually بررسی کنید.</p>";
}

// 3. اصلاح کوئری UserModel (این فقط یادآوری است، باید در کد اصلاح شود)
echo "<h3>3️⃣ یادآوری: اصلاح کوئری در UserModel</h3>";
echo "<p style='color:blue;'>ℹ️ در متد getWishlist کلاس UserModel، نام فیلد w.created_at به w.added_at تغییر یافته است.</p>";

// 4. بررسی نهایی ساختار
echo "<h3>4️⃣ بررسی نهایی ساختار جداول</h3>";

// بررسی users
$result = db_query("SHOW COLUMNS FROM `users`");
if ($result) {
    echo "<h4>ساختار جدول users:</h4>";
    echo "<table border='1' cellpadding='5' style='border-collapse:collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        $highlight = ($row['Field'] === 'address') ? "style='background-color:#d4edda;'" : "";
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

// بررسی wishlist
$result = db_query("SHOW COLUMNS FROM `wishlist`");
if ($result) {
    echo "<h4>ساختار جدول wishlist:</h4>";
    echo "<table border='1' cellpadding='5' style='border-collapse:collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        $highlight = ($row['Field'] === 'added_at') ? "style='background-color:#d4edda;'" : "";
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
echo "<h3>✨ اصلاحات با موفقیت انجام شد!</h3>";
echo "<p>اکنون می‌توانید از صفحات زیر استفاده کنید:</p>";
echo "<ul>";
echo "<li><a href='/profile'>صفحه ویرایش پروفایل</a></li>";
echo "<li><a href='/wishlist'>صفحه علاقه‌مندی‌ها</a></li>";
echo "<li><a href='/dashboard'>داشبورد کاربری</a></li>";
echo "</ul>";
echo "<p><a href='/'>← بازگشت به صفحه اصلی</a></p>";
