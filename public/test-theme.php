<?php
/**
 * Gateway برای اجرای تست سیستم تم
 */

// تعریف مسیرها
define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);
define('BASE_URL', '/Velora/public');

// اجرای تست
require_once __DIR__ . '/../config/test_theme_system.php';
