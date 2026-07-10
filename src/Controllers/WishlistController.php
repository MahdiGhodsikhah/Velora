<?php
/**
 * کنترلر علاقه‌مندی‌ها
 */
class WishlistController {

    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    /**
     * افزودن/حذف محصول از علاقه‌مندی‌ها
     */
    public function toggle(): void {
        header('Content-Type: application/json; charset=utf-8');

        // بررسی لاگین بودن
        if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])) {
            echo json_encode([
                'success' => false,
                'message' => 'برای استفاده از این قابلیت ابتدا وارد شوید',
                'redirect' => BASE_URL . '/login'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // فقط POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'درخواست نامعتبر'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // دریافت داده‌های JSON
        $input = json_decode(file_get_contents('php://input'), true);
        $productId = (int)($input['product_id'] ?? 0);

        if ($productId <= 0) {
            echo json_encode([
                'success' => false,
                'message' => 'شناسه محصول نامعتبر است'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $userId = (int)$_SESSION['user_id'];

        // بررسی وجود محصول
        if (!$this->productExists($productId)) {
            echo json_encode([
                'success' => false,
                'message' => 'محصول مورد نظر یافت نشد'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // چک کنیم که آیا در لیست علاقه‌مندی‌ها هست یا نه
        $exists = $this->isInWishlist($userId, $productId);

        if ($exists) {
            // حذف از علاقه‌مندی‌ها
            $result = $this->removeFromWishlist($userId, $productId);
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'action' => 'removed',
                    'message' => 'محصول از علاقه‌مندی‌ها حذف شد'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'خطا در حذف از علاقه‌مندی‌ها'
                ], JSON_UNESCAPED_UNICODE);
            }
        } else {
            // افزودن به علاقه‌مندی‌ها
            $result = $this->addToWishlist($userId, $productId);
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'action' => 'added',
                    'message' => 'محصول به علاقه‌مندی‌ها اضافه شد'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'خطا در افزودن به علاقه‌مندی‌ها'
                ], JSON_UNESCAPED_UNICODE);
            }
        }
        exit;
    }

    /**
     * بررسی وجود محصول
     */
    private function productExists(int $productId): bool {
        $id = (int)$productId;
        $result = db_fetch_one("SELECT id FROM `products` WHERE `id` = $id AND `is_active` = 1 LIMIT 1");
        return !empty($result);
    }

    /**
     * بررسی اینکه محصول در لیست علاقه‌مندی‌های کاربر هست یا نه
     */
    private function isInWishlist(int $userId, int $productId): bool {
        $userId = (int)$userId;
        $productId = (int)$productId;
        $result = db_fetch_one(
            "SELECT id FROM `wishlist` 
             WHERE `user_id` = $userId AND `product_id` = $productId 
             LIMIT 1"
        );
        return !empty($result);
    }

    /**
     * افزودن به علاقه‌مندی‌ها
     */
    private function addToWishlist(int $userId, int $productId): bool {
        $userId = (int)$userId;
        $productId = (int)$productId;
        $sql = "INSERT INTO `wishlist` (`user_id`, `product_id`, `added_at`) 
                VALUES ($userId, $productId, NOW())";
        return db_query($sql);
    }

    /**
     * حذف از علاقه‌مندی‌ها
     */
    private function removeFromWishlist(int $userId, int $productId): bool {
        $userId = (int)$userId;
        $productId = (int)$productId;
        $sql = "DELETE FROM `wishlist` 
                WHERE `user_id` = $userId AND `product_id` = $productId";
        return db_query($sql);
    }

    /**
     * دریافت وضعیت علاقه‌مندی‌های کاربر برای محصولات
     */
    public function getStatus(): void {
        header('Content-Type: application/json; charset=utf-8');

        if (empty($_SESSION['user_id'])) {
            echo json_encode(['wishlist' => []], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $userId = (int)$_SESSION['user_id'];
        $result = db_fetch_all("SELECT product_id FROM `wishlist` WHERE `user_id` = $userId");
        
        $wishlist = [];
        foreach ($result as $row) {
            $wishlist[] = (int)$row['product_id'];
        }

        echo json_encode(['wishlist' => $wishlist], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
