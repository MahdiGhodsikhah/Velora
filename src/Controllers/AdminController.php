<?php

class AdminController {
    
    private UserModel $userModel;
    private ProductModel $productModel;
    private OrderModel $orderModel;
    
    public function __construct() {
        $this->userModel = new UserModel();
        $this->productModel = new ProductModel();
        $this->orderModel = new OrderModel();
    }
    
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
     * داشبورد اصلی ادمین
     */
    public function dashboard(): void {
        $this->checkAdminAccess();
        
        // آمار کلی
        $stats = [
            'total_users' => $this->userModel->getTotalCount(),
            'total_products' => $this->productModel->getTotalCount(),
            'total_orders' => $this->orderModel->getTotalCount(),
            'pending_orders' => $this->orderModel->getPendingCount(),
            'recent_orders' => $this->orderModel->getRecentOrders(5),
            'recent_users' => $this->userModel->getRecentUsers(5),
        ];
        
        $pageTitle = 'پنل مدیریت';
        require BASE_PATH . '/src/Views/admin/layout/header.php';
        require BASE_PATH . '/src/Views/admin/dashboard.php';
        require BASE_PATH . '/src/Views/admin/layout/footer.php';
    }
    
    /**
     * مدیریت محصولات
     */
    public function products(): void {
        $this->checkAdminAccess();
        
        // صفحه‌بندی
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = isset($_GET['per_page']) ? max(5, min(50, (int)$_GET['per_page'])) : 5; // بین 5 تا 50
        
        $totalProducts = $this->productModel->getTotalCount();
        $totalPages = ceil($totalProducts / $perPage);
        
        // اطمینان از اینکه شماره صفحه معتبر است
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
        }
        
        $products = $this->productModel->getAllForAdmin($page, $perPage);
        
