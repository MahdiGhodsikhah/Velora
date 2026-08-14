-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 14, 2026 at 08:27 AM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `autumn_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
CREATE TABLE IF NOT EXISTS `banners` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(200) COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `subtitle` varchar(300) COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `image_url` varchar(500) COLLATE utf8mb4_persian_ci NOT NULL,
  `link_url` varchar(500) COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `btn_text` varchar(80) COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `position` enum('hero','mid','sidebar') COLLATE utf8mb4_persian_ci DEFAULT 'hero',
  `sort_order` tinyint UNSIGNED DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `subtitle`, `image_url`, `link_url`, `btn_text`, `position`, `sort_order`, `is_active`, `created_at`) VALUES
(4, 'کلکسیون پاییزی شگفت‌انگیز', 'جدیدترین مدل‌های پوشاک با طراحی منحصر‌به‌فرد پاییزی - تخفیف ویژه تا ۵۰٪', '/assets/images/banners/banner-autumn-1.png', '/products', 'مشاهده محصولات', 'hero', 1, 1, '2026-06-17 08:52:19'),
(5, 'استایل پاییزی خود را بسازید', 'با بهترین برندهای پوشاک و اکسسوری - ارسال رایگان برای خریدهای بالای ۵۰۰ هزار تومان', '/assets/images/banners/banner-normal-1.png', '/products?cat=1', 'خرید کنید', 'hero', 2, 1, '2026-06-17 08:52:19'),
(6, 'تخفیف‌های فصلی', 'تا ۷۰٪ تخفیف روی محصولات منتخب - فقط تا پایان هفته', '/assets/images/banners/banner-autumn-1.png', '/products?sale=1', 'خرید با تخفیف', 'hero', 3, 1, '2026-06-17 08:52:19');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `quantity` smallint UNSIGNED NOT NULL DEFAULT '1',
  `added_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_product` (`user_id`,`product_id`),
  KEY `fk_cart_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_persian_ci NOT NULL COMMENT 'نام دسته‌بندی',
  `slug` varchar(120) COLLATE utf8mb4_persian_ci NOT NULL COMMENT 'نامک',
  `description` text COLLATE utf8mb4_persian_ci,
  `image_url` varchar(500) COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `parent_id` int UNSIGNED DEFAULT NULL COMMENT 'دسته والد',
  `sort_order` tinyint UNSIGNED DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_slug` (`slug`),
  KEY `idx_parent` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image_url`, `parent_id`, `sort_order`, `is_active`, `created_at`) VALUES
(1, 'پوشاک مردانه', 'mens-clothing', 'انواع پوشاک مردانه با طرح‌های متنوع پاییزی', '/assets/images/categories/mens-clothing.jpg', NULL, 1, 1, '2026-06-09 15:55:19'),
(2, 'پوشاک زنانه', 'womens-clothing', 'انواع پوشاک زنانه با طرح‌های جذاب', '/assets/images/categories/womens-clothing.jpg', NULL, 2, 1, '2026-06-09 15:55:19'),
(3, 'کفش و کتونی', 'shoes', 'کفش و کتونی اسپرت و رسمی', '/assets/images/categories/shoes.jpg', NULL, 3, 1, '2026-06-09 15:55:19'),
(4, 'اکسسوری', 'accessories', 'ساعت، کیف، کمربند و ...', '/assets/images/categories/accessories.jpg', NULL, 4, 1, '2026-06-09 15:55:19'),
(5, 'ورزشی', 'sports', 'لباس و تجهیزات ورزشی', '/assets/images/categories/sports.jpg', NULL, 5, 1, '2026-06-09 15:55:19');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8mb4_persian_ci NOT NULL,
  `discount_type` enum('percent','fixed') COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `discount_value` int NOT NULL,
  `min_purchase` int DEFAULT '0',
  `max_uses` int DEFAULT NULL,
  `used_count` int DEFAULT '0',
  `valid_from` date DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `order_number` varchar(30) COLLATE utf8mb4_persian_ci NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled','refunded') COLLATE utf8mb4_persian_ci DEFAULT 'pending',
  `total_amount` bigint UNSIGNED NOT NULL,
  `discount_amt` bigint UNSIGNED DEFAULT '0',
  `shipping_cost` bigint UNSIGNED DEFAULT '0',
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci,
  `postal_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `payment_status` enum('unpaid','paid','refunded') COLLATE utf8mb4_persian_ci DEFAULT 'unpaid',
  `notes` text COLLATE utf8mb4_persian_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `idx_user_orders` (`user_id`),
  KEY `idx_order_num` (`order_number`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `status`, `total_amount`, `discount_amt`, `shipping_cost`, `shipping_address`, `postal_code`, `payment_method`, `payment_status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 'ORD-20260721-F9116E9A', 'pending', 2092800, 0, 0, 'مشهد رسالت 147', '9149172740', 'online', 'unpaid', '', '2026-07-21 21:11:10', NULL),
