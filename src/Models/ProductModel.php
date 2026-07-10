<?php
/**
 * مدل محصولات
 */
class ProductModel {

    /**
     * همه محصولات فعال
     */
    public function getAll(int $limit = 12, int $offset = 0): array {
        $limit  = (int)$limit;
        $offset = (int)$offset;
        return db_fetch_all(
            "SELECT p.*, c.`name` AS category_name, c.`slug` AS category_slug
             FROM `products` p
             JOIN `categories` c ON p.`category_id` = c.`id`
             WHERE p.`is_active` = 1
             ORDER BY p.`created_at` DESC
             LIMIT $limit OFFSET $offset"
        );
    }

    /**
     * محصولات ویژه (featured) برای کاروسل صفحه اصلی
     */
    public function getFeatured(int $limit = 8): array {
        $limit = (int)$limit;
        return db_fetch_all(
            "SELECT p.*, c.`name` AS category_name
             FROM `products` p
             JOIN `categories` c ON p.`category_id` = c.`id`
             WHERE p.`is_active` = 1 AND p.`is_featured` = 1
             ORDER BY p.`rating_avg` DESC
             LIMIT $limit"
        );
    }

    /**
     * یک محصول با slug
     */
    public function getBySlug(string $slug): ?array {
        $slug = db_escape($slug);
        return db_fetch_one(
            "SELECT p.*, c.`name` AS category_name, c.`slug` AS category_slug
             FROM `products` p
             JOIN `categories` c ON p.`category_id` = c.`id`
             WHERE p.`slug` = '$slug' AND p.`is_active` = 1
             LIMIT 1"
        );
    }

    /**
     * یک محصول با ID
     */
    public function getById(int $id): ?array {
        $id = (int)$id;
        return db_fetch_one(
            "SELECT p.*, c.`name` AS category_name
             FROM `products` p
             JOIN `categories` c ON p.`category_id` = c.`id`
             WHERE p.`id` = $id AND p.`is_active` = 1
             LIMIT 1"
        );
    }

    /**
     * محصولات بر اساس دسته‌بندی
     */
    public function getByCategory(int $catId, int $limit = 12, int $offset = 0): array {
        $catId  = (int)$catId;
        $limit  = (int)$limit;
        $offset = (int)$offset;
        return db_fetch_all(
            "SELECT p.*, c.`name` AS category_name
             FROM `products` p
             JOIN `categories` c ON p.`category_id` = c.`id`
             WHERE p.`category_id` = $catId AND p.`is_active` = 1
             ORDER BY p.`created_at` DESC
             LIMIT $limit OFFSET $offset"
        );
    }

    /**
     * تصاویر گالری یک محصول
     */
    public function getImages(int $productId): array {
        $id = (int)$productId;
        return db_fetch_all(
            "SELECT * FROM `product_images` WHERE `product_id` = $id ORDER BY `sort_order` ASC"
        );
    }

    /**
     * نظرات تأییدشده یک محصول
     */
    public function getReviews(int $productId): array {
        $id = (int)$productId;
        return db_fetch_all(
            "SELECT * FROM `reviews`
             WHERE `product_id` = $id AND `is_approved` = 1
             ORDER BY `created_at` DESC"
        );
    }

    /**
     * همه دسته‌بندی‌های فعال
     */
    public function getCategories(): array {
        return db_fetch_all(
            "SELECT * FROM `categories` WHERE `is_active` = 1 ORDER BY `sort_order` ASC"
        );
    }

    /**
     * جستجو در محصولات
     */
    public function search(string $query, int $limit = 12): array {
        $q = db_escape($query);
        return db_fetch_all(
            "SELECT p.*, c.`name` AS category_name
             FROM `products` p
             JOIN `categories` c ON p.`category_id` = c.`id`
             WHERE p.`is_active` = 1
               AND (p.`name` LIKE '%$q%' OR p.`description` LIKE '%$q%' OR p.`short_desc` LIKE '%$q%')
             ORDER BY p.`is_featured` DESC, p.`rating_avg` DESC
             LIMIT $limit"
        );
    }

