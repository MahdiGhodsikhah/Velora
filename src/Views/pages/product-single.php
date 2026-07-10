<?php
/**
 * صفحه محصول منفرد - پاییزی شگفت‌انگیز
 * الگو گرفته شده از 1/products page
 */
$base = defined('BASE_URL') ? BASE_URL : '';
$galleryAll = array_merge([$product['main_image']], $product['gallery_arr']);
?>
<!-- لینک CSS اختصاصی صفحه محصول -->
<link rel="stylesheet" href="<?= $base ?>/assets/css/product-single.css">

<!-- برگ‌های متحرک پاییزی - تعداد کمتر برای جلوگیری از حواس‌پرتی -->
<div class="leaf" style="left: 8%; width: 25px; height: 25px; background: #FFD700; animation-duration: 18s; animation-delay: -8s; opacity: 0.4;"></div>
<div class="leaf" style="left: 85%; width: 22px; height: 22px; background: #FF8C00; animation-duration: 16s; animation-delay: -12s; opacity: 0.35;"></div>
<div class="leaf" style="left: 45%; width: 28px; height: 28px; background: #FFA500; animation-duration: 20s; animation-delay: -6s; opacity: 0.4;"></div>
<div class="leaf" style="left: 70%; width: 24px; height: 24px; background: #FFD700; animation-duration: 17s; animation-delay: -10s; opacity: 0.38;"></div>

