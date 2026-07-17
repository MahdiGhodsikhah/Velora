<?php
/**
 * Migration: تغییر سیستم احراز هویت به شماره موبایل
 * - email می‌تواند NULL باشد
 * - phone باید UNIQUE باشد
 * - phone نمی‌تواند NULL باشد
 */

require_once __DIR__ . '/../config/database.php';

echo "🔄 شروع Migration برای تغییر سیستم احراز هویت...\n\n";

try {
    // 1. تغییر email به NULL
    echo "1️⃣ تغییر فیلد email به nullable...\n";
    $sql1 = "ALTER TABLE `users` MODIFY `email` varchar(150) DEFAULT NULL";
    db_query($sql1);
    echo "   ✅ email اکنون می‌تواند NULL باشد\n\n";

    // 2. حذف UNIQUE constraint از email (اگر وجود دارد)
    echo "2️⃣ حذف UNIQUE constraint از email...\n";
    $sql2 = "ALTER TABLE `users` DROP INDEX `email`";
    if (db_query($sql2)) {
        echo "   ✅ UNIQUE constraint از email حذف شد\n\n";
    } else {
        echo "   ⚠️  UNIQUE constraint وجود نداشت یا قبلاً حذف شده\n\n";
    }

    // 3. تغییر phone به NOT NULL
    echo "3️⃣ تغییر فیلد phone به NOT NULL...\n";
    
    // ابتدا مقادیر NULL را با یک شماره موقت پر می‌کنیم
    $sql3a = "UPDATE `users` SET `phone` = CONCAT('temp_', id) WHERE `phone` IS NULL OR `phone` = ''";
    db_query($sql3a);
    
    $sql3b = "ALTER TABLE `users` MODIFY `phone` varchar(20) NOT NULL";
    db_query($sql3b);
    echo "   ✅ phone اکنون الزامی است (NOT NULL)\n\n";

    // 4. اضافه کردن UNIQUE constraint به phone
    echo "4️⃣ اضافه کردن UNIQUE constraint به phone...\n";
    $sql4 = "ALTER TABLE `users` ADD UNIQUE KEY `phone` (`phone`)";
    if (db_query($sql4)) {
        echo "   ✅ phone اکنون باید یکتا باشد (UNIQUE)\n\n";
    } else {
        echo "   ⚠️  UNIQUE constraint قبلاً اضافه شده بود\n\n";
    }

    // 5. اضافه کردن INDEX برای جستجوی سریع
    echo "5️⃣ اضافه کردن INDEX برای phone...\n";
    $sql5 = "ALTER TABLE `users` ADD INDEX `idx_phone` (`phone`)";
    if (db_query($sql5)) {
        echo "   ✅ INDEX برای phone اضافه شد\n\n";
    } else {
        echo "   ⚠️  INDEX قبلاً وجود داشت\n\n";
    }

    echo "✅ Migration با موفقیت انجام شد!\n\n";
    echo "📝 تغییرات اعمال شده:\n";
    echo "   - email: nullable (می‌تواند خالی باشد)\n";
    echo "   - phone: NOT NULL + UNIQUE (الزامی و یکتا)\n";
    echo "   - INDEX برای جستجوی سریع phone\n\n";

} catch (Exception $e) {
    echo "❌ خطا در Migration: " . $e->getMessage() . "\n";
    exit(1);
}

echo "✅ همه چیز آماده است!\n";
echo "🎉 اکنون می‌توانید با شماره موبایل ثبت‌نام و ورود کنید.\n";
