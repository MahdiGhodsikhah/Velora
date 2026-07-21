<?php
/**
 * کنترلر تکمیل خرید
 */
class CheckoutController {

    /**
     * نمایش صفحه تکمیل خرید
     */
    public function index(): void {
        Security::set_security_headers();

        // بررسی ورود کاربر
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_after_login'] = '/checkout';
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // بررسی سبد خرید
        if (empty($_SESSION['cart'])) {
            header('Location: ' . BASE_URL . '/cart');
            exit;
        }

        $cartItems = $this->getCartItems();
        
        if (empty($cartItems)) {
            header('Location: ' . BASE_URL . '/cart');
            exit;
        }

        // محاسبه مجموع
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $price = $item['sale_price'] ?: $item['price'];
            $subtotal += $price * $item['quantity'];
        }
        
        $shipping = $subtotal > 500000 ? 0 : 30000;
        $tax = $subtotal * 0.09;
        $total = $subtotal + $shipping + $tax;

        // دریافت اطلاعات کاربر
        $userModel = new UserModel();
        $user = $userModel->getById((int)$_SESSION['user_id']);

        $pageTitle = 'تکمیل خرید';
        $pageDesc  = 'نهایی کردن سفارش و پرداخت';

        require BASE_PATH . '/src/Views/layouts/minimal-header.php';
        require BASE_PATH . '/src/Views/layouts/navbar.php';
        require BASE_PATH . '/src/Views/pages/checkout.php';
        require BASE_PATH . '/src/Views/layouts/footer.php';
    }

    /**
     * ثبت سفارش
     */
    public function process(): void {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'درخواست نامعتبر']);
            exit;
        }

        // بررسی ورود کاربر
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'لطفا ابتدا وارد شوید']);
            exit;
        }

        // بررسی سبد خرید
        if (empty($_SESSION['cart'])) {
            echo json_encode(['success' => false, 'message' => 'سبد خرید شما خالی است']);
            exit;
        }

        // دریافت داده‌ها
        $address = trim($_POST['address'] ?? '');
        $postalCode = trim($_POST['postal_code'] ?? '');
        $paymentMethod = $_POST['payment_method'] ?? 'online';
        $notes = trim($_POST['notes'] ?? '');

        // اعتبارسنجی
        if (empty($address)) {
            echo json_encode(['success' => false, 'message' => 'لطفا آدرس را وارد کنید']);
            exit;
        }

        if (empty($postalCode)) {
            echo json_encode(['success' => false, 'message' => 'لطفا کد پستی را وارد کنید']);
            exit;
        }

        if (!preg_match('/^\d{10}$/', $postalCode)) {
            echo json_encode(['success' => false, 'message' => 'کد پستی باید ۱۰ رقم باشد']);
            exit;
        }

        try {
            // دریافت آیتم‌های سبد
            $cartItems = $this->getCartItems();
            
            if (empty($cartItems)) {
                echo json_encode(['success' => false, 'message' => 'سبد خرید خالی است']);
                exit;
            }
            
            // محاسبه مجموع
            $subtotal = 0;
            $orderItems = [];

            foreach ($cartItems as $item) {
                $price = $item['sale_price'] ?: $item['price'];
                $quantity = $item['quantity'];
                
                // بررسی موجودی
                if ((int)$item['stock_qty'] < $quantity) {
                    echo json_encode([
                        'success' => false, 
                        'message' => 'موجودی محصول ' . $item['name'] . ' کافی نیست'
                    ]);
                    exit;
                }
                
                $itemSubtotal = $price * $quantity;
                $subtotal += $itemSubtotal;

                $orderItems[] = [
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'unit_price' => $price,
                    'quantity' => $quantity,
                    'subtotal' => $itemSubtotal
                ];
            }

            $shipping = $subtotal > 500000 ? 0 : 30000;
            $tax = $subtotal * 0.09;
            $total = $subtotal + $shipping + $tax;

            // ایجاد سفارش
            $orderModel = new OrderModel();
            $orderNumber = $orderModel->generateOrderNumber();

            $orderData = [
                'user_id' => (int)$_SESSION['user_id'],
                'order_number' => $orderNumber,
                'status' => 'pending',
                'total_amount' => (int)$total,
                'discount_amt' => 0,
                'shipping_cost' => (int)$shipping,
                'shipping_address' => $address,
                'postal_code' => $postalCode,
                'payment_method' => $paymentMethod,
                'payment_status' => 'unpaid',
                'notes' => $notes
            ];

            $orderId = $orderModel->createOrder($orderData);

            if (!$orderId) {
                echo json_encode(['success' => false, 'message' => 'خطا در ایجاد سفارش']);
                exit;
            }

            // افزودن آیتم‌های سفارش
            if (!$orderModel->addOrderItems($orderId, $orderItems)) {
                echo json_encode(['success' => false, 'message' => 'خطا در افزودن آیتم‌های سفارش']);
                exit;
            }

            // کاهش موجودی محصولات
            foreach ($orderItems as $item) {
                $orderModel->decreaseProductStock($item['product_id'], $item['quantity']);
            }

            // به‌روزرسانی آدرس و کد پستی کاربر در پروفایل
            $userModel = new UserModel();
            $userModel->updateUserAddress((int)$_SESSION['user_id'], $address, $postalCode);

            // پاک کردن سبد خرید
            $_SESSION['cart'] = [];

            echo json_encode([
                'success' => true,
                'message' => 'سفارش شما با موفقیت ثبت شد',
                'order_id' => $orderId,
                'order_number' => $orderNumber,
                'redirect' => BASE_URL . '/orders'
            ]);
            exit;

        } catch (Exception $e) {
            error_log("Checkout error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'خطا در ثبت سفارش. لطفا دوباره تلاش کنید']);
            exit;
        }
    }

    /**
     * دریافت آیتم‌های سبد خرید
     */
    private function getCartItems(): array {
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            return [];
        }

        $productModel = new ProductModel();
        $items = [];

        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $product = $productModel->getById((int)$productId);
            if ($product && (int)$product['stock_qty'] >= $quantity) {
                $product['quantity'] = $quantity;
                $items[] = $product;
            }
        }

        return $items;
    }
}
