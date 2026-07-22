<?php
/**
 * کنترلر محصولات
 */
class ProductController {

    private ProductModel $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    /**
     * لیست محصولات
     */
    public function index(): void {
        Security::set_security_headers();

        $catId  = isset($_GET['cat'])    ? (int)$_GET['cat']  : 0;
        $season = isset($_GET['season']) ? trim($_GET['season']) : '';
        $page   = isset($_GET['page'])   ? max(1, (int)$_GET['page']) : 1;
        $limit  = 12;
        $offset = ($page - 1) * $limit;

        // اعتبارسنجی season
        $validSeasons = ['spring', 'summer', 'autumn', 'winter', 'all'];
        if ($season && !in_array($season, $validSeasons)) {
            $season = '';
        }

        // اگر فیلتر فصل فعال است، تم را تغییر بده
        $product_theme = $season ?: null;

        // دریافت محصولات بر اساس فیلتر
        if ($season) {
            $products = $this->productModel->getBySeason($season, $limit, $offset);
            $total    = $this->productModel->countBySeason($season);
        } elseif ($catId > 0) {
            $products = $this->productModel->getByCategory($catId, $limit, $offset);
            $total    = $this->productModel->count($catId);
        } else {
            $products = $this->productModel->getAll($limit, $offset);
            $total    = $this->productModel->count();
        }

        $totalPages = (int)ceil($total / $limit);
        $categories = $this->productModel->getCategories();

        foreach ($products as &$p) {
            $p['gallery_arr'] = json_decode($p['gallery'] ?? '[]', true) ?: [];
        }
        unset($p);

        $pageTitle = 'محصولات - فروشگاه پاییزی';
        $pageDesc  = 'مشاهده و خرید انواع محصولات پاییزی';

        require BASE_PATH . '/src/Views/layouts/header.php';
        require BASE_PATH . '/src/Views/layouts/navbar.php';
        require BASE_PATH . '/src/Views/pages/products.php';
        require BASE_PATH . '/src/Views/layouts/footer.php';
    }

    /**
     * صفحه تک محصول
     */
    public function show(string $slug): void {
        Security::set_security_headers();

        // اعتبارسنجی slug
        $slug = preg_replace('/[^a-z0-9\-]/', '', strtolower(trim($slug)));
        if (empty($slug)) {
            $this->notFound();
            return;
        }

        $product = $this->productModel->getBySlug($slug);
        if (!$product) {
            $this->notFound();
            return;
        }

        $this->productModel->incrementViews((int)$product['id']);
        $product['gallery_arr'] = json_decode($product['gallery'] ?? '[]', true) ?: [];
        $reviews    = $this->productModel->getReviews((int)$product['id']);
        $categories = $this->productModel->getCategories();

        // دریافت فصل محصول برای تغییر تم
        $product_theme = $product['season'] ?? 'autumn';

        // دریافت محصولات مشابه بر اساس فصل
        $similarProducts = [];
        if (!empty($product['season'])) {
            $allSimilar = $this->productModel->getBySeason($product['season'], 9);
            // حذف محصول فعلی از لیست
            $filtered = array_filter($allSimilar, function($p) use ($product) {
                return $p['id'] != $product['id'];
            });
            // Re-index array و محدود کردن به 8 محصول
            $similarProducts = array_values(array_slice($filtered, 0, 8));
            // پردازش گالری
            foreach ($similarProducts as &$p) {
                $p['gallery_arr'] = json_decode($p['gallery'] ?? '[]', true) ?: [];
            }
            unset($p);
        }

        $pageTitle = Security::e($product['name']) . ' - فروشگاه پاییزی';
        $pageDesc  = Security::e($product['short_desc'] ?? $product['name']);

        // استفاده از minimal-header برای حذف Hero و دسته‌بندی‌ها
        require BASE_PATH . '/src/Views/layouts/minimal-header.php';
        require BASE_PATH . '/src/Views/layouts/navbar.php';
        require BASE_PATH . '/src/Views/pages/product-single.php';
        require BASE_PATH . '/src/Views/layouts/footer.php';
    }

    /**
     * افزودن نظر برای محصول
     */
    public function addReview(): void {
        // فقط درخواست POST مجاز است
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/');
            exit;
        }

        // بررسی لاگین بودن کاربر
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'برای ثبت نظر ابتدا وارد شوید.';
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // بررسی CSRF Token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (empty($csrfToken) || !isset($_SESSION['csrf_token']) || $csrfToken !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'درخواست نامعتبر است.';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL . '/'));
            exit;
        }

        // دریافت و اعتبارسنجی داده‌ها
        $productId = (int)($_POST['product_id'] ?? 0);
        $userId    = (int)$_SESSION['user_id'];
        $rating    = (int)($_POST['rating'] ?? 0);
        $title     = trim($_POST['title'] ?? '');
        $body      = trim($_POST['body'] ?? '');

        // اعتبارسنجی
        $errors = [];
        
        if ($productId <= 0) {
            $errors[] = 'محصول نامعتبر است.';
        }

        if ($rating < 1 || $rating > 5) {
            $errors[] = 'امتیاز باید بین ۱ تا ۵ باشد.';
        }

        if (empty($body)) {
            $errors[] = 'متن نظر الزامی است.';
        } elseif (mb_strlen($body) < 10) {
            $errors[] = 'متن نظر باید حداقل ۱۰ کاراکتر باشد.';
        } elseif (mb_strlen($body) > 1000) {
            $errors[] = 'متن نظر نباید بیشتر از ۱۰۰۰ کاراکتر باشد.';
        }

        if (!empty($title) && mb_strlen($title) > 100) {
            $errors[] = 'عنوان نظر نباید بیشتر از ۱۰۰ کاراکتر باشد.';
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL . '/'));
            exit;
        }

        // ذخیره نظر
        $result = $this->productModel->addReview($productId, $userId, $rating, $title, $body);

        if ($result) {
            $_SESSION['success'] = 'نظر شما با موفقیت ثبت شد و پس از تأیید نمایش داده خواهد شد.';
        } else {
            $_SESSION['error'] = 'شما قبلاً برای این محصول نظر ثبت کرده‌اید.';
        }

        // بازگشت به صفحه محصول
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL . '/'));
        exit;
    }

    private function notFound(): void {
        http_response_code(404);
        require BASE_PATH . '/src/Views/pages/404.php';
    }
}
