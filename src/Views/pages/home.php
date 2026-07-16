<?php
$base = defined('BASE_URL') ? BASE_URL : '';
$success = $_SESSION['auth_success'] ?? '';
unset($_SESSION['auth_success']);
?>

<!-- هدر انیمیشنی -->
<?php require BASE_PATH . '/src/Views/partials/animated-hero.php'; ?>

<main id="main-content" tabindex="-1">

    <!-- پیام موفقیت به صورت نوتیفیکیشن -->
    <?php if ($success): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof showNotification === 'function') {
                showNotification(<?= json_encode($success) ?>, 'success');
            }
        });
    </script>
    <?php endif; ?>

    <!-- بخش ویژگی‌ها -->
    <section class="features-strip" aria-label="ویژگی‌های فروشگاه">
        <div class="features-inner">
            <div class="feature-item">
                <i class="fas fa-truck" aria-hidden="true"></i>
                <div>
                    <strong>ارسال سریع</strong>
                    <span>ارسال به سراسر ایران</span>
                </div>
            </div>
            <div class="feature-item">
                <i class="fas fa-shield-alt" aria-hidden="true"></i>
                <div>
                    <strong>خرید امن</strong>
                    <span>درگاه پرداخت معتبر</span>
                </div>
            </div>
            <div class="feature-item">
                <i class="fas fa-undo" aria-hidden="true"></i>
                <div>
                    <strong>ضمانت برگشت</strong>
                    <span>۷ روز ضمانت بازگشت</span>
                </div>
            </div>
            <div class="feature-item">
                <i class="fas fa-headset" aria-hidden="true"></i>
                <div>
                    <strong>پشتیبانی ۲۴/۷</strong>
                    <span>پاسخگو هر ساعت</span>
                </div>
            </div>
        </div>
    </section>

    <!-- دسته‌بندی‌ها -->
    <section class="categories-section" aria-labelledby="cats-heading">
        <div class="section-container">
            <h2 class="section-title" id="cats-heading">
                <span class="title-icon"><i class="fas fa-th-large" aria-hidden="true"></i></span>
                دسته‌بندی محصولات
            </h2>
            <div class="categories-grid">
                <?php foreach (($categories ?? []) as $cat): ?>
                <a href="<?= $base ?>/products?cat=<?= (int)$cat['id'] ?>"
                   class="category-card"
                   aria-label="دسته‌بندی <?= Security::e($cat['name']) ?>">
                    <div class="cat-icon">
                        <?php
                        $icons = ['fas fa-tshirt','fas fa-female','fas fa-shoe-prints','fas fa-gem','fas fa-dumbbell'];
                        $catIndex = array_search($cat['id'], array_column($categories ?? [], 'id'));
                        echo '<i class="' . ($icons[(int)$cat['id']-1] ?? 'fas fa-tag') . '" aria-hidden="true"></i>';
                        ?>
                    </div>
                    <span><?= Security::e($cat['name']) ?></span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- کاروسل محصولات ویژه -->
    <section class="section-container">
        <?php
        $sliderTitle = 'محصولات ویژه پاییز';
        $sliderIcon = 'fas fa-fire-alt';
        require BASE_PATH . '/src/Views/partials/slider.php';
        ?>
    </section>

    <!-- کاروسل کلکسیون پاییزی -->
    <section class="section-container">
        <?php
        // فیلتر محصولات پاییزی (در صورت وجود فیلد season در دیتابیس)
        $autumnProducts = array_filter($featuredProducts ?? [], function($p) {
            return !empty($p['season']) && $p['season'] === 'autumn';
        });
        // اگر محصول پاییزی نداریم، همه محصولات را نشان بده
        if (empty($autumnProducts)) {
            $autumnProducts = array_slice($featuredProducts ?? [], 0, 6);
        }
        $products = $autumnProducts;
        $sliderTitle = 'کلکسیون پاییزی';
        $sliderIcon = 'fas fa-leaf';
        require BASE_PATH . '/src/Views/partials/slider.php';
        ?>
    </section>

    <!-- کاروسل جدیدترین محصولات -->
    <section class="section-container">
        <?php
        // محصولات جدید (می‌توانید از تاریخ created_at استفاده کنید)
        $products = array_slice($featuredProducts ?? [], 0, 8);
        $sliderTitle = 'جدیدترین محصولات';
        $sliderIcon = 'fas fa-sparkles';
        require BASE_PATH . '/src/Views/partials/slider.php';
        ?>
    </section>

    <!-- بخش بلاگ - آخرین مطالب -->
    <section class="blog-section" aria-labelledby="blog-heading">
        <div class="section-container">
            <h2 class="section-title" id="blog-heading">
                <span class="title-icon"><i class="fas fa-blog" aria-hidden="true"></i></span>
                آخرین مطالب بلاگ
                <span class="title-leaf" aria-hidden="true"><i class="fas fa-pen-fancy"></i></span>
            </h2>
            <div class="blog-grid">
                <!-- پست بلاگ ۱ -->
                <article class="blog-card">
                    <div class="blog-image">
                        <img src="<?= $base ?>/assets/images/blog/autumn-fashion.jpg" alt="مد پاییزی" loading="lazy" onerror="this.src='<?= $base ?>/assets/images/products/no-image.jpg'">
                        <div class="blog-category">مد و پوشاک</div>
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <span><i class="fas fa-calendar-alt"></i> ۱۵ آبان ۱۴۰۳</span>
                            <span><i class="fas fa-user"></i> ادمین</span>
                        </div>
                        <h3 class="blog-title">
                            <a href="<?= $base ?>/blog/autumn-fashion-trends">جدیدترین ترندهای مد پاییزی ۱۴۰۳</a>
                        </h3>
                        <p class="blog-excerpt">
                            با شروع فصل پاییز، سبک‌های جدید و رنگ‌های گرم وارد دنیای مد شده‌اند. در این مقاله با جدیدترین ترندهای پاییزی آشنا می‌شوید...
                        </p>
                        <a href="<?= $base ?>/blog/autumn-fashion-trends" class="blog-read-more">
                            ادامه مطلب <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </article>

                <!-- پست بلاگ ۲ -->
                <article class="blog-card">
                    <div class="blog-image">
                        <img src="<?= $base ?>/assets/images/blog/shopping-guide.jpg" alt="راهنمای خرید" loading="lazy" onerror="this.src='<?= $base ?>/assets/images/products/no-image.jpg'">
                        <div class="blog-category">راهنمای خرید</div>
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <span><i class="fas fa-calendar-alt"></i> ۱۰ آبان ۱۴۰۳</span>
                            <span><i class="fas fa-user"></i> ادمین</span>
                        </div>
                        <h3 class="blog-title">
                            <a href="<?= $base ?>/blog/shopping-tips">۱۰ نکته برای خرید هوشمندانه پوشاک</a>
                        </h3>
                        <p class="blog-excerpt">
                            خرید پوشاک می‌تواند چالش‌برانگیز باشد. با این ۱۰ نکته طلایی، خریدی هوشمندانه و رضایت‌بخش تجربه کنید...
                        </p>
                        <a href="<?= $base ?>/blog/shopping-tips" class="blog-read-more">
                            ادامه مطلب <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </article>

                <!-- پست بلاگ ۳ -->
                <article class="blog-card">
                    <div class="blog-image">
                        <img src="<?= $base ?>/assets/images/blog/care-tips.jpg" alt="نگهداری لباس" loading="lazy" onerror="this.src='<?= $base ?>/assets/images/products/no-image.jpg'">
                        <div class="blog-category">نگهداری</div>
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <span><i class="fas fa-calendar-alt"></i> ۵ آبان ۱۴۰۳</span>
                            <span><i class="fas fa-user"></i> ادمین</span>
                        </div>
                        <h3 class="blog-title">
                            <a href="<?= $base ?>/blog/care-guide">راهنمای نگهداری صحیح از لباس‌های پاییزی</a>
                        </h3>
                        <p class="blog-excerpt">
                            برای طولانی‌تر شدن عمر لباس‌هایتان، نکاتی را باید رعایت کنید. از شستشو گرفته تا نگهداری، همه چیز را در اینجا بخوانید...
                        </p>
                        <a href="<?= $base ?>/blog/care-guide" class="blog-read-more">
                            ادامه مطلب <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </article>
            </div>
            <div class="blog-view-all">
                <a href="<?= $base ?>/blog" class="btn-mid-banner">
                    مشاهده همه مطالب <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>
    </section>

</main>
