<?php

class AdminController {
    
    private UserModel $userModel;
    private ProductModel $productModel;
    private OrderModel $orderModel;
    private ReviewModel $reviewModel;
    
    public function __construct() {
        $this->userModel = new UserModel();
        $this->productModel = new ProductModel();
        $this->orderModel = new OrderModel();
        $this->reviewModel = new ReviewModel();
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
        
        // صفحه‌بندی و فیلتر
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = isset($_GET['per_page']) ? max(5, min(50, (int)$_GET['per_page'])) : 20;
        $statusFilter = $_GET['status'] ?? 'all';
        $paymentFilter = $_GET['payment'] ?? 'all';
        
        $totalOrders = $this->orderModel->getTotalCountWithFilter($statusFilter, $paymentFilter);
        $totalPages = ceil($totalOrders / $perPage);
        
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
        }
        
        $orders = $this->orderModel->getAllForAdminPaginated($page, $perPage, $statusFilter, $paymentFilter);
        $pendingCount = $this->orderModel->getPendingCount();
        
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
    
    /**
     * مدیریت نظرات
     */
    public function reviews(): void {
        $this->checkAdminAccess();
        
        // صفحه‌بندی و فیلتر
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = isset($_GET['per_page']) ? max(5, min(50, (int)$_GET['per_page'])) : 20;
        $filter = $_GET['filter'] ?? 'all'; // all, pending, approved
        
        $totalReviews = $this->reviewModel->getTotalCountWithFilter($filter);
        $totalPages = ceil($totalReviews / $perPage);
        
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
        }
        
        $reviews = $this->reviewModel->getAllForAdmin($page, $perPage, $filter);
        $pendingCount = $this->reviewModel->getPendingCount();
        
        $pageTitle = 'مدیریت نظرات';
        require BASE_PATH . '/src/Views/admin/layout/header.php';
        require BASE_PATH . '/src/Views/admin/reviews.php';
        require BASE_PATH . '/src/Views/admin/layout/footer.php';
    }
    
    /**
     * فرم ویرایش نظر
     */
    public function editReview(string $id): void {
        $this->checkAdminAccess();
        
        $reviewId = (int)$id;
        $review = $this->reviewModel->getById($reviewId);
        
        if (!$review) {
            $_SESSION['admin_error'] = 'نظر یافت نشد.';
            $this->redirect('/admin/reviews');
            return;
        }
        
        $pageTitle = 'ویرایش نظر';
        require BASE_PATH . '/src/Views/admin/layout/header.php';
        require BASE_PATH . '/src/Views/admin/review-form.php';
        require BASE_PATH . '/src/Views/admin/layout/footer.php';
    }
    
    /**
     * به‌روزرسانی نظر
     */
    public function updateReview(string $id): void {
        $this->checkAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/reviews');
            return;
        }
        
        $reviewId = (int)$id;
        $success = $this->reviewModel->update($reviewId, $_POST);
        
        if ($success) {
            $_SESSION['admin_success'] = 'نظر با موفقیت به‌روزرسانی شد.';
        } else {
            $_SESSION['admin_error'] = 'خطا در به‌روزرسانی نظر.';
        }
        
        $this->redirect('/admin/reviews');
    }
    
    /**
     * تأیید یا رد نظر
     */
    public function approveReview(): void {
        $this->checkAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/reviews');
            return;
        }
        
        $reviewId = (int)($_POST['review_id'] ?? 0);
        $action = $_POST['action'] ?? 'approve'; // approve or unapprove
        
        if ($reviewId <= 0) {
            $_SESSION['admin_error'] = 'شناسه نظر نامعتبر است.';
            $this->redirect('/admin/reviews');
            return;
        }
        
        if ($action === 'approve') {
            $success = $this->reviewModel->approve($reviewId);
            $message = 'نظر تأیید شد.';
        } else {
            $success = $this->reviewModel->unapprove($reviewId);
            $message = 'تأیید نظر لغو شد.';
        }
        
        if ($success) {
            $_SESSION['admin_success'] = $message;
        } else {
            $_SESSION['admin_error'] = 'خطا در عملیات.';
        }
        
        $this->redirect('/admin/reviews');
    }
    
    /**
     * حذف نظر
     */
    public function deleteReview(): void {
        $this->checkAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/reviews');
            return;
        }
        
        $reviewId = (int)($_POST['review_id'] ?? 0);
        
        if ($reviewId <= 0) {
            $_SESSION['admin_error'] = 'شناسه نظر نامعتبر است.';
            $this->redirect('/admin/reviews');
            return;
        }
        
        $success = $this->reviewModel->delete($reviewId);
        
        if ($success) {
            $_SESSION['admin_success'] = 'نظر با موفقیت حذف شد.';
        } else {
            $_SESSION['admin_error'] = 'خطا در حذف نظر.';
        }
        
        $this->redirect('/admin/reviews');
    }
    
    /**
     * مشاهده جزئیات سفارش
     */
    public function viewOrder(string $id): void {
        $this->checkAdminAccess();
        
        $orderId = (int)$id;
        $order = $this->orderModel->getOrderDetails($orderId);
        
        if (!$order) {
            $_SESSION['admin_error'] = 'سفارش یافت نشد.';
            $this->redirect('/admin/orders');
            return;
        }
        
        $pageTitle = 'جزئیات سفارش #' . $order['order_number'];
        require BASE_PATH . '/src/Views/admin/layout/header.php';
        require BASE_PATH . '/src/Views/admin/order-detail.php';
        require BASE_PATH . '/src/Views/admin/layout/footer.php';
    }
    
    /**
     * به‌روزرسانی وضعیت سفارش
     */
    public function updateOrderStatus(): void {
        $this->checkAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/orders');
            return;
        }
        
        $orderId = (int)($_POST['order_id'] ?? 0);
        $action = $_POST['action'] ?? '';
        $value = $_POST['value'] ?? '';
        
        if ($orderId <= 0) {
            $_SESSION['admin_error'] = 'شناسه سفارش نامعتبر است.';
            $this->redirect('/admin/orders');
            return;
        }
        
        $success = false;
        $message = '';
        
        if ($action === 'status') {
            $success = $this->orderModel->updateStatus($orderId, $value);
            $message = 'وضعیت سفارش به‌روزرسانی شد.';
        } elseif ($action === 'payment') {
            $success = $this->orderModel->updatePaymentStatus($orderId, $value);
            $message = 'وضعیت پرداخت به‌روزرسانی شد.';
        }
        
        if ($success) {
            $_SESSION['admin_success'] = $message;
        } else {
            $_SESSION['admin_error'] = 'خطا در به‌روزرسانی.';
        }
        
        // اگر از صفحه جزئیات آمده، برگرد به همان صفحه
        $returnUrl = $_POST['return_url'] ?? '/admin/orders';
        $this->redirect($returnUrl);
    }
    
    /**
     * حذف سفارش
     */
    public function deleteOrder(): void {
        $this->checkAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/orders');
            return;
        }
        
        $orderId = (int)($_POST['order_id'] ?? 0);
        
        if ($orderId <= 0) {
            $_SESSION['admin_error'] = 'شناسه سفارش نامعتبر است.';
            $this->redirect('/admin/orders');
            return;
        }
        
        $success = $this->orderModel->deleteOrder($orderId);
        
        if ($success) {
            $_SESSION['admin_success'] = 'سفارش با موفقیت حذف شد.';
        } else {
            $_SESSION['admin_error'] = 'خطا در حذف سفارش.';
        }
        
        $this->redirect('/admin/orders');
    }
}
