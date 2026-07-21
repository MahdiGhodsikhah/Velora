<?php
/**
 * به‌روزرسانی جدول orders - اضافه کردن فیلدهای آدرس و کد پستی
 */

// اتصال مستقیم به دیتابیس
$host = '127.0.0.1';
$dbname = 'autumn_shop';
$username = 'root';
$password = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "شروع به‌روزرسانی جدول orders...\n";
    
    // بررسی وجود فیلدها
    $stmt = $db->query("SHOW COLUMNS FROM orders LIKE 'shipping_address'");
    $hasShippingAddress = $stmt->fetch() !== false;
    
    $stmt = $db->query("SHOW COLUMNS FROM orders LIKE 'postal_code'");
    $hasPostalCode = $stmt->fetch() !== false;
    
    // اضافه کردن فیلد shipping_address
    if (!$hasShippingAddress) {
        echo "اضافه کردن فیلد shipping_address...\n";
        $db->exec("ALTER TABLE orders ADD COLUMN shipping_address TEXT COLLATE utf8mb4_persian_ci NULL AFTER shipping_cost");
        echo "✓ فیلد shipping_address اضافه شد\n";
    } else {
        echo "✓ فیلد shipping_address از قبل وجود دارد\n";
    }
    
    // اضافه کردن فیلد postal_code
    if (!$hasPostalCode) {
        echo "اضافه کردن فیلد postal_code...\n";
        $db->exec("ALTER TABLE orders ADD COLUMN postal_code VARCHAR(10) COLLATE utf8mb4_persian_ci NULL AFTER shipping_address");
        echo "✓ فیلد postal_code اضافه شد\n";
    } else {
        echo "✓ فیلد postal_code از قبل وجود دارد\n";
    }
    
    echo "\n✅ به‌روزرسانی جدول orders با موفقیت انجام شد!\n";
    
} catch (PDOException $e) {
    echo "❌ خطا: " . $e->getMessage() . "\n";
    exit(1);
}
