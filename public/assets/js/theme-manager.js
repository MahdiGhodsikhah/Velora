/**
 * مدیریت تم دینامیک سمت کلاینت
 * تغییر تم بدون نیاز به رفرش صفحه
 */

class ThemeManager {
    constructor() {
        this.currentTheme = null;
        this.themes = ['spring', 'summer', 'autumn', 'winter'];
        this.themeCache = new Map();
        this.init();
    }

    /**
     * مقداردهی اولیه
     */
    init() {
        // دریافت تم فعلی از سرور یا localStorage
        this.currentTheme = this.getCurrentTheme();
        
        // اعمال تم اولیه
        this.applyTheme(this.currentTheme, false);
        
        // گوش دادن به تغییرات localStorage از تب‌های دیگر
        window.addEventListener('storage', (e) => {
            if (e.key === 'user_theme' && e.newValue) {
                this.applyTheme(e.newValue, false);
            }
        });
    }

    /**
     * دریافت تم فعلی
     */
    getCurrentTheme() {
        // 1. بررسی data attribute در body
        const bodyTheme = document.body.dataset.theme;
        if (bodyTheme && this.themes.includes(bodyTheme)) {
            return bodyTheme;
        }

        // 2. بررسی localStorage
        const savedTheme = localStorage.getItem('user_theme');
        if (savedTheme && this.themes.includes(savedTheme)) {
            return savedTheme;
        }

        // 3. بازگشت به پیش‌فرض
        return 'autumn';
    }

    /**
     * اعمال تم جدید
     * @param {string} theme - نام تم
     * @param {boolean} saveToStorage - ذخیره در localStorage
     */
    async applyTheme(theme, saveToStorage = true) {
        if (!this.themes.includes(theme)) {
            console.error('تم نامعتبر:', theme);
            return false;
        }

        // حذف کلاس‌های تم قبلی
        this.themes.forEach(t => {
            document.body.classList.remove(`theme-${t}`);
            document.documentElement.classList.remove(`theme-${t}`);
        });

        // اضافه کردن کلاس تم جدید
        document.body.classList.add(`theme-${theme}`);
        document.documentElement.classList.add(`theme-${theme}`);
        document.body.dataset.theme = theme;

        // بارگذاری CSS تم
        await this.loadThemeCSS(theme);

        // ذخیره در localStorage
        if (saveToStorage) {
            localStorage.setItem('user_theme', theme);
            
            // ارسال به سرور برای ذخیره در session
            this.saveThemeToServer(theme);
        }

        // تغییر تم فعلی
        this.currentTheme = theme;

        // رویداد سفارشی برای اطلاع رسانی تغییر تم
        document.dispatchEvent(new CustomEvent('themeChanged', { 
            detail: { theme: theme }
        }));

        return true;
    }

    /**
     * بارگذاری فایل CSS تم
     */
    async loadThemeCSS(theme) {
        // بررسی کش
        if (this.themeCache.has(theme)) {
            return true;
        }

        return new Promise((resolve, reject) => {
            // بررسی وجود لینک قبلی
            let link = document.querySelector(`link[data-theme="${theme}"]`);
            
            if (!link) {
                link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = `${window.BASE_URL || '/Velora/public'}/assets/css/themes/theme-${theme}.css`;
                link.dataset.theme = theme;
                
                link.onload = () => {
                    this.themeCache.set(theme, true);
                    resolve(true);
                };
                
                link.onerror = () => {
                    console.error('خطا در بارگذاری CSS تم:', theme);
                    reject(false);
                };
                
                document.head.appendChild(link);
            } else {
                resolve(true);
            }
        });
    }

    /**
     * ذخیره تم در سرور
     */
    async saveThemeToServer(theme) {
        try {
            const response = await fetch(`${window.BASE_URL || '/Velora/public'}/api/set-theme`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ theme: theme })
            });
            
            if (!response.ok) {
                console.warn('خطا در ذخیره تم در سرور');
            }
        } catch (error) {
            console.warn('خطا در ارتباط با سرور:', error);
        }
    }

    /**
     * تغییر تم
     */
    changeTheme(theme) {
        return this.applyTheme(theme, true);
    }

    /**
     * دریافت تم فعلی
     */
    getTheme() {
        return this.currentTheme;
    }

    /**
     * تشخیص خودکار تم بر اساس فصل جاری
     */
    async autoDetectTheme() {
        try {
            const response = await fetch(`${window.BASE_URL || '/Velora/public'}/api/detect-season`);
            const data = await response.json();
            
            if (data.season) {
                await this.applyTheme(data.season, false);
            }
        } catch (error) {
            console.error('خطا در تشخیص خودکار تم:', error);
        }
    }

    /**
     * ریست کردن تم به حالت پیش‌فرض
     */
    resetTheme() {
        localStorage.removeItem('user_theme');
        this.autoDetectTheme();
    }

    /**
     * پیش‌بارگذاری تمام تم‌ها برای عملکرد بهتر
     */
    preloadAllThemes() {
        this.themes.forEach(theme => {
            if (theme !== this.currentTheme) {
                this.loadThemeCSS(theme);
            }
        });
    }
}

// ایجاد نمونه سراسری
window.themeManager = new ThemeManager();

// تابع راحتی برای تغییر تم
window.changeTheme = function(theme) {
    return window.themeManager.changeTheme(theme);
};

// پیش‌بارگذاری تم‌ها بعد از بارگذاری صفحه
document.addEventListener('DOMContentLoaded', () => {
    // تاخیر کوتاه برای عملکرد بهتر
    setTimeout(() => {
        window.themeManager.preloadAllThemes();
    }, 1000);
});

// مدیریت تم محصول در صفحه محصول
if (document.body.classList.contains('product-page')) {
    const productSeason = document.body.dataset.productSeason;
    if (productSeason) {
        window.themeManager.applyTheme(productSeason, false);
    }
}

// مدیریت تم در صفحه لیست محصولات بر اساس فیلتر
document.addEventListener('filterChanged', (e) => {
    if (e.detail && e.detail.season) {
        window.themeManager.applyTheme(e.detail.season, false);
    }
});

