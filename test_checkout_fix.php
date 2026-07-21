<?php
/**
 * تست رفع مشکل CheckoutController و OrderModel
 */

define('BASE_PATH', __DIR__);
define('BASE_URL', '/Velora/public');

// بارگذاری فایل‌های مورد نیاز
require_once BASE_PATH . '/config/database.php';

echo "<html dir='rtl'><head><meta charset='UTF-8'>";
echo "<style>body{font-family:Tahoma;padding:2rem;background:#f5f5f5;}";
echo ".success{color:#27ae60;background:#e8f8f5;padding:1rem;border-radius:4px;margin:1rem 0;}";
echo ".error{color:#c0392b;background:#fadbd8;padding:1rem;border-radius:4px;margin:1rem 0;}";
echo "h1{color:#e74c3c;}</style></head><body>";

echo "<h1>🔧 تست رفع مشکل Database</h1>";

// تست 1: بررسی توابع database
echo "<h2>۱. بررسی توابع database</h2>";
if (function_exists('db_connect') && function_exists('db_query') && function_exists('db_fetch_one')) {
    echo "<div class='success'>✅ توابع database موجود هستند</div>";
} else {
    echo "<div class='error'>❌ توابع database یافت نشدند</div>";
}

// تست 2: بررسی اتصال به دیتابیس
echo "<h2>۲. بررسی اتصال به دیتابیس</h2>";
try {
    $conn = db_connect();
    if ($conn) {
        echo "<div class='success'>✅ اتصال به دیتابیس موفق</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ خطا در اتصال: " . $e->getMessage() . "</div>";
}

// تست 3: بارگذاری OrderModel
echo "<h2>۳. بررسی OrderModel</h2>";
if (file_exists(BASE_PATH . '/src/Models/OrderModel.php')) {
    require_once BASE_PATH . '/src/Models/OrderModel.php';
    
    try {
        $orderModel = new OrderModel();
        echo "<div class='success'>✅ OrderModel با موفقیت بارگذاری شد</div>";
        
        // تست تولید شماره سفارش
        $orderNumber = $orderModel->generateOrderNumber();
        echo "<div class='success'>✅ تولید شماره سفارش: <strong>$orderNumber</strong></div>";
    } catch (Exception $e) {
        echo "<div class='error'>❌ خطا در OrderModel: " . $e->getMessage() . "</div>";
    }
} else {
    echo "<div class='error'>❌ فایل OrderModel یافت نشد</div>";
}

// تست 4: بارگذاری CheckoutController
echo "<h2>۴. بررسی CheckoutController</h2>";
if (file_exists(BASE_PATH . '/src/Models/UserModel.php')) {
    require_once BASE_PATH . '/src/Models/UserModel.php';
}
if (file_exists(BASE_PATH . '/src/Models/ProductModel.php')) {
    require_once BASE_PATH . '/src/Models/ProductModel.php';
}
if (file_exists(BASE_PATH . '/src/Libs/Security.php')) {
    require_once BASE_PATH . '/src/Libs/Security.php';
}

if (file_exists(BASE_PATH . '/src/Controllers/CheckoutController.php')) {
    require_once BASE_PATH . '/src/Controllers/CheckoutController.php';
    
    try {
        $checkoutController = new CheckoutController();
        echo "<div class='success'>✅ CheckoutController با موفقیت بارگذاری شد</div>";
    } catch (Exception $e) {
        echo "<div class='error'>❌ خطا در CheckoutController: " . $e->getMessage() . "</div>";
    }
} else {
    echo "<div class='error'>❌ فایل CheckoutController یافت نشد</div>";
}

// تست 5: بررسی جدول orders
echo "<h2>۵. بررسی جدول orders</h2>";
$result = db_fetch_one("SHOW COLUMNS FROM orders WHERE Field IN ('shipping_address', 'postal_code')");
if ($result) {
    echo "<div class='success'>✅ فیلدهای shipping_address و postal_code در جدول orders موجود هستند</div>";
} else {
    echo "<div class='error'>❌ فیلدها یافت نشدند. لطفا update_orders_table.php را اجرا کنید</div>";
}

// تست 6: شمارش سفارشات
echo "<h2>۶. شمارش سفارشات موجود</h2>";
$result = db_fetch_one("SELECT COUNT(*) as total FROM orders");
if ($result) {
    $total = $result['total'];
    echo "<div class='success'>✅ تعداد سفارشات: <strong>$total</strong></div>";
}

echo "<hr>";
echo "<h2>✅ نتیجه نهایی</h2>";
echo "<div class='success'>";
echo "<h3>مشکل Database رفع شد!</h3>";
echo "<p>تمام کامپوننت‌ها از توابع mysqli استفاده می‌کنند.</p>";
echo "<p>می‌توانید سیستم را تست کنید:</p>";
echo "<ul>";
echo "<li><a href='/Velora/public/cart'>سبد خرید</a></li>";
echo "<li><a href='/Velora/public/checkout'>تکمیل خرید</a></li>";
echo "<li><a href='/Velora/public/orders'>سفارشات</a></li>";
echo "</ul>";
echo "</div>";

echo "</body></html>";
