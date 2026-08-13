<?php

class AdminController {
    
    /**
     * بررسی دسترسی ادمین
     */
    private function checkAdminAccess(): void {
        // بررسی لاگین بودن کاربر
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            $_SESSION['auth_error'] = 'برای دسترسی به پنل ادمین ابتدا وارد شوید.';
            $this->redirect('/login');
            return;
        }
        
        // بررسی نقش ادمین
        if (($_SESSION['user_role'] ?? '') !== 'admin') {
            $_SESSION['auth_error'] = 'شما دسترسی به این بخش را ندارید.';
            $this->redirect('/');
            return;
        }
    }
    
    /**
     * صفحه تنظیمات تم
     */
    public function themeSettings(): void {
        $this->checkAdminAccess();
        require BASE_PATH . '/src/Views/admin/theme-settings.php';
    }
    
    /**
     * ریدایرکت
     */
    private function redirect(string $path): void {
        $base = defined('BASE_URL') ? BASE_URL : '';
        header('Location: ' . $base . $path);
        exit;
    }
}