(2, 2, 'ORD-20260721-C1116945', 'pending', 2092800, 0, 0, 'مشهد رسالت 147', '9149172740', 'cash', 'unpaid', '', '2026-07-21 21:12:39', NULL),
(3, 2, 'ORD-20260721-2CCE15C8', 'pending', 1046400, 0, 0, 'مشهد رسالت 147', '9149172740', 'online', 'unpaid', '', '2026-07-21 21:24:54', NULL),
(4, 2, 'ORD-20260721-1A79609A', 'pending', 8762510, 0, 0, 'مشهد رسالت 147', '9149172740', 'online', 'unpaid', '', '2026-07-21 21:52:28', NULL),
(5, 2, 'ORD-20260721-7F431425', 'pending', 1046400, 0, 0, 'مشهد رسالت 147', '9149172740', 'online', 'unpaid', '', '2026-07-21 22:58:08', NULL),
(6, 2, 'ORD-20260721-00BB3866', 'pending', 2092800, 0, 0, 'مشهد رسالت 147', '9149172740', 'cash', 'unpaid', '', '2026-07-21 23:27:12', NULL),
(7, 2, 'ORD-20260722-B67116D9', 'pending', 2038300, 0, 0, 'مشهد رسالت 147', '9149172740', 'online', 'unpaid', '', '2026-07-22 09:33:09', NULL),
(8, 2, 'ORD-20260722-F0266D40', 'pending', 3433500, 0, 0, 'مشهد رسالت', '9149172740', 'online', 'unpaid', '', '2026-07-22 10:23:57', NULL),
(9, 2, 'ORD-20260722-209F7BBB', 'pending', 3139200, 0, 0, 'مشهد رسالت', '9149172740', 'online', 'unpaid', '', '2026-07-22 21:25:35', NULL),
(10, 2, 'ORD-20260729-FD582483', 'pending', 6867000, 0, 0, 'مشهد رسالت', '9149172740', 'online', 'unpaid', '', '2026-07-29 10:03:38', NULL),
(11, 2, 'ORD-20260805-ACB465C3', 'pending', 1046400, 0, 0, 'مشهد رسالت', '9149172740', 'online', 'unpaid', '', '2026-08-05 11:24:12', NULL),
(12, 2, 'ORD-20260813-FD5B1030', 'pending', 2038300, 0, 0, 'مشهد رسالت', '9149172740', 'cash', 'unpaid', '', '2026-08-13 21:35:42', NULL),
(13, 2, 'ORD-20260813-2E087334', 'pending', 2038300, 0, 0, 'مشهد رسالت', '9149172740', 'cash', 'unpaid', '', '2026-08-13 21:36:10', NULL),
(14, 2, 'ORD-20260813-92FAE3A1', 'pending', 1308000, 0, 0, 'مشهد رسالت', '9149172740', 'online', 'unpaid', '', '2026-08-14 00:39:53', NULL),
(15, 2, 'ORD-20260813-D444D6EF', 'shipped', 872000, 0, 0, 'مشهد رسالت', '9149172740', 'online', 'paid', '', '2026-08-14 00:42:30', '2026-08-14 00:55:11');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `product_name` varchar(200) COLLATE utf8mb4_persian_ci NOT NULL,
  `unit_price` bigint UNSIGNED NOT NULL,
  `quantity` smallint UNSIGNED NOT NULL DEFAULT '1',
  `subtotal` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_order_items` (`order_id`),
  KEY `fk_item_product` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `unit_price`, `quantity`, `subtotal`) VALUES
(1, 1, 1, 'هودی پاییزی مردانه برند نایک', 960000, 2, 1920000),
(2, 2, 1, 'هودی پاییزی مردانه برند نایک', 960000, 2, 1920000),
(3, 3, 1, 'هودی پاییزی مردانه برند نایک', 960000, 1, 960000),
(4, 4, 1, 'هودی پاییزی مردانه برند نایک', 960000, 1, 960000),
(5, 4, 3, 'ساعت مچی کلاسیک لوکس', 3150000, 1, 3150000),
(6, 4, 2, 'کتونی اسپرت مردانه آدیداس', 1870000, 1, 1870000),
(7, 4, 6, 'شلوار جین اسلیم مردانه', 784000, 1, 784000),
(8, 4, 8, 'کیف دستی چرمی مردانه', 1275000, 1, 1275000),
(9, 5, 1, 'هودی پاییزی مردانه برند نایک', 960000, 1, 960000),
(10, 6, 1, 'هودی پاییزی مردانه برند نایک', 960000, 2, 1920000),
(11, 7, 2, 'کتونی اسپرت مردانه آدیداس', 1870000, 1, 1870000),
(12, 8, 3, 'ساعت مچی کلاسیک لوکس', 3150000, 1, 3150000),
(13, 9, 1, 'هودی پاییزی مردانه برند نایک', 960000, 3, 2880000),
(14, 10, 3, 'ساعت مچی کلاسیک لوکس', 3150000, 2, 6300000),
(15, 11, 1, 'هودی پاییزی مردانه برند نایک', 960000, 1, 960000),
(16, 12, 2, 'کتونی اسپرت مردانه آدیداس', 1870000, 1, 1870000),
(17, 13, 2, 'کتونی اسپرت مردانه آدیداس', 1870000, 1, 1870000),
(18, 14, 9, 'تست', 800000, 1, 800000),
(19, 14, 14, 'تست3', 400000, 1, 400000),
(20, 15, 14, 'تست3', 400000, 2, 800000);

-- --------------------------------------------------------

--
-- Table structure for table `post_tags`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_persian_ci NOT NULL,
  `slug` varchar(220) COLLATE utf8mb4_persian_ci NOT NULL,
  `description` text COLLATE utf8mb4_persian_ci,
  `short_desc` varchar(500) COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `sku` varchar(50) COLLATE utf8mb4_persian_ci DEFAULT NULL COMMENT 'کد محصول',
  `price` bigint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'قیمت به تومان',
  `sale_price` bigint UNSIGNED DEFAULT NULL COMMENT 'قیمت با تخفیف',
  `discount_pct` tinyint UNSIGNED DEFAULT '0' COMMENT 'درصد تخفیف',
  `stock_qty` smallint UNSIGNED NOT NULL DEFAULT '0',
  `main_image` varchar(500) COLLATE utf8mb4_persian_ci NOT NULL DEFAULT '/assets/images/products/no-image.jpg',
  `gallery` json DEFAULT NULL COMMENT 'آرایه JSON آدرس تصاویر',
  `rating_avg` decimal(3,2) DEFAULT '0.00',
  `rating_count` int UNSIGNED DEFAULT '0',
  `is_featured` tinyint(1) DEFAULT '0',
  `season` enum('spring','summer','autumn','winter','all') COLLATE utf8mb4_persian_ci DEFAULT 'all' COMMENT 'فصل مربوط به محصول',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `views` int UNSIGNED DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `sku` (`sku`),
  KEY `idx_category` (`category_id`),
  KEY `idx_featured` (`is_featured`),
  KEY `idx_active` (`is_active`),
  KEY `idx_slug` (`slug`),
  KEY `idx_season` (`season`),
  KEY `idx_season_category` (`season`,`category_id`,`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `short_desc`, `sku`, `price`, `sale_price`, `discount_pct`, `stock_qty`, `main_image`, `gallery`, `rating_avg`, `rating_count`, `is_featured`, `season`, `is_active`, `views`, `created_at`, `updated_at`) VALUES
