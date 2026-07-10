<?php
/**
 * اسکریپت ساخت و راه‌اندازی پایگاه داده
 * اجرا: http://localhost/Project/project/config/setup_database.php
 * پس از اجرا این فایل را حذف کنید یا از دسترس خارج کنید
 */

// جلوگیری از دسترسی خارجی
if (!defined('ALLOW_SETUP') && php_sapi_name() !== 'cli') {
    $allowed_ips = ['127.0.0.1', '::1'];
    if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
        http_response_code(403);
        die('دسترسی مجاز نیست');
    }
}

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'autumn_shop');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
if (!$conn) {
    die('خطا در اتصال: ' . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8mb4');

// ساخت پایگاه داده
$sql_create_db = "CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` 
    CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci";
mysqli_query($conn, $sql_create_db) or die('خطا در ساخت دیتابیس: ' . mysqli_error($conn));
mysqli_select_db($conn, DB_NAME);

// -------------------------------------------------------------------
// جدول دسته‌بندی محصولات
// -------------------------------------------------------------------
$sql_categories = "CREATE TABLE IF NOT EXISTS `categories` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(100) NOT NULL COMMENT 'نام دسته‌بندی',
    `slug`        VARCHAR(120) NOT NULL UNIQUE COMMENT 'نامک',
    `description` TEXT DEFAULT NULL,
    `image_url`   VARCHAR(500) DEFAULT NULL,
    `parent_id`   INT UNSIGNED DEFAULT NULL COMMENT 'دسته والد',
    `sort_order`  TINYINT UNSIGNED DEFAULT 0,
    `is_active`   TINYINT(1) NOT NULL DEFAULT 1,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_slug` (`slug`),
    INDEX `idx_parent` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci";
mysqli_query($conn, $sql_categories) or die('خطا در ساخت جدول categories: ' . mysqli_error($conn));

// -------------------------------------------------------------------
// جدول کاربران
// -------------------------------------------------------------------
$sql_users = "CREATE TABLE IF NOT EXISTS `users` (
    `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `username`        VARCHAR(50) NOT NULL UNIQUE,
    `email`           VARCHAR(150) NOT NULL UNIQUE,
    `phone`           VARCHAR(20) DEFAULT NULL,
    `password_hash`   VARCHAR(255) NOT NULL COMMENT 'هش رمز عبور با password_hash()',
    `full_name`       VARCHAR(100) DEFAULT NULL,
    `avatar_url`      VARCHAR(500) DEFAULT NULL,
    `role`            ENUM('customer','admin','moderator') NOT NULL DEFAULT 'customer',
    `is_active`       TINYINT(1) NOT NULL DEFAULT 1,
    `email_verified`  TINYINT(1) NOT NULL DEFAULT 0,
    `login_attempts`  TINYINT UNSIGNED DEFAULT 0,
    `locked_until`    DATETIME DEFAULT NULL,
    `last_login`      DATETIME DEFAULT NULL,
    `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_email` (`email`),
    INDEX `idx_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci";
mysqli_query($conn, $sql_users) or die('خطا در ساخت جدول users: ' . mysqli_error($conn));

// -------------------------------------------------------------------
// جدول محصولات
// -------------------------------------------------------------------
$sql_products = "CREATE TABLE IF NOT EXISTS `products` (
    `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `category_id`     INT UNSIGNED NOT NULL,
    `name`            VARCHAR(200) NOT NULL,
    `slug`            VARCHAR(220) NOT NULL UNIQUE,
    `description`     TEXT DEFAULT NULL,
    `short_desc`      VARCHAR(500) DEFAULT NULL,
    `sku`             VARCHAR(50) DEFAULT NULL UNIQUE COMMENT 'کد محصول',
    `price`           BIGINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'قیمت به تومان',
    `sale_price`      BIGINT UNSIGNED DEFAULT NULL COMMENT 'قیمت با تخفیف',
    `discount_pct`    TINYINT UNSIGNED DEFAULT 0 COMMENT 'درصد تخفیف',
    `stock_qty`       SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `main_image`      VARCHAR(500) NOT NULL DEFAULT '/assets/images/products/no-image.jpg',
    `gallery`         JSON DEFAULT NULL COMMENT 'آرایه JSON آدرس تصاویر',
    `rating_avg`      DECIMAL(3,2) DEFAULT 0.00,
    `rating_count`    INT UNSIGNED DEFAULT 0,
    `is_featured`     TINYINT(1) DEFAULT 0,
    `is_active`       TINYINT(1) NOT NULL DEFAULT 1,
    `views`           INT UNSIGNED DEFAULT 0,
    `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_category` (`category_id`),
    INDEX `idx_featured` (`is_featured`),
    INDEX `idx_active` (`is_active`),
    INDEX `idx_slug` (`slug`),
    CONSTRAINT `fk_product_cat` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci";
mysqli_query($conn, $sql_products) or die('خطا در ساخت جدول products: ' . mysqli_error($conn));

// -------------------------------------------------------------------
// جدول تصاویر گالری محصول
// -------------------------------------------------------------------
$sql_product_images = "CREATE TABLE IF NOT EXISTS `product_images` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_id`  INT UNSIGNED NOT NULL,
    `image_url`   VARCHAR(500) NOT NULL,
    `alt_text`    VARCHAR(200) DEFAULT NULL,
    `sort_order`  TINYINT UNSIGNED DEFAULT 0,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_product` (`product_id`),
    CONSTRAINT `fk_img_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci";
mysqli_query($conn, $sql_product_images) or die('خطا در ساخت جدول product_images: ' . mysqli_error($conn));

// -------------------------------------------------------------------
// جدول نظرات محصولات
// -------------------------------------------------------------------
$sql_reviews = "CREATE TABLE IF NOT EXISTS `reviews` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_id`  INT UNSIGNED NOT NULL,
    `user_id`     INT UNSIGNED DEFAULT NULL,
    `author_name` VARCHAR(80) DEFAULT NULL,
    `rating`      TINYINT UNSIGNED NOT NULL COMMENT '1 تا 5',
    `title`       VARCHAR(200) DEFAULT NULL,
    `body`        TEXT DEFAULT NULL,
    `is_approved` TINYINT(1) DEFAULT 0,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_product_rev` (`product_id`),
    CONSTRAINT `fk_rev_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    CONSTRAINT `chk_rating` CHECK (`rating` BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci";
mysqli_query($conn, $sql_reviews) or die('خطا در ساخت جدول reviews: ' . mysqli_error($conn));

// -------------------------------------------------------------------
// جدول سبد خرید
// -------------------------------------------------------------------
$sql_cart = "CREATE TABLE IF NOT EXISTS `cart` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`    INT UNSIGNED NOT NULL,
    `product_id` INT UNSIGNED NOT NULL,
    `quantity`   SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    `added_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_user_product` (`user_id`, `product_id`),
    CONSTRAINT `fk_cart_user`    FOREIGN KEY (`user_id`)    REFERENCES `users`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci";
mysqli_query($conn, $sql_cart) or die('خطا در ساخت جدول cart: ' . mysqli_error($conn));

// -------------------------------------------------------------------
// جدول علاقه‌مندی‌ها
// -------------------------------------------------------------------
$sql_wishlist = "CREATE TABLE IF NOT EXISTS `wishlist` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`    INT UNSIGNED NOT NULL,
    `product_id` INT UNSIGNED NOT NULL,
    `added_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_wish_user_product` (`user_id`, `product_id`),
    CONSTRAINT `fk_wish_user`    FOREIGN KEY (`user_id`)    REFERENCES `users`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_wish_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci";
mysqli_query($conn, $sql_wishlist) or die('خطا در ساخت جدول wishlist: ' . mysqli_error($conn));

// -------------------------------------------------------------------
// جدول سفارش‌ها
// -------------------------------------------------------------------
$sql_orders = "CREATE TABLE IF NOT EXISTS `orders` (
    `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`        INT UNSIGNED NOT NULL,
    `order_number`   VARCHAR(30) NOT NULL UNIQUE,
    `status`         ENUM('pending','processing','shipped','delivered','cancelled','refunded') DEFAULT 'pending',
    `total_amount`   BIGINT UNSIGNED NOT NULL,
    `discount_amt`   BIGINT UNSIGNED DEFAULT 0,
    `shipping_cost`  BIGINT UNSIGNED DEFAULT 0,
    `payment_method` VARCHAR(50) DEFAULT NULL,
    `payment_status` ENUM('unpaid','paid','refunded') DEFAULT 'unpaid',
    `notes`          TEXT DEFAULT NULL,
    `created_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_user_orders` (`user_id`),
    INDEX `idx_order_num` (`order_number`),
    CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci";
mysqli_query($conn, $sql_orders) or die('خطا در ساخت جدول orders: ' . mysqli_error($conn));

// -------------------------------------------------------------------
// جدول آیتم‌های سفارش
// -------------------------------------------------------------------
$sql_order_items = "CREATE TABLE IF NOT EXISTS `order_items` (
    `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `order_id`     INT UNSIGNED NOT NULL,
    `product_id`   INT UNSIGNED NOT NULL,
    `product_name` VARCHAR(200) NOT NULL,
    `unit_price`   BIGINT UNSIGNED NOT NULL,
    `quantity`     SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    `subtotal`     BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_order_items` (`order_id`),
    CONSTRAINT `fk_item_order`   FOREIGN KEY (`order_id`)   REFERENCES `orders`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_item_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci";
mysqli_query($conn, $sql_order_items) or die('خطا در ساخت جدول order_items: ' . mysqli_error($conn));

// -------------------------------------------------------------------
// جدول اسلایدر / بنرهای هدر
// -------------------------------------------------------------------
$sql_banners = "CREATE TABLE IF NOT EXISTS `banners` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`       VARCHAR(200) DEFAULT NULL,
    `subtitle`    VARCHAR(300) DEFAULT NULL,
    `image_url`   VARCHAR(500) NOT NULL,
    `link_url`    VARCHAR(500) DEFAULT NULL,
    `btn_text`    VARCHAR(80) DEFAULT NULL,
    `position`    ENUM('hero','mid','sidebar') DEFAULT 'hero',
    `sort_order`  TINYINT UNSIGNED DEFAULT 0,
    `is_active`   TINYINT(1) DEFAULT 1,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci";
mysqli_query($conn, $sql_banners) or die('خطا در ساخت جدول banners: ' . mysqli_error($conn));

// -------------------------------------------------------------------
// جدول توکن‌های session امن
// -------------------------------------------------------------------
$sql_sessions = "CREATE TABLE IF NOT EXISTS `user_sessions` (
    `id`          CHAR(64) NOT NULL COMMENT 'توکن امن',
    `user_id`     INT UNSIGNED NOT NULL,
    `ip_address`  VARCHAR(45) DEFAULT NULL,
    `user_agent`  VARCHAR(300) DEFAULT NULL,
    `expires_at`  DATETIME NOT NULL,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_user_sessions` (`user_id`),
    CONSTRAINT `fk_sess_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci";
mysqli_query($conn, $sql_sessions) or die('خطا در ساخت جدول user_sessions: ' . mysqli_error($conn));

// ===================================================================
// درج داده‌های اولیه
// ===================================================================

// دسته‌بندی‌ها
$sql_insert_cats = "INSERT IGNORE INTO `categories` 
    (`id`, `name`, `slug`, `description`, `image_url`, `sort_order`, `is_active`) VALUES
    (1, 'پوشاک مردانه', 'mens-clothing', 'انواع پوشاک مردانه با طرح‌های متنوع پاییزی', '/assets/images/categories/mens-clothing.jpg', 1, 1),
    (2, 'پوشاک زنانه',  'womens-clothing','انواع پوشاک زنانه با طرح‌های جذاب',           '/assets/images/categories/womens-clothing.jpg', 2, 1),
    (3, 'کفش و کتونی',  'shoes',          'کفش و کتونی اسپرت و رسمی',                   '/assets/images/categories/shoes.jpg',           3, 1),
    (4, 'اکسسوری',      'accessories',    'ساعت، کیف، کمربند و ...',                     '/assets/images/categories/accessories.jpg',     4, 1),
    (5, 'ورزشی',        'sports',         'لباس و تجهیزات ورزشی',                       '/assets/images/categories/sports.jpg',          5, 1)";
mysqli_query($conn, $sql_insert_cats) or die('خطا در درج categories: ' . mysqli_error($conn));

// محصولات
$sql_insert_products = "INSERT IGNORE INTO `products`
    (`id`,`category_id`,`name`,`slug`,`description`,`short_desc`,`sku`,`price`,`sale_price`,`discount_pct`,`stock_qty`,`main_image`,`gallery`,`rating_avg`,`rating_count`,`is_featured`,`is_active`) VALUES
    (1, 1,
     'هودی پاییزی مردانه برند نایک',
     'nike-autumn-hoodie',
     'هودی گرم و شیک مردانه با طرح منحصر‌به‌فرد برند نایک. مناسب فصل پاییز و زمستان. جنس پنبه ۸۰٪ پلی‌استر ۲۰٪.',
     'هودی مردانه نایک - گرم و شیک',
     'SKU-M-001', 1200000, 960000, 20, 45,
     '/assets/images/products/product-1-main.jpg',
     '[\"/assets/images/products/product-1-1.jpg\",\"/assets/images/products/product-1-2.jpg\",\"/assets/images/products/product-1-3.jpg\",\"/assets/images/products/product-1-4.jpg\"]',
     4.50, 50, 1, 1),

    (2, 3,
     'کتونی اسپرت مردانه آدیداس',
     'adidas-sport-sneakers',
     'کفش اسپرت مردانه آدیداس با سولت ضخیم و طراحی ارگونومیک. مناسب پیاده‌روی و ورزش‌های سبک.',
     'کتونی آدیداس - راحت و بادوام',
     'SKU-S-001', 2200000, 1870000, 15, 30,
     '/assets/images/products/product-2-main.jpg',
     '[\"/assets/images/products/product-2-1.jpg\",\"/assets/images/products/product-2-2.jpg\",\"/assets/images/products/product-2-3.jpg\",\"/assets/images/products/product-2-4.jpg\"]',
     4.00, 35, 1, 1),

    (3, 4,
     'ساعت مچی کلاسیک لوکس',
     'luxury-classic-watch',
     'ساعت مچی مردانه با طراحی کلاسیک و بدنه استیل ضدزنگ. مقاوم در برابر آب تا ۵۰ متر.',
     'ساعت کلاسیک - استیل ضدزنگ',
     'SKU-A-001', 3500000, 3150000, 10, 20,
     '/assets/images/products/product-3-main.jpg',
     '[\"/assets/images/products/product-3-1.jpg\",\"/assets/images/products/product-3-2.jpg\",\"/assets/images/products/product-3-3.jpg\",\"/assets/images/products/product-3-4.jpg\"]',
     4.70, 22, 1, 1),

    (4, 2,
     'پالتو زنانه پاییزی',
     'womens-autumn-coat',
     'پالتو زنانه شیک با طرح پاییزی. جنس ترکیبی پشم و پلی‌استر. مناسب محیط‌های رسمی و نیمه‌رسمی.',
     'پالتو زنانه - شیک و گرم',
     'SKU-W-001', 2800000, 2520000, 10, 15,
     '/assets/images/products/product-4-main.jpg',
     '[\"/assets/images/products/product-4-1.jpg\",\"/assets/images/products/product-4-2.jpg\",\"/assets/images/products/product-4-3.jpg\",\"/assets/images/products/product-4-4.jpg\"]',
     4.30, 18, 1, 1),

    (5, 5,
     'تراک‌شوت ورزشی مردانه',
     'mens-tracksuit-sport',
     'تراک‌شوت کامل مردانه مناسب ورزش و پیاده‌روی. شامل سویشرت و شلوار. جنس کجراه با طرح آستین راه‌راه.',
     'تراک‌شوت کامل ورزشی',
     'SKU-SP-001', 1800000, 1440000, 20, 25,
     '/assets/images/products/product-5-main.jpg',
     '[\"/assets/images/products/product-5-1.jpg\",\"/assets/images/products/product-5-2.jpg\",\"/assets/images/products/product-5-3.jpg\",\"/assets/images/products/product-5-4.jpg\"]',
     4.20, 30, 1, 1),

    (6, 1,
     'شلوار جین اسلیم مردانه',
     'mens-slim-jeans',
     'شلوار جین مردانه با برش اسلیم فیت. مناسب استفاده روزمره. جنس دنیم با اضافه الاستین.',
     'جین اسلیم - راحت و شیک',
     'SKU-M-002', 980000, 784000, 20, 60,
     '/assets/images/products/product-6-main.jpg',
     '[\"/assets/images/products/product-6-1.jpg\",\"/assets/images/products/product-6-2.jpg\",\"/assets/images/products/product-6-3.jpg\",\"/assets/images/products/product-6-4.jpg\"]',
     4.10, 45, 0, 1),

    (7, 3,
     'بوت چرم طبیعی زنانه',
     'womens-leather-boot',
     'بوت زنانه از چرم طبیعی گاو با آستر پارچه‌ای گرم. مناسب فصل سرد. پاشنه ۵ سانتی‌متر.',
     'بوت چرم زنانه - گرم و مد روز',
     'SKU-S-002', 4200000, 3570000, 15, 12,
     '/assets/images/products/product-7-main.jpg',
     '[\"/assets/images/products/product-7-1.jpg\",\"/assets/images/products/product-7-2.jpg\",\"/assets/images/products/product-7-3.jpg\",\"/assets/images/products/product-7-4.jpg\"]',
     4.60, 28, 0, 1),

    (8, 4,
     'کیف دستی چرمی مردانه',
     'mens-leather-handbag',
     'کیف دستی مردانه از چرم مصنوعی با کیفیت بالا. دارای چندین جیب داخلی و قفل امنیتی.',
     'کیف چرمی - سبک و کاربردی',
     'SKU-A-002', 1500000, 1275000, 15, 35,
     '/assets/images/products/product-8-main.jpg',
     '[\"/assets/images/products/product-8-1.jpg\",\"/assets/images/products/product-8-2.jpg\",\"/assets/images/products/product-8-3.jpg\",\"/assets/images/products/product-8-4.jpg\"]',
     3.90, 20, 0, 1)";
mysqli_query($conn, $sql_insert_products) or die('خطا در درج products: ' . mysqli_error($conn));

// بنرهای هدر اسلایدر
$sql_insert_banners = "INSERT IGNORE INTO `banners`
    (`id`,`title`,`subtitle`,`image_url`,`link_url`,`btn_text`,`position`,`sort_order`,`is_active`) VALUES
    (1, 'کلکسیون پاییز ۱۴۰۳', 'جدیدترین مدل‌های پاییزی با تخفیف ویژه', '/assets/images/banners/banner-1.jpg', '/products', 'مشاهده محصولات', 'hero', 1, 1),
    (2, 'تخفیف ۳۰٪ روی پوشاک', 'فرصت طلایی برای خرید لباس‌های پاییزی', '/assets/images/banners/banner-2.jpg', '/products?cat=1', 'خرید کنید', 'hero', 2, 1),
    (3, 'کفش‌های جدید رسیدند', 'کالکشن جدید کفش پاییز و زمستان', '/assets/images/banners/banner-3.jpg', '/products?cat=3', 'مشاهده کفش‌ها', 'hero', 3, 1)";
mysqli_query($conn, $sql_insert_banners) or die('خطا در درج banners: ' . mysqli_error($conn));

// نظرات نمونه
$sql_insert_reviews = "INSERT IGNORE INTO `reviews`
    (`product_id`,`author_name`,`rating`,`title`,`body`,`is_approved`) VALUES
    (1, 'علی محمدی', 5, 'عالی بود', 'خیلی گرم و راحته. کیفیتش از قیمتش بیشتره.', 1),
    (1, 'حسن رضایی', 4, 'خوب بود', 'طرحش قشنگه ولی کمی گشادتر از اندازه‌ام بود.', 1),
    (2, 'مریم احمدی', 4, 'راحت و سبک', 'برای پیاده‌روی عالیه. پاهام خسته نمیشه.', 1),
    (3, 'رضا کریمی', 5, 'ساعت بینظیر', 'استیلش خیلی شیکه. همه ازش تعریف می‌کنن.', 1)";
mysqli_query($conn, $sql_insert_reviews) or die('خطا در درج reviews: ' . mysqli_error($conn));

// کاربر ادمین پیش‌فرض
$admin_pass = password_hash('Admin@1234', PASSWORD_BCRYPT, ['cost' => 12]);
$sql_admin = "INSERT IGNORE INTO `users`
    (`username`,`email`,`phone`,`password_hash`,`full_name`,`role`,`is_active`,`email_verified`) VALUES
    ('admin', 'admin@autumnshop.ir', '09120000000', '" . mysqli_real_escape_string($conn, $admin_pass) . "', 'مدیر سیستم', 'admin', 1, 1)";
mysqli_query($conn, $sql_admin) or die('خطا در درج admin: ' . mysqli_error($conn));

mysqli_close($conn);

echo '<div style="font-family:Tahoma;direction:rtl;padding:30px;background:#f0fdf4;border:2px solid #22c55e;border-radius:10px;max-width:600px;margin:40px auto;">';
echo '<h2 style="color:#16a34a;">✅ پایگاه داده با موفقیت راه‌اندازی شد</h2>';
echo '<ul style="line-height:2.2">';
echo '<li>✔ دیتابیس <strong>' . DB_NAME . '</strong> ساخته شد</li>';
echo '<li>✔ ۹ جدول ایجاد شد</li>';
echo '<li>✔ ۵ دسته‌بندی درج شد</li>';
echo '<li>✔ ۸ محصول نمونه درج شد</li>';
echo '<li>✔ ۳ بنر هدر درج شد</li>';
echo '<li>✔ کاربر ادمین ایجاد شد (username: admin / pass: Admin@1234)</li>';
echo '</ul>';
echo '<p style="color:#dc2626;font-weight:bold">⚠️ این فایل را پس از راه‌اندازی از سرور حذف کنید!</p>';
echo '</div>';
