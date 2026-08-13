<div class="admin-table">
    <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: #1e293b;">
            لیست محصولات (<?= count($products) ?> محصول)
        </h3>
        <button class="btn btn-primary btn-sm" disabled>افزودن محصول جدید</button>
    </div>
    
    <!-- جدول محصولات -->
    <div style="overflow-x: auto;">
        <table style="min-width: 1800px;">
            <thead>
                <tr>
                    <th>شناسه</th>
                    <th>تصویر</th>
                    <th>نام محصول</th>
                    <th>دسته‌بندی</th>
                    <th>کد SKU</th>
                    <th>قیمت اصلی</th>
                    <th>قیمت فروش</th>
                    <th>درصد تخفیف</th>
                    <th>موجودی</th>
                    <th>فصل</th>
                    <th>امتیاز</th>
                    <th>بازدید</th>
                    <th>وضعیت</th>
                    <th>تاریخ ایجاد</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <tr style="cursor: pointer;" onclick="toggleProductDetails(<?= $product['id'] ?>)">
                            <td style="font-weight: 600; color: #4f46e5;">#<?= $product['id'] ?></td>
                            <td>
                                <?php if (!empty($product['main_image'])): ?>
                                    <img src="<?= BASE_URL . $product['main_image'] ?>" 
                                         alt="<?= Security::e($product['name']) ?>" 
                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                                <?php else: ?>
                                    <div style="width: 50px; height: 50px; background: #f1f5f9; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                                        📦
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td style="max-width: 250px;">
                                <div style="font-weight: 600; color: #1e293b;">
                                    <?= Security::e($product['name']) ?>
                                </div>
                                <div style="font-size: 0.75rem; color: #94a3b8;">
                                    <?= Security::e($product['slug']) ?>
                                </div>
                            </td>
                            <td>
                                <?php if (!empty($product['category_name'])): ?>
                                    <div style="font-weight: 600; color: #1e293b;">
                                        <?= Security::e($product['category_name']) ?>
                                    </div>
                                    <div style="font-size: 0.75rem; color: #94a3b8;">
                                        دسته #<?= $product['category_id'] ?>
                                    </div>
                                <?php else: ?>
                                    <span style="color: #94a3b8;">-</span>
                                <?php endif; ?>
                            </td>
                            <td style="font-family: monospace; font-size: 0.875rem; color: #64748b;">
                                <?= Security::e($product['sku'] ?? '-') ?>
                            </td>
                            <td style="font-weight: 600;">
                                <?= number_format($product['price'] ?? 0) ?> ت
                            </td>
                            <td style="font-weight: 600;">
                                <?php 
                                $salePrice = $product['sale_price'] ?? 0;
                                if ($salePrice > 0): ?>
                                    <span style="color: #dc2626;"><?= number_format($salePrice) ?> ت</span>
                                <?php else: ?>
                                    <span style="color: #94a3b8;">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                $discountPct = $product['discount_pct'] ?? 0;
                                if ($discountPct > 0): ?>
                                    <span class="badge badge-danger"><?= $discountPct ?>٪</span>
                                <?php else: ?>
                                    <span style="color: #94a3b8;">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                $stockQty = $product['stock_qty'] ?? 0;
                                if ($stockQty > 10): ?>
                                    <span class="badge badge-success"><?= $stockQty ?> عدد</span>
                                <?php elseif ($stockQty > 0): ?>
                                    <span class="badge badge-warning"><?= $stockQty ?> عدد</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">ناموجود</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $seasonLabels = [
                                    'spring' => ['label' => '🌸 بهار', 'class' => 'info'],
                                    'summer' => ['label' => '☀️ تابستان', 'class' => 'warning'],
                                    'autumn' => ['label' => '🍂 پاییز', 'class' => 'danger'],
                                    'winter' => ['label' => '❄️ زمستان', 'class' => 'info'],
                                    'all' => ['label' => '🌍 همه فصول', 'class' => 'success']
                                ];
                                $seasonValue = $product['season'] ?? 'all';
                                $season = $seasonLabels[$seasonValue] ?? ['label' => $seasonValue, 'class' => 'info'];
                                ?>
                                <span class="badge badge-<?= $season['class'] ?>" style="white-space: nowrap;">
                                    <?= $season['label'] ?>
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.25rem; white-space: nowrap;">
                                    <span style="color: #fbbf24;">★</span>
                                    <span style="font-weight: 600;"><?= $product['rating_avg'] ?? 0 ?></span>
                                    <span style="font-size: 0.75rem; color: #94a3b8;">(<?= $product['rating_count'] ?? 0 ?>)</span>
                                </div>
                            </td>
                            <td>
                                <span style="color: #64748b; font-size: 0.875rem;">
                                    👁️ <?= number_format($product['views'] ?? 0) ?>
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                    <?php if ($product['is_active'] ?? 0): ?>
                                        <span class="badge badge-success">فعال</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">غیرفعال</span>
                                    <?php endif; ?>
                                    <?php if ($product['is_featured'] ?? 0): ?>
                                        <span class="badge badge-warning">⭐ ویژه</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td style="font-size: 0.875rem; color: #64748b; white-space: nowrap;">
                                <?= jdate('Y/m/d', strtotime($product['created_at'])) ?>
                            </td>
                        </tr>
                        
                        <!-- ردیف جزئیات کامل محصول (مخفی - کلیک کنید برای نمایش) -->
                        <tr id="details-<?= $product['id'] ?>" style="display: none; background: #f8fafc;">
                            <td colspan="14" style="padding: 2rem;">
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                                    <!-- توضیحات کوتاه -->
                                    <div>
                                        <h4 style="font-size: 0.875rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">
                                            📝 توضیحات کوتاه
                                        </h4>
                                        <p style="font-size: 0.875rem; color: #64748b; margin: 0; line-height: 1.6;">
                                            <?= Security::e($product['short_desc'] ?? '-') ?>
                                        </p>
                                    </div>
                                    
                                    <!-- توضیحات کامل -->
                                    <div style="grid-column: span 2;">
                                        <h4 style="font-size: 0.875rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">
                                            📋 توضیحات کامل
                                        </h4>
                                        <p style="font-size: 0.875rem; color: #64748b; margin: 0; line-height: 1.6;">
                                            <?= nl2br(Security::e($product['description'] ?? '-')) ?>
                                        </p>
                                    </div>
                                    
                                    <!-- اطلاعات اضافی -->
                                    <div>
                                        <h4 style="font-size: 0.875rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">
                                            ℹ️ اطلاعات بیشتر
                                        </h4>
                                        <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.875rem; color: #64748b;">
                                            <li style="padding: 0.25rem 0;">
                                                <strong>آخرین به‌روزرسانی:</strong>
                                                <?= !empty($product['updated_at']) ? jdate('Y/m/d H:i', strtotime($product['updated_at'])) : 'هرگز' ?>
                                            </li>
                                            <li style="padding: 0.25rem 0;">
                                                <strong>گالری تصاویر:</strong>
                                                <?php 
                                                $gallery = $product['gallery'] ?? null;
                                                if ($gallery && $gallery !== 'null') {
                                                    $galleryArray = json_decode($gallery, true);
                                                    echo is_array($galleryArray) ? count($galleryArray) . ' تصویر' : 'ندارد';
                                                } else {
                                                    echo 'ندارد';
                                                }
                                                ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="14" style="text-align: center; color: #94a3b8; padding: 3rem;">
                            هیچ محصولی یافت نشد
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleProductDetails(productId) {
    const detailsRow = document.getElementById('details-' + productId);
    if (detailsRow) {
        detailsRow.style.display = detailsRow.style.display === 'none' ? 'table-row' : 'none';
    }
}
</script>

<style>
    .admin-table tbody tr:not([id^="details-"]):hover {
        background: #f1f5f9 !important;
    }
    
    .admin-table tbody tr[id^="details-"] {
        background: #f8fafc !important;
    }
</style>
