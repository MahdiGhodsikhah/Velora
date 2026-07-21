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
    // Dropdown منوها (دسته‌بندی و کاربر)
    // =================================================================
    
    console.log('🔧 Setting up dropdown handlers...');
    console.log('Found dropdowns:', $('.has-dropdown').length);
    console.log('Found dropdown buttons:', $('.has-dropdown > button').length);
    console.log('Found dropdown links:', $('.has-dropdown > a').length);
    
    // Toggle dropdown با کلیک
    $('.has-dropdown > a, .has-dropdown > button').on('click', function(e) {
        console.log('🖱️ Dropdown clicked!', $(this).text());
        e.preventDefault();
        e.stopPropagation();
        
        const $parent = $(this).parent('.has-dropdown');
        const isOpen = $parent.hasClass('open');
        
        console.log('Current state:', isOpen ? 'open' : 'closed');
        console.log('Parent element:', $parent[0]);
        
        // بستن همه dropdownها
        $('.has-dropdown').removeClass('open');
        $('.has-dropdown > a, .has-dropdown > button').attr('aria-expanded', 'false');
        
        // باز کردن این dropdown
        if (!isOpen) {
            $parent.addClass('open');
            $(this).attr('aria-expanded', 'true');
            console.log('✅ Dropdown opened! Classes:', $parent.attr('class'));
            
            // بررسی dropdown menu
            const $menu = $parent.find('.dropdown-menu');
            console.log('Dropdown menu found:', $menu.length);
            console.log('Dropdown menu styles:', {
                display: $menu.css('display'),
                opacity: $menu.css('opacity'),
                visibility: $menu.css('visibility'),
                position: $menu.css('position')
            });
        } else {
            console.log('❌ Dropdown closed!');
        }
    });
    
    // بستن dropdown با کلیک خارج
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.has-dropdown').length) {
            $('.has-dropdown').removeClass('open');
            $('.has-dropdown > a, .has-dropdown > button').attr('aria-expanded', 'false');
        }
    });
    
    // جلوگیری از بسته شدن با کلیک داخل dropdown
    $('.dropdown-menu').on('click', function(e) {
        e.stopPropagation();
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
        $dots.on('click', function () {
            goToSlide($(this).data('slide'));
            startSlideTimer();
        });

        $('.hero-next').on('click', function () {
            goToSlide(currentSlide + 1);
            startSlideTimer();
        });

        $('.hero-prev').on('click', function () {
            goToSlide(currentSlide - 1);
            startSlideTimer();
        });

        // keyboard navigation
        $(document).on('keydown', function (e) {
            if ($('.hero-banner-slider').length) {
                if (e.key === 'ArrowLeft')  { goToSlide(currentSlide + 1); startSlideTimer(); }
                if (e.key === 'ArrowRight') { goToSlide(currentSlide - 1); startSlideTimer(); }
            }
        });

        startSlideTimer();

        // پوز روی hover
        $('.hero-banner-slider').on('mouseenter', function () {
            clearInterval(slideTimer);
        }).on('mouseleave', function () {
            startSlideTimer();
        });
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
                    responsive: [
                        { breakpoint: 992, settings: { slidesToShow: 2, arrows: true } },
                        { breakpoint: 576, settings: { slidesToShow: 1, arrows: false } }
                    ]
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
    
    $(document).on('click', '.btn-add', function () {
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
            return;
        }

        const originalText = $btn.text();
        $btn.text('در حال افزودن...').prop('disabled', true);

        const csrfToken = $('meta[name="csrf-token"]').attr('content') || '';
        console.log('CSRF Token:', csrfToken);
        console.log('Sending to:', window.BASE_URL + '/cart/add');
        
        $.ajax({
            url:    (window.BASE_URL || '') + '/cart/add',
            method: 'POST',
            data:   { product_id: productId, quantity: 1, csrf_token: csrfToken },
            dataType: 'json',
            success: function (res) {
                console.log('✅ Cart response:', res);
                
                if (res && res.success) {
                    $btn.text('✓ افزوده شد');
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
                        $btn.text(originalText).prop('disabled', false);
                    }, 2000);
                } else {
                    console.log('❌ Success but no data');
                    $btn.text('خطا در افزودن').prop('disabled', false);
                    setTimeout(function () { $btn.text(originalText); }, 2000);
                }
            },
            error: function (xhr, status, error) {
                console.error('❌ AJAX Error:', status, error);
                console.error('Response:', xhr.responseText);
                $btn.text(originalText).prop('disabled', false);
            }
        });
    });

    // =================================================================
    // ۸. شمارنده آمار هدر (Count Up)
    // =================================================================
    function animateCountUp() {
        $('.stat-num').each(function () {
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
                    observer.disconnect();
                }
            });
        }, { threshold: 0.3 });

        const $stats = $('.hero-stats');
        if ($stats.length) observer.observe($stats[0]);
    }

    // =================================================================
    // ۹. نوتیفیکیشن‌ها و آلرت‌ها
    // =================================================================
    
    // تابع نمایش نوتیفیکیشن
    window.showNotification = function(message, type) {
        type = type || 'info';
        
        const $notification = $('<div>')
            .addClass('notification notification-' + type)
            .html('<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + '"></i> ' + message)
            .appendTo('body');
        
        setTimeout(function() {
            $notification.addClass('show');
        }, 100);
        
        setTimeout(function() {
            $notification.removeClass('show');
            setTimeout(function() {
                $notification.remove();
            }, 300);
        }, 3000);
    };
    
    $(document).on('click', '.alert-close', function () {
        $(this).closest('.alert').fadeOut(300, function () {
            $(this).remove();
        });
    });

    // بستن خودکار آلرت موفقیت
    setTimeout(function () {
        $('.alert-success').fadeOut(400, function () { $(this).remove(); });
    }, 5000);

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

        document.querySelectorAll('.feature-item, .category-card, .product-card, .feature-card, .blog-card').forEach(function (el) {
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
