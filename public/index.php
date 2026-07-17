<?php
/**
 * نقطه ورود اصلی پروژه (Front Controller)
 */

// -------------------------------------------------------------------
// تنظیمات امنیتی Session
// -------------------------------------------------------------------
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_strict_mode', 1);
ini_set('session.gc_maxlifetime', 3600);
// ini_set('session.cookie_secure', 1); // فعال کنید اگر HTTPS دارید

session_start();

// بازسازی session ID بعد از ورود (در AuthController انجام می‌شود)

// -------------------------------------------------------------------
// مسیر پایه
// -------------------------------------------------------------------
define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);
define('BASE_URL', '/Velora/public');

// -------------------------------------------------------------------
// تنظیمات نمایش خطا (در پروداکشن false کنید)
// -------------------------------------------------------------------
define('APP_DEBUG', true);
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', BASE_PATH . '/logs/error.log');
}

// -------------------------------------------------------------------
// بارگذاری فایل‌های اصلی
// -------------------------------------------------------------------
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/config/routes.php';
require_once BASE_PATH . '/src/Libs/Security.php';
require_once BASE_PATH . '/src/Libs/jdf.php';
require_once BASE_PATH . '/src/Libs/ImageUploader.php';
require_once BASE_PATH . '/src/Models/UserModel.php';
require_once BASE_PATH . '/src/Models/ProductModel.php';
require_once BASE_PATH . '/src/Controllers/HomeController.php';
require_once BASE_PATH . '/src/Controllers/ProductController.php';
require_once BASE_PATH . '/src/Controllers/AuthController.php';
require_once BASE_PATH . '/src/Controllers/AboutController.php';
require_once BASE_PATH . '/src/Controllers/ErrorController.php';
require_once BASE_PATH . '/src/Controllers/UserController.php';
require_once BASE_PATH . '/src/Controllers/CartController.php';
require_once BASE_PATH . '/src/Controllers/WishlistController.php';

// -------------------------------------------------------------------
// روتینگ
// -------------------------------------------------------------------
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri    = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

// حذف پیشوند base URL (case-insensitive)
$base = '/Velora/public';
if (stripos($uri, $base) === 0) {
    $uri = substr($uri, strlen($base));
}
if (empty($uri)) $uri = '/';

$route = dispatch_route($method, $uri);
$controllerName = $route['controller'];
$actionName     = $route['action'];
$params         = $route['params'];

// بررسی وجود کنترلر
if (class_exists($controllerName)) {
    $controller = new $controllerName();
    if (method_exists($controller, $actionName)) {
        call_user_func_array([$controller, $actionName], $params);
    } else {
        $err = new ErrorController();
        $err->notFound();
    }
} else {
    // fallback به HomeController
    $home = new HomeController();
    $home->index();
}
