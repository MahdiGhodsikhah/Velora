/**
 * اسکریپت صفحه سبد خرید
 */

// اطمینان از load شدن jQuery
if (typeof jQuery === 'undefined') {
    console.error('❌ jQuery is not loaded for cart!');
} else {
    console.log('✅ jQuery is loaded for cart');
    
    jQuery(document).ready(function($) {
        console.log('🛒 Cart page scripts loaded');
        console.log('Cart items count:', $('.cart-item').length);
        console.log('BASE_URL:', window.BASE_URL);
        
        // افزایش تعداد
        $(document).on('click', '.qty-plus', function() {
            const productId = $(this).data('id');
            const $input = $(this).siblings('.qty-input');
            const max = parseInt($input.attr('max'));
            let val = parseInt($input.val()) || 1;
            
            console.log('➕ Plus clicked:', { productId, val, max });
            
            if (val < max) {
                val++;
                $input.val(val);
                updateCart(productId, val);
            }
        });

        // کاهش تعداد
        $(document).on('click', '.qty-minus', function() {
            const productId = $(this).data('id');
            const $input = $(this).siblings('.qty-input');
            let val = parseInt($input.val()) || 1;
            
            console.log('➖ Minus clicked:', { productId, val });
            
            if (val > 1) {
                val--;
                $input.val(val);
                updateCart(productId, val);
            }
        });

        // حذف محصول
        $(document).on('click', '.btn-remove-item', function() {
            const productId = $(this).data('id');
            const $item = $(this).closest('.cart-item');
            
            console.log('🗑️ Remove clicked:', productId);
            
            if (confirm('آیا مطمئن هستید که می‌خواهید این محصول را حذف کنید؟')) {
                console.log('Sending remove request to:', window.BASE_URL + '/cart/remove');
                
                $.ajax({
                    url: (window.BASE_URL || '') + '/cart/remove',
                    method: 'POST',
                    data: { product_id: productId },
                    dataType: 'json',
                    success: function(res) {
                        console.log('✅ Remove response:', res);
                        if (res && res.success) {
                            $item.fadeOut(400, function() {
                                $(this).remove();
                                if ($('.cart-item').length === 0) {
                                    location.reload();
                                } else {
                                    recalculateTotals();
                                }
                            });
                            updateCartBadge(res.cart_count);
                            
                            if (typeof showNotification === 'function') {
                                showNotification('محصول از سبد خرید حذف شد', 'success');
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('❌ Remove error:', status, error);
                        console.error('Response:', xhr.responseText);
                        if (typeof showNotification === 'function') {
                            showNotification('خطا در حذف محصول', 'error');
                        } else {
                            alert('خطا در حذف محصول');
                        }
                    }
                });
            }
        });

        // به‌روزرسانی سبد
        function updateCart(productId, quantity) {
            console.log('🔄 Updating cart:', { productId, quantity });
            console.log('Sending to:', window.BASE_URL + '/cart/update');
            
            $.ajax({
                url: (window.BASE_URL || '') + '/cart/update',
                method: 'POST',
                data: { product_id: productId, quantity: quantity },
                dataType: 'json',
                success: function(res) {
                    console.log('✅ Update response:', res);
                    if (res && res.success) {
                        recalculateTotals();
                        updateCartBadge(res.cart_count);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('❌ Update error:', status, error);
                    console.error('Response:', xhr.responseText);
                    if (typeof showNotification === 'function') {
                        showNotification('خطا در به‌روزرسانی سبد', 'error');
                    } else {
                        alert('خطا در به‌روزرسانی');
                    }
                }
            });
        }

        // محاسبه مجدد مجموع
        function recalculateTotals() {
            let subtotal = 0;
            
            $('.cart-item').each(function() {
                const $item = $(this);
                const price = parseFloat($item.find('.total-price').data('price'));
                const quantity = parseInt($item.find('.qty-input').val());
                const total = price * quantity;
                
                $item.find('.total-price').text(total.toLocaleString('fa-IR'));
                subtotal += total;
            });

            const shipping = subtotal > 500000 ? 0 : 30000;
            const tax = subtotal * 0.09;
            const total = subtotal + shipping + tax;

            $('.subtotal-amount').text(subtotal.toLocaleString('fa-IR') + ' تومان');
            $('.tax-amount').text(tax.toLocaleString('fa-IR') + ' تومان');
            $('.total-amount').text(total.toLocaleString('fa-IR') + ' تومان');
            
            if (shipping > 0) {
                $('.shipping-amount').html(shipping.toLocaleString('fa-IR') + ' تومان');
            } else {
                $('.shipping-amount').html('<span class="free-shipping">رایگان</span>');
            }

            // پیام ارسال رایگان
            if (subtotal < 500000) {
                const remaining = 500000 - subtotal;
                $('.free-shipping-notice').show().html(
                    '<i class="fas fa-info-circle"></i> با خرید ' + 
                    remaining.toLocaleString('fa-IR') + 
                    ' تومان دیگر، ارسال رایگان!'
                );
            } else {
                $('.free-shipping-notice').hide();
            }
        }

        // به‌روزرسانی badge
        function updateCartBadge(count) {
            if (count > 0) {
                if ($('.badge-count').length) {
                    $('.badge-count').text(count);
                } else {
                    $('.cart-btn').append('<span class="badge-count">' + count + '</span>');
                }
            } else {
                $('.badge-count').remove();
            }
        }

        // اعمال کد تخفیف
        $('.coupon-form').on('submit', function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const $input = $form.find('.coupon-input');
            const $button = $form.find('.btn-apply-coupon');
            const couponCode = $input.val().trim();
            
            if (!couponCode) {
                if (typeof showNotification === 'function') {
                    showNotification('لطفا کد تخفیف را وارد کنید', 'error');
                } else {
                    alert('لطفا کد تخفیف را وارد کنید');
                }
                return;
            }
            
            // غیرفعال کردن دکمه
            $button.prop('disabled', true).text('در حال بررسی...');
            
            $.ajax({
                url: (window.BASE_URL || '') + '/cart/apply-coupon',
                method: 'POST',
                data: { coupon_code: couponCode },
                dataType: 'json',
                success: function(res) {
                    console.log('✅ Coupon response:', res);
                    
                    if (res && res.success) {
                        // نمایش پیام موفقیت
                        if (typeof showNotification === 'function') {
                            showNotification(res.message || 'کد تخفیف با موفقیت اعمال شد', 'success');
                        } else {
                            alert(res.message || 'کد تخفیف با موفقیت اعمال شد');
                        }
                        
                        // محاسبه مجدد با تخفیف
                        if (res.discount_amount) {
                            recalculateTotals();
                            // TODO: نمایش مقدار تخفیف در UI
                        }
                        
                        // پاک کردن input
                        $input.val('');
                    } else {
                        // نمایش پیام خطا
                        if (typeof showNotification === 'function') {
                            showNotification(res.message || 'کد تخفیف نامعتبر است', 'error');
                        } else {
                            alert(res.message || 'کد تخفیف نامعتبر است');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('❌ Coupon error:', status, error);
                    console.error('Response:', xhr.responseText);
                    
                    // نمایش پیام خطا
                    if (typeof showNotification === 'function') {
                        showNotification('کد تخفیف نامعتبر است یا منقضی شده', 'error');
                    } else {
                        alert('کد تخفیف نامعتبر است');
                    }
                },
                complete: function() {
                    // فعال کردن دوباره دکمه
                    $button.prop('disabled', false).text('اعمال');
                }
            });
        });
    });
}
