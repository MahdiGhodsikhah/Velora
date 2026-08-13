<?php
/**
 * مدل نظرات (Reviews)
 */
class ReviewModel {

    /**
     * تعداد کل نظرات
     */
    public function getTotalCount(): int {
        $result = db_fetch_one("SELECT COUNT(*) as total FROM `reviews`");
        return (int)($result['total'] ?? 0);
    }
    
    /**
     * تعداد نظرات در انتظار تأیید
     */
    public function getPendingCount(): int {
        $result = db_fetch_one("SELECT COUNT(*) as total FROM `reviews` WHERE `is_approved` = 0");
        return (int)($result['total'] ?? 0);
    }
    
    /**
     * لیست تمام نظرات با صفحه‌بندی
     */
    public function getAllForAdmin(int $page = 1, int $perPage = 20, string $filter = 'all'): array {
        $offset = ($page - 1) * $perPage;
        
        $whereClause = '';
        if ($filter === 'pending') {
            $whereClause = 'WHERE r.is_approved = 0';
        } elseif ($filter === 'approved') {
            $whereClause = 'WHERE r.is_approved = 1';
        }
        
        return db_fetch_all(
            "SELECT r.*, 
                    p.name AS product_name, 
                    p.slug AS product_slug,
                    u.username,
                    u.full_name
             FROM `reviews` r
             LEFT JOIN `products` p ON r.product_id = p.id
             LEFT JOIN `users` u ON r.user_id = u.id
             $whereClause
             ORDER BY r.created_at DESC
             LIMIT $perPage OFFSET $offset"
        );
    }
    
    /**
     * تعداد کل نظرات با فیلتر
     */
    public function getTotalCountWithFilter(string $filter = 'all'): int {
        $whereClause = '';
        if ($filter === 'pending') {
            $whereClause = 'WHERE is_approved = 0';
        } elseif ($filter === 'approved') {
            $whereClause = 'WHERE is_approved = 1';
        }
        
        $result = db_fetch_one("SELECT COUNT(*) as total FROM `reviews` $whereClause");
        return (int)($result['total'] ?? 0);
    }
    
    /**
     * دریافت یک نظر با ID
     */
    public function getById(int $id): ?array {
        $id = (int)$id;
        return db_fetch_one(
            "SELECT r.*, 
                    p.name AS product_name, 
                    p.slug AS product_slug,
                    u.username,
                    u.full_name
             FROM `reviews` r
             LEFT JOIN `products` p ON r.product_id = p.id
             LEFT JOIN `users` u ON r.user_id = u.id
             WHERE r.id = $id
             LIMIT 1"
        );
    }
    
    /**
     * تأیید نظر
     */
    public function approve(int $id): bool {
        $id = (int)$id;
        return db_query("UPDATE `reviews` SET `is_approved` = 1 WHERE `id` = $id");
    }
    
    /**
     * رد تأیید نظر
     */
    public function unapprove(int $id): bool {
        $id = (int)$id;
        return db_query("UPDATE `reviews` SET `is_approved` = 0 WHERE `id` = $id");
    }
    
    /**
     * حذف نظر
     */
    public function delete(int $id): bool {
        $id = (int)$id;
        return db_query("DELETE FROM `reviews` WHERE `id` = $id");
    }
    
    /**
     * ویرایش نظر
     */
    public function update(int $id, array $data): bool {
        $id = (int)$id;
        $title = db_escape($data['title'] ?? '');
        $body = db_escape($data['body'] ?? '');
        $rating = (int)($data['rating'] ?? 5);
        $isApproved = isset($data['is_approved']) ? 1 : 0;
        
        // محدود کردن rating بین 1 تا 5
        $rating = max(1, min(5, $rating));
        
        $sql = "UPDATE `reviews` SET
                `title` = '$title',
                `body` = '$body',
                `rating` = $rating,
                `is_approved` = $isApproved
                WHERE `id` = $id";
        
        return db_query($sql);
    }
}
