<?php
/**
 * مدل سفارشات
 */
class OrderModel {

    /**
     * ایجاد سفارش جدید
     */
    public function createOrder(array $orderData): ?int {
        $userId = (int)$orderData['user_id'];
        $orderNumber = db_escape($orderData['order_number']);
        $status = db_escape($orderData['status'] ?? 'pending');
        $totalAmount = (int)$orderData['total_amount'];
        $discountAmt = (int)($orderData['discount_amt'] ?? 0);
        $shippingCost = (int)($orderData['shipping_cost'] ?? 0);
        $shippingAddress = db_escape($orderData['shipping_address'] ?? '');
        $postalCode = db_escape($orderData['postal_code'] ?? '');
        $paymentMethod = db_escape($orderData['payment_method'] ?? 'online');
        $paymentStatus = db_escape($orderData['payment_status'] ?? 'unpaid');
        $notes = db_escape($orderData['notes'] ?? '');
        
        $sql = "INSERT INTO orders 
                (user_id, order_number, status, total_amount, discount_amt, shipping_cost, 
                 shipping_address, postal_code, payment_method, payment_status, notes) 
                VALUES 
                ($userId, '$orderNumber', '$status', $totalAmount, $discountAmt, $shippingCost, 
                 '$shippingAddress', '$postalCode', '$paymentMethod', '$paymentStatus', '$notes')";
        
        return db_insert($sql);
    }

    /**
     * افزودن آیتم‌های سفارش
     */
    public function addOrderItems(int $orderId, array $items): bool {
        foreach ($items as $item) {
            $productId = (int)$item['product_id'];
            $productName = db_escape($item['product_name']);
            $unitPrice = (int)$item['unit_price'];
            $quantity = (int)$item['quantity'];
            $subtotal = (int)$item['subtotal'];
            
            $sql = "INSERT INTO order_items 
                    (order_id, product_id, product_name, unit_price, quantity, subtotal) 
                    VALUES 
                    ($orderId, $productId, '$productName', $unitPrice, $quantity, $subtotal)";
            
            if (!db_query($sql)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * دریافت سفارشات کاربر
     */
    public function getUserOrders(int $userId, int $limit = 50): array {
        $userId = (int)$userId;
        $limit = (int)$limit;
        
        $sql = "SELECT o.*, 
                       COUNT(oi.id) as items_count,
                       SUM(oi.quantity) as total_items
                FROM orders o
                LEFT JOIN order_items oi ON o.id = oi.order_id
                WHERE o.user_id = $userId
                GROUP BY o.id
                ORDER BY o.created_at DESC
                LIMIT $limit";
        
        return db_fetch_all($sql);
    }

    /**
     * دریافت جزئیات سفارش
     */
    public function getOrderById(int $orderId, ?int $userId = null): ?array {
        $orderId = (int)$orderId;
        
        $sql = "SELECT * FROM orders WHERE id = $orderId";
        if ($userId !== null) {
            $userId = (int)$userId;
            $sql .= " AND user_id = $userId";
        }
        $sql .= " LIMIT 1";
        
        return db_fetch_one($sql);
    }

    /**
     * دریافت آیتم‌های سفارش
     */
    public function getOrderItems(int $orderId): array {
        $orderId = (int)$orderId;
        
        $sql = "SELECT oi.*, p.main_image, p.slug
                FROM order_items oi
                LEFT JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = $orderId";
        
        return db_fetch_all($sql);
    }

    /**
     * به‌روزرسانی وضعیت سفارش
     */
    public function updateOrderStatus(int $orderId, string $status): bool {
        $orderId = (int)$orderId;
        $status = db_escape($status);
        
        $sql = "UPDATE orders SET status = '$status' WHERE id = $orderId";
        return db_query($sql) !== false;
    }

    /**
     * به‌روزرسانی وضعیت پرداخت
     */
    public function updatePaymentStatus(int $orderId, string $paymentStatus): bool {
        $orderId = (int)$orderId;
        $paymentStatus = db_escape($paymentStatus);
        
        $sql = "UPDATE orders SET payment_status = '$paymentStatus' WHERE id = $orderId";
        return db_query($sql) !== false;
    }

    /**
     * تولید شماره سفارش یکتا
     */
    public function generateOrderNumber(): string {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(4)));
    }

    /**
     * کاهش موجودی محصولات
     */
    public function decreaseProductStock(int $productId, int $quantity): bool {
        $productId = (int)$productId;
        $quantity = (int)$quantity;
        
        $sql = "UPDATE products 
                SET stock_qty = stock_qty - $quantity 
                WHERE id = $productId AND stock_qty >= $quantity";
        
        return db_query($sql) !== false;
    }
}
