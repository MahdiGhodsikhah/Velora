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
        $cartCount = 0;
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            $cartCount = array_sum($_SESSION['cart']);
        }
        
        $stats = [
            'total_orders'    => $this->userModel->getTotalOrders($userId),
            'total_reviews'   => $this->userModel->getTotalReviews($userId),
            'wishlist_count'  => $this->userModel->getWishlistCount($userId),
            'cart_count'      => $cartCount,
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

        $userId = (int)$_SESSION['user_id'];

        // Rate Limiting ساده - فقط PHP (3 ثانیه بین هر درخواست)
        if (!isset($_SESSION['last_profile_update'])) {
            $_SESSION['last_profile_update'] = 0;
        }
        
        $timeSinceLastUpdate = time() - $_SESSION['last_profile_update'];
        if ($timeSinceLastUpdate < 3) {
            $_SESSION['error'] = 'لطفا 3 ثانیه صبر کنید و دوباره تلاش کنید.';
            header('Location: ' . BASE_URL . '/profile');
            exit;
        }
        
        $_SESSION['last_profile_update'] = time();
        $fullName = trim($_POST['full_name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $phone    = trim($_POST['phone'] ?? '');
        $address  = trim($_POST['address'] ?? '');
        $job      = trim($_POST['job'] ?? '');
        $birthDate = trim($_POST['birth_date'] ?? '');
        $postalCode = trim($_POST['postal_code'] ?? '');

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

        if (!empty($postalCode) && !preg_match('/^[0-9]{10}$/', $postalCode)) {
            $errors[] = 'کد پستی باید 10 رقم باشد.';
        }

        if (!empty($birthDate)) {
            // بررسی فرمت تاریخ (YYYY-MM-DD)
            $date = \DateTime::createFromFormat('Y-m-d', $birthDate);
            if (!$date || $date->format('Y-m-d') !== $birthDate) {
                $errors[] = 'فرمت تاریخ تولد نامعتبر است.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header('Location: ' . BASE_URL . '/profile');
            exit;
        }

        // به‌روزرسانی
        $data = [
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'job' => $job,
            'birth_date' => $birthDate,
            'postal_code' => $postalCode
        ];
        
        // مدیریت عکس پروفایل
        $user = $this->userModel->getById($userId);
        
        // حذف عکس پروفایل
        if (isset($_POST['remove_profile_image']) && $_POST['remove_profile_image'] === '1') {
            if (!empty($user['profile_image'])) {
                ImageUploader::deleteProfileImage($user['profile_image']);
            }
            $data['profile_image'] = null;
        }
        // آپلود عکس جدید
        elseif (!empty($_FILES['profile_image']) && $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = ImageUploader::uploadProfileImage($_FILES['profile_image'], $userId);
            
            if ($uploadResult['success']) {
                // حذف عکس قبلی
                if (!empty($user['profile_image'])) {
                    ImageUploader::deleteProfileImage($user['profile_image']);
                }
                $data['profile_image'] = $uploadResult['path'];
            } else {
                $_SESSION['error'] = $uploadResult['error'];
                header('Location: ' . BASE_URL . '/profile');
                exit;
            }
        }
        
        $result = $this->userModel->updateProfile($userId, $data);

        if ($result) {
            // ریست کردن تایمر
            $_SESSION['last_profile_update'] = 0;
            
            $_SESSION['success'] = 'اطلاعات شما با موفقیت به‌روزرسانی شد.';
        } else {
            $_SESSION['error'] = 'خطا در به‌روزرسانی اطلاعات.';
        }

        header('Location: ' . BASE_URL . '/profile');
        exit;
    }
    
    /**
     * تغییر رمز عبور
     */
    public function changePassword(): void {
        $this->checkAuth();
        
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'درخواست نامعتبر'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $userId = (int)$_SESSION['user_id'];

        // Rate Limiting ساده - 5 تلاش در 5 دقیقه
        if (!isset($_SESSION['password_change_attempts'])) {
            $_SESSION['password_change_attempts'] = [];
        }
        
        // پاک کردن تلاش‌های قدیمی (بیشتر از 5 دقیقه)
        $_SESSION['password_change_attempts'] = array_filter(
            $_SESSION['password_change_attempts'],
            function($timestamp) {
                return (time() - $timestamp) < 300; // 5 دقیقه
            }
        );
        
        // بررسی تعداد تلاش‌ها
        if (count($_SESSION['password_change_attempts']) >= 5) {
            echo json_encode(['success' => false, 'message' => 'تعداد تلاش‌های شما بیش از حد مجاز است. لطفا 5 دقیقه دیگر تلاش کنید'], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        // ثبت تلاش جدید
        $_SESSION['password_change_attempts'][] = time();
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // اعتبارسنجی
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            echo json_encode(['success' => false, 'message' => 'تمام فیلدها الزامی است'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // بررسی رمز عبور فعلی
        if (!$this->userModel->verifyCurrentPassword($userId, $currentPassword)) {
            echo json_encode(['success' => false, 'message' => 'رمز عبور فعلی اشتباه است'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // بررسی تطابق رمز عبورهای جدید
        if ($newPassword !== $confirmPassword) {
            echo json_encode(['success' => false, 'message' => 'رمز عبور جدید و تکرار آن یکسان نیستند'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // اعتبارسنجی قدرت رمز عبور
        if (!Security::validate_password($newPassword)) {
            echo json_encode(['success' => false, 'message' => 'رمز عبور باید حداقل 8 کاراکتر، شامل حرف و عدد باشد'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // تغییر رمز عبور
        $result = $this->userModel->changePassword($userId, $newPassword);

        if ($result) {
            // پاک کردن تلاش‌ها بعد از موفقیت
            $_SESSION['password_change_attempts'] = [];
            
            echo json_encode(['success' => true, 'message' => 'رمز عبور با موفقیت تغییر یافت'], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['success' => false, 'message' => 'خطا در تغییر رمز عبور'], JSON_UNESCAPED_UNICODE);
        }
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
