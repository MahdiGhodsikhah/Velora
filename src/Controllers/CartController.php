<?php
/**
 * کنترلر سبد خرید
 */
class CartController {

    /**
     * نمایش سبد خرید
     */
    public function index(): void {
        Security::set_security_headers();

        // دریافت آیتم‌های سبد خرید
        $cartItems = $this->getCartItems();
        
        // محاسبه مجموع
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $price = $item['sale_price'] ?: $item['price'];
            $subtotal += $price * $item['quantity'];
        }
        
        $shipping = $subtotal > 500000 ? 0 : 30000; // ارسال رایگان برای خرید بالای 500 هزار
        $tax = $subtotal * 0.09; // 9% مالیات
        $total = $subtotal + $shipping + $tax;

        $pageTitle = 'سبد خرید';
        $pageDesc  = 'مشاهده و مدیریت سبد خرید';

        require BASE_PATH . '/src/Views/layouts/minimal-header.php';
        require BASE_PATH . '/src/Views/layouts/navbar.php';
        require BASE_PATH . '/src/Views/pages/cart.php';
        require BASE_PATH . '/src/Views/layouts/footer.php';
    }

    /**
     * افزودن به سبد خرید
     */
    public function add(): void {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity  = (int)($_POST['quantity'] ?? 1);

        if ($productId <= 0 || $quantity <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            exit;
        }

        // بررسی موجودی محصول
        $productModel = new ProductModel();
        $product = $productModel->getById($productId);

        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'محصول یافت نشد']);
            exit;
        }

        if ((int)$product['stock_qty'] < $quantity) {
            echo json_encode(['success' => false, 'message' => 'موجودی کافی نیست']);
            exit;
        }

        // افزودن به سبد
        $this->addToCart($productId, $quantity);

        // شمارش تعداد کل آیتم‌ها
        $cartCount = $this->getCartCount();

        echo json_encode([
            'success' => true,
            'message' => 'محصول به سبد خرید اضافه شد',
            'cart_count' => $cartCount
        ]);
        exit;
    }

    /**
     * حذف از سبد خرید
     */
    public function remove(): void {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false]);
            exit;
        }

        $productId = (int)($_POST['product_id'] ?? 0);

        if ($productId <= 0) {
            echo json_encode(['success' => false]);
            exit;
        }

        $this->removeFromCart($productId);

        echo json_encode([
            'success' => true,
            'cart_count' => $this->getCartCount()
        ]);
        exit;
    }

    /**
     * به‌روزرسانی تعداد
     */
    public function update(): void {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false]);
            exit;
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity  = (int)($_POST['quantity'] ?? 1);

        if ($productId <= 0 || $quantity < 0) {
            echo json_encode(['success' => false]);
            exit;
        }

        if ($quantity === 0) {
            $this->removeFromCart($productId);
        } else {
            $this->updateCartItem($productId, $quantity);
        }

        // محاسبه مجدد
        $cartItems = $this->getCartItems();
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $price = $item['sale_price'] ?: $item['price'];
            $subtotal += $price * $item['quantity'];
        }

        echo json_encode([
            'success' => true,
            'cart_count' => $this->getCartCount(),
            'subtotal' => $subtotal
        ]);
        exit;
    }

    // ===== متدهای کمکی =====

    private function getCartItems(): array {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $cart = $_SESSION['cart'];
        if (empty($cart)) {
            return [];
        }

        $productModel = new ProductModel();
        $items = [];

        foreach ($cart as $productId => $quantity) {
            $product = $productModel->getById((int)$productId);
            if ($product) {
                $product['quantity'] = $quantity;
                $product['gallery_arr'] = json_decode($product['gallery'] ?? '[]', true) ?: [];
                $items[] = $product;
            }
        }

        return $items;
    }

    private function addToCart(int $productId, int $quantity): void {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
    }

    private function removeFromCart(int $productId): void {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
    }

    private function updateCartItem(int $productId, int $quantity): void {
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = $quantity;
        }
    }

    private function getCartCount(): int {
        if (!isset($_SESSION['cart'])) {
            return 0;
        }
        return array_sum($_SESSION['cart']);
    }
}
