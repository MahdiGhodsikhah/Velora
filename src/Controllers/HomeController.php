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

        // دریافت محصولات ویژه برای هر فصل به طور جداگانه
        $springProducts = $this->productModel->getFeaturedBySeason('spring', 8);
        $summerProducts = $this->productModel->getFeaturedBySeason('summer', 8);
        $autumnProducts = $this->productModel->getFeaturedBySeason('autumn', 8);
        $winterProducts = $this->productModel->getFeaturedBySeason('winter', 8);
        
        $categories = $this->productModel->getCategories();
        $banners = $this->productModel->getBanners('hero');

        // پردازش گالری JSON محصولات برای تمام لیست‌های محصولات
        $productLists = [&$springProducts, &$summerProducts, &$autumnProducts, &$winterProducts];
        
        foreach ($productLists as &$productList) {
            foreach ($productList as &$p) {
                $p['gallery_arr'] = json_decode($p['gallery'] ?? '[]', true) ?: [];
            }
            unset($p); // آزادسازی رفرنس
        }
        unset($productList); // آزادسازی رفرنس

        $pageTitle = 'فروشگاه پاییزی شگفت‌انگیز';
        $pageDesc  = 'جدیدترین مدل‌های پوشاک، کفش و اکسسوری با طرح‌های پاییزی';

        require BASE_PATH . '/src/Views/layouts/header.php';
        require BASE_PATH . '/src/Views/layouts/navbar.php';
        require BASE_PATH . '/src/Views/pages/home.php';
        require BASE_PATH . '/src/Views/layouts/footer.php';
    }
}
