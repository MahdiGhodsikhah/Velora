<?php
/**
 * Gateway برای ابزار به‌روزرسانی فصل محصولات
 */

// تعریف مسیرها
define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);
define('BASE_URL', '/Velora/public');

// اجرای ابزار
require_once __DIR__ . '/../config/update_products_season.php';
