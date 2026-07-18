<?php
/**
 * کنترلر خطاها
 */
class ErrorController {

    public function notFound(): void {
        http_response_code(404);
        header("HTTP/1.1 404 Not Found");
        require BASE_PATH . '/src/Views/pages/404.php';
        exit;
    }

    public function serverError(): void {
        http_response_code(500);
        header("HTTP/1.1 500 Internal Server Error");
        $pageTitle = '۵۰۰ - خطای سرور';
        require BASE_PATH . '/src/Views/pages/500.php';
        exit;
    }
}
