<?php
/**
 * بررسی مسیر عکس پروفایل در دیتابیس
 */

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/config/database.php';

echo "<h2>بررسی عکس‌های پروفایل کاربران</h2>";
echo "<hr>";

// دریافت تمام کاربران
$users = db_fetch_all("SELECT id, username, profile_image FROM users");

foreach ($users as $user) {
    echo "<div style='margin-bottom: 20px; padding: 15px; border: 1px solid #ddd;'>";
    echo "<strong>User ID:</strong> {$user['id']}<br>";
    echo "<strong>Username:</strong> {$user['username']}<br>";
    echo "<strong>Profile Image (از DB):</strong> " . ($user['profile_image'] ?? 'NULL') . "<br>";
    
    if (!empty($user['profile_image'])) {
        $imagePath = $user['profile_image'];
        
        echo "<strong>مسیرهای مختلف:</strong><br>";
        
        // حالت 1: مسیر مستقیم
        $path1 = BASE_PATH . '/public' . $imagePath;
        echo "1. BASE_PATH/public + profile_image: <code>$path1</code> - " . (file_exists($path1) ? '✅ EXISTS' : '❌ NOT FOUND') . "<br>";
        
        // حالت 2: بدون /public
        $path2 = BASE_PATH . $imagePath;
        echo "2. BASE_PATH + profile_image: <code>$path2</code> - " . (file_exists($path2) ? '✅ EXISTS' : '❌ NOT FOUND') . "<br>";
        
        // حالت 3: با /public در اول مسیر
        if (strpos($imagePath, '/public') === false) {
            $path3 = BASE_PATH . '/public' . $imagePath;
        } else {
            $path3 = BASE_PATH . $imagePath;
        }
        echo "3. Smart check: <code>$path3</code> - " . (file_exists($path3) ? '✅ EXISTS' : '❌ NOT FOUND') . "<br>";
        
        // نمایش عکس
        echo "<br><strong>تست نمایش عکس:</strong><br>";
        echo "URL 1: <code>/public{$imagePath}</code><br>";
        echo "<img src='/public{$imagePath}' style='width:100px; height:100px; object-fit:cover; border-radius:50%; margin: 10px;' onerror=\"this.style.border='2px solid red';\">";
        
        echo "<br>URL 2: <code>{$imagePath}</code><br>";
        echo "<img src='{$imagePath}' style='width:100px; height:100px; object-fit:cover; border-radius:50%; margin: 10px;' onerror=\"this.style.border='2px solid red';\">";
    }
    
    echo "</div>";
}

echo "<hr>";
echo "<h3>BASE_PATH: " . BASE_PATH . "</h3>";
echo "<h3>Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "</h3>";
