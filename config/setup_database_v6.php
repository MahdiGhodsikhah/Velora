<?php
/**
 * اسکریپت راه‌اندازی دیتابیس - نسخه 6
 * اضافه کردن فیلدهای جدید به جدول users
 * 
 * اضافات:
 * - job (شغل)
 * - birth_date (تاریخ تولد)
 * - postal_code (کد پستی)
 */

require_once __DIR__ . '/database.php';

echo "<h2>🔧 راه‌اندازی دیتابیس - نسخه 6</h2>";
echo "<p>اضافه کردن فیلدهای جدید به جدول users...</p>";
echo "<hr>";

// 1. اضافه کردن فیلد job (شغل)
echo "<h3>1️⃣ اضافه کردن فیلد job (شغل)</h3>";
$sql = "ALTER TABLE `users` 
        ADD COLUMN `job` VARCHAR(100) COLLATE utf8mb4_persian_ci DEFAULT NULL 
        COMMENT 'شغل کاربر' 
        AFTER `full_name`";

if (db_query($sql)) {
    echo "<p style='color:green;'>✅ فیلد job با موفقیت اضافه شد.</p>";
} else {
    $checkSql = "SHOW COLUMNS FROM `users` LIKE 'job'";
    $result = db_query($checkSql);
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<p style='color:orange;'>⚠️ فیلد job از قبل وجود دارد.</p>";
    } else {
        echo "<p style='color:red;'>❌ خطا در اضافه کردن فیلد job</p>";
    }
}

// 2. اضافه کردن فیلد birth_date (تاریخ تولد)
echo "<h3>2️⃣ اضافه کردن فیلد birth_date (تاریخ تولد)</h3>";
$sql = "ALTER TABLE `users` 
        ADD COLUMN `birth_date` DATE DEFAULT NULL 
        COMMENT 'تاریخ تولد (میلادی)' 
        AFTER `job`";

if (db_query($sql)) {
    echo "<p style='color:green;'>✅ فیلد birth_date با موفقیت اضافه شد.</p>";
} else {
    $checkSql = "SHOW COLUMNS FROM `users` LIKE 'birth_date'";
    $result = db_query($checkSql);
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<p style='color:orange;'>⚠️ فیلد birth_date از قبل وجود دارد.</p>";
    } else {
        echo "<p style='color:red;'>❌ خطا در اضافه کردن فیلد birth_date</p>";
    }
}

// 3. اضافه کردن فیلد postal_code (کد پستی)
echo "<h3>3️⃣ اضافه کردن فیلد postal_code (کد پستی)</h3>";
$sql = "ALTER TABLE `users` 
        ADD COLUMN `postal_code` VARCHAR(10) DEFAULT NULL 
        COMMENT 'کد پستی 10 رقمی' 
        AFTER `address`";

if (db_query($sql)) {
    echo "<p style='color:green;'>✅ فیلد postal_code با موفقیت اضافه شد.</p>";
} else {
    $checkSql = "SHOW COLUMNS FROM `users` LIKE 'postal_code'";
    $result = db_query($checkSql);
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<p style='color:orange;'>⚠️ فیلد postal_code از قبل وجود دارد.</p>";
    } else {
        echo "<p style='color:red;'>❌ خطا در اضافه کردن فیلد postal_code</p>";
    }
}

// 4. بررسی نهایی ساختار
echo "<h3>4️⃣ بررسی نهایی ساختار جدول users</h3>";

$result = db_query("SHOW COLUMNS FROM `users`");
if ($result) {
    echo "<table border='1' cellpadding='5' style='border-collapse:collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        $highlight = in_array($row['Field'], ['job', 'birth_date', 'postal_code']) 
            ? "style='background-color:#d4edda;'" 
            : "";
        echo "<tr $highlight>";
        echo "<td>{$row['Field']}</td>";
        echo "<td>{$row['Type']}</td>";
        echo "<td>{$row['Null']}</td>";
        echo "<td>{$row['Key']}</td>";
        echo "<td>{$row['Default']}</td>";
        echo "<td>{$row['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<hr>";
echo "<h3>✨ به‌روزرسانی دیتابیس با موفقیت انجام شد!</h3>";
echo "<p><strong>فیلدهای اضافه شده:</strong></p>";
echo "<ul>";
echo "<li>✅ job - شغل کاربر (VARCHAR 100)</li>";
echo "<li>✅ birth_date - تاریخ تولد میلادی (DATE)</li>";
echo "<li>✅ postal_code - کد پستی 10 رقمی (VARCHAR 10)</li>";
echo "</ul>";
echo "<p>اکنون می‌توانید از صفحه پروفایل استفاده کنید:</p>";
echo "<ul>";
echo "<li><a href='/profile'>صفحه ویرایش پروفایل</a></li>";
echo "<li><a href='/dashboard'>داشبورد کاربری</a></li>";
echo "</ul>";
echo "<p><a href='/'>← بازگشت به صفحه اصلی</a></p>";
