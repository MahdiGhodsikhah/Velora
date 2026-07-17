<?php
/**
 * کنترلر احراز هویت
 * امنیت: CSRF, Rate Limiting, Password Hashing, Brute Force Protection
 */
class AuthController {

    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    // -------------------------------------------------------------------
    // فرم ورود
    // -------------------------------------------------------------------
    public function loginForm(): void {
        Security::set_security_headers();
        if ($this->isLoggedIn()) {
            $this->redirect('/');
            return;
        }
        $pageTitle = 'ورود به حساب کاربری';
        $error     = $_SESSION['auth_error'] ?? '';
        $success   = $_SESSION['auth_success'] ?? '';
        unset($_SESSION['auth_error'], $_SESSION['auth_success']);

        require BASE_PATH . '/src/Views/layouts/minimal-header.php';
        require BASE_PATH . '/src/Views/pages/login.php';
        require BASE_PATH . '/src/Views/layouts/footer.php';
    }

    // -------------------------------------------------------------------
    // پردازش ورود
    // -------------------------------------------------------------------
    public function login(): void {
        Security::set_security_headers();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
            return;
        }

        // CSRF
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!Security::verify_csrf($csrfToken)) {
            $_SESSION['auth_error'] = 'توکن امنیتی نامعتبر است. لطفاً دوباره تلاش کنید.';
            $this->redirect('/login');
            return;
        }

        // Rate Limiting بر اساس IP
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        if (!Security::rate_limit('login_' . $ip, 10, 900)) {
            $_SESSION['auth_error'] = 'تعداد تلاش‌های ورود بیش از حد مجاز است. ۱۵ دقیقه دیگر تلاش کنید.';
            $this->redirect('/login');
            return;
        }

        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';

        // اعتبارسنجی ابتدایی
        if (empty($phone) || empty($password)) {
            $_SESSION['auth_error'] = 'شماره موبایل و رمز عبور الزامی است.';
            $this->redirect('/login');
            return;
        }

        // اعتبارسنجی فرمت شماره
        if (!Security::validate_phone($phone)) {
            $_SESSION['auth_error'] = 'شماره موبایل یا رمز عبور اشتباه است.';
            $this->redirect('/login');
            return;
        }

        // یافتن کاربر با شماره موبایل
        $user = $this->userModel->findByPhone($phone);

        if (!$user || !$user['is_active']) {
            // پیام مبهم برای جلوگیری از user enumeration
            $_SESSION['auth_error'] = 'شماره موبایل یا رمز عبور اشتباه است.';
            $this->redirect('/login');
            return;
        }

        // بررسی قفل بودن حساب
        if ($this->userModel->isLocked($user)) {
            $until = date('H:i', strtotime($user['locked_until']));
            $_SESSION['auth_error'] = "حساب شما تا ساعت $until قفل شده است.";
            $this->redirect('/login');
            return;
        }

        // تأیید رمز عبور
        if (!Security::verify_password($password, $user['password_hash'])) {
            $this->userModel->incrementLoginAttempts((int)$user['id']);

            // قفل کردن بعد از ۵ تلاش ناموفق
            if ((int)$user['login_attempts'] >= 4) {
                $this->userModel->lockAccount((int)$user['id'], 15);
                $_SESSION['auth_error'] = 'حساب شما به مدت ۱۵ دقیقه قفل شد.';
            } else {
                $_SESSION['auth_error'] = 'شماره موبایل یا رمز عبور اشتباه است.';
            }
            $this->redirect('/login');
            return;
        }

        // ورود موفق
        $this->userModel->resetLoginAttempts((int)$user['id']);
        Security::rate_limit_reset('login_' . $ip);

        // بازسازی session برای جلوگیری از session fixation
        session_regenerate_id(true);

        $_SESSION['user_id']   = (int)$user['id'];
        $_SESSION['username']  = $user['username'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['logged_in'] = true;

        $_SESSION['auth_success'] = 'خوش آمدید، ' . Security::e($user['username']) . '!';
        $this->redirect('/');
    }

    // -------------------------------------------------------------------
    // فرم ثبت‌نام
    // -------------------------------------------------------------------
    public function registerForm(): void {
        Security::set_security_headers();
        if ($this->isLoggedIn()) {
            $this->redirect('/');
            return;
        }
        $pageTitle = 'ایجاد حساب کاربری';
        $error     = $_SESSION['auth_error'] ?? '';
        $success   = $_SESSION['auth_success'] ?? '';
        unset($_SESSION['auth_error'], $_SESSION['auth_success']);

        require BASE_PATH . '/src/Views/layouts/minimal-header.php';
        require BASE_PATH . '/src/Views/pages/register.php';
        require BASE_PATH . '/src/Views/layouts/footer.php';
    }

    // -------------------------------------------------------------------
    // پردازش ثبت‌نام
    // -------------------------------------------------------------------
    public function register(): void {
        Security::set_security_headers();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
            return;
        }

        // CSRF
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!Security::verify_csrf($csrfToken)) {
            $_SESSION['auth_error'] = 'توکن امنیتی نامعتبر است.';
            $this->redirect('/register');
            return;
        }

        // Rate Limiting - 3 تلاش در 1 دقیقه
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        if (!Security::rate_limit('register_' . $ip, 3, 60)) {
            $_SESSION['auth_error'] = 'تعداد ثبت‌نام‌های شما بیش از حد مجاز است. لطفاً 1 دقیقه صبر کنید.';
            $this->redirect('/register');
            return;
        }

        $phone     = trim($_POST['phone'] ?? '');
        $password  = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';
        $terms     = isset($_POST['terms']) && $_POST['terms'] === 'on';

        // ذخیره مقادیر برای نمایش مجدد در صورت خطا
        $_SESSION['form_data'] = [
            'phone' => $phone
        ];

        // اعتبارسنجی
        $errors = [];

        // بررسی خالی بودن همه فیلدها
        if (empty($phone) && empty($password) && empty($password2)) {
            $_SESSION['auth_error'] = 'لطفاً اطلاعات را تکمیل کنید.';
            $this->redirect('/register');
            return;
        }

        // بررسی خالی نبودن فیلدهای الزامی
        if (empty($phone)) {
            $errors[] = 'شماره موبایل الزامی است.';
        } elseif (!Security::validate_phone($phone)) {
            $errors[] = 'شماره موبایل باید با ۰۹ شروع شده و ۱۱ رقم باشد.';
        }

        if (empty($password)) {
            $errors[] = 'رمز عبور الزامی است.';
        } elseif (!Security::validate_password($password)) {
            $errors[] = 'رمز عبور باید حداقل ۸ کاراکتر، شامل حرف و عدد باشد.';
        }

        if (empty($password2)) {
            $errors[] = 'تکرار رمز عبور الزامی است.';
        } elseif ($password !== $password2) {
            $errors[] = 'رمز عبور و تکرار آن یکسان نیستند.';
        }

        if (!$terms) {
            $errors[] = 'برای ثبت نام باید قوانین و مقررات را بپذیرید.';
        }

        if (!empty($errors)) {
            $_SESSION['auth_error'] = implode('<br>', $errors);
            $this->redirect('/register');
            return;
        }

        // بررسی تکراری نبودن شماره
        if ($this->userModel->findByPhone($phone)) {
            $_SESSION['auth_error'] = 'این شماره موبایل قبلاً ثبت شده است.';
            $this->redirect('/register');
            return;
        }

        // تولید نام کاربری یکتا
        $username = $this->generateUniqueUsername();

        // ثبت کاربر - ایمیل خالی
        $userId = $this->userModel->create($username, '', $phone, $password);
        if (!$userId) {
            $_SESSION['auth_error'] = 'خطا در ثبت‌نام. لطفاً دوباره تلاش کنید.';
            $this->redirect('/register');
            return;
        }

        // پاک کردن داده‌های فرم
        unset($_SESSION['form_data']);
        
        // لاگین خودکار بعد از ثبت نام
        $user = $this->userModel->findByUsername($username);
        if ($user) {
            // بازسازی session برای امنیت
            session_regenerate_id(true);
            
            $_SESSION['user_id']   = (int)$user['id'];
            $_SESSION['username']  = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['logged_in'] = true;
            $_SESSION['auth_success'] = 'خوش آمدید، ' . Security::e($user['username']) . '! ثبت‌نام شما با موفقیت انجام شد.';
        }
        
        $this->redirect('/');
    }

    // -------------------------------------------------------------------
    // خروج
    // -------------------------------------------------------------------
    public function logout(): void {
        session_unset();
        session_destroy();
        session_start();
        session_regenerate_id(true);
        $_SESSION['auth_success'] = 'از حساب کاربری خود خارج شدید.';
        $this->redirect('/');
    }

    // -------------------------------------------------------------------
    // کمکی
    // -------------------------------------------------------------------
    
    /**
     * تولید نام کاربری یکتا
     */
    private function generateUniqueUsername(): string {
        do {
            // تولید نام کاربری با فرمت: user + 8 رقم تصادفی
            $username = 'user' . rand(10000000, 99999999);
        } while ($this->userModel->findByUsername($username));
        
        return $username;
    }
    
    private function isLoggedIn(): bool {
        return !empty($_SESSION['logged_in']) && !empty($_SESSION['user_id']);
    }

    private function redirect(string $path): void {
        $base = defined('BASE_URL') ? BASE_URL : '';
        header('Location: ' . $base . $path);
        exit;
    }
}
