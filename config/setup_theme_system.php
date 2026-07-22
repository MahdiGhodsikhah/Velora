<?php
/**
 * اسکریپت راه‌اندازی سیستم تم دینامیک چندفصلی
 * این فایل جدول site_settings را ایجاد می‌کند
 */

require_once __DIR__ . '/database.php';

echo "شروع راه‌اندازی سیستم تم...\n";

// ایجاد جدول تنظیمات سایت
$sql_settings = "
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) COLLATE utf8mb4_persian_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_persian_ci,
  `setting_type` enum('text','number','boolean','json') COLLATE utf8mb4_persian_ci DEFAULT 'text',
  `description` varchar(255) COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;
";

if (db_query($sql_settings)) {
    echo "✓ جدول site_settings ایجاد شد\n";
} else {
    echo "✗ خطا در ایجاد جدول site_settings\n";
}

// درج تنظیمات پیش‌فرض
$default_settings = [
    [
        'key' => 'active_theme',
        'value' => 'autumn',
        'type' => 'text',
        'desc' => 'تم فعال سایت (autumn, winter, spring, summer)'
    ],
    [
        'key' => 'theme_auto_detect',
        'value' => '1',
        'type' => 'boolean',
        'desc' => 'تشخیص خودکار تم بر اساس فصل'
    ],
    [
        'key' => 'theme_allow_user_choice',
        'value' => '1',
        'type' => 'boolean',
        'desc' => 'اجازه انتخاب تم توسط کاربر'
    ]
];

foreach ($default_settings as $setting) {
    $key = db_escape($setting['key']);
    $value = db_escape($setting['value']);
    $type = db_escape($setting['type']);
    $desc = db_escape($setting['desc']);
    
    $check = db_fetch_one("SELECT id FROM site_settings WHERE setting_key = '$key'");
    if (!$check) {
        $sql = "INSERT INTO site_settings (setting_key, setting_value, setting_type, description) 
                VALUES ('$key', '$value', '$type', '$desc')";
        if (db_query($sql)) {
            echo "✓ تنظیم '$key' اضافه شد\n";
        }
    } else {
        echo "- تنظیم '$key' قبلا وجود دارد\n";
    }
}

// اطمینان از وجود ستون season در جدول products
$check_column = db_query("SHOW COLUMNS FROM products LIKE 'season'");
if (mysqli_num_rows($check_column) == 0) {
    $add_season = "ALTER TABLE products 
                   ADD COLUMN `season` ENUM('spring','summer','autumn','winter','all') 
                   COLLATE utf8mb4_persian_ci DEFAULT 'all' 
                   COMMENT 'فصل مرتبط به محصول' 
                   AFTER `is_featured`";
    if (db_query($add_season)) {
        echo "✓ ستون 'season' به جدول products اضافه شد\n";
    }
    
    // اضافه کردن ایندکس برای بهبود عملکرد
    db_query("ALTER TABLE products ADD INDEX idx_season (season)");
    db_query("ALTER TABLE products ADD INDEX idx_season_category (season, category_id, is_active)");
    echo "✓ ایندکس‌های season اضافه شد\n";
} else {
    echo "- ستون 'season' قبلا در جدول products وجود دارد\n";
}

// به‌روزرسانی محصولات موجود با فصل پاییز
$update_products = "UPDATE products SET season = 'autumn' WHERE season IS NULL OR season = ''";
db_query($update_products);
echo "✓ محصولات موجود به فصل پاییز تنظیم شدند\n";

echo "\n=== راه‌اندازی سیستم تم با موفقیت انجام شد ===\n";
echo "تم پیش‌فرض: پاییزی (autumn)\n";
echo "تشخیص خودکار: فعال\n";
echo "انتخاب توسط کاربر: فعال\n";

