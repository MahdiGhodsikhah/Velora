/**
 * اسکریپت صفحه محصول منفرد
 * پیاده‌سازی Vanilla JavaScript بدون وابستگی به jQuery
 */
'use strict';

console.log('🎯 product-single.js loaded');

// صبر برای لود شدن DOM
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Product single page initialized');
    
    // =================================================================
    // ۱. مدیریت گالری تصاویر
    // =================================================================
    const thumbButtons = document.querySelectorAll('.thumb-btn');
    const mainImg = document.getElementById('mainGalleryImg');
    
    thumbButtons.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const newSrc = this.dataset.img;
            
            if (mainImg && newSrc) {
                // افکت fade
                mainImg.style.opacity = '0';
                setTimeout(function() {
                    mainImg.src = newSrc;
                    mainImg.style.opacity = '1';
                }, 200);
                
                // تغییر کلاس active
                thumbButtons.forEach(function(b) { b.classList.remove('active'); });
                btn.classList.add('active');
            }
        });
    });
    
    // =================================================================
    // ۲. مدیریت تعداد محصول (Quantity)
    // =================================================================
    const qtyInput = document.querySelector('.qty-input');
    const qtyPlus = document.querySelector('.qty-plus');
    const qtyMinus = document.querySelector('.qty-minus');
    
    if (qtyPlus && qtyInput) {
        qtyPlus.addEventListener('click', function() {
            const max = parseInt(qtyInput.getAttribute('max')) || 99;
            let val = parseInt(qtyInput.value) || 1;
            
            console.log('➕ Plus clicked - current:', val, 'max:', max);
            
            if (val < max) {
                val++;
                qtyInput.value = val;
                console.log('New value:', val);
            }
        });
    }
    
    if (qtyMinus && qtyInput) {
        qtyMinus.addEventListener('click', function() {
            let val = parseInt(qtyInput.value) || 1;
            
            console.log('➖ Minus clicked - current:', val);
            
            if (val > 1) {
                val--;
                qtyInput.value = val;
                console.log('New value:', val);
            }
        });
    }
    
    // منع تغییر مستقیم با کیبورد (به جز Arrow keys)
    if (qtyInput) {
        qtyInput.addEventListener('keydown', function(e) {
            // فقط اجازه Arrow Up, Arrow Down, Tab
            if (e.key !== 'ArrowUp' && e.key !== 'ArrowDown' && e.key !== 'Tab') {
                e.preventDefault();
            }
        });
        
        // اجرای عملکرد plus/minus با Arrow keys
        qtyInput.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowUp') {
                e.preventDefault();
                qtyPlus && qtyPlus.click();
            } else if (e.key === 'ArrowDown') {
                e.preventDefault();
                qtyMinus && qtyMinus.click();
            }
        });
    }
    
    // =================================================================
    // ۳. افزودن به سبد خرید
    // =================================================================
    const addToCartBtn = document.querySelector('.btn-add-single');
    
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            // چک کردن disabled بودن دکمه
            if (this.classList.contains('disabled') || this.disabled) {
                console.log('❌ Button is disabled');
                return;
            }
            
            const productId = parseInt(this.dataset.productId);
            const qty = parseInt(qtyInput ? qtyInput.value : 1);
            
            if (!productId) {
                console.error('❌ No product ID found');
                return;
            }
            
            const originalHtml = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال افزودن...';
            this.disabled = true;
            
            console.log('🛒 Adding to cart:', { productId, qty });
            console.log('Sending to:', window.BASE_URL + '/cart/add');
            
            // ارسال درخواست AJAX با Fetch API
            fetch((window.BASE_URL || '') + '/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_id=' + productId + '&quantity=' + qty
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                console.log('✅ Cart response:', data);
                
                if (data && data.success) {
                    addToCartBtn.innerHTML = '<i class="fas fa-check"></i> افزوده شد';
                    
                    // به‌روزرسانی badge سبد خرید
                    if (data.cart_count !== undefined) {
                        updateCartBadge(data.cart_count);
                    }
                    
                    // نمایش نوتیفیکیشن
                    showNotification(data.message || 'محصول به سبد خرید اضافه شد', 'success');
                    
                    // بازگشت به حالت اولیه بعد از 2 ثانیه
                    setTimeout(function() {
                        addToCartBtn.innerHTML = originalHtml;
                        addToCartBtn.disabled = false;
                    }, 2000);
                } else {
                    addToCartBtn.innerHTML = 'خطا در افزودن';
                    addToCartBtn.disabled = false;
                    
                    showNotification(data.message || 'خطا در افزودن به سبد', 'error');
                    
                    setTimeout(function() {
                        addToCartBtn.innerHTML = originalHtml;
                    }, 2000);
                }
            })
            .catch(function(error) {
                console.error('❌ AJAX Error:', error);
                
                addToCartBtn.innerHTML = originalHtml;
                addToCartBtn.disabled = false;
                
                showNotification('خطا در ارتباط با سرور', 'error');
            });
        });
    }
    
    // =================================================================
    // ۴. افزودن به علاقه‌مندی (Wishlist)
    // =================================================================
    
    // توجه: event handler اصلی برای wishlist در main.js هست
    // اینجا فقط رفتار خاص صفحه محصول را اضافه می‌کنیم
    
    console.log('✅ Wishlist handling setup for product page');
    
    // =================================================================
    // ۵. شمارنده کاراکتر برای فرم نظر
    // =================================================================
    const reviewBody = document.getElementById('review-body');
    const charCount = document.getElementById('charCount');
    
    if (reviewBody && charCount) {
        reviewBody.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
    }
    
    // =================================================================
    // ۶. ستاره‌های امتیاز در فرم نظر
    // =================================================================
    const ratingLabels = document.querySelectorAll('.star-rating-input label');
    const ratingText = document.getElementById('ratingText');
    const ratingInputs = document.querySelectorAll('.star-rating-input input');
    
    ratingLabels.forEach(function(label) {
        label.addEventListener('mouseenter', function() {
            const rating = this.previousElementSibling.value;
            if (ratingText) {
                ratingText.textContent = rating + ' ستاره';
            }
        });
    });
    
    const starRatingInput = document.querySelector('.star-rating-input');
    if (starRatingInput && ratingText) {
        starRatingInput.addEventListener('mouseleave', function() {
            const checkedInput = document.querySelector('.star-rating-input input:checked');
            if (checkedInput) {
                ratingText.textContent = checkedInput.value + ' ستاره انتخاب شده';
            } else {
                ratingText.textContent = 'انتخاب کنید';
            }
        });
    }
    
    ratingInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            if (ratingText) {
                ratingText.textContent = this.value + ' ستاره انتخاب شده';
            }
        });
    });
    
    // =================================================================
    // ۷. توابع کمکی
    // =================================================================
    
    // استفاده از تابع showNotification که در main.js تعریف شده
    // اگر main.js لود نشده، تابع خودمون رو تعریف می‌کنیم
    function showNotification(message, type) {
        // اگر window.showNotification وجود داره، از اون استفاده می‌کنیم
        if (typeof window.showNotification === 'function') {
            window.showNotification(message, type);
            return;
        }
        
        // در غیر این صورت، Vanilla JS implementation
        type = type || 'info';
        
        // حذف نوتیفیکیشن‌های قبلی
        const oldNotifications = document.querySelectorAll('.notification');
        oldNotifications.forEach(function(notif) {
            notif.remove();
        });
        
        const notification = document.createElement('div');
        notification.className = 'notification notification-' + type;
        
        const icon = type === 'success' ? 'check-circle' : (type === 'error' ? 'exclamation-circle' : 'info-circle');
        
        const iconEl = document.createElement('i');
        iconEl.className = 'fas fa-' + icon;
        
        const textEl = document.createElement('span');
        textEl.textContent = message;
        
        notification.appendChild(iconEl);
        notification.appendChild(textEl);
        
        document.body.appendChild(notification);
        
        // نمایش با انیمیشن
        setTimeout(function() {
            notification.classList.add('show');
        }, 50);
        
        // حذف بعد از 3 ثانیه
        setTimeout(function() {
            notification.classList.remove('show');
            setTimeout(function() {
                notification.remove();
            }, 300);
        }, 3000);
    }
    
    // به‌روزرسانی badge سبد خرید
    function updateCartBadge(count) {
        let badge = document.querySelector('.badge-count');
        
        if (badge) {
            badge.textContent = count;
        } else {
            // ساخت badge جدید
            const cartBtn = document.querySelector('.cart-btn');
            if (cartBtn) {
                badge = document.createElement('span');
                badge.className = 'badge-count';
                badge.textContent = count;
                cartBtn.appendChild(badge);
            }
        }
    }
    
    // =================================================================
    // ۸. ایجاد برگ‌های متحرک پاییزی
    // =================================================================
    const body = document.body;
    const colors = ['#FFD700', '#FF8C00', '#FFA500'];
    
    for (let i = 0; i < 4; i++) {
        const leaf = document.createElement('div');
        leaf.className = 'leaf';
        
        const left = 15 + Math.random() * 70;
        const size = 18 + Math.random() * 12;
        const duration = 15 + Math.random() * 10;
        const delay = -(Math.random() * duration);
        const color = colors[Math.floor(Math.random() * colors.length)];
        
        leaf.style.cssText = [
            'left:' + left + '%',
            'width:' + size + 'px',
            'height:' + size + 'px',
            'opacity:0.35',
            'background:' + color,
            'animation-duration:' + duration + 's',
            'animation-delay:' + delay + 's'
        ].join(';');
        
        body.appendChild(leaf);
    }
    
    // =================================================================
    // ۹. Initialize کردن کاروسل محصولات مشابه (Slick)
    // =================================================================
    
    // چک کردن وجود jQuery و Slick برای کاروسل
    function initSimilarProductsCarousel() {
        // چک کردن jQuery
        if (typeof jQuery === 'undefined') {
            console.log('⏳ jQuery not loaded yet for carousel, retrying...');
            return false;
        }
        
        // چک کردن Slick
        if (typeof jQuery.fn.slick === 'undefined') {
            console.log('⏳ Slick not loaded yet, retrying...');
            return false;
        }
        
        const $ = jQuery;
        const $carousel = $('.products-carousel-section .main-product-slider');
        
        if ($carousel.length && !$carousel.hasClass('slick-initialized')) {
            try {
                $carousel.slick({
                    infinite:      true,
                    rtl:           true,
                    dots:          true,
                    arrows:        true,
                    prevArrow:    '<button type="button" class="slick-prev custom-arrow" aria-label="محصول قبلی"><i class="fas fa-chevron-right"></i></button>',
                    nextArrow:    '<button type="button" class="slick-next custom-arrow" aria-label="محصول بعدی"><i class="fas fa-chevron-left"></i></button>',
                    speed:         500,
                    slidesToShow:  3,
                    slidesToScroll:1,
                    autoplay:      true,
                    autoplaySpeed: 3500,
                    draggable:     true,
                    swipe:         true,
                    touchThreshold: 10,
                    accessibility: true,
                    pauseOnHover:  true,
                    responsive: [
                        { breakpoint: 992, settings: { slidesToShow: 2, arrows: true } },
                        { breakpoint: 576, settings: { slidesToShow: 1, arrows: false } }
                    ]
                });
                
                console.log('✅ کاروسل محصولات مشابه با موفقیت initialize شد!');
                return true;
            } catch (error) {
                console.error('❌ خطا در initialize کردن کاروسل:', error);
                return false;
            }
        }
        
        return true;
    }
    
    // تلاش برای initialize کردن کاروسل با چند مرحله
    let carouselInitAttempts = 0;
    const maxCarouselAttempts = 10;
    
    function tryInitCarousel() {
        if (carouselInitAttempts >= maxCarouselAttempts) {
            console.log('❌ Unable to initialize carousel after ' + maxCarouselAttempts + ' attempts');
            return;
        }
        
        carouselInitAttempts++;
        
        if (!initSimilarProductsCarousel()) {
            setTimeout(tryInitCarousel, 300);
        }
    }
    
    // شروع تلاش برای initialize کردن کاروسل
    setTimeout(tryInitCarousel, 100);
    
    console.log('✅ Product single page fully initialized!');
});
