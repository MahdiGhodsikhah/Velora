<?php
/**
 * صفحه تکمیل خرید
 */
$base = defined('BASE_URL') ? BASE_URL : '';
?>

<link rel="stylesheet" href="<?= $base ?>/assets/css/checkout.css">

<main id="main-content" class="checkout-page">
    <div class="checkout-container">
        
        <!-- هدر صفحه -->
        <div class="page-header">
            <h1>
                <i class="fas fa-credit-card"></i>
                تکمیل خرید
            </h1>
            <p>لطفا اطلاعات خود را بررسی و تکمیل کنید</p>
        </div>

        <div id="checkout-message"></div>

        <div class="checkout-layout">
            
            <!-- فرم تکمیل خرید -->
            <div class="checkout-form">
                <form id="checkout-form">
                    
                    <!-- اطلاعات ارسال -->
                    <div class="form-section">
                        <h3><i class="fas fa-map-marker-alt"></i> اطلاعات ارسال</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>
                                    آدرس کامل 
                                    <span class="required">*</span>
                                </label>
                                <textarea 
                                    name="address" 
                                    id="address" 
                                    required
                                    placeholder="آدرس کامل خود را وارد کنید..."
                                ><?= Security::e($user['address'] ?? '') ?></textarea>
                                <small>شامل: استان، شهر، خیابان، کوچه، پلاک و واحد</small>
                            </div>

                            <div class="form-group">
                                <label>
                                    کد پستی 
                                    <span class="required">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="postal_code" 
                                    id="postal_code" 
                                    required
                                    pattern="\d{10}"
                                    maxlength="10"
                                    value="<?= Security::e($user['postal_code'] ?? '') ?>"
                                    placeholder="کد پستی ۱۰ رقمی"
                                >
                                <small>کد پستی باید ۱۰ رقم و بدون خط تیره باشد</small>
                            </div>
                        </div>
                    </div>

                    <!-- روش پرداخت -->
                    <div class="form-section">
                        <h3><i class="fas fa-credit-card"></i> روش پرداخت</h3>
                        
                        <div class="payment-methods">
                            <label class="payment-method-item">
                                <input type="radio" name="payment_method" value="online" checked>
                                <div class="payment-method-content">
                                    <i class="fas fa-credit-card"></i>
                                    <div class="payment-method-text">
                                        <h4>پرداخت آنلاین</h4>
                                        <p>پرداخت امن با کارت بانکی</p>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="payment-method-item">
                                <input type="radio" name="payment_method" value="cash">
                                <div class="payment-method-content">
                                    <i class="fas fa-money-bill"></i>
                                    <div class="payment-method-text">
                                        <h4>پرداخت در محل</h4>
                                        <p>پرداخت نقدی هنگام تحویل</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- یادداشت سفارش -->
                    <div class="form-section">
                        <h3><i class="fas fa-sticky-note"></i> یادداشت سفارش (اختیاری)</h3>
                        
                        <div class="form-group">
                            <textarea 
                                name="notes" 
                                id="notes"
                                placeholder="توضیحات تکمیلی درباره سفارش..."
                            ></textarea>
                        </div>
                    </div>

                </form>
            </div>

            <!-- خلاصه سفارش -->
            <div class="order-summary-box">
                <h3>خلاصه سفارش</h3>
                
                <div class="order-items">
                    <?php foreach ($cartItems as $item): ?>
                    <div class="order-item">
                        <div class="order-item-image">
                            <img src="<?= $base ?><?= Security::e($item['main_image']) ?>" 
                                 alt="<?= Security::e($item['name']) ?>">
                        </div>
                        <div class="order-item-details">
                            <div class="order-item-name"><?= Security::e($item['name']) ?></div>
                            <div class="order-item-price">
                                <?= number_format($item['sale_price'] ?: $item['price']) ?> تومان × <?= (int)$item['quantity'] ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="order-summary-row">
                    <span>جمع جزء:</span>
                    <span><?= number_format((int)$subtotal) ?> تومان</span>
                </div>

                <div class="order-summary-row">
                    <span>هزینه ارسال:</span>
                    <span>
                        <?php if ($shipping > 0): ?>
                        <?= number_format((int)$shipping) ?> تومان
                        <?php else: ?>
                        <span style="color: #27ae60;">رایگان</span>
                        <?php endif; ?>
                    </span>
                </div>

                <div class="order-summary-row">
                    <span>مالیات (۹٪):</span>
                    <span><?= number_format((int)$tax) ?> تومان</span>
                </div>

                <div class="order-summary-row total">
                    <span>مجموع نهایی:</span>
                    <span><?= number_format((int)$total) ?> تومان</span>
                </div>

                <button type="button" id="btn-submit-order" class="btn-submit-order">
                    <i class="fas fa-check-circle"></i>
                    ثبت نهایی سفارش
                </button>
            </div>

        </div>

    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkout-form');
    const submitBtn = document.getElementById('btn-submit-order');
    const messageDiv = document.getElementById('checkout-message');

    // استایل برای انتخاب روش پرداخت
    document.querySelectorAll('.payment-method-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.payment-method-item').forEach(i => i.classList.remove('selected'));
            this.classList.add('selected');
        });
    });

    // ثبت سفارش
    submitBtn.addEventListener('click', function() {
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال ثبت...';

        fetch('<?= $base ?>/checkout/process', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageDiv.innerHTML = '<div class="alert alert-success">' + 
                    '<i class="fas fa-check-circle"></i> ' + data.message + 
                    '</div>';
                
                setTimeout(() => {
                    window.location.href = data.redirect || '<?= $base ?>/orders';
                }, 1500);
            } else {
                messageDiv.innerHTML = '<div class="alert alert-danger">' + 
                    '<i class="fas fa-exclamation-circle"></i> ' + data.message + 
                    '</div>';
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> ثبت نهایی سفارش';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messageDiv.innerHTML = '<div class="alert alert-danger">' + 
                '<i class="fas fa-exclamation-circle"></i> خطا در ارتباط با سرور' + 
                '</div>';
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> ثبت نهایی سفارش';
        });
    });

    // اعتبارسنجی کد پستی
    const postalCodeInput = document.getElementById('postal_code');
    postalCodeInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
    });
});
</script>
