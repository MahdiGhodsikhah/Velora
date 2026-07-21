<?php
/**
 * سیستم روتینگ پروژه
 * مسیریابی URL ها به کنترلرها و اکشن‌ها
 */

$routes = [
    // صفحه اصلی
    'GET:/'                     => ['HomeController', 'index'],
    'GET:/home'                 => ['HomeController', 'index'],

    // محصولات
    'GET:/products'             => ['ProductController', 'index'],
    'GET:/products/{slug}'      => ['ProductController', 'show'],
    'POST:/reviews/add'         => ['ProductController', 'addReview'],

    // احراز هویت
    'GET:/login'                => ['AuthController', 'loginForm'],
    'POST:/login'               => ['AuthController', 'login'],
    'GET:/register'             => ['AuthController', 'registerForm'],
    'POST:/register'            => ['AuthController', 'register'],
    'GET:/logout'               => ['AuthController', 'logout'],

    // درباره ما
    'GET:/about'                => ['AboutController', 'index'],

    // پروفایل کاربر
    'GET:/dashboard'            => ['UserController', 'dashboard'],
    'GET:/profile'              => ['UserController', 'profile'],
    'POST:/profile'             => ['UserController', 'updateProfile'],
    'POST:/profile/update'      => ['UserController', 'updateProfile'],
    'POST:/profile/change-password' => ['UserController', 'changePassword'],
    'POST:/profile/upload-image'    => ['UserController', 'uploadProfileImage'],
    'POST:/profile/remove-image'    => ['UserController', 'removeProfileImage'],
    'GET:/wishlist'             => ['UserController', 'wishlist'],

    // سبد خرید
    'GET:/cart'                 => ['CartController', 'index'],
    'POST:/cart/add'            => ['CartController', 'add'],
    'POST:/cart/remove'         => ['CartController', 'remove'],
    'POST:/cart/update'         => ['CartController', 'update'],
    'POST:/cart/apply-coupon'   => ['CartController', 'applyCoupon'],

    // تکمیل خرید
    'GET:/checkout'             => ['CheckoutController', 'index'],
    'POST:/checkout/process'    => ['CheckoutController', 'process'],

    // سفارشات
    'GET:/orders'               => ['UserController', 'orders'],

    // علاقه‌مندی‌ها
    'POST:/wishlist/toggle'     => ['WishlistController', 'toggle'],
    'GET:/wishlist/status'      => ['WishlistController', 'getStatus'],

    // 404
    'GET:/404'                  => ['ErrorController', 'notFound'],
];

/**
 * پارس کردن URL و پیدا کردن روت مناسب
 */
function dispatch_route(string $method, string $uri): array {
    global $routes;

    // پاکسازی URI
    $uri = strtok($uri, '?');
    $uri = '/' . trim($uri, '/');
    if ($uri !== '/') $uri = rtrim($uri, '/');

    $key = strtoupper($method) . ':' . $uri;

    // تطبیق مستقیم
    if (isset($routes[$key])) {
        return ['controller' => $routes[$key][0], 'action' => $routes[$key][1], 'params' => []];
    }

    // تطبیق با پارامترهای دینامیک
    foreach ($routes as $route => $target) {
        [$route_method, $route_path] = explode(':', $route, 2);
        if (strtoupper($method) !== $route_method) continue;

        $pattern = preg_replace('/\{[a-z_]+\}/', '([^/]+)', $route_path);
        $pattern = '#^' . $pattern . '$#';
        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches);
            return ['controller' => $target[0], 'action' => $target[1], 'params' => $matches];
        }
    }

    return ['controller' => 'ErrorController', 'action' => 'notFound', 'params' => []];
}
