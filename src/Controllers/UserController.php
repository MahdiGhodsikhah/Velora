<?php
/**
 * کنترلر کاربری - مدیریت پنل کاربری
 */
class UserController {

    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    /**
     * پنل کاربری - داشبورد
     */
    public function dashboard(): void {
        $this->checkAuth();
        Security::set_security_headers();

        $userId = (int)$_SESSION['user_id'];
        $user   = $this->userModel->getById($userId);

        if (!$user) {
            $this->logout();
            return;
        }

        // آمار کاربر
        $stats = [
            'total_orders'    => $this->userModel->getTotalOrders($userId),
            'total_reviews'   => $this->userModel->getTotalReviews($userId),
            'wishlist_count'  => $this->userModel->getWishlistCount($userId),
            'cart_count'      => 0, // بعداً از سبد خرید
        ];

        // آخرین سفارشات
        $recentOrders = $this->userModel->getRecentOrders($userId, 5);

        // آخرین نظرات
        $recentReviews = $this->userModel->getRecentReviews($userId, 5);

        $pageTitle = 'پنل کاربری - ' . Security::e($user['username']);
        $pageDesc  = 'مدیریت حساب کاربری و سفارشات';

        require BASE_PATH . '/src/Views/layouts/minimal-header.php';
        require BASE_PATH . '/src/Views/layouts/navbar.php';
        require BASE_PATH . '/src/Views/pages/dashboard.php';
        require BASE_PATH . '/src/Views/layouts/footer.php';
    }

    /**
     * ویرایش پروفایل
     */
    public function profile(): void {
        $this->checkAuth();
        Security::set_security_headers();

        $userId = (int)$_SESSION['user_id'];
        $user   = $this->userModel->getById($userId);

        if (!$user) {
            $this->logout();
            return;
        }

        $pageTitle = 'ویرایش حساب کاربری';
        $pageDesc  = 'ویرایش اطلاعات حساب کاربری';

        require BASE_PATH . '/src/Views/layouts/minimal-header.php';
        require BASE_PATH . '/src/Views/layouts/navbar.php';
        require BASE_PATH . '/src/Views/pages/profile.php';
        require BASE_PATH . '/src/Views/layouts/footer.php';
    }

    /**
     * به‌روزرسانی پروفایل
     */
    public function updateProfile(): void {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/profile');
            exit;
        }

        // بررسی CSRF Token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (empty($csrfToken) || !isset($_SESSION['csrf_token']) || $csrfToken !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'درخواست نامعتبر است.';
            header('Location: ' . BASE_URL . '/profile');
            exit;
        }

        $userId   = (int)$_SESSION['user_id'];
        $fullName = trim($_POST['full_name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $phone    = trim($_POST['phone'] ?? '');
        $address  = trim($_POST['address'] ?? '');

        // اعتبارسنجی
        $errors = [];

        if (empty($fullName)) {
            $errors[] = 'نام و نام خانوادگی الزامی است.';
        } elseif (mb_strlen($fullName) < 3 || mb_strlen($fullName) > 100) {
            $errors[] = 'نام باید بین 3 تا 100 کاراکتر باشد.';
        }

        if (empty($email)) {
            $errors[] = 'ایمیل الزامی است.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'فرمت ایمیل نامعتبر است.';
        }

        if (!empty($phone) && !preg_match('/^09[0-9]{9}$/', $phone)) {
            $errors[] = 'شماره موبایل باید با 09 شروع شود و 11 رقم باشد.';
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header('Location: ' . BASE_URL . '/profile');
            exit;
        }

        // به‌روزرسانی
        $result = $this->userModel->updateProfile($userId, $fullName, $email, $phone, $address);

        if ($result) {
            $_SESSION['success'] = 'اطلاعات شما با موفقیت به‌روزرسانی شد.';
        } else {
            $_SESSION['error'] = 'خطا در به‌روزرسانی اطلاعات.';
        }

        header('Location: ' . BASE_URL . '/profile');
        exit;
    }

    /**
     * علاقه‌مندی‌ها
     */
    public function wishlist(): void {
        $this->checkAuth();
        Security::set_security_headers();

        $userId = (int)$_SESSION['user_id'];
        $wishlistProducts = $this->userModel->getWishlist($userId);

        foreach ($wishlistProducts as &$p) {
            $p['gallery_arr'] = json_decode($p['gallery'] ?? '[]', true) ?: [];
        }
        unset($p);

        $pageTitle = 'علاقه‌مندی‌های من';
        $pageDesc  = 'لیست محصولات مورد علاقه';

        require BASE_PATH . '/src/Views/layouts/minimal-header.php';
        require BASE_PATH . '/src/Views/layouts/navbar.php';
        require BASE_PATH . '/src/Views/pages/wishlist.php';
        require BASE_PATH . '/src/Views/layouts/footer.php';
    }

    /**
     * بررسی لاگین بودن کاربر
     */
    private function checkAuth(): void {
        if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])) {
            $_SESSION['error'] = 'برای دسترسی به این صفحه ابتدا وارد شوید.';
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    /**
     * خروج از حساب
     */
    private function logout(): void {
        session_destroy();
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}
