<?php
/**
 * کنترلر خطاها
 */
class ErrorController {

    public function notFound(): void {
        http_response_code(404);
        require BASE_PATH . '/src/Views/pages/404.php';
    }

    public function serverError(): void {
        http_response_code(500);
        $pageTitle = '۵۰۰ - خطای سرور';
        require BASE_PATH . '/src/Views/pages/500.php';
    }
}
