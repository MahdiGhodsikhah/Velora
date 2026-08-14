<?php
/**
 * فایل تست برای بررسی محصولات ویژه فصلی
 */

// تنظیمات اولیه
define('BASE_PATH', __DIR__);
require_once BASE_PATH . '/config/database.php';

// اتصال به دیتابیس
$conn = db_connect();

echo "<!DOCTYPE html>
<html lang='fa' dir='rtl'>
<head>
    <meta charset='UTF-8'>
    <title>تست محصولات ویژه فصلی</title>
    <style>
        body { font-family: Tahoma, Arial; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { color: #333; border-bottom: 3px solid #4CAF50; padding-bottom: 10px; }
        h2 { color: #666; margin-top: 30px; background: #fff; padding: 15px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        th, td { padding: 12px; text-align: right; border-bottom: 1px solid #ddd; }
        th { background: #4CAF50; color: white; font-weight: bold; }
        tr:hover { background: #f9f9f9; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .badge-success { background: #4CAF50; color: white; }
        .badge-danger { background: #f44336; color: white; }
        .badge-warning { background: #ff9800; color: white; }
        .badge-info { background: #2196F3; color: white; }
        .count { font-size: 18px; color: #4CAF50; font-weight: bold; }
        .error { background: #f44336; color: white; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .success { background: #4CAF50; color: white; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .query { background: #333; color: #0f0; padding: 15px; border-radius: 8px; margin: 20px 0; font-family: monospace; white-space: pre-wrap; }
    </style>
</head>
<body>
<div class='container'>
    <h1>🧪 تست محصولات ویژه فصلی</h1>
";

// تست کوئری برای هر فصل
$seasons = [
    'spring' => ['label' => '🌸 بهار', 'class' => 'info'],
    'summer' => ['label' => '☀️ تابستان', 'class' => 'warning'],
    'autumn' => ['label' => '🍂 پاییز', 'class' => 'danger'],
    'winter' => ['label' => '❄️ زمستان', 'class' => 'info']
];

foreach ($seasons as $season => $info) {
    echo "<h2>{$info['label']} - محصولات ویژه</h2>";
    
    $query = "SELECT p.*, c.`name` AS category_name, c.`slug` AS category_slug
             FROM `products` p
             JOIN `categories` c ON p.`category_id` = c.`id`
             WHERE p.`is_active` = 1 
               AND p.`is_featured` = 1 
               AND (p.`season` = '$season' OR p.`season` = 'all')
             ORDER BY p.`rating_avg` DESC, p.`created_at` DESC
             LIMIT 8";
    
    echo "<div class='query'>کوئری: $query</div>";
    
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        echo "<div class='error'>❌ خطا در اجرای کوئری: " . mysqli_error($conn) . "</div>";
        continue;
    }
    
    $products = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
    
    $count = count($products);
    echo "<p class='count'>تعداد محصولات یافت شده: $count</p>";
    
    if ($count > 0) {
        echo "<table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>نام محصول</th>
                    <th>دسته‌بندی</th>
                    <th>فصل</th>
                    <th>ویژه؟</th>
                    <th>فعال؟</th>
                    <th>امتیاز</th>
                    <th>تاریخ ایجاد</th>
                </tr>
            </thead>
            <tbody>";
        
        foreach ($products as $p) {
            $seasonBadge = "<span class='badge badge-{$seasons[$p['season']]['class']}'>{$seasons[$p['season']]['label']}</span>";
            $featuredBadge = $p['is_featured'] ? "<span class='badge badge-success'>✓ ویژه</span>" : "<span class='badge badge-danger'>✗</span>";
            $activeBadge = $p['is_active'] ? "<span class='badge badge-success'>✓ فعال</span>" : "<span class='badge badge-danger'>✗</span>";
            
            echo "<tr>
                <td>{$p['id']}</td>
                <td><strong>{$p['name']}</strong><br><small>{$p['slug']}</small></td>
                <td>{$p['category_name']}</td>
                <td>$seasonBadge</td>
                <td>$featuredBadge</td>
                <td>$activeBadge</td>
                <td>{$p['rating_avg']}</td>
                <td>" . date('Y/m/d H:i', strtotime($p['created_at'])) . "</td>
            </tr>";
        }
        
        echo "</tbody></table>";
    } else {
        echo "<p style='color: #999; padding: 20px; background: #fff; border-radius: 8px;'>هیچ محصولی یافت نشد</p>";
    }
}

// تست همه محصولات ویژه
echo "<h2>📊 تمام محصولات ویژه (بدون فیلتر فصل)</h2>";

$query = "SELECT p.*, c.`name` AS category_name
         FROM `products` p
         JOIN `categories` c ON p.`category_id` = c.`id`
         WHERE p.`is_active` = 1 AND p.`is_featured` = 1
         ORDER BY p.`rating_avg` DESC, p.`created_at` DESC";

echo "<div class='query'>کوئری: $query</div>";

$result = mysqli_query($conn, $query);
$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

$count = count($products);
echo "<p class='count'>تعداد کل محصولات ویژه: $count</p>";

if ($count > 0) {
    echo "<table>
        <thead>
            <tr>
                <th>ID</th>
                <th>نام محصول</th>
                <th>دسته‌بندی</th>
                <th>فصل</th>
                <th>ویژه؟</th>
                <th>فعال؟</th>
                <th>امتیاز</th>
            </tr>
        </thead>
        <tbody>";
    
    foreach ($products as $p) {
        $seasonInfo = $seasons[$p['season']] ?? ['label' => $p['season'], 'class' => 'info'];
        $seasonBadge = "<span class='badge badge-{$seasonInfo['class']}'>{$seasonInfo['label']}</span>";
        $featuredBadge = $p['is_featured'] ? "<span class='badge badge-success'>✓ ویژه</span>" : "<span class='badge badge-danger'>✗</span>";
        $activeBadge = $p['is_active'] ? "<span class='badge badge-success'>✓ فعال</span>" : "<span class='badge badge-danger'>✗</span>";
        
        echo "<tr>
            <td>{$p['id']}</td>
            <td><strong>{$p['name']}</strong></td>
            <td>{$p['category_name']}</td>
            <td>$seasonBadge</td>
            <td>$featuredBadge</td>
            <td>$activeBadge</td>
            <td>{$p['rating_avg']}</td>
        </tr>";
    }
    
    echo "</tbody></table>";
}

echo "
    <div class='success' style='margin-top: 40px;'>
        ✅ تست کامل شد! اگر نتایج را می‌بینید، یعنی کوئری‌ها درست کار می‌کنند.
    </div>
    
    <p style='margin-top: 20px; padding: 15px; background: #fff; border-radius: 8px;'>
        <strong>⚠️ نکته:</strong> پس از مشاهده نتایج، این فایل را حذف کنید:<br>
        <code style='background: #f5f5f5; padding: 5px 10px; border-radius: 4px;'>test_products.php</code>
    </p>
</div>
</body>
</html>";

mysqli_close($conn);
?>
