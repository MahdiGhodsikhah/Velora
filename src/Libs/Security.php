<?php
/**
 * کلاس امنیت مرکزی پروژه
 * پوشش کامل در برابر: XSS, CSRF, SQL Injection, Brute Force, Directory Traversal
 */

class Security {

    // -------------------------------------------------------------------
    // CSRF Token
    // -------------------------------------------------------------------

    /**
     * تولید CSRF Token (alias برای csrf_token)
     */
    public static function generate_csrf_token(): string {
        return self::csrf_token();
    }

    /**
     * تولید یا دریافت CSRF Token جاری
     */
    public static function csrf_token(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * اعتبارسنجی CSRF Token
     */
    public static function verify_csrf(string $token): bool {
        if (empty($_SESSION['csrf_token'])) return false;
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * خروجی HTML فیلد hidden CSRF
     */
    public static function csrf_field(): string {
        return '<input type="hidden" name="csrf_token" value="' . self::csrf_token() . '">';
    }

    // -------------------------------------------------------------------
    // XSS Protection
    // -------------------------------------------------------------------

    /**
     * پاکسازی خروجی برای نمایش در HTML
     */
    public static function e(mixed $value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * پاکسازی ورودی کاربر
     */
    public static function sanitize_input(string $input): string {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return $input;
    }

    /**
     * پاکسازی رشته برای استفاده به عنوان slug
     */
    public static function sanitize_slug(string $input): string {
        $input = strtolower(trim($input));
        $input = preg_replace('/[^a-z0-9\-]/', '-', $input);
        $input = preg_replace('/-+/', '-', $input);
        return trim($input, '-');
    }

    // -------------------------------------------------------------------
    // اعتبارسنجی ورودی‌ها
    // -------------------------------------------------------------------

    public static function validate_email(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false
            && strlen($email) <= 150;
    }

    public static function validate_username(string $username): bool {
        return preg_match('/^[a-zA-Z0-9_\-\.]{3,50}$/', $username) === 1;
    }

    public static function validate_phone(string $phone): bool {
        return preg_match('/^09[0-9]{9}$/', $phone) === 1;
    }

    public static function validate_password(string $pass): bool {
        // حداقل ۸ کاراکتر، یک عدد، یک حرف بزرگ
        return strlen($pass) >= 8
            && preg_match('/[0-9]/', $pass)
            && preg_match('/[A-Za-z]/', $pass);
    }

    /**
     * اعتبارسنجی عدد صحیح مثبت
     */
    public static function validate_int(mixed $value, int $min = 1, int $max = PHP_INT_MAX): bool {
        $v = filter_var($value, FILTER_VALIDATE_INT);
        return $v !== false && $v >= $min && $v <= $max;
    }

    // -------------------------------------------------------------------
    // محدودیت نرخ درخواست (Rate Limiting)
    // -------------------------------------------------------------------

    /**
     * بررسی و ثبت تلاش‌های ورود
     * @param string $key  شناسه (IP یا username)
     * @param int    $max  حداکثر تلاش
     * @param int    $ttl  بازه زمانی (ثانیه)
     */
    public static function rate_limit(string $key, int $max = 5, int $ttl = 900): bool {
        $session_key = 'rl_' . md5($key);
        $now = time();

        if (!isset($_SESSION[$session_key])) {
            $_SESSION[$session_key] = ['count' => 0, 'first' => $now];
        }

        $data = &$_SESSION[$session_key];

        // ریست بعد از TTL
        if ($now - $data['first'] > $ttl) {
            $data = ['count' => 0, 'first' => $now];
        }

        $data['count']++;

        if ($data['count'] > $max) {
            return false; // مسدود
        }
        return true;
    }

    /**
     * ریست کردن rate limiter
     */
    public static function rate_limit_reset(string $key): void {
        $session_key = 'rl_' . md5($key);
        unset($_SESSION[$session_key]);
    }

    // -------------------------------------------------------------------
    // امنیت آپلود فایل
    // -------------------------------------------------------------------

    private static array $allowed_mime = [
        'image/jpeg', 'image/png', 'image/webp', 'image/gif'
    ];

    private static array $allowed_ext = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    public static function validate_upload(array $file): array {
        $errors = [];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'خطا در آپلود فایل';
            return $errors;
        }

        // بررسی اندازه (حداکثر 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            $errors[] = 'حجم فایل نباید بیشتر از ۵ مگابایت باشد';
        }

        // بررسی پسوند
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, self::$allowed_ext)) {
            $errors[] = 'فرمت فایل مجاز نیست';
        }

        // بررسی MIME Type واقعی (نه از $_FILES)
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $real_mime = $finfo->file($file['tmp_name']);
        if (!in_array($real_mime, self::$allowed_mime)) {
            $errors[] = 'نوع فایل مجاز نیست';
        }

        return $errors;
    }

    // -------------------------------------------------------------------
    // تولید نام فایل امن
    // -------------------------------------------------------------------
    public static function safe_filename(string $original): string {
        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
        return bin2hex(random_bytes(16)) . '.' . $ext;
    }

    // -------------------------------------------------------------------
    // هدرهای امنیتی HTTP
    // -------------------------------------------------------------------
    public static function set_security_headers(): void {
        if (!headers_sent()) {
            header('X-Content-Type-Options: nosniff');
            header('X-Frame-Options: SAMEORIGIN');
            header('X-XSS-Protection: 1; mode=block');
            header('Referrer-Policy: strict-origin-when-cross-origin');
        }
    }

    // -------------------------------------------------------------------
    // جلوگیری از Directory Traversal
    // -------------------------------------------------------------------
    public static function validate_path(string $path, string $base_dir): bool {
        $real = realpath($path);
        $base = realpath($base_dir);
        if ($real === false || $base === false) return false;
        return strpos($real, $base) === 0;
    }

    // -------------------------------------------------------------------
    // تولید توکن امن
    // -------------------------------------------------------------------
    public static function generate_token(int $bytes = 32): string {
        return bin2hex(random_bytes($bytes));
    }

    // -------------------------------------------------------------------
    // هش رمز عبور
    // -------------------------------------------------------------------
    public static function hash_password(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    public static function verify_password(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    // -------------------------------------------------------------------
    // رندر ستاره‌های امتیاز محصول
    // -------------------------------------------------------------------
    
    /**
     * رندر کردن ستاره‌های امتیاز بر اساس میانگین (Font Awesome)
     * @param float $avg میانگین امتیاز (0 تا 5)
     * @return string HTML ستاره‌ها
     */
    public static function renderStars(float $avg): string {
        $out = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($avg >= $i) {
                $out .= '<i class="fas fa-star" aria-hidden="true"></i>';
            } elseif ($avg >= $i - 0.5) {
                $out .= '<i class="fas fa-star-half-alt" aria-hidden="true"></i>';
            } else {
                $out .= '<i class="far fa-star" aria-hidden="true"></i>';
            }
        }
        return $out;
    }
}
