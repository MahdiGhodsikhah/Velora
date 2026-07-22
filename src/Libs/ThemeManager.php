<?php
/**
 * مدیریت تم دینامیک چندفصلی
 * شامل تشخیص خودکار فصل بر اساس تقویم هجری شمسی
 */

class ThemeManager {
    
    private static $instance = null;
    private $conn;
    
    // تم‌های موجود
    const THEMES = ['spring', 'summer', 'autumn', 'winter'];
    
    // نقشه فصل‌های شمسی به انگلیسی
    const SEASON_MAP = [
        'spring'  => 'بهار',
        'summer'  => 'تابستان',
        'autumn'  => 'پاییز',
        'winter'  => 'زمستان'
    ];
    
    private function __construct() {
        $this->conn = db_connect();
    }
    
    /**
     * الگوی Singleton
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * تشخیص فصل جاری بر اساس تقویم هجری شمسی
     * 
     * فروردین - خرداد - اردیبهشت: بهار (spring)
     * تیر - مرداد - شهریور: تابستان (summer)
     * مهر - آبان - آذر: پاییز (autumn)
     * دی - بهمن - اسفند: زمستان (winter)
     */
    public function detectCurrentSeason() {
        // تبدیل تاریخ میلادی به شمسی
        require_once BASE_PATH . '/src/Libs/jdf.php';
        
        $jalali_date = gregorian_to_jalali(
            (int)date('Y'),
            (int)date('m'),
            (int)date('d')
        );
        
        $month = (int)$jalali_date[1];
        
        // تعیین فصل بر اساس ماه شمسی
        if ($month >= 1 && $month <= 3) {
            return 'spring';  // فروردین تا خرداد
        } elseif ($month >= 4 && $month <= 6) {
            return 'summer';  // تیر تا شهریور
        } elseif ($month >= 7 && $month <= 9) {
            return 'autumn';  // مهر تا آذر
        } else {
            return 'winter';  // دی تا اسفند
        }
    }
    
    /**
     * دریافت تم فعال سایت
     * اولویت: تم دستی ادمین > تم کاربر > تشخیص خودکار > پیش‌فرض
     */
    public function getActiveTheme() {
        // 1. بررسی تم دستی تنظیم شده توسط ادمین
        $admin_theme = $this->getSetting('active_theme');
        $auto_detect = $this->getSetting('theme_auto_detect');
        $allow_user = $this->getSetting('theme_allow_user_choice');
        
        // 2. اگر تشخیص خودکار غیرفعال است و ادمین تم را تنظیم کرده
        if ($auto_detect === '0' && $admin_theme) {
            return $admin_theme;
        }
        
        // 3. بررسی انتخاب کاربر از localStorage (از طریق session)
        if ($allow_user === '1' && isset($_SESSION['user_theme'])) {
            $user_theme = $_SESSION['user_theme'];
            if (in_array($user_theme, self::THEMES)) {
                return $user_theme;
            }
        }
        
        // 4. تشخیص خودکار فصل
        if ($auto_detect === '1') {
            return $this->detectCurrentSeason();
        }
        
        // 5. بازگشت به تم پیش‌فرض
        return $admin_theme ?: 'autumn';
    }
    
    /**
     * دریافت تم برای یک محصول خاص بر اساس فصل محصول
     */
    public function getProductTheme($season) {
        if (!empty($season) && in_array($season, self::THEMES)) {
            return $season;
        }
        return $this->getActiveTheme();
    }
    
    /**
     * تنظیم تم دستی توسط ادمین
     */
    public function setAdminTheme($theme) {
        if (!in_array($theme, self::THEMES)) {
            return false;
        }
        return $this->updateSetting('active_theme', $theme);
    }
    
    /**
     * فعال/غیرفعال کردن تشخیص خودکار
     */
    public function setAutoDetect($enabled) {
        $value = $enabled ? '1' : '0';
        return $this->updateSetting('theme_auto_detect', $value);
    }
    
    /**
     * فعال/غیرفعال کردن انتخاب توسط کاربر
     */
    public function setAllowUserChoice($enabled) {
        $value = $enabled ? '1' : '0';
        return $this->updateSetting('theme_allow_user_choice', $value);
    }
    
    /**
     * ذخیره انتخاب تم کاربر
     */
    public function setUserTheme($theme) {
        if (!in_array($theme, self::THEMES)) {
            return false;
        }
        $_SESSION['user_theme'] = $theme;
        return true;
    }
    
    /**
     * دریافت تنظیمات از پایگاه داده
     */
    private function getSetting($key) {
        $key = db_escape($key);
        $result = db_fetch_one("SELECT setting_value FROM site_settings WHERE setting_key = '$key'");
        return $result ? $result['setting_value'] : null;
    }
    
    /**
     * به‌روزرسانی تنظیمات
     */
    private function updateSetting($key, $value) {
        $key = db_escape($key);
        $value = db_escape($value);
        
        $check = db_fetch_one("SELECT id FROM site_settings WHERE setting_key = '$key'");
        if ($check) {
            $sql = "UPDATE site_settings SET setting_value = '$value' WHERE setting_key = '$key'";
        } else {
            $sql = "INSERT INTO site_settings (setting_key, setting_value) VALUES ('$key', '$value')";
        }
        
        return db_query($sql) !== false;
    }
    
    /**
     * دریافت اطلاعات تمام تم‌ها
     */
    public function getAllThemes() {
        return [
            'spring' => [
                'name' => 'بهار',
                'name_en' => 'spring',
                'description' => 'تم شاد و رنگارنگ بهاری',
                'primary_color' => '#10b981',
                'icon' => '🌸'
            ],
            'summer' => [
                'name' => 'تابستان',
                'name_en' => 'summer',
                'description' => 'تم گرم و شاداب تابستانی',
                'primary_color' => '#f59e0b',
                'icon' => '☀️'
            ],
            'autumn' => [
                'name' => 'پاییز',
                'name_en' => 'autumn',
                'description' => 'تم گرم و دنج پاییزی',
                'primary_color' => '#d97706',
                'icon' => '🍂'
            ],
            'winter' => [
                'name' => 'زمستان',
                'name_en' => 'winter',
                'description' => 'تم سرد و زیبای زمستانی',
                'primary_color' => '#3b82f6',
                'icon' => '❄️'
            ]
        ];
    }
    
    /**
     * دریافت تنظیمات فعلی تم
     */
    public function getThemeSettings() {
        return [
            'active_theme' => $this->getActiveTheme(),
            'auto_detect' => $this->getSetting('theme_auto_detect') === '1',
            'allow_user_choice' => $this->getSetting('theme_allow_user_choice') === '1',
            'current_season' => $this->detectCurrentSeason(),
            'themes' => $this->getAllThemes()
        ];
    }
    
    /**
     * دریافت کلاس‌های CSS مرتبط با تم
     */
    public function getThemeClasses($theme = null) {
        $theme = $theme ?: $this->getActiveTheme();
        return "theme-{$theme}";
    }
    
    /**
     * دریافت مسیر فایل CSS تم
     */
    public function getThemeCSSPath($theme = null) {
        $theme = $theme ?: $this->getActiveTheme();
        return BASE_URL . "/assets/css/themes/theme-{$theme}.css";
    }
}

