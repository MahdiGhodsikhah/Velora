<?php $base = defined('BASE_URL') ? BASE_URL : ''; ?>

<!-- صفحه درباره ما -->
<div class="page-hero-about">
    <div class="hero-about-bg"></div>
    <div class="hero-about-leaves">
        <div class="hero-leaf hl-1"></div>
        <div class="hero-leaf hl-2"></div>
        <div class="hero-leaf hl-3"></div>
        <div class="hero-leaf hl-4"></div>
    </div>
    <div class="page-hero-inner">
        <div class="hero-badge">
            <i class="fas fa-leaf"></i>
            <span>داستان ما</span>
        </div>
        <h1>درباره فروشگاه Velora</h1>
        <p class="hero-subtitle">جایی که سبک زندگی با کیفیت آغاز می‌شود</p>
        <nav class="breadcrumb-nav" aria-label="مسیر صفحه">
            <ul class="breadcrumb">
                <li><a href="<?= $base ?>/">خانه</a></li>
                <li>درباره ما</li>
            </ul>
        </nav>
    </div>
</div>

<main class="about-page">
    <div class="section-container">
        <!-- بخش معرفی با تصویر -->
        <section class="about-intro">
            <div class="about-content-wrapper">
                <div class="about-text-side">
                    <div class="section-label">
                        <i class="fas fa-quote-right"></i>
                        <span>داستان ما</span>
                    </div>
                    <h2 class="about-title">
                        فروشگاه پاییزی 
                        <span class="title-gradient">Velora Shop</span>
                    </h2>
                    <p class="about-lead">
                        ما در فروشگاه Velora با افتخار ارائه‌دهنده بهترین محصولات پوشاک، کفش و اکسسوری با طرح‌های منحصر به فرد پاییزی هستیم. 
                        هدف ما ایجاد تجربه‌ای فراموش‌نشدنی در خرید آنلاین برای شماست.
                    </p>
                    <div class="about-highlights">
                        <div class="highlight-item">
                            <i class="fas fa-check-circle"></i>
                            <span>محصولات با کیفیت و اصل</span>
                        </div>
                        <div class="highlight-item">
                            <i class="fas fa-check-circle"></i>
                            <span>ضمانت بازگشت وجه</span>
                        </div>
                        <div class="highlight-item">
                            <i class="fas fa-check-circle"></i>
                            <span>ارسال رایگان به سراسر کشور</span>
                        </div>
                    </div>
                    <p class="about-description">
                        از سال ۱۴۰۰ فعالیت خود را آغاز کرده‌ایم و توانسته‌ایم اعتماد بیش از ۱۲,۰۰۰ مشتری را جلب کنیم. 
                        تیم ما با دقت و علاقه، محصولاتی با کیفیت بالا و قیمت مناسب برای شما انتخاب می‌کند.
                    </p>
                </div>
                <div class="about-image-side">
                    <div class="about-image-wrapper">
                        <div class="image-decoration"></div>
                        <div class="floating-badge badge-1">
                            <i class="fas fa-award"></i>
                            <span>کیفیت برتر</span>
                        </div>
                        <div class="floating-badge badge-2">
                            <i class="fas fa-users"></i>
                            <span>+۱۲ هزار مشتری</span>
                        </div>
                        <div class="about-main-visual">
                            <i class="fas fa-shopping-bag visual-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- بخش آمار به سبک مدرن -->
        <section class="about-stats-modern">
            <div class="stats-modern-grid">
                <div class="stat-modern-card">
                    <div class="stat-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number" data-target="500">0</div>
                        <div class="stat-label">محصول متنوع</div>
                    </div>
                </div>
                <div class="stat-modern-card">
                    <div class="stat-icon">
                        <i class="fas fa-smile"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number" data-target="12000">0</div>
                        <div class="stat-label">مشتری راضی</div>
                    </div>
                </div>
                <div class="stat-modern-card">
                    <div class="stat-icon">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number" data-target="98">0</div>
                        <div class="stat-label">رضایت مشتری</div>
                    </div>
                </div>
                <div class="stat-modern-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number" data-target="4">0</div>
                        <div class="stat-label">سال تجربه</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- بخش ویژگی‌ها -->
        <section class="about-features-modern">
            <div class="section-header-center">
                <div class="section-label">
                    <i class="fas fa-star"></i>
                    <span>مزایای خرید از Velora</span>
                </div>
                <h2 class="section-title-big">
                    چرا 
                    <span class="title-gradient">Velora</span>
                    را انتخاب کنید؟
                </h2>
                <p class="section-subtitle">ما بهترین تجربه خرید آنلاین را برای شما فراهم می‌کنیم</p>
            </div>
            
            <div class="features-modern-grid">
                <div class="feature-modern-card">
                    <div class="feature-visual">
                        <div class="feature-icon-wrapper">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                    </div>
                    <div class="feature-content">
                        <h3>کیفیت تضمین شده</h3>
                        <p>تمام محصولات ما از برندهای معتبر و با ضمانت کیفیت اصالت ارائه می‌شوند</p>
                    </div>
                </div>
                
                <div class="feature-modern-card">
                    <div class="feature-visual">
                        <div class="feature-icon-wrapper">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                    </div>
                    <div class="feature-content">
                        <h3>ارسال سریع و رایگان</h3>
                        <p>ارسال رایگان به سراسر کشور و تحویل سریع محصولات در کمترین زمان ممکن</p>
                    </div>
                </div>
                
                <div class="feature-modern-card">
                    <div class="feature-visual">
                        <div class="feature-icon-wrapper">
                            <i class="fas fa-undo-alt"></i>
                        </div>
                    </div>
                    <div class="feature-content">
                        <h3>ضمانت بازگشت</h3>
                        <p>۷ روز ضمانت بازگشت کالا و بازپرداخت وجه بدون هیچ شرط و شروطی</p>
                    </div>
                </div>
                
                <div class="feature-modern-card">
                    <div class="feature-visual">
                        <div class="feature-icon-wrapper">
                            <i class="fas fa-headphones-alt"></i>
                        </div>
                    </div>
                    <div class="feature-content">
                        <h3>پشتیبانی ۲۴/۷</h3>
                        <p>پشتیبانی همه‌روزه و پاسخگویی سریع به سوالات و نیازهای شما</p>
                    </div>
                </div>
                
                <div class="feature-modern-card">
                    <div class="feature-visual">
                        <div class="feature-icon-wrapper">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                    <div class="feature-content">
                        <h3>پرداخت امن</h3>
                        <p>سیستم پرداخت کاملاً امن و محافظت شده از اطلاعات شخصی شما</p>
                    </div>
                </div>
                
                <div class="feature-modern-card">
                    <div class="feature-visual">
                        <div class="feature-icon-wrapper">
                            <i class="fas fa-tags"></i>
                        </div>
                    </div>
                    <div class="feature-content">
                        <h3>قیمت‌های رقابتی</h3>
                        <p>بهترین قیمت‌ها در بازار و تخفیف‌های ویژه برای مشتریان وفادار</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- بخش تماس با طراحی جذاب -->
        <section class="about-contact-modern">
            <div class="contact-modern-wrapper">
                <div class="contact-header">
                    <div class="section-label">
                        <i class="fas fa-comments"></i>
                        <span>در ارتباط باشیم</span>
                    </div>
                    <h2 class="section-title-big">
                        راه‌های 
                        <span class="title-gradient">ارتباطی</span>
                    </h2>
                    <p class="section-subtitle">ما همیشه برای پاسخگویی به سوالات شما آماده هستیم</p>
                </div>
                
                <div class="contact-modern-grid">
                    <div class="contact-modern-card">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h3>آدرس دفتر</h3>
                            <p>تهران، خیابان ولیعصر، پلاک ۱۲۳</p>
                        </div>
                    </div>
                    
                    <div class="contact-modern-card">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h3>تلفن تماس</h3>
                            <p>۰۲۱-۱۲۳۴۵۶۷۸</p>
                        </div>
                    </div>
                    
                    <div class="contact-modern-card">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h3>ایمیل</h3>
                            <p>info@velora.shop</p>
                        </div>
                    </div>
                    
                    <div class="contact-modern-card">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-details">
                            <h3>ساعت کاری</h3>
                            <p>شنبه تا پنجشنبه، ۹ صبح تا ۶ عصر</p>
                        </div>
                    </div>
                </div>
                
                <div class="contact-cta">
                    <a href="<?= $base ?>/products" class="btn-cta-modern">
                        <span>مشاهده محصولات</span>
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </section>
    </div>
</main>
