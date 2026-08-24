-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 24, 2026 at 05:17 AM
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
-- Database: `velora_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
CREATE TABLE IF NOT EXISTS `banners` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `subtitle` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `image_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `link_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `btn_text` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `position` enum('hero','mid','sidebar') CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT 'hero',
  `sort_order` tinyint UNSIGNED DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `subtitle`, `image_url`, `link_url`, `btn_text`, `position`, `sort_order`, `is_active`, `created_at`) VALUES
(4, 'کلکسیون پاییزی شگفت‌انگیز', 'جدیدترین مدل‌های پوشاک با طراحی منحصر‌به‌فرد پاییزی - تخفیف ویژه تا ۵۰٪', '/assets/images/banners/banner-autumn-1.png', '/products', 'مشاهده محصولات', 'hero', 3, 1, '2026-06-17 08:52:19'),
(5, 'فصل جدید، استایل جدید', 'با بهترین برندهای پوشاک و اکسسوری - ارسال رایگان برای خریدهای بالای ۵۰۰ هزار تومان', '/assets/images/banners/banner-spring-1.png', '/products?season=spring', 'خرید کنید', 'hero', 1, 1, '2026-06-17 08:52:19'),
(6, 'تخفیف‌های فصلی', 'تا ۷۰٪ تخفیف روی محصولات منتخب - فقط تا پایان هفته', '/assets/images/banners/banner-summer-1.png', '/products?sale=1', 'خرید با تخفیف', 'hero', 2, 1, '2026-06-17 08:52:19'),
(7, 'آنچه می‌پوشی، بخشی از توست', 'انتخابی از میان استایل‌های متنوع، برای ساختن ظاهری که واقعاً بیانگر توست.', '/assets/images/banners/banner-winter-1.png', '/products?season=winter', 'بزن بریم', 'hero', 4, 1, '2026-08-16 16:59:07');

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
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL COMMENT 'نام دسته‌بندی',
  `slug` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL COMMENT 'نامک',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci,
  `sort_order` tinyint UNSIGNED DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `sort_order`, `is_active`, `created_at`) VALUES
(1, 'پوشاک مردانه', 'mens-clothing', 'انواع پوشاک مردانه با طرح‌های متنوع پاییزی', 1, 1, '2026-06-09 15:55:19'),
(2, 'پوشاک زنانه', 'womens-clothing', 'انواع پوشاک زنانه با طرح‌های جذاب', 2, 1, '2026-06-09 15:55:19'),
(3, 'کفش و کتونی', 'shoes', 'کفش و کتونی اسپرت و رسمی', 3, 1, '2026-06-09 15:55:19'),
(4, 'اکسسوری', 'accessories', 'ساعت، کیف، کمربند و ...', 4, 1, '2026-06-09 15:55:19'),
(5, 'ورزشی', 'sports', 'لباس و تجهیزات ورزشی', 5, 1, '2026-06-09 15:55:19');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `order_number` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT 'pending',
  `total_amount` bigint UNSIGNED NOT NULL,
  `discount_amt` bigint UNSIGNED DEFAULT '0',
  `shipping_cost` bigint UNSIGNED DEFAULT '0',
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci,
  `postal_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `payment_method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `payment_status` enum('unpaid','paid','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT 'unpaid',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `idx_user_orders` (`user_id`),
  KEY `idx_order_num` (`order_number`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

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
(11, 2, 'ORD-20260805-ACB465C3', 'processing', 1046400, 0, 0, 'مشهد رسالت', '9149172740', 'online', 'paid', '', '2026-08-05 11:24:12', '2026-08-14 15:56:59'),
(12, 2, 'ORD-20260813-FD5B1030', 'pending', 2038300, 0, 0, 'مشهد رسالت', '9149172740', 'cash', 'unpaid', '', '2026-08-13 21:35:42', NULL),
(13, 2, 'ORD-20260813-2E087334', 'pending', 2038300, 0, 0, 'مشهد رسالت', '9149172740', 'cash', 'unpaid', '', '2026-08-13 21:36:10', NULL),
(16, 2, 'ORD-20260817-70272870', 'pending', 1111800, 0, 0, 'مشهد رسالت', '9149172740', 'online', 'paid', '', '2026-08-17 08:32:08', '2026-08-17 08:33:14'),
(17, 2, 'ORD-20260817-76283C25', 'shipped', 2038300, 0, 0, 'مشهد رسالت', '9149172740', 'online', 'unpaid', '', '2026-08-17 09:49:55', '2026-08-21 15:54:53'),
(18, 2, 'ORD-20260822-4A4F2D0E', 'pending', 2686850, 0, 0, 'مشهد رسالت', '9149172740', 'online', 'unpaid', '', '2026-08-22 09:13:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `product_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `unit_price` bigint UNSIGNED NOT NULL,
  `quantity` smallint UNSIGNED NOT NULL DEFAULT '1',
  `subtotal` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_order_items` (`order_id`),
  KEY `fk_item_product` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

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
(21, 16, 27, 'شومیز زنانه کژوال', 1020000, 1, 1020000),
(22, 17, 2, 'کتونی اسپرت مردانه نایک', 1870000, 1, 1870000),
(23, 18, 23, 'کاپشن مردانه سبک', 2465000, 1, 2465000);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int UNSIGNED NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `slug` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci,
  `short_desc` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `sku` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL COMMENT 'کد محصول',
  `price` bigint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'قیمت به تومان',
  `sale_price` bigint UNSIGNED DEFAULT NULL COMMENT 'قیمت با تخفیف',
  `discount_pct` tinyint UNSIGNED DEFAULT '0' COMMENT 'درصد تخفیف',
  `stock_qty` smallint UNSIGNED NOT NULL DEFAULT '0',
  `main_image` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL DEFAULT '/assets/images/products/no-image.jpg',
  `gallery` json DEFAULT NULL COMMENT 'آرایه JSON آدرس تصاویر',
  `rating_avg` decimal(3,2) DEFAULT '0.00',
  `rating_count` int UNSIGNED DEFAULT '0',
  `is_featured` tinyint(1) DEFAULT '0',
  `season` enum('spring','summer','autumn','winter','all') CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT 'all' COMMENT 'فصل مربوط به محصول',
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
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `short_desc`, `sku`, `price`, `sale_price`, `discount_pct`, `stock_qty`, `main_image`, `gallery`, `rating_avg`, `rating_count`, `is_featured`, `season`, `is_active`, `views`, `created_at`, `updated_at`) VALUES
(1, 1, 'هودی پاییزی مردانه برند نایک', 'nike-autumn-hoodie', 'هودی گرم و شیک مردانه با طرح منحصر‌به‌فرد برند نایک. مناسب فصل پاییز و زمستان. جنس پنبه ۸۰٪ پلی‌استر ۲۰٪.', 'هودی مردانه نایک - گرم و شیک', 'SKU-M-001', 1200000, 960000, 20, 32, '/assets/images/products/product_1_main_1786890774_7b705668.jpg', '[\"/assets/images/products/product_1_gallery_1_1786890774_058c2505.jpg\", \"/assets/images/products/product_1_gallery_2_1786890774_2d0e29f4.jpg\"]', 0.00, 0, 1, 'autumn', 1, 159, '2026-06-09 15:55:19', '2026-08-21 15:58:10'),
(2, 3, 'کتونی اسپرت مردانه نایک', 'nike-sport-sneakers', 'کفش اسپرت مردانه آدیداس با سولت ضخیم و طراحی ارگونومیک. مناسب پیاده‌روی و ورزش‌های سبک.', 'کتونی آدیداس - راحت و بادوام', 'SKU-S-001', 2200000, 1870000, 15, 25, '/assets/images/products/product_2_main_1786888148_e9d366c6.jpg', '[\"/assets/images/products/product_2_gallery_1_1786888148_69dc27bf.jpg\"]', 0.00, 0, 1, 'spring', 1, 71, '2026-06-09 15:55:19', '2026-08-17 12:06:14'),
(3, 4, 'ساعت مچی کلاسیک لوکس', 'luxury-classic-watch', 'ساعت مچی مردانه با طراحی کلاسیک و بدنه استیل ضدزنگ. مقاوم در برابر آب تا ۵۰ متر.', 'ساعت کلاسیک - استیل ضدزنگ', 'SKU-A-001', 3500000, 3150000, 10, 16, '/assets/images/products/product_3_main_1786889444_58eb727c.jpg', '[\"/assets/images/products/product_3_gallery_1_1786889444_6cd67e11.jpg\", \"/assets/images/products/product_3_gallery_2_1786889444_4daf73c1.jpg\"]', 0.00, 0, 1, 'summer', 1, 158, '2026-06-09 15:55:19', '2026-08-21 13:31:03'),
(4, 2, 'پالتو زنانه پاییزی', 'womens-autumn-coat', 'پالتو زنانه شیک با طرح پاییزی. جنس ترکیبی پشم و پلی‌استر. مناسب محیط‌های رسمی و نیمه‌رسمی.', 'پالتو زنانه - شیک و گرم', 'SKU-W-001', 2800000, 2520000, 10, 15, '/assets/images/products/product_4_main_1786888967_6fa93370.jpg', '[\"/assets/images/products/product_4_gallery_1_1786888967_967d0f7c.jpg\"]', 0.00, 0, 1, 'autumn', 1, 11, '2026-06-09 15:55:19', '2026-08-16 23:09:42'),
(5, 5, 'تراک‌شوت ورزشی مردانه', 'mens-tracksuit-sport', 'تراک‌شوت کامل مردانه مناسب ورزش و پیاده‌روی. شامل سویشرت و شلوار. جنس کجراه با طرح آستین راه‌راه.', 'تراک‌شوت کامل ورزشی', 'SKU-SP-001', 1800000, 1440000, 20, 25, '/assets/images/products/product_5_main_1786888671_109c82a4.jpg', '[\"/assets/images/products/product_5_gallery_1_1786888671_053c4d38.jpg\", \"/assets/images/products/product_5_gallery_2_1786888671_bf176ee4.jpg\", \"/assets/images/products/product_5_gallery_3_1786888671_51579d40.jpg\"]', 0.00, 0, 1, 'summer', 1, 38, '2026-06-09 15:55:19', '2026-08-16 23:10:11'),
(6, 1, 'شلوار جین اسلیم مردانه', 'mens-slim-jeans', 'شلوار جین مردانه با برش اسلیم فیت. مناسب استفاده روزمره. جنس دنیم با اضافه الاستین.', 'جین اسلیم - راحت و شیک', 'SKU-M-002', 980000, 784000, 20, 59, '/assets/images/products/product_6_main_1786888823_42dd3d81.jpg', '[\"/assets/images/products/product_6_gallery_1_1786888823_a6d4ea56.jpg\", \"/assets/images/products/product_6_gallery_2_1786888823_21f49380.jpg\"]', 5.00, 1, 0, 'all', 1, 91, '2026-06-09 15:55:19', '2026-08-23 23:32:14'),
(7, 3, 'بوت چرم طبیعی زنانه', 'womens-leather-boot', 'بوت زنانه از چرم طبیعی گاو با آستر پارچه‌ای گرم. مناسب فصل سرد. پاشنه ۵ سانتی‌متر.', 'بوت چرم زنانه - گرم و مد روز', 'SKU-S-002', 4200000, 3570000, 15, 12, '/assets/images/products/product_7_main_1786888375_507b02d3.jpg', '[\"/assets/images/products/product_7_gallery_1_1786888375_c9150374.jpg\"]', 0.00, 0, 1, 'winter', 1, 9, '2026-06-09 15:55:19', '2026-08-16 19:07:08'),
(8, 4, 'کیف دستی چرمی مردانه', 'mens-leather-handbag', 'کیف دستی مردانه از چرم مصنوعی با کیفیت بالا. دارای چندین جیب داخلی و قفل امنیتی.', 'کیف چرمی - سبک و کاربردی', 'SKU-A-002', 1500000, 1275000, 15, 34, '/assets/images/products/product_8_main_1786890181_bc506aba.jpg', '[\"/assets/images/products/product_8_gallery_1_1786890181_34601d5c.jpg\", \"/assets/images/products/product_8_gallery_2_1786890181_77c3fa39.jpg\", \"/assets/images/products/product_8_gallery_3_1786890181_a2fe6ff7.jpg\", \"/assets/images/products/product_8_gallery_4_1786890181_ca6cb326.jpg\"]', 4.00, 1, 0, 'winter', 1, 48, '2026-06-09 15:55:19', '2026-08-16 23:10:32'),
(19, 1, 'تی‌شرت ساده مردانه', 'mens-basic-tshirt', 'تی‌شرت ساده مردانه با طراحی مینیمال و پارچه‌ای نرم و راحت. مناسب استفاده روزمره و استایل‌های مختلف.', 'تی‌شرت مردانه - ساده و راحت', 'SKU-M-003', 750000, 637500, 15, 48, '/assets/images/products/product_19_main_1786907200_aeb92cf2.jpg', NULL, 0.00, 0, 1, 'summer', 1, 6, '2026-08-16 21:32:56', '2026-08-23 23:32:02'),
(22, 1, 'سویشرت مردانه کژوال', 'mens-casual-sweatshirt', 'سویشرت مردانه کژوال با طراحی راحت و گرم، مناسب استفاده روزمره و استایل‌های پاییزی.', 'سویشرت مردانه - کژوال و گرم', 'SKU-M-006', 1600000, 1360000, 15, 22, '/assets/images/products/product_22_main_1786907211_ff5f676a.jpg', NULL, 0.00, 0, 1, 'autumn', 1, 3, '2026-08-16 21:32:56', '2026-08-22 09:09:31'),
(23, 1, 'کاپشن مردانه سبک', 'mens-light-jacket', 'کاپشن مردانه سبک با طراحی کاربردی و مناسب برای روزهای سرد. انتخابی مناسب برای استایل روزمره زمستانی.', 'کاپشن مردانه - سبک و گرم', 'SKU-M-007', 2900000, 2465000, 15, 17, '/assets/images/products/product_23_main_1786907386_8704fb05.jpg', '[\"/assets/images/products/product_23_gallery_1_1786907386_4f9ca2d6.jpg\"]', 0.00, 0, 1, 'winter', 1, 4, '2026-08-16 21:32:56', '2026-08-22 09:13:47'),
(27, 2, 'شومیز زنانه کژوال', 'womens-casual-blouse', 'شومیز زنانه کژوال با طراحی ظریف و راحت، مناسب استفاده روزمره و استایل‌های بهاری.', 'شومیز زنانه - کژوال و ظریف', 'SKU-W-002', 1200000, 1020000, 15, 23, '/assets/images/products/product_27_main_1786908050_c815b371.jpg', NULL, 0.00, 0, 1, 'spring', 1, 5, '2026-08-16 21:32:56', '2026-08-23 23:31:53'),
(30, 2, 'کت زنانه مینیمال', 'womens-minimal-blazer', 'کت زنانه مینیمال با طراحی ساده و شیک، مناسب استایل‌های رسمی و نیمه‌رسمی.', 'کت زنانه - مینیمال و شیک', 'SKU-W-005', 2750000, 2337500, 15, 14, '/assets/images/products/product_30_main_1786908061_f7ad0ad9.jpg', '[\"/assets/images/products/product_30_gallery_1_1786908061_475a6811.jpg\"]', 0.00, 0, 1, 'spring', 1, 1, '2026-08-16 21:32:56', '2026-08-18 23:50:51'),
(31, 2, 'بارانی زنانه کلاسیک', 'womens-classic-trench', 'بارانی زنانه کلاسیک با طراحی شیک و کاربردی، مناسب روزهای خنک و بارانی پاییز.', 'بارانی زنانه - کلاسیک و شیک', 'SKU-W-006', 2950000, 2507500, 15, 11, '/assets/images/products/product_31_main_1786908597_2cf6a51e.jpg', NULL, 0.00, 0, 1, 'autumn', 1, 1, '2026-08-16 21:32:56', '2026-08-21 15:42:40'),
(33, 2, 'شلوار پارچه‌ای زنانه', 'womens-wide-leg-pants', 'شلوار پارچه‌ای زنانه با فرم وایدلگ و طراحی راحت، مناسب استایل‌های روزمره و نیمه‌رسمی.', 'شلوار زنانه - وایدلگ و راحت', 'SKU-W-008', 1300000, 1105000, 15, 28, '/assets/images/products/product_33_main_1786908607_be5e61c8.jpg', NULL, 0.00, 0, 1, 'spring', 1, 2, '2026-08-16 21:32:56', '2026-08-21 13:51:12'),
(41, 3, 'نیم‌بوت مردانه چرمی', 'mens-leather-ankle-boots', 'نیم‌بوت مردانه چرمی با طراحی مقاوم و شیک، مناسب فصل زمستان و روزهای سرد.', 'نیم‌بوت مردانه - چرمی و گرم', 'SKU-S-009', 3600000, 3060000, 15, 9, '/assets/images/products/product_41_main_1786908615_506a69bd.jpg', '[\"/assets/images/products/product_41_gallery_1_1786908776_e02b2b29.jpg\", \"/assets/images/products/product_41_gallery_2_1786908776_4965d3f7.jpg\"]', 0.00, 0, 1, 'winter', 1, 4, '2026-08-16 21:32:56', '2026-08-21 13:48:58'),
(48, 4, 'عینک آفتابی کلاسیک', 'classic-sunglasses', 'عینک آفتابی کلاسیک با طراحی ساده و جذاب، مناسب استفاده روزمره و استایل‌های تابستانی.', 'عینک آفتابی - کلاسیک و شیک', 'SKU-A-008', 1300000, 1105000, 15, 30, '/assets/images/products/product_48_main_1786908623_54fb9e0c.jpg', '[\"/assets/images/products/product_48_gallery_1_1786908851_84ed9d37.jpg\"]', 0.00, 0, 1, 'summer', 1, 0, '2026-08-16 21:32:56', '2026-08-16 23:04:11'),
(49, 4, 'شال زنانه بافت', 'womens-knitted-scarf', 'شال زنانه بافت با طراحی گرم و زیبا، مناسب استفاده در روزهای سرد و استایل‌های زمستانی.', 'شال زنانه - بافت و گرم', 'SKU-A-009', 700000, 560000, 20, 38, '/assets/images/products/product_49_main_1786908635_7b0979d4.jpg', '[\"/assets/images/products/product_49_gallery_1_1786908635_de6fe7d5.jpg\"]', 0.00, 0, 1, 'winter', 1, 1, '2026-08-16 21:32:56', '2026-08-16 23:41:30');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `author_name` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `rating` tinyint UNSIGNED NOT NULL COMMENT '1 تا 5',
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci,
  `is_approved` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_product_rev` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `author_name`, `rating`, `title`, `body`, `is_approved`, `created_at`) VALUES
