<?php
/**
 * Gateway برای اجرای اسکریپت نصب تم
 */

// تعریف مسیرها
define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);
define('BASE_URL', '/Velora/public');

// اجرای اسکریپت نصب
require_once __DIR__ . '/../config/setup_theme_system.php';