    /**
     * افزایش تعداد بازدید
     */
    public function incrementViews(int $id): void {
        $id = (int)$id;
        db_query("UPDATE `products` SET `views` = `views` + 1 WHERE `id` = $id");
    }

    /**
     * تعداد کل محصولات
     */
    public function count(int $catId = 0): int {
        $where = $catId > 0 ? "AND `category_id` = " . (int)$catId : '';
        $row = db_fetch_one("SELECT COUNT(*) AS cnt FROM `products` WHERE `is_active` = 1 $where");
        return (int)($row['cnt'] ?? 0);
    }

    /**
     * محصولات بر اساس فصل
     */
    public function getBySeason(string $season, int $limit = 12, int $offset = 0): array {
        $season = db_escape($season);
        $limit  = (int)$limit;
        $offset = (int)$offset;
        return db_fetch_all(
            "SELECT p.*, c.`name` AS category_name, c.`slug` AS category_slug
             FROM `products` p
             JOIN `categories` c ON p.`category_id` = c.`id`
             WHERE p.`is_active` = 1 AND (p.`season` = '$season' OR p.`season` = 'all')
             ORDER BY p.`created_at` DESC
             LIMIT $limit OFFSET $offset"
        );
    }

    /**
     * تعداد محصولات بر اساس فصل
     */
    public function countBySeason(string $season): int {
        $season = db_escape($season);
        $row = db_fetch_one(
            "SELECT COUNT(*) AS cnt FROM `products` 
             WHERE `is_active` = 1 AND (`season` = '$season' OR `season` = 'all')"
        );
        return (int)($row['cnt'] ?? 0);
    }

    /**
     * بنرهای فعال
     */
    public function getBanners(string $position = 'hero'): array {
        $pos = db_escape($position);
        return db_fetch_all(
            "SELECT * FROM `banners` WHERE `is_active` = 1 AND `position` = '$pos' ORDER BY `sort_order` ASC"
        );
    }

    /**
     * افزودن نظر جدید
     */
    public function addReview(int $productId, int $userId, int $rating, string $title, string $body): bool {
        $productId = (int)$productId;
        $userId    = (int)$userId;
        $rating    = max(1, min(5, (int)$rating));
        $title     = db_escape(trim($title));
        $body      = db_escape(trim($body));
        
        // بررسی اینکه آیا کاربر قبلا نظر داده
        $exists = db_fetch_one(
            "SELECT `id` FROM `reviews` 
             WHERE `product_id` = $productId AND `user_id` = $userId 
             LIMIT 1"
        );
        
        if ($exists) {
            return false; // کاربر قبلا نظر داده
        }
        
        $sql = "INSERT INTO `reviews` 
                (`product_id`, `user_id`, `rating`, `title`, `body`, `is_approved`, `created_at`) 
                VALUES 
                ($productId, $userId, $rating, '$title', '$body', 0, NOW())";
        
        $result = db_query($sql);
        
        if ($result) {
            // به‌روزرسانی میانگین امتیاز محصول
            $this->updateProductRating($productId);
        }
        
        return $result;
    }

    /**
     * به‌روزرسانی میانگین امتیاز و تعداد نظرات محصول
     */
    private function updateProductRating(int $productId): void {
        $productId = (int)$productId;
        
        $stats = db_fetch_one(
            "SELECT 
                AVG(`rating`) AS avg_rating,
                COUNT(*) AS review_count
             FROM `reviews`
             WHERE `product_id` = $productId AND `is_approved` = 1"
        );
        
        $avgRating = $stats ? round((float)$stats['avg_rating'], 1) : 0;
        $count     = $stats ? (int)$stats['review_count'] : 0;
        
        db_query(
            "UPDATE `products` 
             SET `rating_avg` = $avgRating, `rating_count` = $count 
             WHERE `id` = $productId"
        );
    }
}
