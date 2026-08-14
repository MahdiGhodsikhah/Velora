<?php
/**
 * کنترلر صفحه اصلی
 */
class HomeController {

    private ProductModel $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    public function index(): void {
        Security::set_security_headers();

        // پاک کردن تم محصول برای برگشت به تم انتخابی کاربر
        $themeManager = ThemeManager::getInstance();
        $themeManager->clearProductTheme();

        $featuredProducts = $this->productModel->getFeatured(8);
        $categories       = $this->productModel->getCategories();
        $banners          = $this->productModel->getBanners('hero');

        // پردازش گالری JSON محصولات
        foreach ($featuredProducts as &$p) {
            $p['gallery_arr'] = json_decode($p['gallery'] ?? '[]', true) ?: [];
        }
        unset($p);

        // فیلتر محصولات بر اساس فصل - array_values برای ریست کردن ایندکس‌ها
        $springProducts = array_values(array_filter($featuredProducts, function($p) {
            return isset($p['season']) && ($p['season'] === 'spring' || $p['season'] === 'all') && ($p['is_featured'] ?? 0);
        }));
        $summerProducts = array_values(array_filter($featuredProducts, function($p) {
            return isset($p['season']) && ($p['season'] === 'summer' || $p['season'] === 'all') && ($p['is_featured'] ?? 0);
        }));
        $autumnProducts = array_values(array_filter($featuredProducts, function($p) {
            return isset($p['season']) && ($p['season'] === 'autumn' || $p['season'] === 'all') && ($p['is_featured'] ?? 0);
        }));
        $winterProducts = array_values(array_filter($featuredProducts, function($p) {
            return isset($p['season']) && ($p['season'] === 'winter' || $p['season'] === 'all') && ($p['is_featured'] ?? 0);
        }));

        $pageTitle = 'فروشگاه پاییزی شگفت‌انگیز';
        $pageDesc  = 'جدیدترین مدل‌های پوشاک، کفش و اکسسوری با طرح‌های پاییزی';

        require BASE_PATH . '/src/Views/layouts/header.php';
        require BASE_PATH . '/src/Views/layouts/navbar.php';
        require BASE_PATH . '/src/Views/pages/home.php';
        require BASE_PATH . '/src/Views/layouts/footer.php';
    }
}
