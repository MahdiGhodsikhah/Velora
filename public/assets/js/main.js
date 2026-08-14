/**
 * اسکریپت اصلی - فروشگاه پاییزی شگفت‌انگیز
 * امنیت: جلوگیری از XSS در دستکاری DOM
 */
'use strict';

console.log('🚀 main.js loaded successfully!');

$(document).ready(function () {

    console.log('✅ jQuery Document Ready!');
    console.log('BASE_URL:', window.BASE_URL);

    // =================================================================
    // ۱. ناوبری - همبرگر و اسکرول
    // =================================================================
    const $navbar    = $('#mainNavbar');
    const $hamburger = $('#hamburgerBtn');
    const $mobileMenu= $('#mobileMenu');

    // نوار ناوبری sticky
    $(window).on('scroll', function () {
        if ($(this).scrollTop() > 50) {
            $navbar.addClass('scrolled');
        } else {
            $navbar.removeClass('scrolled');
        }
    });

    // همبرگر
    $hamburger.on('click', function () {
        const isOpen = $hamburger.hasClass('open');
        $hamburger.toggleClass('open');
        $hamburger.attr('aria-expanded', !isOpen);
        $mobileMenu.toggleClass('open').attr('aria-hidden', isOpen);
    });

    // بستن منو با کلیک خارج
    $(document).on('click', function (e) {
        if ($mobileMenu.hasClass('open') &&
            !$hamburger.is(e.target) && !$hamburger.has(e.target).length &&
            !$mobileMenu.is(e.target) && !$mobileMenu.has(e.target).length) {
            $hamburger.removeClass('open').attr('aria-expanded', 'false');
            $mobileMenu.removeClass('open').attr('aria-hidden', 'true');
        }
    });

    // فرم جستجو
    $('.search-toggle').on('click', function (e) {
        e.stopPropagation();
        const $wrap = $(this).closest('.search-wrap');
        const isOpen = $wrap.hasClass('open');
        $wrap.toggleClass('open');
        $(this).attr('aria-expanded', !isOpen);
        
        if (!isOpen) {
            $wrap.find('.search-input').focus();
            // اضافه کردن overlay در موبایل
            if ($(window).width() <= 768) {
                if (!$('#searchOverlay').length) {
                    $('<div id="searchOverlay" class="search-overlay active"></div>').appendTo('body');
                    $('#searchOverlay').on('click', function() {
                        $wrap.removeClass('open');
                        $('.search-toggle').attr('aria-expanded', 'false');
                        $(this).remove();
                    });
                }
            }
        } else {
            $('#searchOverlay').remove();
        }
    });
    
    // بستن جستجو با کلیک خارج در دسکتاپ
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-wrap').length) {
            $('.search-wrap').removeClass('open');
            $('.search-toggle').attr('aria-expanded', 'false');
            $('#searchOverlay').remove();
        }
    });
    
    // جلوگیری از بسته شدن با کلیک داخل فرم
    $('.search-form').on('click', function(e) {
        e.stopPropagation();
    });

    // =================================================================
    // Dropdown منوها - Hover + Click با رفع مشکل بسته نشدن
    // =================================================================
    
    console.log('🔽 Setting up dropdown handlers...');
    
    let isDesktop = $(window).width() > 1024;
    console.log('isDesktop:', isDesktop);
    
    // تشخیص تغییر سایز صفحه
    $(window).on('resize', function() {
        isDesktop = $(window).width() > 1024;
        if (isDesktop) {
            // در دسکتاپ، همه کلیک‌ها رو حذف کن
            $('.has-dropdown').removeClass('clicked keep-open');
        }
    });
    
    // Hover برای دسکتاپ
    $('.has-dropdown').on('mouseenter', function() {
        if (isDesktop && !$(this).hasClass('clicked')) {
            $(this).addClass('keep-open');
        }
    });
    
    $('.has-dropdown').on('mouseleave', function() {
        if (isDesktop) {
            $(this).removeClass('keep-open');
        }
    });
    
    // Click برای موبایل و تبلت
    $('.has-dropdown > a, .has-dropdown > button').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $parent = $(this).parent('.has-dropdown');
        const wasOpen = $parent.hasClass('clicked');
        
        console.log('🔽 Dropdown clicked, wasOpen:', wasOpen);
        
        // بستن تمام dropdownها
        $('.has-dropdown').removeClass('clicked keep-open');
        $('.has-dropdown > a, .has-dropdown > button').attr('aria-expanded', 'false');
        
        // اگر بسته بود، بازش کن
        if (!wasOpen) {
            $parent.addClass('clicked');
            $(this).attr('aria-expanded', 'true');
            console.log('✅ Opening dropdown');
        } else {
            console.log('❌ Closing dropdown');
        }
    });
    
    // بستن dropdown با کلیک خارج
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.has-dropdown').length) {
            const hadOpen = $('.has-dropdown.clicked').length > 0;
            $('.has-dropdown').removeClass('clicked keep-open');
            $('.has-dropdown > a, .has-dropdown > button').attr('aria-expanded', 'false');
            if (hadOpen) {
                console.log('🔽 Closed all dropdowns (click outside)');
            }
        }
    });
    
    // جلوگیری از بسته شدن با کلیک داخل dropdown
    $('.dropdown-menu').on('click', function(e) {
        e.stopPropagation();
    });
    
    console.log('✅ Dropdown handlers ready!');

    // =================================================================
    // Theme Switcher - Handle page reload for theme change
    // =================================================================
    
    $('.dropdown-theme a').on('click', function(e) {
        e.preventDefault();
        const themeUrl = $(this).attr('href');
        
        // نمایش loading
        const $btn = $('.theme-btn');
        const originalIcon = $btn.find('i').first().attr('class');
        $btn.find('i').first().attr('class', 'fas fa-spinner fa-spin');
        
        // انتقال به URL جدید (باعث reload صفحه می‌شود)
        window.location.href = themeUrl;
    });
    
    // Theme Switcher Mobile
    $('.mobile-menu a[href*="theme="]').on('click', function(e) {
        e.preventDefault();
        const themeUrl = $(this).attr('href');
        
        // نمایش loading
        const $hamburger = $('#hamburgerBtn');
        $hamburger.addClass('loading');
        
        // انتقال به URL جدید
        window.location.href = themeUrl;
    });

    // =================================================================
    // ۲. هدر انیمیشنی - برگ‌های ریزان (با Font Awesome)
    // =================================================================
    const leafIcons = ['fas fa-leaf', 'fas fa-seedling', 'fab fa-pagelines', 'fas fa-spa'];
    const $leavesContainer = $('#fallingLeaves');

    if ($leavesContainer.length) {
        for (let i = 0; i < 16; i++) {
            createFallingLeaf(i);
        }
    }

    function createFallingLeaf(index) {
        const leaf = document.createElement('div');
        leaf.className = 'falling-leaf';
        
        const icon = document.createElement('i');
        icon.className = leafIcons[Math.floor(Math.random() * leafIcons.length)];
        leaf.appendChild(icon);

        const size      = 14 + Math.random() * 18;
        const left      = Math.random() * 100;
        const duration  = 8 + Math.random() * 14;
        const delay     = -Math.random() * 12;
        const drift     = (Math.random() - 0.5) * 120;

        leaf.style.cssText = [
            'font-size:' + size + 'px',
            'left:' + left + '%',
            'animation-duration:' + duration + 's',
            'animation-delay:' + delay + 's',
            '--drift:' + drift + 'px',
            'opacity:' + (0.5 + Math.random() * 0.5)
        ].join(';');

        $leavesContainer[0].appendChild(leaf);
    }

    // =================================================================
    // ۳. ذرات متحرک هدر
    // =================================================================
    const $particlesContainer = $('#heroParticles');
    if ($particlesContainer.length) {
        for (let i = 0; i < 25; i++) {
            const p = document.createElement('div');
            p.className = 'particle';

            const size     = 3 + Math.random() * 5;
            const left     = Math.random() * 100;
            const top      = Math.random() * 100;
            const duration = 15 + Math.random() * 25;
            const delay    = -Math.random() * 20;
            const tx       = (Math.random() - 0.5) * 200;

            p.style.cssText = [
                'width:' + size + 'px',
                'height:' + size + 'px',
                'left:' + left + '%',
                'top:' + top + '%',
                'animation-duration:' + duration + 's',
                'animation-delay:' + delay + 's',
                '--tx:' + tx + 'px'
            ].join(';');

            $particlesContainer[0].appendChild(p);
        }
    }

    // =================================================================
    // ۴. اسلایدر بنر هدر
    // =================================================================
    let currentSlide = 0;
    let slideTimer   = null;
    const $slides    = $('.hero-slide');
    const $dots      = $('.hero-dot');
    const $arrowPrev = $('.hero-arrow-prev');
    const $arrowNext = $('.hero-arrow-next');
    const SLIDE_DELAY = 5000;

    function goToSlide(index) {
        if ($slides.length === 0) return;
        $slides.removeClass('active');
        $dots.removeClass('active').attr('aria-selected', 'false');

        currentSlide = (index + $slides.length) % $slides.length;
        $slides.eq(currentSlide).addClass('active');
        $dots.eq(currentSlide).addClass('active').attr('aria-selected', 'true');
    }

    function startSlideTimer() {
        clearInterval(slideTimer);
        slideTimer = setInterval(function () {
            goToSlide(currentSlide + 1);
        }, SLIDE_DELAY);
    }

    if ($slides.length > 1) {
        // دات‌ها
        $dots.on('click', function () {
            goToSlide($(this).data('slide'));
            startSlideTimer();
        });

        // فلش قبلی
        $arrowPrev.on('click', function () {
            goToSlide(currentSlide - 1);
            startSlideTimer();
        });

        // فلش بعدی
        $arrowNext.on('click', function () {
            goToSlide(currentSlide + 1);
            startSlideTimer();
        });

        // کیبورد ناوبری
        $(document).on('keydown', function (e) {
            if (e.key === 'ArrowRight') {
                goToSlide(currentSlide - 1);
                startSlideTimer();
            } else if (e.key === 'ArrowLeft') {
                goToSlide(currentSlide + 1);
                startSlideTimer();
            }
        });

        // Pause اسلایدر با هاور
        $('.hero-banner-slider').on('mouseenter', function () {
            clearInterval(slideTimer);
        }).on('mouseleave', function () {
            startSlideTimer();
        });

        startSlideTimer();
    }

    // =================================================================
    // ۵. کاروسل محصولات (Slick)
    // =================================================================
    
    // اسلایدر تصاویر داخل کارت محصول
    function initProductImageSliders() {
        $('.product-slider').each(function () {
            const $slider = $(this);
            
            // اگر قبلاً initialize شده، skip کن
            if ($slider.hasClass('slick-initialized')) {
                return;
            }
            
            // حذف lazy loading از تصاویر
            $slider.find('img[loading="lazy"]').attr('loading', 'eager');
            
            // اگر فقط یک تصویر داره، نیازی به اسلایدر نیست
            const imageCount = $slider.find('.img-wrap').length;
            if (imageCount <= 1) {
                $slider.addClass('single-image');
                return;
            }
            
            // initialize کردن slick
            $slider.slick({
                infinite:      true,
                dots:          true,
                arrows:        false,
                speed:         400,
                autoplay:      true,
                autoplaySpeed: 2800,
                rtl:           true,
                slidesToShow:  1,
                slidesToScroll:1,
                fade:          true,
                cssEase:      'cubic-bezier(0.4,0,0.2,1)',
                swipe:         true,
                touchThreshold: 10,
                pauseOnHover:  true,
                pauseOnFocus:  true,
                accessibility: true,
                adaptiveHeight: false
            });
            
            // اطمینان از لود شدن تصاویر
            $slider.find('img').each(function() {
                if (this.complete) {
                    $(this).css('opacity', '1');
                } else {
                    $(this).on('load', function() {
                        $(this).css('opacity', '1');
                    });
                }
            });
        });
    }

    // اجرای اولیه
    initProductImageSliders();
    
    // اجرای مجدد بعد از بارگذاری محصولات جدید (AJAX)
    $(document).on('productsLoaded', function() {
        initProductImageSliders();
    });

    // اسلایدر اصلی محصولات (کاروسل بزرگ)
    if ($('.main-product-slider').length) {
        $('.main-product-slider').each(function () {
            if (!$(this).hasClass('slick-initialized')) {
                // حذف lazy loading از تصاویر کاروسل قبل از initialize
                $(this).find('img[loading="lazy"]').attr('loading', 'eager');
                
                $(this).slick({
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
                    centerMode:    false,
                    edgeFriction:  0.15,
                    responsive: [
                        { breakpoint: 992, settings: { slidesToShow: 2, arrows: true, centerMode: false } },
                        { breakpoint: 576, settings: { slidesToShow: 1, arrows: false, centerMode: false } }
                    ]
                });
                
                // اطمینان از لود شدن تصاویر بعد از initialize
                $(this).find('img').each(function() {
                    if (this.complete) {
                        $(this).css('opacity', '1');
                    } else {
                        $(this).on('load', function() {
                            $(this).css('opacity', '1');
                        });
                    }
                });
            }
        });
    }

    // =================================================================
    // ۶. علاقه‌مندی (Wishlist)
    // =================================================================
    console.log('❤️ Setting up wishlist handlers...');
    
    // بارگذاری وضعیت wishlist برای کاربران لاگین شده
    function loadWishlistStatus() {
        $.ajax({
            url: (window.BASE_URL || '') + '/wishlist/status',
            method: 'GET',
            dataType: 'json',
            success: function (res) {
                if (res && res.wishlist && Array.isArray(res.wishlist)) {
                    console.log('✅ Wishlist loaded:', res.wishlist);
                    
                    // علامت‌گذاری محصولات موجود در wishlist
                    res.wishlist.forEach(function(productId) {
                        $('.wishlist-btn[data-id="' + productId + '"]').addClass('active').find('i').removeClass('far').addClass('fas');
                    });
                }
            },
            error: function(xhr) {
                // اگر کاربر لاگین نیست، خطا نمی‌دهیم
                console.log('ℹ️ Wishlist status not loaded (user not logged in or error)');
            }
        });
    }
    
    // بارگذاری وضعیت
    loadWishlistStatus();
    
    $(document).on('click', '.wishlist-btn', function (e) {
        e.preventDefault();
        console.log('❤️ Wishlist button clicked!');
        
        const $btn = $(this);
        const productId = parseInt($btn.data('id'), 10);
        
        console.log('Product ID:', productId);
        
        if (!productId) {
            console.log('❌ No product ID');
            return;
        }

        // تغییر موقت وضعیت
        const wasActive = $btn.hasClass('active');
        $btn.toggleClass('active');
        
        // تغییر آیکون
        const $icon = $btn.find('i');
        if ($icon.length) {
            $icon.toggleClass('far fas');
        }

        console.log('Sending to:', window.BASE_URL + '/wishlist/toggle');
        
        // ارسال به سرور (AJAX)
        $.ajax({
            url: (window.BASE_URL || '') + '/wishlist/toggle',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ product_id: productId }),
            dataType: 'json',
            success: function (res) {
                console.log('✅ Wishlist response:', res);
                
                if (res && res.success) {
                    // نمایش پیام
                    if (res.message) {
                        showNotification(res.message, 'success');
                    }
                    
                    // حذف redirect - فقط پیام نشان بده
                    // اگر کاربر لاگین نیست، فقط پیام نمایش داده می‌شود
                } else {
                    // ریورت در صورت خطا
                    $btn.toggleClass('active');
                    if ($icon.length) {
                        $icon.toggleClass('far fas');
                    }
                    
                    if (res && res.message) {
                        showNotification(res.message, 'error');
                    }
                    
                    // حذف redirect - فقط پیام
                }
            },
            error: function (xhr, status, error) {
                console.error('❌ Wishlist AJAX Error:', status, error);
                console.error('Response:', xhr.responseText);
                
                // ریورت در صورت خطا
                $btn.toggleClass('active');
                if ($icon.length) {
                    $icon.toggleClass('far fas');
                }
                
                showNotification('خطا در ارتباط با سرور', 'error');
            }
        });
    });

    // =================================================================
    // ۷. افزودن به سبد خرید
    // =================================================================
    
    console.log('🛒 Setting up cart handlers...');
    
    $(document).on('click', '.btn-add', function (e) {
        e.preventDefault();
        console.log('🛒 Add to cart clicked!');
        
        const $btn = $(this);
        if ($btn.hasClass('disabled') || $btn.is(':disabled')) {
            console.log('❌ Button is disabled');
            return;
        }

        const productId = parseInt($btn.data('product-id'), 10);
        console.log('Product ID:', productId);
        
        if (!productId) {
            console.log('❌ No product ID');
            showNotification('شناسه محصول یافت نشد', 'error');
            return;
        }

        const originalHtml = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin"></i> در حال افزودن...').prop('disabled', true);

        console.log('Sending to:', window.BASE_URL + '/cart/add');
        
        $.ajax({
            url:    (window.BASE_URL || '') + '/cart/add',
            method: 'POST',
            data:   { product_id: productId, quantity: 1 },
            dataType: 'json',
            success: function (res) {
                console.log('✅ Cart response:', res);
                
                if (res && res.success) {
                    $btn.html('<i class="fas fa-check"></i> افزوده شد');
                    
                    // نمایش notification - همیشه نمایش داده می‌شود
                    showNotification(res.message || 'محصول به سبد خرید اضافه شد', 'success');
                    
                    // به‌روزرسانی badge سبد خرید
                    if (res.cart_count !== undefined) {
                        const $badge = $('.badge-count');
                        if ($badge.length) {
                            $badge.text(res.cart_count);
                        } else {
                            $('.cart-btn').append('<span class="badge-count">' + parseInt(res.cart_count, 10) + '</span>');
                        }
                    }
                    
                    setTimeout(function () {
                        $btn.html(originalHtml).prop('disabled', false);
                    }, 2000);
                } else {
                    console.log('❌ Response not successful:', res);
                    $btn.html('<i class="fas fa-times"></i> خطا').prop('disabled', false);
                    showNotification(res.message || 'خطا در افزودن محصول', 'error');
                    setTimeout(function () { 
                        $btn.html(originalHtml).prop('disabled', false); 
                    }, 2000);
                }
            },
            error: function (xhr, status, error) {
                console.error('❌ AJAX Error:', status, error);
                console.error('Response:', xhr.responseText);
                $btn.html(originalHtml).prop('disabled', false);
                showNotification('خطا در ارتباط با سرور', 'error');
            }
        });
    });

    // =================================================================
    // ۸. شمارنده آمار هدر و صفحه درباره ما (Count Up)
    // =================================================================
    function animateCountUp() {
        $('.stat-num, .stat-number').each(function () {
            const $this  = $(this);
            const target = parseInt($this.data('target'), 10);
            if (!target || $this.data('animated')) return;

            $this.data('animated', true);
            const duration = 2000;
            const step     = target / (duration / 16);
            let current    = 0;

            const timer = setInterval(function () {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                $this.text(Math.floor(current).toLocaleString('fa-IR'));
            }, 16);
        });
    }

    // تریگر با Intersection Observer
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    animateCountUp();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });

        const $stats = $('.hero-stats, .stats-modern-grid');
        if ($stats.length) {
            $stats.each(function() {
                observer.observe(this);
            });
        }
    }

    // =================================================================
    // ۹. نوتیفیکیشن‌ها
    // =================================================================
    
    // تابع نمایش نوتیفیکیشن - دقیقاً مثل product-single.js (Vanilla JS)
    window.showNotification = function(message, type) {
        console.log('📢 Showing notification:', message, type);
        
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
    };
    
    // تست notification در console
    console.log('✅ showNotification function is ready!');
    console.log('Test it with: showNotification("تست پیام", "success")');
    
    // تبدیل alert‌های موجود به notification - فقط alert‌های خارج از modal
    setTimeout(function() {
        $('.alert').not('.modal .alert, .modal .alert-info').each(function() {
            const $alert = $(this);
            const message = $alert.find('span').text().trim() || $alert.text().replace('×', '').trim();
            
            if (!message) return;
            
            let type = 'info';
            if ($alert.hasClass('alert-success')) {
                type = 'success';
            } else if ($alert.hasClass('alert-error')) {
                type = 'error';
            }
            
            // پنهان کردن alert
            $alert.hide();
            
            // نمایش به عنوان notification
            showNotification(message, type);
        });
    }, 300);
    
    $(document).on('click', '.alert-close', function () {
        $(this).closest('.alert').fadeOut(300, function () {
            $(this).remove();
        });
    });

    // =================================================================
    // ۱۰. لازی‌لود تصاویر
    // =================================================================
    if ('loading' in HTMLImageElement.prototype) {
        $('img[loading="lazy"]').each(function () {
            if (this.dataset.src) {
                this.src = this.dataset.src;
            }
        });
    }

    // =================================================================
    // ۱۱. انیمیشن اسکرول (Scroll Reveal)
    // =================================================================
    if ('IntersectionObserver' in window) {
        const revealObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        document.querySelectorAll('.feature-item, .category-card, .product-card, .feature-card').forEach(function (el) {
            el.classList.add('will-reveal');
            revealObserver.observe(el);
        });

        // انیمیشن ورود برای فوتر
        const footerObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('footer-visible');
                    footerObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        const footer = document.querySelector('.site-footer');
        if (footer) {
            footerObserver.observe(footer);
        }
    }

}); // end ready
