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

        $featuredProducts = $this->productModel->getFeatured(8);
        $categories       = $this->productModel->getCategories();
        $banners          = $this->productModel->getBanners('hero');

        // پردازش گالری JSON محصولات
        foreach ($featuredProducts as &$p) {
            $p['gallery_arr'] = json_decode($p['gallery'] ?? '[]', true) ?: [];
        }
        unset($p);

        $pageTitle = 'فروشگاه پاییزی شگفت‌انگیز';
        $pageDesc  = 'جدیدترین مدل‌های پوشاک، کفش و اکسسوری با طرح‌های پاییزی';

        require BASE_PATH . '/src/Views/layouts/header.php';
        require BASE_PATH . '/src/Views/layouts/navbar.php';
        require BASE_PATH . '/src/Views/pages/home.php';
        require BASE_PATH . '/src/Views/layouts/footer.php';
    }
}
