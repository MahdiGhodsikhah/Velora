<?php
/**
 * کنترلر صفحه درباره ما
 */
class AboutController {

    public function index(): void {
        Security::set_security_headers();

        $pageTitle = 'درباره ما - فروشگاه Velora';
        $pageDesc  = 'آشنایی با فروشگاه پاییزی Velora و خدمات ما';

        require BASE_PATH . '/src/Views/layouts/header.php';
        require BASE_PATH . '/src/Views/layouts/navbar.php';
        require BASE_PATH . '/src/Views/pages/about.php';
        require BASE_PATH . '/src/Views/layouts/footer.php';
    }
}
