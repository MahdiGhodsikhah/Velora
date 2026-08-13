<?php if (isset($_SESSION['admin_success'])): ?>
    <div class="alert alert-success" style="margin-bottom: 1.5rem;">
        <?= $_SESSION['admin_success'] ?>
        <?php unset($_SESSION['admin_success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['admin_error'])): ?>
    <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
        <?= $_SESSION['admin_error'] ?>
        <?php unset($_SESSION['admin_error']); ?>
    </div>
<?php endif; ?>

<div class="admin-table">
    <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: #1e293b;">
                لیست محصولات
            </h3>
            <p style="margin: 0.25rem 0 0; font-size: 0.875rem; color: #64748b;">
                کل: <?= number_format($totalProducts) ?> محصول | 
                صفحه <?= $page ?> از <?= $totalPages ?>
            </p>
        </div>
        
        <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <!-- انتخاب تعداد نمایش -->
            <form method="GET" action="<?= BASE_URL ?>/admin/products" style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="hidden" name="page" value="1">
                <label for="per_page" style="font-size: 0.875rem; color: #64748b; white-space: nowrap;">نمایش:</label>
                <select id="per_page" 
                        name="per_page" 
                        onchange="this.form.submit()"
                        style="padding: 0.375rem 0.5rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.875rem; cursor: pointer;">
                    <option value="5" <?= $perPage == 5 ? 'selected' : '' ?>>5</option>
                    <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10</option>
                    <option value="20" <?= $perPage == 20 ? 'selected' : '' ?>>20</option>
                    <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50</option>
                </select>
            </form>
            
            <a href="<?= BASE_URL ?>/admin/products/create" class="btn btn-primary btn-sm">
                ➕ افزودن محصول جدید
            </a>
        </div>
    </div>
    
    <!-- جدول محصولات -->
    <div style="overflow-x: auto; position: relative;">
        <table style="min-width: 1800px;">
            <thead style="position: sticky; top: 0; z-index: 10; background: #f8fafc;">
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
                    <th>عملیات</th>
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
                            <td style="white-space: nowrap;">
                                <a href="<?= BASE_URL ?>/admin/products/edit/<?= $product['id'] ?>" 
                                   class="btn btn-sm btn-warning" 
                                   style="padding: 0.375rem 0.75rem; font-size: 0.875rem; text-decoration: none; display: inline-block; margin-left: 0.25rem;"
                                   onclick="event.stopPropagation();">
                                    ✏️ ویرایش
                                </a>
                                <button onclick="confirmDelete(<?= $product['id'] ?>, '<?= Security::e($product['name']) ?>'); event.stopPropagation();" 
                                        class="btn btn-sm btn-danger" 
                                        style="padding: 0.375rem 0.75rem; font-size: 0.875rem;">
                                    🗑️ حذف
                                </button>
                            </td>
                        </tr>
                        
                        <!-- ردیف جزئیات کامل محصول (مخفی - کلیک کنید برای نمایش) -->
                        <tr id="details-<?= $product['id'] ?>" style="display: none; background: #f8fafc;">
                            <td colspan="15" style="padding: 2rem;">
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
                        <td colspan="15" style="text-align: center; color: #94a3b8; padding: 3rem;">
                            هیچ محصولی یافت نشد
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- صفحه‌بندی -->
<?php if ($totalPages > 1): ?>
    <div style="padding: 1.5rem; background: white; border-radius: 12px; margin-top: 1.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
        <nav aria-label="صفحه‌بندی محصولات">
            <ul style="display: flex; justify-content: center; align-items: center; gap: 0.5rem; list-style: none; padding: 0; margin: 0; flex-wrap: wrap;">
                
                <!-- دکمه صفحه اول -->
                <?php if ($page > 1): ?>
                    <li>
                        <a href="<?= BASE_URL ?>/admin/products?page=1&per_page=<?= $perPage ?>" 
                           style="display: inline-block; padding: 0.5rem 0.875rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #475569; text-decoration: none; font-size: 0.875rem; transition: all 0.2s;">
                            ⏮️ اول
                        </a>
                    </li>
                <?php endif; ?>
                
                <!-- دکمه قبلی -->
                <?php if ($page > 1): ?>
                    <li>
                        <a href="<?= BASE_URL ?>/admin/products?page=<?= $page - 1 ?>&per_page=<?= $perPage ?>" 
                           style="display: inline-block; padding: 0.5rem 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #475569; text-decoration: none; font-size: 0.875rem; font-weight: 600; transition: all 0.2s;">
                            ← قبلی
                        </a>
                    </li>
                <?php else: ?>
                    <li>
                        <span style="display: inline-block; padding: 0.5rem 1rem; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 6px; color: #cbd5e1; font-size: 0.875rem; font-weight: 600; cursor: not-allowed;">
                            ← قبلی
                        </span>
                    </li>
                <?php endif; ?>
                
                <!-- شماره صفحات -->
                <?php
                // محاسبه محدوده صفحات برای نمایش
                $rangeStart = max(1, $page - 2);
                $rangeEnd = min($totalPages, $page + 2);
                
                // اگر در ابتدا هستیم، محدوده را بیشتر کن
                if ($page <= 3) {
                    $rangeEnd = min($totalPages, 5);
                }
                
                // اگر در انتها هستیم، محدوده را از اول بیشتر کن
                if ($page > $totalPages - 3) {
                    $rangeStart = max(1, $totalPages - 4);
                }
                
                // نمایش ... اگر صفحه اول نمایش داده نشده
                if ($rangeStart > 1): ?>
                    <li>
                        <span style="display: inline-block; padding: 0.5rem 0.875rem; color: #94a3b8;">...</span>
                    </li>
                <?php endif; ?>
                
                <?php for ($i = $rangeStart; $i <= $rangeEnd; $i++): ?>
                    <li>
                        <?php if ($i == $page): ?>
                            <span style="display: inline-block; padding: 0.5rem 0.875rem; background: #4f46e5; color: white; border-radius: 6px; font-weight: 600; font-size: 0.875rem;">
                                <?= $i ?>
                            </span>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/admin/products?page=<?= $i ?>&per_page=<?= $perPage ?>" 
                               style="display: inline-block; padding: 0.5rem 0.875rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #475569; text-decoration: none; font-size: 0.875rem; transition: all 0.2s;">
                                <?= $i ?>
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endfor; ?>
                
                <!-- نمایش ... اگر صفحه آخر نمایش داده نشده -->
                <?php if ($rangeEnd < $totalPages): ?>
                    <li>
                        <span style="display: inline-block; padding: 0.5rem 0.875rem; color: #94a3b8;">...</span>
                    </li>
                <?php endif; ?>
                
                <!-- دکمه بعدی -->
                <?php if ($page < $totalPages): ?>
                    <li>
                        <a href="<?= BASE_URL ?>/admin/products?page=<?= $page + 1 ?>&per_page=<?= $perPage ?>" 
                           style="display: inline-block; padding: 0.5rem 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #475569; text-decoration: none; font-size: 0.875rem; font-weight: 600; transition: all 0.2s;">
                            بعدی →
                        </a>
                    </li>
                <?php else: ?>
                    <li>
                        <span style="display: inline-block; padding: 0.5rem 1rem; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 6px; color: #cbd5e1; font-size: 0.875rem; font-weight: 600; cursor: not-allowed;">
                            بعدی →
                        </span>
                    </li>
                <?php endif; ?>
                
                <!-- دکمه صفحه آخر -->
                <?php if ($page < $totalPages): ?>
                    <li>
                        <a href="<?= BASE_URL ?>/admin/products?page=<?= $totalPages ?>&per_page=<?= $perPage ?>" 
                           style="display: inline-block; padding: 0.5rem 0.875rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #475569; text-decoration: none; font-size: 0.875rem; transition: all 0.2s;">
                            آخر ⏭️
                        </a>
                    </li>
                <?php endif; ?>
                
            </ul>
        </nav>
        
        <!-- اطلاعات صفحه فعلی -->
        <div style="text-align: center; margin-top: 1rem; color: #64748b; font-size: 0.875rem;">
            نمایش <?= ($page - 1) * $perPage + 1 ?> تا <?= min($page * $perPage, $totalProducts) ?> از <?= number_format($totalProducts) ?> محصول
        </div>
        
        <!-- پرش به صفحه -->
        <div style="text-align: center; margin-top: 1rem;">
            <form method="GET" action="<?= BASE_URL ?>/admin/products" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                <input type="hidden" name="per_page" value="<?= $perPage ?>">
                <label for="jumpToPage" style="font-size: 0.875rem; color: #64748b;">پرش به صفحه:</label>
                <input type="number" 
                       id="jumpToPage" 
                       name="page" 
                       min="1" 
                       max="<?= $totalPages ?>" 
                       value="<?= $page ?>" 
                       style="width: 70px; padding: 0.375rem 0.5rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.875rem;">
                <button type="submit" 
                        style="padding: 0.375rem 0.75rem; background: #4f46e5; color: white; border: none; border-radius: 6px; font-size: 0.875rem; cursor: pointer; font-weight: 600;">
                    برو
                </button>
            </form>
        </div>
    </div>
<?php endif; ?>

<style>
    /* Hover effects */
    nav ul li a:hover {
        background: #e2e8f0 !important;
        border-color: #cbd5e1 !important;
    }
</style>

<!-- فرم حذف مخفی -->
<form id="deleteForm" method="POST" action="<?= BASE_URL ?>/admin/products/delete" style="display: none;">
    <input type="hidden" name="product_id" id="deleteProductId">
</form>

<script>
function toggleProductDetails(productId) {
    const detailsRow = document.getElementById('details-' + productId);
    if (detailsRow) {
        detailsRow.style.display = detailsRow.style.display === 'none' ? 'table-row' : 'none';
    }
}

function confirmDelete(productId, productName) {
    if (confirm('آیا مطمئن هستید که می‌خواهید محصول "' + productName + '" را حذف کنید؟\n\nاین عمل قابل بازگشت نیست!')) {
        document.getElementById('deleteProductId').value = productId;
        document.getElementById('deleteForm').submit();
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