(1, 1, 'هودی پاییزی مردانه برند نایک', 'nike-autumn-hoodie', 'هودی گرم و شیک مردانه با طرح منحصر‌به‌فرد برند نایک. مناسب فصل پاییز و زمستان. جنس پنبه ۸۰٪ پلی‌استر ۲۰٪.', 'هودی مردانه نایک - گرم و شیک', 'SKU-M-001', 1200000, 960000, 20, 32, '/assets/images/products/product-1-main.jpg', '[\"/assets/images/products/product-1-1.jpg\", \"/assets/images/products/product-1-2.jpg\", \"/assets/images/products/product-1-3.jpg\", \"/assets/images/products/product-1-4.jpg\"]', 4.50, 50, 1, 'autumn', 1, 151, '2026-06-09 15:55:19', '2026-08-14 00:43:59'),
(2, 3, 'کتونی اسپرت مردانه آدیداس', 'adidas-sport-sneakers', 'کفش اسپرت مردانه آدیداس با سولت ضخیم و طراحی ارگونومیک. مناسب پیاده‌روی و ورزش‌های سبک.', 'کتونی آدیداس - راحت و بادوام', 'SKU-S-001', 2200000, 1870000, 15, 26, '/assets/images/products/product-2-main.jpg', '[\"/assets/images/products/product-2-1.jpg\", \"/assets/images/products/product-2-2.jpg\", \"/assets/images/products/product-2-3.jpg\", \"/assets/images/products/product-2-4.jpg\"]', 4.00, 35, 1, 'spring', 1, 62, '2026-06-09 15:55:19', '2026-08-13 21:36:10'),
(3, 4, 'ساعت مچی کلاسیک لوکس', 'luxury-classic-watch', 'ساعت مچی مردانه با طراحی کلاسیک و بدنه استیل ضدزنگ. مقاوم در برابر آب تا ۵۰ متر.', 'ساعت کلاسیک - استیل ضدزنگ', 'SKU-A-001', 3500000, 3150000, 10, 16, '/assets/images/products/product-3-main.jpg', '[\"/assets/images/products/product-3-1.jpg\", \"/assets/images/products/product-3-2.jpg\", \"/assets/images/products/product-3-3.jpg\", \"/assets/images/products/product-3-4.jpg\"]', 4.70, 22, 1, 'summer', 1, 144, '2026-06-09 15:55:19', '2026-08-13 20:51:40'),
(4, 2, 'پالتو زنانه پاییزی', 'womens-autumn-coat', 'پالتو زنانه شیک با طرح پاییزی. جنس ترکیبی پشم و پلی‌استر. مناسب محیط‌های رسمی و نیمه‌رسمی.', 'پالتو زنانه - شیک و گرم', 'SKU-W-001', 2800000, 2520000, 10, 15, '/assets/images/products/product-4-main.jpg', '[\"/assets/images/products/product-4-1.jpg\", \"/assets/images/products/product-4-2.jpg\", \"/assets/images/products/product-4-3.jpg\", \"/assets/images/products/product-4-4.jpg\"]', 4.30, 18, 1, 'autumn', 1, 6, '2026-06-09 15:55:19', '2026-08-05 09:35:11'),
(5, 5, 'تراک‌شوت ورزشی مردانه', 'mens-tracksuit-sport', 'تراک‌شوت کامل مردانه مناسب ورزش و پیاده‌روی. شامل سویشرت و شلوار. جنس کجراه با طرح آستین راه‌راه.', 'تراک‌شوت کامل ورزشی', 'SKU-SP-001', 1800000, 1440000, 20, 25, '/assets/images/products/product-5-main.jpg', '[\"/assets/images/products/product-5-1.jpg\", \"/assets/images/products/product-5-2.jpg\", \"/assets/images/products/product-5-3.jpg\", \"/assets/images/products/product-5-4.jpg\"]', 4.20, 30, 1, 'winter', 1, 30, '2026-06-09 15:55:19', '2026-08-13 16:57:38'),
(6, 1, 'شلوار جین اسلیم مردانه', 'mens-slim-jeans', 'شلوار جین مردانه با برش اسلیم فیت. مناسب استفاده روزمره. جنس دنیم با اضافه الاستین.', 'جین اسلیم - راحت و شیک', 'SKU-M-002', 980000, 784000, 20, 59, '/assets/images/products/product-6-main.jpg', '[\"/assets/images/products/product-6-1.jpg\", \"/assets/images/products/product-6-2.jpg\", \"/assets/images/products/product-6-3.jpg\", \"/assets/images/products/product-6-4.jpg\"]', 4.10, 45, 0, 'all', 1, 2, '2026-06-09 15:55:19', '2026-07-23 00:58:26'),
(7, 3, 'بوت چرم طبیعی زنانه', 'womens-leather-boot', 'بوت زنانه از چرم طبیعی گاو با آستر پارچه‌ای گرم. مناسب فصل سرد. پاشنه ۵ سانتی‌متر.', 'بوت چرم زنانه - گرم و مد روز', 'SKU-S-002', 4200000, 3570000, 15, 12, '/assets/images/products/product-7-main.jpg', '[\"/assets/images/products/product-7-1.jpg\", \"/assets/images/products/product-7-2.jpg\", \"/assets/images/products/product-7-3.jpg\", \"/assets/images/products/product-7-4.jpg\"]', 4.60, 28, 0, 'spring', 1, 1, '2026-06-09 15:55:19', '2026-07-23 00:58:26'),
(8, 4, 'کیف دستی چرمی مردانه', 'mens-leather-handbag', 'کیف دستی مردانه از چرم مصنوعی با کیفیت بالا. دارای چندین جیب داخلی و قفل امنیتی.', 'کیف چرمی - سبک و کاربردی', 'SKU-A-002', 1500000, 1275000, 15, 34, '/assets/images/products/product-8-main.jpg', '[\"/assets/images/products/product-8-1.jpg\", \"/assets/images/products/product-8-2.jpg\", \"/assets/images/products/product-8-3.jpg\", \"/assets/images/products/product-8-4.jpg\"]', 0.00, 0, 0, 'winter', 1, 6, '2026-06-09 15:55:19', '2026-08-14 00:33:50'),
(9, 1, 'تست', 'test', 'this product for test no real', 'this for test', 'SKU-M-005', 1000000, 800000, 20, 11, '/assets/images/products/12.webp', NULL, 0.00, 0, 1, 'spring', 1, 4, '2026-08-13 23:43:09', '2026-08-14 00:39:53'),
(13, 1, 'تست2', 'test2', 'this product for test 2', 'test 2', 'SKU-M-003', 2000000, 1000000, 50, 20, '/assets/images/products/1.jpg', NULL, 0.00, 0, 1, 'spring', 1, 1, '2026-08-13 23:49:05', '2026-08-14 00:35:57'),
(14, 1, 'تست3', 'test3', 'this product for test 3', 'test 3', 'SKU-M-004', 500000, 400000, 20, 17, '/assets/images/products/7.jpg', NULL, 0.00, 0, 1, 'spring', 1, 2, '2026-08-13 23:50:57', '2026-08-14 00:42:30');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
CREATE TABLE IF NOT EXISTS `product_images` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int UNSIGNED NOT NULL,
  `image_url` varchar(500) COLLATE utf8mb4_persian_ci NOT NULL,
  `alt_text` varchar(200) COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `sort_order` tinyint UNSIGNED DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `author_name` varchar(80) COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `rating` tinyint UNSIGNED NOT NULL COMMENT '1 تا 5',
  `title` varchar(200) COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_persian_ci,
  `is_approved` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_product_rev` (`product_id`)
) ;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `author_name`, `rating`, `title`, `body`, `is_approved`, `created_at`) VALUES
(1, 1, NULL, 'علی محمدی', 5, 'عالی بود', 'خیلی گرم و راحته. کیفیتش از قیمتش بیشتره.', 1, '2026-06-09 15:55:19'),
(2, 1, NULL, 'حسن رضایی', 4, 'خوب بود', 'طرحش قشنگه ولی کمی گشادتر از اندازه‌ام بود.', 1, '2026-06-09 15:55:19'),
(3, 2, NULL, 'مریم احمدی', 4, 'راحت و سبک', 'برای پیاده‌روی عالیه. پاهام خسته نمیشه.', 1, '2026-06-09 15:55:19'),
(4, 3, NULL, 'رضا کریمی', 5, 'ساعت بینظیر', 'استیلش خیلی شیکه. همه ازش تعریف می‌کنن.', 1, '2026-06-09 15:55:19'),
(5, 8, 2, NULL, 4, 'محصول خیلی خوبیه', 'محصول عالی هست واقعا ارزش خرید دارد', 1, '2026-06-30 13:08:06');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `setting_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci,
  `setting_type` enum('text','number','boolean','json') CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT 'text',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `updated_at`, `created_at`) VALUES