        $pageTitle = 'مدیریت محصولات';
        require BASE_PATH . '/src/Views/admin/layout/header.php';
        require BASE_PATH . '/src/Views/admin/products.php';
        require BASE_PATH . '/src/Views/admin/layout/footer.php';
    }
    
    /**
     * فرم ایجاد محصول جدید
     */
    public function createProduct(): void {
        $this->checkAdminAccess();
        
        $categories = $this->productModel->getAllCategories();
        
        $pageTitle = 'افزودن محصول جدید';
        require BASE_PATH . '/src/Views/admin/layout/header.php';
        require BASE_PATH . '/src/Views/admin/product-form.php';
        require BASE_PATH . '/src/Views/admin/layout/footer.php';
    }
    
    /**
     * ذخیره محصول جدید
     */
    public function storeProduct(): void {
        $this->checkAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/products');
            return;
        }
        
        // اعتبارسنجی
        $errors = [];
        
        if (empty($_POST['name'])) {
            $errors[] = 'نام محصول الزامی است.';
        }
        
        if (empty($_POST['slug'])) {
            $errors[] = 'اسلاگ الزامی است.';
        }
        
        if (empty($_POST['category_id'])) {
            $errors[] = 'دسته‌بندی الزامی است.';
        }
        
        if (empty($_POST['price']) || $_POST['price'] <= 0) {
            $errors[] = 'قیمت باید بیشتر از صفر باشد.';
        }
        
        if (!empty($errors)) {
            $_SESSION['admin_error'] = implode('<br>', $errors);
            $this->redirect('/admin/products/create');
            return;
        }
        
        // ذخیره محصول
        $productId = $this->productModel->createProduct($_POST);
        
        if ($productId) {
            $_SESSION['admin_success'] = 'محصول با موفقیت ایجاد شد.';
            $this->redirect('/admin/products');
        } else {
            $_SESSION['admin_error'] = 'خطا در ایجاد محصول.';
            $this->redirect('/admin/products/create');
        }
    }
    
    /**
     * فرم ویرایش محصول
     */
    public function editProduct(string $id): void {
        $this->checkAdminAccess();
        
        $productId = (int)$id;
        $product = $this->productModel->getByIdForAdmin($productId);
        
        if (!$product) {
            $_SESSION['admin_error'] = 'محصول یافت نشد.';
            $this->redirect('/admin/products');
            return;
        }
        
        $categories = $this->productModel->getAllCategories();
        
        $pageTitle = 'ویرایش محصول';
        require BASE_PATH . '/src/Views/admin/layout/header.php';
        require BASE_PATH . '/src/Views/admin/product-form.php';
        require BASE_PATH . '/src/Views/admin/layout/footer.php';
    }
    
    /**
     * به‌روزرسانی محصول
     */
    public function updateProduct(string $id): void {
        $this->checkAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/products');
            return;
        }
        
        $productId = (int)$id;
        
        // اعتبارسنجی
        $errors = [];
        
        if (empty($_POST['name'])) {
            $errors[] = 'نام محصول الزامی است.';
        }
        
        if (empty($_POST['slug'])) {
            $errors[] = 'اسلاگ الزامی است.';
        }
        
        if (empty($_POST['category_id'])) {
            $errors[] = 'دسته‌بندی الزامی است.';
        }
        
        if (empty($_POST['price']) || $_POST['price'] <= 0) {
            $errors[] = 'قیمت باید بیشتر از صفر باشد.';
        }
        
        if (!empty($errors)) {
            $_SESSION['admin_error'] = implode('<br>', $errors);
            $this->redirect('/admin/products/edit/' . $productId);
            return;
        }
        
        // به‌روزرسانی محصول
        $success = $this->productModel->updateProduct($productId, $_POST);
        
        if ($success) {
            $_SESSION['admin_success'] = 'محصول با موفقیت به‌روزرسانی شد.';
        } else {
            $_SESSION['admin_error'] = 'خطا در به‌روزرسانی محصول.';
        }
        
        $this->redirect('/admin/products');
    }
    
    /**
     * حذف محصول
     */
    public function deleteProduct(): void {
        $this->checkAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/products');
            return;
        }
        
        $productId = (int)($_POST['product_id'] ?? 0);
        
        if ($productId <= 0) {
            $_SESSION['admin_error'] = 'شناسه محصول نامعتبر است.';
            $this->redirect('/admin/products');
            return;
        }
        
        $success = $this->productModel->deleteProduct($productId);
        
        if ($success) {
            $_SESSION['admin_success'] = 'محصول با موفقیت حذف شد.';
        } else {
            $_SESSION['admin_error'] = 'خطا در حذف محصول.';
        }
        
        $this->redirect('/admin/products');
    }
    
    /**
     * مدیریت سفارشات
     */
    public function orders(): void {
        $this->checkAdminAccess();
        
        $orders = $this->orderModel->getAllForAdmin();
        
        $pageTitle = 'مدیریت سفارشات';
        require BASE_PATH . '/src/Views/admin/layout/header.php';
        require BASE_PATH . '/src/Views/admin/orders.php';
        require BASE_PATH . '/src/Views/admin/layout/footer.php';
    }
    
    /**
     * مدیریت کاربران
     */
    public function users(): void {
        $this->checkAdminAccess();
        
        $users = $this->userModel->getAllForAdmin();
        
        $pageTitle = 'مدیریت کاربران';
        require BASE_PATH . '/src/Views/admin/layout/header.php';
        require BASE_PATH . '/src/Views/admin/users.php';
        require BASE_PATH . '/src/Views/admin/layout/footer.php';
    }
    
    /**
     * صفحه تنظیمات تم
     */
    public function themeSettings(): void {
        $this->checkAdminAccess();
        
        $configPath = BASE_PATH . '/config/theme.php';
        $config = require $configPath;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mode = $_POST['mode'] ?? 'automatic';
            $adminTheme = $_POST['admin_theme'] ?? null;
            
            $validModes = ['automatic', 'manual'];
            $validThemes = ['spring', 'summer', 'autumn', 'winter'];
            
            if (!in_array($mode, $validModes)) {
                $mode = 'automatic';
            }
            
            if ($mode === 'manual' && !in_array($adminTheme, $validThemes)) {
                $adminTheme = null;
                $mode = 'automatic';
            }
            
            if ($mode === 'automatic') {
                $adminTheme = null;
            }
            
            $newConfig = "<?php\n\nreturn [\n    'mode' => '$mode',\n    'admin_selected_theme' => " . 
                         ($adminTheme ? "'$adminTheme'" : 'null') . "\n];\n";
            
            file_put_contents($configPath, $newConfig);
            $_SESSION['admin_success'] = 'تنظیمات تم با موفقیت ذخیره شد.';
            $this->redirect('/admin/theme-settings');
            exit;
        }
        
        $pageTitle = 'تنظیمات تم';
        require BASE_PATH . '/src/Views/admin/layout/header.php';
        require BASE_PATH . '/src/Views/admin/theme-settings.php';
        require BASE_PATH . '/src/Views/admin/layout/footer.php';
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