(16, 8, 2, NULL, 4, 'محصول خیلی خوبیه', 'محصول عالی هست واقعا ارزش خرید دارد', 1, '2026-08-14 13:17:38'),
(17, 6, 2, NULL, 5, 'محصول خیلی خوبیه', 'محصول عالی هست واقعا ارزش خرید دارد', 1, '2026-08-14 13:17:56');

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
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci COMMENT 'آدرس کاربر',
  `postal_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL COMMENT 'کد پستی 10 رقمی',
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL COMMENT 'هش رمز عبور با password_hash()',
  `full_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `profile_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL COMMENT 'مسیر عکس پروفایل',
  `preferred_theme` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT 'automatic',
  `job` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL COMMENT 'شغل کاربر',
  `birth_date` date DEFAULT NULL COMMENT 'تاریخ تولد (میلادی)',
  `role` enum('customer','admin','moderator') CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL DEFAULT 'customer',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
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

INSERT INTO `users` (`id`, `username`, `email`, `phone`, `address`, `postal_code`, `password_hash`, `full_name`, `profile_image`, `preferred_theme`, `job`, `birth_date`, `role`, `is_active`, `login_attempts`, `locked_until`, `last_login`, `created_at`, `updated_at`) VALUES
(2, 'mahdi', 'mahdi84m17@gmail.com', '09929954844', 'مشهد رسالت', '9149172740', '$2y$12$EUxezRKyu0omZbrXlP0dn.sLlGDxxbUHn7dANflD5e.N2Izd1h9Aq', 'مهدی قدسی خواه', '/uploads/profiles/profile_2_mahdi_1786625096.jpg', 'summer', 'دانشجو', NULL, 'admin', 1, 0, NULL, '2026-08-23 23:31:37', '2026-06-30 12:59:51', '2026-08-23 23:31:37'),
(3, 'ali', 'ali@gmail.com', 'temp_3', NULL, NULL, '$2y$12$6.6NolqrdCRTJki0zuKERuru6LlcHAQHdw8UECeX1Rxjdma7ndCI.', NULL, NULL, 'automatic', NULL, NULL, 'customer', 1, 0, NULL, '2026-07-17 23:35:34', '2026-07-16 22:13:50', '2026-07-18 00:07:14'),
(4, 'ali2', '', 'temp_4', NULL, NULL, '$2y$12$UWEQfVbOP6CrLnQLXFrG.OIofpW21Hhy3rRdOuspu7imKNN.w3ifu', NULL, NULL, 'automatic', NULL, NULL, 'customer', 1, 0, NULL, NULL, '2026-07-17 23:35:54', '2026-07-18 00:07:14'),
(6, 'user89514957', '', '09929954843', NULL, NULL, '$2y$12$lVHhi1o0GOmbk.icGdKpKuv25RWQSf32DARjF4N85/w.feDLPWC96', NULL, NULL, 'automatic', NULL, NULL, 'customer', 1, 4, NULL, '2026-07-18 00:24:49', '2026-07-18 00:07:54', '2026-07-22 09:35:35');

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

DROP TABLE IF EXISTS `user_sessions`;
CREATE TABLE IF NOT EXISTS `user_sessions` (
  `id` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL COMMENT 'توکن امن',
  `user_id` int UNSIGNED NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `user_agent` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

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
