<?php
/**
 * کنترلر مدیریت تم و API های مرتبط
 */

require_once BASE_PATH . '/src/Libs/ThemeManager.php';

class ThemeController {
    
    private $themeManager;
    
    public function __construct() {
        $this->themeManager = ThemeManager::getInstance();
    }
    
    /**
     * API: تنظیم تم کاربر
     */
    public function setTheme() {
        header('Content-Type: application/json');
        
        // فقط درخواست POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'متد نامعتبر']);
            return;
        }
        
        // دریافت داده JSON
        $input = json_decode(file_get_contents('php://input'), true);
        $theme = $input['theme'] ?? null;
        
        if (!$theme || !in_array($theme, ThemeManager::THEMES)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'تم نامعتبر']);
            return;
        }
        
        // ذخیره در session
        $result = $this->themeManager->setUserTheme($theme);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'تم با موفقیت تنظیم شد',
                'theme' => $theme
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'خطا در تنظیم تم']);
        }
    }
    
    /**
     * API: تشخیص فصل جاری
     */
    public function detectSeason() {
        header('Content-Type: application/json');
        
        $season = $this->themeManager->detectCurrentSeason();
        
        echo json_encode([
            'success' => true,
            'season' => $season,
            'season_fa' => ThemeManager::SEASON_MAP[$season] ?? $season
        ]);
    }
    
    /**
     * API: دریافت تم فعال
     */
    public function getActiveTheme() {
        header('Content-Type: application/json');
        
        $theme = $this->themeManager->getActiveTheme();
        $settings = $this->themeManager->getThemeSettings();
        
        echo json_encode([
            'success' => true,
            'theme' => $theme,
            'settings' => $settings
        ]);
    }
    
    /**
     * صفحه مدیریت تم در پنل ادمین
     */
    public function adminThemeSettings() {
        // بررسی دسترسی ادمین
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        $settings = $this->themeManager->getThemeSettings();
        $themes = $this->themeManager->getAllThemes();
        
        require BASE_PATH . '/src/Views/admin/theme-settings.php';
    }
    
    /**
     * ذخیره تنظیمات تم توسط ادمین
     */
    public function saveAdminThemeSettings() {
        // بررسی دسترسی ادمین
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/theme-settings');
            exit;
        }
        
        // دریافت داده‌های فرم
        $active_theme = $_POST['active_theme'] ?? null;
        $auto_detect = isset($_POST['auto_detect']) ? 1 : 0;
        $allow_user_choice = isset($_POST['allow_user_choice']) ? 1 : 0;
        
        $success = true;
        
        // تنظیم تم دستی
        if ($active_theme && in_array($active_theme, ThemeManager::THEMES)) {
            $success = $success && $this->themeManager->setAdminTheme($active_theme);
        }
        
        // تنظیم تشخیص خودکار
        $success = $success && $this->themeManager->setAutoDetect($auto_detect);
        
        // تنظیم اجازه انتخاب توسط کاربر
        $success = $success && $this->themeManager->setAllowUserChoice($allow_user_choice);
        
        if ($success) {
            $_SESSION['alert'] = [
                'type' => 'success',
                'message' => 'تنظیمات تم با موفقیت ذخیره شد'
            ];
        } else {
            $_SESSION['alert'] = [
                'type' => 'danger',
                'message' => 'خطا در ذخیره تنظیمات'
            ];
        }
        
        header('Location: ' . BASE_URL . '/admin/theme-settings');
        exit;
    }
    
    /**
     * ویجت انتخاب تم برای کاربران
     */
    public function themeSelector() {
        $current_theme = $this->themeManager->getActiveTheme();
        $themes = $this->themeManager->getAllThemes();
        $allow_user_choice = $this->themeManager->getThemeSettings()['allow_user_choice'];
        
        // اگر انتخاب توسط کاربر مجاز نیست، نمایش نده
        if (!$allow_user_choice) {
            return;
        }
        
        require BASE_PATH . '/src/Views/partials/theme-selector.php';
    }
}

