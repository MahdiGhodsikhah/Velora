<?php $base = defined('BASE_URL') ? BASE_URL : ''; ?>

<!-- صفحه درباره ما -->
<div class="page-hero-small">
    <div class="page-hero-inner">
        <h1>درباره ما</h1>
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
        <!-- بخش معرفی -->
        <section class="about-intro">
            <div class="about-content">
                <div class="about-text">
                    <h2>فروشگاه پاییزی Velora</h2>
                    <p class="lead">
                        ما در فروشگاه Velora با افتخار ارائه‌دهنده بهترین محصولات پوشاک، کفش و اکسسوری با طرح‌های منحصر به فرد پاییزی هستیم. 
                        هدف ما ایجاد تجربه‌ای فراموش‌نشدنی در خرید آنلاین برای شماست.
                    </p>
                    <p>
                        از سال ۱۴۰۰ فعالیت خود را آغاز کرده‌ایم و توانسته‌ایم اعتماد بیش از ۱۲,۰۰۰ مشتری را جلب کنیم. 
                        تیم ما با دقت و علاقه، محصولاتی با کیفیت بالا و قیمت مناسب برای شما انتخاب می‌کند.
                    </p>
                </div>
                <div class="about-image">
                    <div class="image-wrapper">
                        <i class="fas fa-leaf about-icon"></i>
                    </div>
                </div>
            </div>
        </section>

        <!-- بخش ویژگی‌ها -->
        <section class="about-features">
            <h2 class="section-title">
                <span class="title-icon"><i class="fas fa-star"></i></span>
                چرا Velora؟
            </h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>کیفیت تضمین شده</h3>
                    <p>تمام محصولات ما از برندهای معتبر و با ضمانت کیفیت ارائه می‌شوند</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3>ارسال سریع</h3>
                    <p>ارسال رایگان به سراسر کشور و تحویل سریع محصولات</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-undo"></i>
                    </div>
                    <h3>ضمانت بازگشت</h3>
                    <p>۷ روز ضمانت بازگشت کالا و بازپرداخت وجه</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>پشتیبانی ۲۴/۷</h3>
                    <p>پشتیبانی همه‌روزه و پاسخگویی سریع به سوالات شما</p>
                </div>
            </div>
        </section>

        <!-- بخش آمار -->
        <section class="about-stats">
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">محصول</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">12,000+</div>
                    <div class="stat-label">مشتری راضی</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">رضایت مشتری</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">4+</div>
                    <div class="stat-label">سال تجربه</div>
                </div>
            </div>
        </section>

        <!-- بخش تماس -->
        <section class="about-contact">
            <h2 class="section-title">
                <span class="title-icon"><i class="fas fa-phone-alt"></i></span>
                ارتباط با ما
            </h2>
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <strong>آدرس</strong>
                        <p>تهران، خیابان ولیعصر، پلاک ۱۲۳</p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <strong>تلفن</strong>
                        <p>۰۲۱-۱۲۳۴۵۶۷۸</p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <strong>ایمیل</strong>
                        <p>info@velora.shop</p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <strong>ساعت کاری</strong>
                        <p>شنبه تا پنجشنبه، ۹ صبح تا ۶ عصر</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
