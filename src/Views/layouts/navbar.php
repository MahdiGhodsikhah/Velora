<?php
$base       = defined('BASE_URL') ? BASE_URL : '';
$loggedIn   = !empty($_SESSION['logged_in']);
$username   = Security::e($_SESSION['username'] ?? '');
$cartCount  = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>
<nav class="main-navbar" id="mainNavbar" role="navigation" aria-label="ناوبری اصلی">
    <div class="navbar-inner">
        <!-- لوگو -->
        <a href="<?= $base ?>/" class="navbar-logo" aria-label="صفحه اصلی">
            <span class="logo-icon"><i class="fas fa-leaf" aria-hidden="true"></i></span>
            <span class="logo-text">Velora<span class="logo-accent"> Shop</span></span>
        </a>

        <!-- منوی اصلی -->
        <ul class="navbar-menu" role="menubar">
            <li role="none"><a href="<?= $base ?>/" role="menuitem"><i class="fas fa-home" aria-hidden="true"></i> خانه</a></li>
            <li role="none"><a href="<?= $base ?>/products" role="menuitem"><i class="fas fa-shopping-bag" aria-hidden="true"></i> محصولات</a></li>
            <li class="has-dropdown" role="none">
                <a href="#" role="menuitem" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-tags" aria-hidden="true"></i> دسته‌بندی <i class="fas fa-chevron-down dropdown-arrow" aria-hidden="true"></i>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li role="none"><a href="<?= $base ?>/products?cat=1" role="menuitem"><i class="fas fa-tshirt"></i> پوشاک مردانه</a></li>
                    <li role="none"><a href="<?= $base ?>/products?cat=2" role="menuitem"><i class="fas fa-female"></i> پوشاک زنانه</a></li>
                    <li role="none"><a href="<?= $base ?>/products?cat=3" role="menuitem"><i class="fas fa-shoe-prints"></i> کفش و کتونی</a></li>
                    <li role="none"><a href="<?= $base ?>/products?cat=4" role="menuitem"><i class="fas fa-gem"></i> اکسسوری</a></li>
                    <li role="none"><a href="<?= $base ?>/products?cat=5" role="menuitem"><i class="fas fa-dumbbell"></i> ورزشی</a></li>
                </ul>
            </li>
            <li class="has-dropdown" role="none">
                <a href="#" role="menuitem" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-calendar-alt" aria-hidden="true"></i> کلکسیون فصلی <i class="fas fa-chevron-down dropdown-arrow" aria-hidden="true"></i>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li role="none"><a href="<?= $base ?>/products?season=autumn" role="menuitem"><i class="fas fa-leaf"></i> محصولات پاییزی</a></li>
                    <li role="none"><a href="<?= $base ?>/products?season=spring" role="menuitem"><i class="fas fa-seedling"></i> محصولات بهاری</a></li>
                    <li role="none"><a href="<?= $base ?>/products?season=summer" role="menuitem"><i class="fas fa-sun"></i> محصولات تابستانی</a></li>
                    <li role="none"><a href="<?= $base ?>/products?season=winter" role="menuitem"><i class="fas fa-snowflake"></i> محصولات زمستانی</a></li>
                </ul>
            </li>
            <li role="none"><a href="<?= $base ?>/about" role="menuitem"><i class="fas fa-info-circle" aria-hidden="true"></i> درباره ما</a></li>
        </ul>

        <!-- بخش راست نوار ناوبری -->
        <div class="navbar-actions">
            <!-- جستجو -->
            <div class="search-wrap">
                <button class="icon-btn search-toggle" aria-label="جستجو" aria-expanded="false">
                    <i class="fas fa-search" aria-hidden="true"></i>
                </button>
                <form class="search-form" action="<?= $base ?>/products" method="GET" role="search" aria-label="جستجوی محصول">
                    <input type="search" name="q" class="search-input" placeholder="جستجوی محصول..." maxlength="100" aria-label="عبارت جستجو">
                    <button type="submit" aria-label="ارسال جستجو"><i class="fas fa-search" aria-hidden="true"></i></button>
                </form>
            </div>

            <!-- سبد خرید -->
            <a href="<?= $base ?>/cart" class="icon-btn cart-btn" aria-label="سبد خرید">
                <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                <?php if ($cartCount > 0): ?>
                <span class="badge-count" aria-label="<?= $cartCount ?> مورد در سبد"><?= $cartCount ?></span>
                <?php endif; ?>
            </a>

            <!-- کاربر -->
            <?php if ($loggedIn): ?>
            <?php
                // دریافت اطلاعات کاربر برای نمایش عکس
                $userModel = new UserModel();
                $currentUser = $userModel->getById($_SESSION['user_id']);
                $profileImage = !empty($currentUser['profile_image']) ? $currentUser['profile_image'] : '';
            ?>
            <div class="user-menu has-dropdown">
                <button class="icon-btn user-btn" aria-label="منوی کاربر" aria-expanded="false">
                    <?php if ($profileImage): ?>
                        <img src="<?= $base . Security::e($profileImage) ?>" 
                             alt="<?= $username ?>" 
                             class="user-avatar-nav"
                             style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; margin-left: 8px;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                        <i class="fas fa-user-circle" aria-hidden="true" style="display: none;"></i>
                    <?php else: ?>
                        <i class="fas fa-user-circle" aria-hidden="true"></i>
                    <?php endif; ?>
                    <span class="user-name-nav"><?= $username ?></span>
                    <i class="fas fa-chevron-down dropdown-arrow" aria-hidden="true"></i>
                </button>
                <ul class="dropdown-menu dropdown-user" role="menu">
                    <li role="none">
                        <a href="<?= $base ?>/dashboard" role="menuitem">
                            <i class="fas fa-th-large" aria-hidden="true"></i> پنل کاربری
                        </a>
                    </li>
                    <li role="none">
                        <a href="<?= $base ?>/profile" role="menuitem">
                            <i class="fas fa-user-edit" aria-hidden="true"></i> ویرایش حساب
                        </a>
                    </li>
                    <li role="none">
                        <a href="<?= $base ?>/wishlist" role="menuitem">
                            <i class="fas fa-heart" aria-hidden="true"></i> علاقه‌مندی‌ها
                        </a>
                    </li>
                    <li role="none">
                        <a href="<?= $base ?>/orders" role="menuitem">
                            <i class="fas fa-shopping-bag" aria-hidden="true"></i> سفارش‌ها
                        </a>
                    </li>
                    <li class="menu-divider" role="separator"></li>
                    <li role="none">
                        <a href="<?= $base ?>/logout" role="menuitem" class="logout-link">
                            <i class="fas fa-sign-out-alt" aria-hidden="true"></i> خروج از حساب
                        </a>
                    </li>
                </ul>
            </div>
            <?php else: ?>
            <a href="<?= $base ?>/login" class="btn-nav-login">
                <i class="fas fa-sign-in-alt" aria-hidden="true"></i> ورود
            </a>
            <?php endif; ?>

            <!-- همبرگر موبایل -->
            <button class="hamburger" id="hamburgerBtn" aria-label="باز کردن منو" aria-expanded="false" aria-controls="mobileMenu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>

    <!-- منوی موبایل -->
    <div class="mobile-menu" id="mobileMenu" aria-hidden="true">
        <ul>
            <li><a href="<?= $base ?>/"><i class="fas fa-home"></i> خانه</a></li>
            <li><a href="<?= $base ?>/products"><i class="fas fa-shopping-bag"></i> محصولات</a></li>
            <li><a href="<?= $base ?>/products?cat=1"><i class="fas fa-tshirt"></i> پوشاک مردانه</a></li>
            <li><a href="<?= $base ?>/products?cat=2"><i class="fas fa-female"></i> پوشاک زنانه</a></li>
            <li><a href="<?= $base ?>/products?cat=3"><i class="fas fa-shoe-prints"></i> کفش و کتونی</a></li>
            <li><a href="<?= $base ?>/products?cat=4"><i class="fas fa-gem"></i> اکسسوری</a></li>
            <li><a href="<?= $base ?>/products?season=autumn"><i class="fas fa-leaf"></i> محصولات پاییزی</a></li>
            <li><a href="<?= $base ?>/products?season=spring"><i class="fas fa-seedling"></i> محصولات بهاری</a></li>
            <li><a href="<?= $base ?>/products?season=summer"><i class="fas fa-sun"></i> محصولات تابستانی</a></li>
            <li><a href="<?= $base ?>/products?season=winter"><i class="fas fa-snowflake"></i> محصولات زمستانی</a></li>
            <li><a href="<?= $base ?>/about"><i class="fas fa-info-circle"></i> درباره ما</a></li>
            <?php if ($loggedIn): ?>
            <li><a href="<?= $base ?>/dashboard"><i class="fas fa-th-large"></i> پنل کاربری</a></li>
            <li><a href="<?= $base ?>/profile"><i class="fas fa-user-edit"></i> ویرایش حساب</a></li>
            <li><a href="<?= $base ?>/wishlist"><i class="fas fa-heart"></i> علاقه‌مندی‌ها</a></li>
            <li><a href="<?= $base ?>/orders"><i class="fas fa-shopping-bag"></i> سفارش‌ها</a></li>
            <li><a href="<?= $base ?>/logout"><i class="fas fa-sign-out-alt"></i> خروج از حساب</a></li>
            <?php else: ?>
            <li><a href="<?= $base ?>/login"><i class="fas fa-sign-in-alt"></i> ورود</a></li>
            <li><a href="<?= $base ?>/register"><i class="fas fa-user-plus"></i> ثبت‌نام</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
