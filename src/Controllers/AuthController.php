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

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // اعتبارسنجی ابتدایی
        if (empty($username) || empty($password)) {
            $_SESSION['auth_error'] = 'نام کاربری و رمز عبور الزامی است.';
            $this->redirect('/login');
            return;
        }

        // اعتبارسنجی فرمت
        if (!Security::validate_username($username) && !Security::validate_email($username)) {
            $_SESSION['auth_error'] = 'نام کاربری یا رمز عبور اشتباه است.';
            $this->redirect('/login');
            return;
        }

        // یافتن کاربر
        $user = $this->userModel->findByUsername($username);
        if (!$user) {
            $user = $this->userModel->findByEmail($username);
        }

        if (!$user || !$user['is_active']) {
            // پیام مبهم برای جلوگیری از user enumeration
            $_SESSION['auth_error'] = 'نام کاربری یا رمز عبور اشتباه است.';
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
                $_SESSION['auth_error'] = 'نام کاربری یا رمز عبور اشتباه است.';
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

        // Rate Limiting
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        if (!Security::rate_limit('register_' . $ip, 3, 3600)) {
            $_SESSION['auth_error'] = 'تعداد ثبت‌نام‌های شما بیش از حد مجاز است.';
            $this->redirect('/register');
            return;
        }

        $username  = trim($_POST['username'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $phone     = trim($_POST['phone'] ?? '');
        $password  = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';

        // اعتبارسنجی
        $errors = [];

        if (!Security::validate_username($username)) {
            $errors[] = 'نام کاربری باید ۳ تا ۵۰ کاراکتر و فقط شامل حروف انگلیسی، اعداد، خط تیره و نقطه باشد.';
        }
        if (!Security::validate_email($email)) {
            $errors[] = 'آدرس ایمیل معتبر نیست.';
        }
        if (!empty($phone) && !Security::validate_phone($phone)) {
            $errors[] = 'شماره موبایل باید با ۰۹ شروع شده و ۱۱ رقم باشد.';
        }
        if (!Security::validate_password($password)) {
            $errors[] = 'رمز عبور باید حداقل ۸ کاراکتر، شامل حرف و عدد باشد.';
        }
        if ($password !== $password2) {
            $errors[] = 'رمز عبور و تکرار آن یکسان نیستند.';
        }

        if (!empty($errors)) {
            $_SESSION['auth_error'] = implode('<br>', $errors);
            $this->redirect('/register');
            return;
        }

        // بررسی تکراری نبودن
        if ($this->userModel->findByUsername($username)) {
            $_SESSION['auth_error'] = 'این نام کاربری قبلاً ثبت شده است.';
            $this->redirect('/register');
            return;
        }
        if ($this->userModel->findByEmail($email)) {
            $_SESSION['auth_error'] = 'این ایمیل قبلاً ثبت شده است.';
            $this->redirect('/register');
            return;
        }

        // ثبت کاربر
        $userId = $this->userModel->create($username, $email, $phone, $password);
        if (!$userId) {
            $_SESSION['auth_error'] = 'خطا در ثبت‌نام. لطفاً دوباره تلاش کنید.';
            $this->redirect('/register');
            return;
        }

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
    private function isLoggedIn(): bool {
        return !empty($_SESSION['logged_in']) && !empty($_SESSION['user_id']);
    }

    private function redirect(string $path): void {
        $base = defined('BASE_URL') ? BASE_URL : '';
        header('Location: ' . $base . $path);
        exit;
    }
}
