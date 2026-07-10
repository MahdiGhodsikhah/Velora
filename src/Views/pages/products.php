<?php
$base = defined('BASE_URL') ? BASE_URL : '';
?>

<main id="main-content" class="products-page">
    <div class="page-hero-small">
        <div class="page-hero-inner">
            <h1><i class="fas fa-shopping-bag" aria-hidden="true"></i> محصولات</h1>
            <nav aria-label="مسیر صفحه" class="breadcrumb-nav">
                <ol class="breadcrumb">
                    <li><a href="<?= $base ?>/">خانه</a></li>
                    <li aria-current="page">محصولات</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="products-layout">
        <!-- سایدبار فیلتر -->
        <aside class="filter-sidebar" aria-label="فیلتر محصولات">
            <!-- فیلتر دسته‌بندی -->
            <div class="filter-box">
                <h3 class="filter-title"><i class="fas fa-filter" aria-hidden="true"></i> دسته‌بندی</h3>
                <ul class="filter-cats">
                    <li>
                        <a href="<?= $base ?>/products"
                           class="<?= (!isset($_GET['cat']) && !isset($_GET['season'])) ? 'active' : '' ?>">
                            <i class="fas fa-th"></i> همه محصولات
                        </a>
                    </li>
                    <?php foreach (($categories ?? []) as $cat): ?>
                    <li>
                        <a href="<?= $base ?>/products?cat=<?= (int)$cat['id'] ?>"
                           class="<?= (isset($_GET['cat']) && (int)$_GET['cat'] === (int)$cat['id']) ? 'active' : '' ?>">
                            <?php
                            $catIcons = [
                                1 => 'fas fa-tshirt',
                                2 => 'fas fa-female', 
                                3 => 'fas fa-shoe-prints',
                                4 => 'fas fa-gem',
                                5 => 'fas fa-dumbbell'
                            ];
                            $icon = $catIcons[$cat['id']] ?? 'fas fa-tag';
                            ?>
                            <i class="<?= $icon ?>"></i> <?= Security::e($cat['name']) ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- فیلتر فصلی -->
            <div class="filter-box" style="margin-top: 20px;">
                <h3 class="filter-title"><i class="fas fa-calendar-alt" aria-hidden="true"></i> فیلتر فصلی</h3>
                <ul class="filter-cats filter-seasons">
                    <li>
                        <a href="<?= $base ?>/products?season=autumn"
                           class="season-autumn <?= (isset($_GET['season']) && $_GET['season'] === 'autumn') ? 'active' : '' ?>">
                            <i class="fas fa-leaf"></i> <span>پاییزی</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= $base ?>/products?season=spring"
                           class="season-spring <?= (isset($_GET['season']) && $_GET['season'] === 'spring') ? 'active' : '' ?>">
                            <i class="fas fa-seedling"></i> <span>بهاری</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= $base ?>/products?season=summer"
                           class="season-summer <?= (isset($_GET['season']) && $_GET['season'] === 'summer') ? 'active' : '' ?>">
                            <i class="fas fa-sun"></i> <span>تابستانی</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= $base ?>/products?season=winter"
                           class="season-winter <?= (isset($_GET['season']) && $_GET['season'] === 'winter') ? 'active' : '' ?>">
                            <i class="fas fa-snowflake"></i> <span>زمستانی</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- گرید محصولات -->
        <div class="products-content">
            <div class="products-toolbar">
                <span class="results-count">
                    <?= number_format($total ?? 0) ?> محصول یافت شد
                </span>
            </div>

            <?php if (empty($products)): ?>
            <div class="no-products-full" role="status">
                <i class="fas fa-box-open" aria-hidden="true"></i>
                <p>هیچ محصولی در این دسته‌بندی وجود ندارد.</p>
                <a href="<?= $base ?>/products" class="btn-back">مشاهده همه محصولات</a>
            </div>
            <?php else: ?>

            <div class="products-grid" role="list" aria-label="لیست محصولات">
                <?php foreach ($products as $product): ?>
                <div role="listitem">
                    <?php require BASE_PATH . '/src/Views/partials/product-card.php'; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- صفحه‌بندی -->
            <?php if (($totalPages ?? 1) > 1): ?>
            <nav class="pagination-nav" aria-label="صفحه‌بندی">
                <?php
                $catParam = isset($_GET['cat']) ? '&cat=' . (int)$_GET['cat'] : '';
                $seasonParam = isset($_GET['season']) ? '&season=' . urlencode($_GET['season']) : '';
                $params = $catParam . $seasonParam;
                
                for ($p = 1; $p <= ($totalPages ?? 1); $p++): ?>
                <a href="<?= $base ?>/products?page=<?= $p . $params ?>"
                   class="page-btn <?= ($page ?? 1) === $p ? 'active' : '' ?>"
                   aria-label="صفحه <?= $p ?>"
                   <?= ($page ?? 1) === $p ? 'aria-current="page"' : '' ?>>
                    <?= $p ?>
                </a>
                <?php endfor; ?>
            </nav>
            <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>
</main>
