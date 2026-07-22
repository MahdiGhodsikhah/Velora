<?php
/**
 * کنترلر پنل مدیریت ادمین
 */

require_once BASE_PATH . '/src/Libs/ThemeManager.php';

class AdminController {
    
    /**
     * داشبورد اصلی ادمین
     */
    public function dashboard() {
        // بررسی دسترسی ادمین
        $this->checkAdminAccess();
        
        // آمارگیری
        $stats = $this->getStatistics();
        
        $pageTitle = 'پنل مدیریت';
        require BASE_PATH . '/src/Views/admin/dashboard.php';
    }
    
    /**
     * لیست محصولات
     */
    public function products() {
        $this->checkAdminAccess();
        
        $productModel = new ProductModel();
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $products = $productModel->getAll($limit, $offset);
        $total = $productModel->count();
        $totalPages = ceil($total / $limit);
        
        $pageTitle = 'مدیریت محصولات';
        require BASE_PATH . '/src/Views/admin/products-list.php';
    }
    
    /**
     * فرم افزودن محصول
     */
    public function addProduct() {
        $this->checkAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->saveProduct();
            return;
        }
        
        $productModel = new ProductModel();
        $categories = $productModel->getCategories();
        
        $pageTitle = 'افزودن محصول جدید';
        require BASE_PATH . '/src/Views/admin/product-form.php';
    }
    
    /**
     * فرم ویرایش محصول
     */
    public function editProduct($id) {
        $this->checkAdminAccess();
        
        $productModel = new ProductModel();
        $product = $productModel->getById((int)$id);
        
        if (!$product) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'محصول یافت نشد'];
            header('Location: ' . BASE_URL . '/admin/products');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->saveProduct($id);
            return;
        }
        
        $categories = $productModel->getCategories();
        
        $pageTitle = 'ویرایش محصول';
        require BASE_PATH . '/src/Views/admin/product-form.php';
    }
    
    /**
     * حذف محصول
     */
    public function deleteProduct($id) {
        $this->checkAdminAccess();
        
        $productModel = new ProductModel();
        $id = (int)$id;
        
        $sql = "DELETE FROM products WHERE id = $id";
        if (db_query($sql)) {
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'محصول با موفقیت حذف شد'];
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'خطا در حذف محصول'];
        }
        
        header('Location: ' . BASE_URL . '/admin/products');
        exit;
    }
    
    /**
     * ذخیره محصول (افزودن/ویرایش)
     */
    private function saveProduct($id = null) {
        // دریافت داده‌ها
        $name = db_escape($_POST['name'] ?? '');
        $slug = db_escape($_POST['slug'] ?? '');
        $category_id = (int)($_POST['category_id'] ?? 0);
        $price = (int)($_POST['price'] ?? 0);
        $sale_price = !empty($_POST['sale_price']) ? (int)$_POST['sale_price'] : null;
        $stock_qty = (int)($_POST['stock_qty'] ?? 0);
        $season = db_escape($_POST['season'] ?? 'all');
        $description = db_escape($_POST['description'] ?? '');
        $short_desc = db_escape($_POST['short_desc'] ?? '');
        $sku = db_escape($_POST['sku'] ?? '');
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // محاسبه درصد تخفیف
        $discount_pct = 0;
        if ($sale_price && $sale_price < $price) {
            $discount_pct = round((($price - $sale_price) / $price) * 100);
        }
        
        // اعتبارسنجی
        if (empty($name) || empty($slug) || $category_id == 0) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'لطفاً تمام فیلدهای ضروری را پر کنید'];
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
        
        if ($id) {
            // ویرایش
            $id = (int)$id;
            $sql = "UPDATE products SET 
                    name = '$name',
                    slug = '$slug',
                    category_id = $category_id,
                    price = $price,
                    sale_price = " . ($sale_price ? $sale_price : 'NULL') . ",
                    discount_pct = $discount_pct,
                    stock_qty = $stock_qty,
                    season = '$season',
                    description = '$description',
                    short_desc = '$short_desc',
                    sku = '$sku',
                    is_featured = $is_featured,
                    is_active = $is_active
                    WHERE id = $id";
            
            if (db_query($sql)) {
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'محصول با موفقیت به‌روزرسانی شد'];
                header('Location: ' . BASE_URL . '/admin/products');
            } else {
                $_SESSION['alert'] = ['type' => 'danger', 'message' => 'خطا در به‌روزرسانی محصول'];
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
        } else {
            // افزودن
            $main_image = '/assets/images/products/no-image.jpg';
            
            $sql = "INSERT INTO products (
                    category_id, name, slug, description, short_desc, sku,
                    price, sale_price, discount_pct, stock_qty, main_image,
                    season, is_featured, is_active
                ) VALUES (
                    $category_id, '$name', '$slug', '$description', '$short_desc', '$sku',
                    $price, " . ($sale_price ? $sale_price : 'NULL') . ", $discount_pct, $stock_qty, '$main_image',
                    '$season', $is_featured, $is_active
                )";
            
            if (db_insert($sql)) {
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'محصول با موفقیت اضافه شد'];
                header('Location: ' . BASE_URL . '/admin/products');
            } else {
                $_SESSION['alert'] = ['type' => 'danger', 'message' => 'خطا در افزودن محصول'];
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
        }
        exit;
    }
    
    /**
     * دریافت آمار
     */
    private function getStatistics() {
        $stats = [];
        
        // تعداد محصولات
        $result = db_fetch_one("SELECT COUNT(*) as count FROM products");
        $stats['total_products'] = $result['count'] ?? 0;
        
        // تعداد سفارشات
        $result = db_fetch_one("SELECT COUNT(*) as count FROM orders");
        $stats['total_orders'] = $result['count'] ?? 0;
        
        // تعداد کاربران
        $result = db_fetch_one("SELECT COUNT(*) as count FROM users");
        $stats['total_users'] = $result['count'] ?? 0;
        
        // مجموع فروش
        $result = db_fetch_one("SELECT SUM(total_amount) as total FROM orders WHERE payment_status = 'paid'");
        $stats['total_revenue'] = $result['total'] ?? 0;
        
        // محصولات به تفکیک فصل
        $seasons = ['spring', 'summer', 'autumn', 'winter', 'all'];
        $stats['products_by_season'] = [];
        foreach ($seasons as $season) {
            $result = db_fetch_one("SELECT COUNT(*) as count FROM products WHERE season = '$season'");
            $stats['products_by_season'][$season] = $result['count'] ?? 0;
        }
        
        return $stats;
    }
    
    /**
     * بررسی دسترسی ادمین
     */
    private function checkAdminAccess() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'دسترسی غیرمجاز'];
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }
}
