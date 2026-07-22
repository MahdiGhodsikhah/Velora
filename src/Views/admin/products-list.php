<?php
/**
 * لیست محصولات در پنل ادمین
 */

require_once BASE_PATH . '/src/Views/layouts/header.php';
?>

<style>
.admin-products {
    padding: 30px;
    background: #f5f7fa;
    min-height: 100vh;
}
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}
.products-table {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    overflow: hidden;
}
.products-table table {
    width: 100%;
    border-collapse: collapse;
}
.products-table th {
    background: #f8f9fa;
    padding: 15px;
    text-align: right;
    font-weight: bold;
    border-bottom: 2px solid #e9ecef;
}
.products-table td {
    padding: 15px;
    border-bottom: 1px solid #e9ecef;
}
.products-table tr:hover {
    background: #f8f9fa;
}
.product-img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
}
.badge-season {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: bold;
}
.season-spring { background: #dcfce7; color: #166534; }
.season-summer { background: #fef3c7; color: #92400e; }
.season-autumn { background: #fed7aa; color: #9a3412; }
.season-winter { background: #dbeafe; color: #1e3a8a; }
.season-all { background: #f3f4f6; color: #374151; }
.action-btns {
    display: flex;
    gap: 5px;
}
.btn-sm {
    padding: 5px 10px;
    font-size: 0.85rem;
}
</style>

<div class="admin-products">
    
    <div class="page-header">
        <h1>مدیریت محصولات</h1>
        <a href="<?= BASE_URL ?>/admin/products/add" class="btn btn-primary">
            <i class="fas fa-plus ms-1"></i> افزودن محصول جدید
        </a>
    </div>

    <?php if (isset($_SESSION['alert'])): ?>
        <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['alert']['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>

    <div class="products-table">
        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">شماره</th>
                    <th style="width: 80px;">تصویر</th>
                    <th>نام محصول</th>
                    <th style="width: 120px;">قیمت</th>
                    <th style="width: 100px;">موجودی</th>
                    <th style="width: 120px;">فصل</th>
                    <th style="width: 100px;">وضعیت</th>
                    <th style="width: 150px;">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px;">
                            محصولی یافت نشد
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= $product['id'] ?></td>
                        <td>
                            <img src="<?= BASE_URL . htmlspecialchars($product['main_image']) ?>" 
                                 alt="<?= htmlspecialchars($product['name']) ?>"
                                 class="product-img"
                                 onerror="this.src='<?= BASE_URL ?>/assets/images/products/no-image.jpg'">
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($product['name']) ?></strong><br>
                            <small style="color: #666;"><?= htmlspecialchars($product['sku'] ?? '') ?></small>
                        </td>
                        <td>
                            <?php if ($product['sale_price']): ?>
                                <del style="color: #999;"><?= number_format($product['price']) ?></del><br>
                                <strong style="color: #dc2626;"><?= number_format($product['sale_price']) ?> تومان</strong>
                            <?php else: ?>
                                <strong><?= number_format($product['price']) ?> تومان</strong>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($product['stock_qty'] > 10): ?>
                                <span class="badge bg-success"><?= $product['stock_qty'] ?></span>
                            <?php elseif ($product['stock_qty'] > 0): ?>
                                <span class="badge bg-warning"><?= $product['stock_qty'] ?></span>
                            <?php else: ?>
                                <span class="badge bg-danger">ناموجود</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $seasonIcons = [
                                'spring' => '🌸 بهار',
                                'summer' => '☀️ تابستان',
                                'autumn' => '🍂 پاییز',
                                'winter' => '❄️ زمستان',
                                'all' => '🌍 همه'
                            ];
                            $season = $product['season'] ?? 'all';
                            ?>
                            <span class="badge-season season-<?= $season ?>">
                                <?= $seasonIcons[$season] ?? $season ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($product['is_active']): ?>
                                <span class="badge bg-success">فعال</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">غیرفعال</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-btns">
                                <a href="<?= BASE_URL ?>/admin/products/edit/<?= $product['id'] ?>" 
                                   class="btn btn-sm btn-primary" title="ویرایش">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/products/<?= $product['slug'] ?>" 
                                   class="btn btn-sm btn-info" title="مشاهده" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button onclick="deleteProduct(<?= $product['id'] ?>, '<?= htmlspecialchars($product['name']) ?>')"
                                        class="btn btn-sm btn-danger" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- صفحه‌بندی -->
    <?php if ($totalPages > 1): ?>
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>

</div>

<script>
function deleteProduct(id, name) {
    if (confirm('آیا مطمئن هستید که می‌خواهید محصول "' + name + '" را حذف کنید؟\nاین عمل قابل بازگشت نیست.')) {
        window.location.href = '<?= BASE_URL ?>/admin/products/delete/' + id;
    }
}
</script>

<?php require_once BASE_PATH . '/src/Views/layouts/footer.php'; ?>