(1, 'active_theme', 'spring', 'text', 'تم فعال سایت (autumn, winter, spring, summer)', '2026-07-23 14:44:29', '2026-07-23 00:55:20'),
(2, 'theme_auto_detect', '1', 'boolean', 'تشخیص خودکار تم بر اساس فصل', NULL, '2026-07-23 00:55:20'),
(3, 'theme_allow_user_choice', '1', 'boolean', 'اجازه انتخاب تم توسط کاربر', NULL, '2026-07-23 00:55:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_persian_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_persian_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci COMMENT 'آدرس کاربر',
  `postal_code` varchar(10) COLLATE utf8mb4_persian_ci DEFAULT NULL COMMENT 'کد پستی 10 رقمی',
  `password_hash` varchar(255) COLLATE utf8mb4_persian_ci NOT NULL COMMENT 'هش رمز عبور با password_hash()',
  `full_name` varchar(100) COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `profile_image` varchar(255) COLLATE utf8mb4_persian_ci DEFAULT NULL COMMENT 'مسیر عکس پروفایل',
  `preferred_theme` varchar(20) COLLATE utf8mb4_persian_ci DEFAULT 'automatic',
  `job` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL COMMENT 'شغل کاربر',
  `birth_date` date DEFAULT NULL COMMENT 'تاریخ تولد (میلادی)',
  `avatar_url` varchar(500) COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `role` enum('customer','admin','moderator') COLLATE utf8mb4_persian_ci NOT NULL DEFAULT 'customer',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `email_verified` tinyint(1) NOT NULL DEFAULT '0',
  `login_attempts` tinyint UNSIGNED DEFAULT '0',
  `locked_until` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `phone` (`phone`),
  KEY `idx_email` (`email`),
  KEY `idx_username` (`username`),
  KEY `idx_phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `phone`, `address`, `postal_code`, `password_hash`, `full_name`, `profile_image`, `preferred_theme`, `job`, `birth_date`, `avatar_url`, `role`, `is_active`, `email_verified`, `login_attempts`, `locked_until`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@autumnshop.ir', '09120000000', NULL, NULL, '$2y$12$ief.AmWpLNaKX12vFAG3j..g68qbjvh7CRuwgUOsc/LGxZD6ODBg.', 'مدیر سیستم', NULL, 'automatic', NULL, NULL, NULL, 'admin', 1, 1, 0, NULL, NULL, '2026-06-09 15:55:19', NULL),
(2, 'mahdi', 'mahdi84m17@gmail.com', '09929954844', 'مشهد رسالت', '9149172740', '$2y$12$EUxezRKyu0omZbrXlP0dn.sLlGDxxbUHn7dANflD5e.N2Izd1h9Aq', 'مهدی قدسی خواه', '/uploads/profiles/profile_2_mahdi_1786625096.jpg', 'summer', 'دانشجو', NULL, NULL, 'admin', 1, 0, 0, NULL, '2026-08-13 21:44:06', '2026-06-30 12:59:51', '2026-08-14 00:42:30'),
(3, 'ali', 'ali@gmail.com', 'temp_3', NULL, NULL, '$2y$12$6.6NolqrdCRTJki0zuKERuru6LlcHAQHdw8UECeX1Rxjdma7ndCI.', NULL, NULL, 'automatic', NULL, NULL, NULL, 'customer', 1, 0, 0, NULL, '2026-07-17 23:35:34', '2026-07-16 22:13:50', '2026-07-18 00:07:14'),
(4, 'ali2', '', 'temp_4', NULL, NULL, '$2y$12$UWEQfVbOP6CrLnQLXFrG.OIofpW21Hhy3rRdOuspu7imKNN.w3ifu', NULL, NULL, 'automatic', NULL, NULL, NULL, 'customer', 1, 0, 0, NULL, NULL, '2026-07-17 23:35:54', '2026-07-18 00:07:14'),
(6, 'user89514957', '', '09929954843', NULL, NULL, '$2y$12$lVHhi1o0GOmbk.icGdKpKuv25RWQSf32DARjF4N85/w.feDLPWC96', NULL, NULL, 'automatic', NULL, NULL, NULL, 'customer', 1, 0, 4, NULL, '2026-07-18 00:24:49', '2026-07-18 00:07:54', '2026-07-22 09:35:35');

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

DROP TABLE IF EXISTS `user_sessions`;
CREATE TABLE IF NOT EXISTS `user_sessions` (
  `id` char(64) COLLATE utf8mb4_persian_ci NOT NULL COMMENT 'توکن امن',
  `user_id` int UNSIGNED NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `user_agent` varchar(300) COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_sessions` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE IF NOT EXISTS `wishlist` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `added_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_wish_user_product` (`user_id`,`product_id`),
  KEY `fk_wish_product` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `added_at`) VALUES
(46, 2, 8, '2026-07-07 15:44:38'),
(48, 2, 2, '2026-07-22 10:20:10'),
(50, 2, 5, '2026-08-04 23:49:36'),
(58, 2, 4, '2026-08-13 13:02:19'),
(60, 2, 6, '2026-08-13 13:05:53'),
(61, 2, 1, '2026-08-13 13:13:16'),
(66, 2, 3, '2026-08-13 13:27:09');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_item_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_item_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_cat` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_img_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_rev_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD CONSTRAINT `fk_sess_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `fk_wish_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_wish_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
