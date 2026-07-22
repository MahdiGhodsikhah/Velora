<?php
/**
 * داشبورد اصلی پنل مدیریت
 */

require_once BASE_PATH . '/src/Views/layouts/header.php';
?>

<style>
.admin-dashboard {
    padding: 30px;
    background: #f5f7fa;
    min-height: 100vh;
}
.admin-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}
.stat-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.12);
}
.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    margin-bottom: 15px;
}
.stat-card h3 {
    font-size: 2rem;
    margin: 10px 0;
    color: #333;
}
.stat-card p {
    color: #666;
    font-size: 0.95rem;
}
.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin: 30px 0;
}
.action-btn {
    background: white;
    border: 2px solid #e9ecef;
    padding: 20px;
    border-radius: 12px;
    text-align: center;
    text-decoration: none;
    color: #333;
    transition: all 0.3s ease;
    display: block;
}
.action-btn:hover {
    border-color: #667eea;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
}
.action-btn i {
    font-size: 2.5rem;
    margin-bottom: 10px;
    display: block;
}
.season-chart {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
</style>

<div class="admin-dashboard">
    
    <div class="admin-header">
        <h1>پنل مدیریت Velora</h1>
        <p>خوش آمدید، <?= htmlspecialchars($_SESSION['username'] ?? 'ادمین') ?></p>
    </div>

    <?php if (isset($_SESSION['alert'])): ?>
        <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['alert']['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>

    <!-- آمار کلی -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <i class="fas fa-box" style="color: white;"></i>
            </div>
            <h3><?= number_format($stats['total_products']) ?></h3>
            <p>محصولات</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                <i class="fas fa-shopping-cart" style="color: white;"></i>
            </div>
            <h3><?= number_format($stats['total_orders']) ?></h3>
            <p>سفارشات</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                <i class="fas fa-users" style="color: white;"></i>
            </div>
            <h3><?= number_format($stats['total_users']) ?></h3>
            <p>کاربران</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                <i class="fas fa-dollar-sign" style="color: white;"></i>
            </div>
            <h3><?= number_format($stats['total_revenue']) ?></h3>
            <p>درآمد (تومان)</p>
        </div>
    </div>

    <!-- دسترسی سریع -->
    <h2 style="margin: 30px 0 20px; color: #333;">دسترسی سریع</h2>
    <div class="quick-actions">
        <a href="<?= BASE_URL ?>/admin/products/add" class="action-btn">
            <i class="fas fa-plus-circle" style="color: #667eea;"></i>
            <strong>افزودن محصول</strong>
        </a>
        <a href="<?= BASE_URL ?>/admin/products" class="action-btn">
            <i class="fas fa-box-open" style="color: #f093fb;"></i>
            <strong>مدیریت محصولات</strong>
        </a>
        <a href="<?= BASE_URL ?>/admin/theme-settings" class="action-btn">
            <i class="fas fa-palette" style="color: #43e97b;"></i>
            <strong>تنظیمات تم</strong>
        </a>
        <a href="<?= BASE_URL ?>/update-products-season.php" class="action-btn">
            <i class="fas fa-calendar-alt" style="color: #f5576c;"></i>
            <strong>مدیریت فصل محصولات</strong>
        </a>
    </div>

    <!-- آمار محصولات به تفکیک فصل -->
    <div class="season-chart">
        <h3 style="margin-bottom: 20px; color: #333;">محصولات به تفکیک فصل</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>فصل</th>
                    <th>تعداد محصولات</th>
                    <th>درصد</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $seasonNames = [
                    'spring' => '🌸 بهار',
                    'summer' => '☀️ تابستان',
                    'autumn' => '🍂 پاییز',
                    'winter' => '❄️ زمستان',
                    'all' => '🌍 همه فصول'
                ];
                $total = array_sum($stats['products_by_season']);
                foreach ($stats['products_by_season'] as $season => $count):
                    $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                ?>
                <tr>
                    <td><strong><?= $seasonNames[$season] ?? $season ?></strong></td>
                    <td><?= $count ?></td>
                    <td>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar" style="width: <?= $percentage ?>%;">
                                <?= $percentage ?>%
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<?php require_once BASE_PATH . '/src/Views/layouts/footer.php'; ?>