<main id="main-content" class="product-single-page">

    <!-- پیام‌های سیستم -->
    <?php if (isset($_SESSION['success'])): ?>
    <div class="container pt-3">
        <div class="alert alert-success" role="status">
            <i class="fas fa-check-circle" aria-hidden="true"></i>
            <?= Security::e($_SESSION['success']) ?>
            <button class="alert-close" aria-label="بستن">&times;</button>
        </div>
    </div>
    <?php unset($_SESSION['success']); endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
    <div class="container pt-3">
        <div class="alert alert-error" role="alert">
            <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
            <?= $_SESSION['error'] ?>
            <button class="alert-close" aria-label="بستن">&times;</button>
        </div>
    </div>
    <?php unset($_SESSION['error']); endif; ?>

    <!-- Breadcrumb -->
    <div class="page-hero-small">
        <div class="page-hero-inner">
            <nav aria-label="مسیر صفحه" class="breadcrumb-nav">
                <ol class="breadcrumb">
                    <li><a href="<?= $base ?>/">خانه</a></li>
                    <li><a href="<?= $base ?>/products?cat=<?= (int)($product['category_id'] ?? 0) ?>"><?= Security::e($product['category_name'] ?? '') ?></a></li>
                    <li aria-current="page"><?= Security::e($product['name']) ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- لی‌آوت اصلی محصول - دو ستونه -->
    <div class="product-single-wrap">
        <!-- ستون چپ: گالری تصاویر -->
        <div class="product-gallery">
            <div class="gallery-main">
                <img src="<?= Security::e($galleryAll[0] ?? '/assets/images/products/no-image.jpg') ?>"
                     id="mainGalleryImg"
                     alt="<?= Security::e($product['name']) ?>"
                     class="main-gallery-img">
            </div>
            <?php if (count($galleryAll) > 1): ?>
            <div class="gallery-thumbs">
                <?php foreach ($galleryAll as $i => $img): ?>
                <button class="thumb-btn <?= $i === 0 ? 'active' : '' ?>"
                        data-img="<?= Security::e($img) ?>"
                        aria-label="تصویر <?= $i + 1 ?>"
                        type="button">
                    <img src="<?= Security::e($img) ?>"
                         alt="تصویر <?= $i + 1 ?> از <?= Security::e($product['name']) ?>"
                         loading="lazy">
                </button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- ستون راست: اطلاعات محصول -->
        <div class="product-info">
            <div class="product-cat-badge"><?= Security::e($product['category_name']) ?></div>
            <h1 class="product-name"><?= Security::e($product['name']) ?></h1>

            <!-- ستاره‌ها -->
            <div class="product-rating" aria-label="امتیاز: <?= (float)$product['rating_avg'] ?> از ۵">
                <?php
                $avg = round((float)$product['rating_avg'], 1);
                for ($s = 1; $s <= 5; $s++):
                    if ($avg >= $s): ?>
                    <i class="fas fa-star" style="color: #FFD700;"></i>
                    <?php elseif ($avg >= $s - 0.5): ?>
                    <i class="fas fa-star-half-alt" style="color: #FFD700;"></i>
                    <?php else: ?>
                    <i class="far fa-star" style="color: #FFD700;"></i>
                    <?php endif;
                endfor; ?>
                <span class="rating-text"><?= $avg ?> از ۵ (<?= (int)$product['rating_count'] ?> نظر)</span>
            </div>

            <!-- قیمت -->
            <div class="product-price-box">
                <?php if ($product['sale_price']): ?>
                <span class="original-price"><?= number_format((int)$product['price']) ?> تومان</span>
                <div class="sale-price-wrap">
                    <span class="sale-price"><?= number_format((int)$product['sale_price']) ?> تومان</span>
                    <span class="sale-badge sale-badge-animated"><?= (int)$product['discount_pct'] ?>٪ تخفیف</span>
                </div>
                <?php else: ?>
                <span class="sale-price"><?= number_format((int)$product['price']) ?> تومان</span>
                <?php endif; ?>
            </div>

            <!-- توضیحات کوتاه -->
            <?php if ($product['short_desc']): ?>
            <p class="product-short-desc-full"><?= Security::e($product['short_desc']) ?></p>
            <?php endif; ?>

            <!-- وضعیت موجودی -->
            <div class="stock-info <?= (int)$product['stock_qty'] > 0 ? 'in-stock' : 'out-stock' ?>">
                <i class="fas <?= (int)$product['stock_qty'] > 0 ? 'fa-check-circle' : 'fa-times-circle' ?>" aria-hidden="true"></i>
                <?= (int)$product['stock_qty'] > 0 ? 'موجود در انبار (' . (int)$product['stock_qty'] . ' عدد)' : 'ناموجود' ?>
            </div>

            <!-- اکشن‌ها -->
            <div class="product-actions">
                <div class="quantity-wrap">
                    <button class="qty-btn qty-minus" aria-label="کاهش تعداد">-</button>
                    <input type="number" class="qty-input" value="1" min="1" max="<?= (int)$product['stock_qty'] ?>" aria-label="تعداد">
                    <button class="qty-btn qty-plus" aria-label="افزایش تعداد">+</button>
                </div>
                <button class="btn-add-single <?= (int)$product['stock_qty'] <= 0 ? 'disabled' : '' ?>"
                        data-product-id="<?= (int)$product['id'] ?>"
                        <?= (int)$product['stock_qty'] <= 0 ? 'disabled aria-disabled="true"' : '' ?>>
                    <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                    <?= (int)$product['stock_qty'] > 0 ? 'افزودن به سبد خرید' : 'ناموجود' ?>
                </button>
                <button class="btn-wishlist-single wishlist-btn" data-id="<?= (int)$product['id'] ?>" aria-label="افزودن به علاقه‌مندی">
                    <i class="far fa-heart" aria-hidden="true"></i>
                </button>
            </div>

            <!-- SKU -->
            <?php if ($product['sku']): ?>
            <div class="product-meta">
                <span>کد محصول: <strong><?= Security::e($product['sku']) ?></strong></span>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- بخش ویژگی‌های محصول - گلسی (Glassmorphism) -->
    <section class="features">
        <div class="feature-card">
            <i class="fas fa-star"></i>
            <h3>کیفیت عالی</h3>
            <p>با استفاده از مواد اولیه پاییزی و تکنولوژی پیشرفته</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-leaf"></i>
            <h3>طبیعی و سالم</h3>
            <p>بی‌خطر و مناسب برای تمامی افراد</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-bolt"></i>
            <h3>ارسال سریع</h3>
            <p>ارسال رایگان برای خریدهای بالای ۵۰۰ هزار تومان</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-heart"></i>
            <h3>رضایت کامل</h3>
            <p>ضمانت رضایت 100% از کاربران و بازگشت وجه</p>
        </div>
    </section>

    <!-- توضیحات کامل -->
    <?php if ($product['description']): ?>
    <div class="product-full-desc">
        <h2>توضیحات محصول</h2>
        <div class="desc-content"><?= nl2br(Security::e($product['description'])) ?></div>
    </div>
    <?php endif; ?>

    <!-- محصولات مشابه بر اساس فصل -->
    <?php if (!empty($similarProducts) && count($similarProducts) > 0): ?>
    <section class="products-carousel-section" aria-labelledby="similar-products-heading">
        <div class="section-header">
            <h2 class="section-title" id="similar-products-heading">
                <span class="title-icon"><i class="fas fa-leaf" aria-hidden="true"></i></span>
                محصولات مشابه
                <span class="title-leaf" aria-hidden="true"><i class="fas fa-leaf"></i></span>
            </h2>
            <a href="<?= $base ?>/products?cat=<?= (int)($product['category_id'] ?? 0) ?>" class="see-all-btn">
                مشاهده همه <i class="fas fa-arrow-left" aria-hidden="true"></i>
            </a>
        </div>

        <div class="main-product-slider" role="region" aria-label="محصولات مشابه" aria-roledescription="carousel">
            <?php foreach ($similarProducts as $product): ?>
            <div role="group" aria-roledescription="slide">
                <?php require BASE_PATH . '/src/Views/partials/product-card.php'; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- نظرات -->
    <section class="reviews-section" aria-labelledby="reviews-heading">
        <h2 id="reviews-heading">نظرات کاربران <?php if (!empty($reviews)): ?>(<?= count($reviews) ?>)<?php endif; ?></h2>
        
        <!-- فرم ثبت نظر برای کاربران لاگین شده -->
        <?php if (isset($_SESSION['user_id'])): ?>
        <div class="review-form-wrap">
            <h3>نظر خود را بنویسید</h3>
            <form method="POST" action="<?= $base ?>/reviews/add" class="review-form" id="reviewForm">
                <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div class="form-group">
                    <label for="review-rating">امتیاز شما <span class="required">*</span></label>
                    <div class="star-rating-input" id="starRatingInput">
                        <?php for ($s = 5; $s >= 1; $s--): ?>
                        <input type="radio" name="rating" id="star<?= $s ?>" value="<?= $s ?>" required>
                        <label for="star<?= $s ?>" title="<?= $s ?> ستاره"><i class="fas fa-star"></i></label>
                        <?php endfor; ?>
                    </div>
                    <span class="rating-text" id="ratingText">انتخاب کنید</span>
                </div>

                <div class="form-group">
                    <label for="review-title">عنوان نظر (اختیاری)</label>
                    <input type="text" name="title" id="review-title" class="form-control" maxlength="100" placeholder="خلاصه نظر شما...">
                </div>

                <div class="form-group">
                    <label for="review-body">متن نظر <span class="required">*</span></label>
                    <textarea name="body" id="review-body" class="form-control" rows="5" required maxlength="1000" placeholder="نظر خود را در مورد این محصول بنویسید..."></textarea>
                    <small class="char-counter"><span id="charCount">0</span> / 1000 کاراکتر</small>
                </div>

                <button type="submit" class="btn-submit-review">
                    <i class="fas fa-paper-plane"></i> ارسال نظر
                </button>
            </form>
        </div>
        <?php else: ?>
        <div class="login-prompt">
            <i class="fas fa-info-circle"></i>
            برای ثبت نظر ابتدا <a href="<?= $base ?>/login">وارد شوید</a> یا <a href="<?= $base ?>/register">ثبت‌نام</a> کنید.
        </div>
        <?php endif; ?>

        <!-- لیست نظرات -->
        <?php if (!empty($reviews)): ?>
        <div class="reviews-list">
            <?php foreach ($reviews as $review): ?>
            <article class="review-card">
                <div class="review-header">
                    <span class="reviewer-name"><?= Security::e($review['author_name'] ?? 'کاربر ناشناس') ?></span>
                    <div class="review-rating" aria-label="امتیاز: <?= (int)$review['rating'] ?>">
                        <?php for ($s = 1; $s <= 5; $s++): ?>
                        <i class="<?= $s <= (int)$review['rating'] ? 'fas' : 'far' ?> fa-star" style="color: #FFD700; font-size: 0.9rem;"></i>
                        <?php endfor; ?>
                    </div>
                    <time class="review-date" datetime="<?= Security::e($review['created_at']) ?>">
                        <?= Security::e(substr($review['created_at'], 0, 10)) ?>
                    </time>
                </div>
                <?php if ($review['title']): ?>
                <h3 class="review-title"><?= Security::e($review['title']) ?></h3>
                <?php endif; ?>
                <?php if ($review['body']): ?>
                <p class="review-body"><?= Security::e($review['body']) ?></p>
                <?php endif; ?>
            </article>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="no-reviews">
            <i class="fas fa-comments"></i>
            <p>هنوز نظری ثبت نشده است. اولین نفری باشید که نظر می‌دهید!</p>
        </div>
        <?php endif; ?>
    </section>

</main>

<!-- اسکریپت اختصاصی صفحه محصول (بدون وابستگی به jQuery) -->
<script src="<?= $base ?>/assets/js/product-single.js"></script>
