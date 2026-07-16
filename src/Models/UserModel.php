<?php
/**
 * مدل کاربران
 */
class UserModel {

    /**
     * پیدا کردن کاربر با username
     */
    public function findByUsername(string $username): ?array {
        $u = db_escape($username);
        return db_fetch_one("SELECT * FROM `users` WHERE `username` = '$u' LIMIT 1");
    }

    /**
     * پیدا کردن کاربر با email
     */
    public function findByEmail(string $email): ?array {
        $e = db_escape($email);
        return db_fetch_one("SELECT * FROM `users` WHERE `email` = '$e' LIMIT 1");
    }

    /**
     * پیدا کردن کاربر با ID
     */
    public function findById(int $id): ?array {
        $id = (int)$id;
        return db_fetch_one("SELECT * FROM `users` WHERE `id` = $id LIMIT 1");
    }

    /**
     * ایجاد کاربر جدید
     */
    public function create(string $username, string $email, string $phone, string $password): int|false {
        $u    = db_escape($username);
        $e    = db_escape($email);
        $p    = db_escape($phone);
        $hash = db_escape(Security::hash_password($password));

        $sql = "INSERT INTO `users` (`username`,`email`,`phone`,`password_hash`,`role`,`is_active`)
                VALUES ('$u','$e','$p','$hash','customer',1)";
        return db_insert($sql);
    }

    /**
     * افزایش تعداد تلاش‌های ناموفق ورود
     */
    public function incrementLoginAttempts(int $userId): void {
        $id = (int)$userId;
        db_query("UPDATE `users` SET `login_attempts` = `login_attempts` + 1 WHERE `id` = $id");
    }

    /**
     * قفل کردن حساب کاربری
     */
    public function lockAccount(int $userId, int $minutes = 15): void {
        $id      = (int)$userId;
        $until   = date('Y-m-d H:i:s', time() + ($minutes * 60));
        db_query("UPDATE `users` SET `locked_until` = '$until' WHERE `id` = $id");
    }

    /**
     * ریست کردن تلاش‌های ورود
     */
    public function resetLoginAttempts(int $userId): void {
        $id = (int)$userId;
        db_query("UPDATE `users` SET `login_attempts` = 0, `locked_until` = NULL, `last_login` = NOW() WHERE `id` = $id");
    }

    /**
     * بررسی قفل بودن حساب
     */
    public function isLocked(array $user): bool {
        if (empty($user['locked_until'])) return false;
        return strtotime($user['locked_until']) > time();
    }

    /**
     * دریافت کاربر با ID - alias برای سازگاری
     */
    public function getById(int $id): ?array {
        return $this->findById($id);
    }

    /**
     * به‌روزرسانی پروفایل کاربر
     */
    public function updateProfile(int $userId, array $data): bool {
        $userId   = (int)$userId;
        $fullName = db_escape(trim($data['full_name'] ?? ''));
        $email    = db_escape(trim($data['email'] ?? ''));
        $phone    = db_escape(trim($data['phone'] ?? ''));
        $address  = db_escape(trim($data['address'] ?? ''));
        $job      = db_escape(trim($data['job'] ?? ''));
        $birthDate = !empty($data['birth_date']) ? "'" . db_escape($data['birth_date']) . "'" : 'NULL';
        $postalCode = db_escape(trim($data['postal_code'] ?? ''));

        $sql = "UPDATE `users` 
                SET `full_name` = '$fullName', 
                    `email` = '$email', 
                    `phone` = '$phone', 
                    `address` = '$address',
                    `job` = '$job',
                    `birth_date` = $birthDate,
                    `postal_code` = '$postalCode',
                    `updated_at` = NOW()
                WHERE `id` = $userId";

        return db_query($sql);
    }
    
    /**
     * تغییر رمز عبور کاربر
     */
    public function changePassword(int $userId, string $newPassword): bool {
        $userId = (int)$userId;
        $hash = db_escape(Security::hash_password($newPassword));
        
        $sql = "UPDATE `users` 
                SET `password_hash` = '$hash', 
                    `updated_at` = NOW()
                WHERE `id` = $userId";
        
        return db_query($sql);
    }
    
    /**
     * بررسی صحت رمز عبور فعلی
     */
    public function verifyCurrentPassword(int $userId, string $password): bool {
        $user = $this->findById($userId);
        if (!$user) {
            return false;
        }
        return Security::verify_password($password, $user['password_hash']);
    }

    /**
     * تعداد کل سفارشات کاربر
     */
    public function getTotalOrders(int $userId): int {
        $userId = (int)$userId;
        $result = db_fetch_one("SELECT COUNT(*) as total FROM `orders` WHERE `user_id` = $userId");
        return (int)($result['total'] ?? 0);
    }

    /**
     * تعداد کل نظرات کاربر
     */
    public function getTotalReviews(int $userId): int {
        $userId = (int)$userId;
        $result = db_fetch_one("SELECT COUNT(*) as total FROM `reviews` WHERE `user_id` = $userId");
        return (int)($result['total'] ?? 0);
    }

    /**
     * تعداد علاقه‌مندی‌های کاربر
     */
    public function getWishlistCount(int $userId): int {
        $userId = (int)$userId;
        $result = db_fetch_one("SELECT COUNT(*) as total FROM `wishlist` WHERE `user_id` = $userId");
        return (int)($result['total'] ?? 0);
    }

    /**
     * آخرین سفارشات کاربر
     */
    public function getRecentOrders(int $userId, int $limit = 5): array {
        $userId = (int)$userId;
        $limit  = (int)$limit;
        return db_fetch_all(
            "SELECT * FROM `orders` 
             WHERE `user_id` = $userId 
             ORDER BY `created_at` DESC 
             LIMIT $limit"
        );
    }

    /**
     * آخرین نظرات کاربر
     */
    public function getRecentReviews(int $userId, int $limit = 5): array {
        $userId = (int)$userId;
        $limit  = (int)$limit;
        return db_fetch_all(
            "SELECT r.*, p.name as product_name, p.slug as product_slug, p.main_image
             FROM `reviews` r
             JOIN `products` p ON r.product_id = p.id
             WHERE r.`user_id` = $userId 
             ORDER BY r.`created_at` DESC 
             LIMIT $limit"
        );
    }

    /**
     * علاقه‌مندی‌های کاربر
     */
    public function getWishlist(int $userId): array {
        $userId = (int)$userId;
        return db_fetch_all(
            "SELECT p.*, c.name as category_name, w.added_at
             FROM `wishlist` w
             JOIN `products` p ON w.product_id = p.id
             JOIN `categories` c ON p.category_id = c.id
             WHERE w.user_id = $userId AND p.is_active = 1
             ORDER BY w.added_at DESC"
        );
    }
}
