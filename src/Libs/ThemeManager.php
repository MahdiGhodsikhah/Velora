<?php

class ThemeManager {
    
    private static ?ThemeManager $instance = null;
    private string $activeTheme;
    private array $themeConfig;
    private array $availableThemes = ['spring', 'summer', 'autumn', 'winter'];
    private string $defaultTheme = 'autumn';
    
    private function __construct() {
        $this->loadThemeConfig();
        $this->activeTheme = $this->resolveTheme();
    }
    
    public static function getInstance(): ThemeManager {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function loadThemeConfig(): void {
        $configPath = BASE_PATH . '/config/theme.php';
        if (file_exists($configPath)) {
            $this->themeConfig = require $configPath;
        } else {
            $this->themeConfig = [
                'mode' => 'automatic',
                'admin_selected_theme' => null
            ];
        }
    }
    
    private function resolveTheme(): string {
        // اگر کاربر از navbar تم جدید انتخاب کرده
        if (isset($_GET['theme']) && in_array($_GET['theme'], $this->availableThemes)) {
            $_SESSION['user_selected_theme'] = $_GET['theme'];
            unset($_SESSION['product_theme']);
            return $_GET['theme'];
        }
        
        // اولویت اول: انتخاب کاربر از navbar (حفظ انتخاب کاربر)
        if (isset($_SESSION['user_selected_theme'])) {
            $theme = $_SESSION['user_selected_theme'];
            if (in_array($theme, $this->availableThemes)) {
                return $theme;
            }
        }
        
        // اولویت دوم: تم محصول (فقط برای صفحه محصول)
        if (isset($_SESSION['product_theme'])) {
            $theme = $_SESSION['product_theme'];
            if (in_array($theme, $this->availableThemes)) {
                return $theme;
            }
        }
        
        // اولویت سوم: تم ذخیره شده کاربر در دیتابیس
        if (isset($_SESSION['user_id'])) {
            $userTheme = $this->getUserTheme($_SESSION['user_id']);
            if ($userTheme && $userTheme !== 'automatic' && in_array($userTheme, $this->availableThemes)) {
                return $userTheme;
            }
        }
        
        // اولویت چهارم: تم انتخاب شده توسط ادمین
        if (!empty($this->themeConfig['admin_selected_theme']) && 
            in_array($this->themeConfig['admin_selected_theme'], $this->availableThemes)) {
            return $this->themeConfig['admin_selected_theme'];
        }
        
        // پیش‌فرض: تم فصلی
        return $this->getCurrentSeasonTheme();
    }
    
    private function getUserTheme(int $userId): ?string {
        $userId = (int)$userId;
        $result = db_fetch_one("SELECT `preferred_theme` FROM `users` WHERE `id` = $userId LIMIT 1");
        return $result['preferred_theme'] ?? null;
    }
    
    private function getCurrentSeasonTheme(): string {
        $month = (int)date('n');
        
        if ($month >= 3 && $month <= 5) {
            return 'spring';
        } elseif ($month >= 6 && $month <= 8) {
            return 'summer';
        } elseif ($month >= 9 && $month <= 11) {
            return 'autumn';
        } else {
            return 'winter';
        }
    }
    
    public function getActiveTheme(): string {
        return $this->activeTheme;
    }
    
    public function setProductTheme(?string $season): void {
        // فقط تم محصول رو تنظیم می‌کنیم بدون پاک کردن انتخاب کاربر
        if ($season && in_array($season, $this->availableThemes)) {
            $_SESSION['product_theme'] = $season;
            // انتخاب کاربر رو حفظ می‌کنیم و پاک نمی‌کنیم
            $this->activeTheme = $season;
        }
    }
    
    public function clearProductTheme(): void {
        // پاک کردن تم محصول برای برگشت به تم انتخابی کاربر
        unset($_SESSION['product_theme']);
        $this->activeTheme = $this->resolveTheme();
    }
    
    public function clearUserSelectedTheme(): void {
        unset($_SESSION['user_selected_theme']);
        $this->activeTheme = $this->resolveTheme();
    }
    
    public function getThemeCssPath(): string {
        $theme = $this->activeTheme;
        $path = BASE_URL . '/assets/css/themes/' . $theme . '.css';
        $fullPath = PUBLIC_PATH . '/assets/css/themes/' . $theme . '.css';
        
        if (!file_exists($fullPath)) {
            $theme = $this->defaultTheme;
            $path = BASE_URL . '/assets/css/themes/' . $theme . '.css';
        }
        
        return $path;
    }
    
    public function getThemeAssetsPath(string $type = 'images'): string {
        $theme = $this->activeTheme;
        return BASE_URL . '/assets/' . $type . '/themes/' . $theme;
    }
    
    public function getAvailableThemes(): array {
        return $this->availableThemes;
    }
    
    public function isValidTheme(string $theme): bool {
        return in_array($theme, $this->availableThemes);
    }
}
