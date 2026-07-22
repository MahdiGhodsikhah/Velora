<?php
/**
 * تست کامل سیستم تم دینامیک
 * این اسکریپت تمام بخش‌های سیستم تم را تست می‌کند
 */

require_once __DIR__ . '/database.php';
require_once dirname(__DIR__) . '/src/Libs/ThemeManager.php';
require_once dirname(__DIR__) . '/src/Libs/jdf.php';

?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تست سیستم تم</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Tahoma, Arial, sans-serif;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        .header h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .test-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .test-section h2 {
            color: #d97706;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #fed7aa;
        }
        .test-item {
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            border-left: 4px solid #ddd;
        }
        .test-pass {
            background: #d4edda;
            border-left-color: #28a745;
            color: #155724;
        }
        .test-fail {
            background: #f8d7da;
            border-left-color: #dc3545;
            color: #721c24;
        }
        .test-info {
            background: #d1ecf1;
            border-left-color: #0dcaf0;
            color: #055160;
        }
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin: 0 5px;
        }
        .badge-success { background: #28a745; color: white; }
        .badge-danger { background: #dc3545; color: white; }
        .badge-info { background: #0dcaf0; color: white; }
        .badge-warning { background: #ffc107; color: #000; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            padding: 12px;
            text-align: right;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
        }
        .icon { font-size: 1.2rem; margin-left: 5px; }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .stat-card h3 {
            font-size: 2rem;
            margin-bottom: 5px;
        }
        .stat-card p {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <div class="header">
            <h1>🎨 تست کامل سیستم تم دینامیک</h1>
            <p>بررسی عملکرد تمام بخش‌های سیستم مدیریت تم چندفصلی</p>
        </div>

        <?php
        $themeManager = ThemeManager::getInstance();
        $totalTests = 0;
        $passedTests = 0;
        $failedTests = 0;
        
        /**
         * تست 1: بررسی جدول site_settings
         */
        echo '<div class="test-section">';
        echo '<h2><span class="icon">📊</span>تست 1: جدول پایگاه داده</h2>';
        
        $tableExists = db_query("SHOW TABLES LIKE 'site_settings'");
        $totalTests++;
        if ($tableExists && mysqli_num_rows($tableExists) > 0) {
            echo '<div class="test-item test-pass">✅ جدول site_settings وجود دارد</div>';
            $passedTests++;
        } else {
            echo '<div class="test-item test-fail">❌ جدول site_settings وجود ندارد</div>';
            $failedTests++;
        }
        
        $settings = db_fetch_all("SELECT * FROM site_settings WHERE setting_key LIKE 'theme%' OR setting_key = 'active_theme'");
        echo '<table>';
        echo '<tr><th>کلید</th><th>مقدار</th><th>نوع</th></tr>';
        foreach ($settings as $setting) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($setting['setting_key']) . '</td>';
            echo '<td><strong>' . htmlspecialchars($setting['setting_value']) . '</strong></td>';
            echo '<td>' . htmlspecialchars($setting['setting_type']) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
        
        /**
         * تست 2: تشخیص فصل
         */
        echo '<div class="test-section">';
        echo '<h2><span class="icon">🗓️</span>تست 2: تشخیص خودکار فصل</h2>';
        
        $currentSeason = $themeManager->detectCurrentSeason();
        $totalTests++;
        if (in_array($currentSeason, ['spring', 'summer', 'autumn', 'winter'])) {
            echo '<div class="test-item test-pass">✅ تشخیص فصل با موفقیت انجام شد</div>';
            $passedTests++;
        } else {
            echo '<div class="test-item test-fail">❌ خطا در تشخیص فصل</div>';
            $failedTests++;
        }
        
        // نمایش اطلاعات تاریخ
        $today = gregorian_to_jalali(date('Y'), date('m'), date('d'));
        $jalaliDate = implode('/', $today);
        $seasonMap = ThemeManager::SEASON_MAP;
        
        echo '<div class="test-item test-info">';
        echo '<strong>تاریخ امروز (شمسی):</strong> ' . $jalaliDate . '<br>';
        echo '<strong>فصل جاری:</strong> ' . $seasonMap[$currentSeason] . ' (' . $currentSeason . ')';
        echo '</div>';
        echo '</div>';
        
        /**
         * تست 3: دریافت تم فعال
         */
        echo '<div class="test-section">';
        echo '<h2><span class="icon">🎨</span>تست 3: تم فعال</h2>';
        
        $activeTheme = $themeManager->getActiveTheme();
        $totalTests++;
        if (in_array($activeTheme, ThemeManager::THEMES)) {
            echo '<div class="test-item test-pass">✅ تم فعال با موفقیت دریافت شد</div>';
            $passedTests++;
        } else {
            echo '<div class="test-item test-fail">❌ تم فعال نامعتبر است</div>';
            $failedTests++;
        }
        
        $themeSettings = $themeManager->getThemeSettings();
        echo '<div class="test-item test-info">';
        echo '<strong>تم فعال:</strong> ' . $activeTheme . '<br>';
        echo '<strong>تشخیص خودکار:</strong> ' . ($themeSettings['auto_detect'] ? '✅ فعال' : '❌ غیرفعال') . '<br>';
        echo '<strong>انتخاب توسط کاربر:</strong> ' . ($themeSettings['allow_user_choice'] ? '✅ فعال' : '❌ غیرفعال');
        echo '</div>';
        echo '</div>';
        
        /**
         * تست 4: فایل‌های CSS تم
         */
        echo '<div class="test-section">';
        echo '<h2><span class="icon">📁</span>تست 4: فایل‌های CSS تم</h2>';
        
        $themesPath = dirname(__DIR__) . '/public/assets/css/themes/';
        $themes = ['autumn', 'winter', 'spring', 'summer'];
        
        foreach ($themes as $theme) {
            $filePath = $themesPath . 'theme-' . $theme . '.css';
            $totalTests++;
            if (file_exists($filePath)) {
                $fileSize = filesize($filePath);
                echo '<div class="test-item test-pass">';
                echo '✅ theme-' . $theme . '.css وجود دارد (' . number_format($fileSize) . ' بایت)';
                echo '</div>';
                $passedTests++;
            } else {
                echo '<div class="test-item test-fail">';
                echo '❌ theme-' . $theme . '.css یافت نشد';
                echo '</div>';
                $failedTests++;
            }
        }
        echo '</div>';
        
        /**
         * تست 5: فایل JavaScript
         */
        echo '<div class="test-section">';
        echo '<h2><span class="icon">⚙️</span>تست 5: فایل JavaScript</h2>';
        
        $jsPath = dirname(__DIR__) . '/public/assets/js/theme-manager.js';
        $totalTests++;
        if (file_exists($jsPath)) {
            $fileSize = filesize($jsPath);
            echo '<div class="test-item test-pass">';
            echo '✅ theme-manager.js وجود دارد (' . number_format($fileSize) . ' بایت)';
            echo '</div>';
            $passedTests++;
        } else {
            echo '<div class="test-item test-fail">❌ theme-manager.js یافت نشد</div>';
            $failedTests++;
        }
        echo '</div>';
        
        /**
         * تست 6: محصولات با فصل
         */
        echo '<div class="test-section">';
        echo '<h2><span class="icon">📦</span>تست 6: محصولات</h2>';
        
        // بررسی ستون season
        $columns = db_query("SHOW COLUMNS FROM products LIKE 'season'");
        $totalTests++;
        if ($columns && mysqli_num_rows($columns) > 0) {
            echo '<div class="test-item test-pass">✅ ستون season در جدول products وجود دارد</div>';
            $passedTests++;
            
            // شمارش محصولات به تفکیک فصل
            $seasonCounts = [];
            foreach (['spring', 'summer', 'autumn', 'winter', 'all'] as $season) {
                $result = db_fetch_one("SELECT COUNT(*) as count FROM products WHERE season = '$season'");
                $seasonCounts[$season] = $result['count'] ?? 0;
            }
            
            echo '<table>';
            echo '<tr><th>فصل</th><th>تعداد محصولات</th></tr>';
            foreach ($seasonCounts as $season => $count) {
                $icon = ['spring' => '🌸', 'summer' => '☀️', 'autumn' => '🍂', 'winter' => '❄️', 'all' => '🌍'][$season];
                $name = $seasonMap[$season] ?? 'همه فصول';
                echo '<tr><td>' . $icon . ' ' . $name . '</td><td><strong>' . $count . '</strong></td></tr>';
            }
            echo '</table>';
            
        } else {
            echo '<div class="test-item test-fail">❌ ستون season در جدول products وجود ندارد</div>';
            $failedTests++;
        }
        echo '</div>';
        
        /**
         * تست 7: کلاس‌های PHP
         */
        echo '<div class="test-section">';
        echo '<h2><span class="icon">🔧</span>تست 7: کلاس‌های PHP</h2>';
        
        $totalTests++;
        if (class_exists('ThemeManager')) {
            echo '<div class="test-item test-pass">✅ کلاس ThemeManager بارگذاری شده</div>';
            $passedTests++;
        } else {
            echo '<div class="test-item test-fail">❌ کلاس ThemeManager بارگذاری نشده</div>';
            $failedTests++;
        }
        
        $totalTests++;
        if (class_exists('ThemeController')) {
            echo '<div class="test-item test-pass">✅ کلاس ThemeController بارگذاری شده</div>';
            $passedTests++;
        } else {
            echo '<div class="test-item test-fail">❌ کلاس ThemeController بارگذاری نشده</div>';
            $failedTests++;
        }
        echo '</div>';
        
        /**
         * آمار نهایی
         */
        $successRate = round(($passedTests / $totalTests) * 100);
        $statusClass = $successRate >= 80 ? 'badge-success' : ($successRate >= 50 ? 'badge-warning' : 'badge-danger');
        
        echo '<div class="test-section">';
        echo '<h2><span class="icon">📈</span>آمار نهایی</h2>';
        echo '<div class="stats">';
        echo '<div class="stat-card">';
        echo '<h3>' . $totalTests . '</h3>';
        echo '<p>تست کل</p>';
        echo '</div>';
        echo '<div class="stat-card">';
        echo '<h3>' . $passedTests . '</h3>';
        echo '<p>✅ موفق</p>';
        echo '</div>';
        echo '<div class="stat-card">';
        echo '<h3>' . $failedTests . '</h3>';
        echo '<p>❌ ناموفق</p>';
        echo '</div>';
        echo '<div class="stat-card">';
        echo '<h3>' . $successRate . '%</h3>';
        echo '<p>نرخ موفقیت</p>';
        echo '</div>';
        echo '</div>';
        
        if ($successRate == 100) {
            echo '<div class="test-item test-pass" style="text-align: center; font-size: 1.2rem;">';
            echo '🎉 همه تست‌ها با موفقیت انجام شد! سیستم تم آماده استفاده است.';
            echo '</div>';
        } elseif ($successRate >= 80) {
            echo '<div class="test-item test-info" style="text-align: center;">';
            echo '⚠️ اکثر تست‌ها موفق بودند. لطفا موارد ناموفق را بررسی کنید.';
            echo '</div>';
        } else {
            echo '<div class="test-item test-fail" style="text-align: center;">';
            echo '❌ تست‌های زیادی ناموفق بودند. لطفا نصب را مجدد انجام دهید.';
            echo '</div>';
        }
        echo '</div>';
        ?>

        <div class="test-section">
            <h2><span class="icon">🔗</span>لینک‌های مفید</h2>
            <ul style="line-height: 2;">
                <li><a href="/Velora/public/" target="_blank">🏠 صفحه اصلی</a></li>
                <li><a href="/Velora/public/products" target="_blank">🛍️ لیست محصولات</a></li>
                <li><a href="/Velora/public/products?season=winter" target="_blank">❄️ محصولات زمستانی (تست تم)</a></li>
                <li><a href="/Velora/public/admin/theme-settings" target="_blank">⚙️ پنل مدیریت تم</a></li>
                <li><a href="/Velora/config/update_products_season.php" target="_blank">📝 به‌روزرسانی فصل محصولات</a></li>
                <li><a href="/Velora/THEME_SYSTEM_README.md" target="_blank">📖 مستندات کامل</a></li>
            </ul>
        </div>

    </div>
</body>
</html>
