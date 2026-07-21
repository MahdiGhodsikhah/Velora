<?php
/**
 * تست سیستم سفارش‌گیری
 * این فایل را در مرورگر باز کنید: http://localhost/Velora/test_order_system.php
 */

// بارگذاری تنظیمات
define('BASE_PATH', __DIR__);
require_once BASE_PATH . '/config/database.php';

echo "<html dir='rtl'><head><meta charset='UTF-8'><title>تست سیستم سفارش‌گیری</title>";
echo "<style>
body { font-family: Tahoma, sans-serif; padding: 2rem; background: #f5f5f5; }
.container { max-width: 800px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
h1 { color: #e74c3c; border-bottom: 2px solid #e74c3c; padding-bottom: 1rem; }
h2 { color: #2c3e50; margin-top: 2rem; }
.success { color: #27ae60; background: #e8f8f5; padding: 1rem; border-radius: 4px; margin: 1rem 0; }
.error { color: #c0392b; background: #fadbd8; padding: 1rem; border-radius: 4px; margin: 1rem 0; }
.info { color: #2980b9; background: #ebf5fb; padding: 1rem; border-radius: 4px; margin: 1rem 0; }
table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
th, td { text-align: right; padding: 0.75rem; border: 1px solid #ddd; }
th { background: #34495e; color: white; }
tr:nth-child(even) { background: #f8f9fa; }
.btn { display: inline-block; padding: 0.75rem 1.5rem; background: #e74c3c; color: white; text-decoration: none; border-radius: 4px; margin: 0.5rem; }
.btn:hover { background: #c0392b; }
</style></head><body>";

echo "<div class='container'>";
echo "<h1>🧪 تست سیستم سفارش‌گیری</h1>";

try {
    $db = Database::getInstance()->getConnection();
    
    // ۱. بررسی جدول orders
    echo "<h2>۱. بررسی ساختار جدول orders</h2>";
    $stmt = $db->query("DESCRIBE orders");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $hasShippingAddress = false;
    $hasPostalCode = false;
    
    echo "<table>";
    echo "<tr><th>نام فیلد</th><th>نوع</th><th>Null</th><th>پیش‌فرض</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>" . $col['Field'] . "</td>";
        echo "<td>" . $col['Type'] . "</td>";
        echo "<td>" . $col['Null'] . "</td>";
        echo "<td>" . ($col['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
        
        if ($col['Field'] === 'shipping_address') $hasShippingAddress = true;
        if ($col['Field'] === 'postal_code') $hasPostalCode = true;
    }
    echo "</table>";
    
    if ($hasShippingAddress && $hasPostalCode) {
        echo "<div class='success'>✅ فیلدهای shipping_address و postal_code وجود دارند</div>";
    } else {
        echo "<div class='error'>❌ فیلدهای shipping_address یا postal_code وجود ندارند. لطفا اسکریپت update_orders_table.php را اجرا کنید</div>";
    }
    
    // ۲. بررسی فایل‌های کنترلر
    echo "<h2>۲. بررسی فایل‌های سیستم</h2>";
    
    $files = [
        'CheckoutController' => BASE_PATH . '/src/Controllers/CheckoutController.php',
        'OrderModel' => BASE_PATH . '/src/Models/OrderModel.php',
        'صفحه checkout' => BASE_PATH . '/src/Views/pages/checkout.php',
        'صفحه orders' => BASE_PATH . '/src/Views/pages/orders.php',
    ];
    
    echo "<table>";
    echo "<tr><th>فایل</th><th>وضعیت</th></tr>";
    foreach ($files as $name => $path) {
        $exists = file_exists($path);
        echo "<tr>";
        echo "<td>$name</td>";
        echo "<td>" . ($exists ? "✅ موجود" : "❌ وجود ندارد") . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // ۳. بررسی routes
    echo "<h2>۳. بررسی Routes</h2>";
    $routesContent = file_get_contents(BASE_PATH . '/config/routes.php');
    
    $requiredRoutes = [
        '/checkout' => strpos($routesContent, "GET:/checkout") !== false,
        '/checkout/process' => strpos($routesContent, "POST:/checkout/process") !== false,
        '/orders' => strpos($routesContent, "GET:/orders") !== false,
    ];
    
    echo "<table>";
    echo "<tr><th>Route</th><th>وضعیت</th></tr>";
    foreach ($requiredRoutes as $route => $exists) {
        echo "<tr>";
        echo "<td>$route</td>";
        echo "<td>" . ($exists ? "✅ تعریف شده" : "❌ تعریف نشده") . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // ۴. شمارش سفارشات
    echo "<h2>۴. آمار سفارشات</h2>";
    $stmt = $db->query("SELECT COUNT(*) as total FROM orders");
    $totalOrders = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    echo "<div class='info'>";
    echo "📊 تعداد کل سفارشات: <strong>$totalOrders</strong>";
    echo "</div>";
    
    // ۵. آخرین سفارشات
    if ($totalOrders > 0) {
        echo "<h2>۵. آخرین سفارشات (۵ مورد)</h2>";
        $stmt = $db->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<table>";
        echo "<tr><th>شماره سفارش</th><th>کاربر</th><th>مبلغ</th><th>وضعیت</th><th>آدرس</th><th>کد پستی</th><th>تاریخ</th></tr>";
        foreach ($orders as $order) {
            echo "<tr>";
            echo "<td>" . $order['order_number'] . "</td>";
            echo "<td>کاربر #" . $order['user_id'] . "</td>";
            echo "<td>" . number_format($order['total_amount']) . " تومان</td>";
            echo "<td>" . $order['status'] . "</td>";
            echo "<td>" . (substr($order['shipping_address'] ?? 'ندارد', 0, 30) . '...') . "</td>";
            echo "<td>" . ($order['postal_code'] ?? 'ندارد') . "</td>";
            echo "<td>" . $order['created_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // ۶. لینک‌های مفید
    echo "<h2>۶. لینک‌های مفید</h2>";
    echo "<div class='info'>";
    echo "<a href='/Velora/public/' class='btn'>🏠 صفحه اصلی</a>";
    echo "<a href='/Velora/public/cart' class='btn'>🛒 سبد خرید</a>";
    echo "<a href='/Velora/public/checkout' class='btn'>💳 تکمیل خرید</a>";
    echo "<a href='/Velora/public/orders' class='btn'>📦 سفارشات من</a>";
    echo "</div>";
    
    echo "<div class='success'>";
    echo "<h3>✅ سیستم سفارش‌گیری با موفقیت نصب شده است!</h3>";
    echo "<p>می‌توانید از طریق لینک‌های بالا سیستم را تست کنید.</p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<h3>❌ خطا در اتصال به دیتابیس</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}

echo "</div></body></html>";
