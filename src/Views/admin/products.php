<div class="admin-table">
    <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: #1e293b;">لیست محصولات</h3>
        <button class="btn btn-primary btn-sm" disabled>افزودن محصول جدید</button>
    </div>
    <table>
        <thead>
            <tr>
                <th>شناسه</th>
                <th>تصویر</th>
                <th>نام محصول</th>
                <th>دسته‌بندی</th>
                <th>قیمت</th>
                <th>موجودی</th>
                <th>امتیاز</th>
                <th>وضعیت</th>
                <th>تاریخ ایجاد</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td style="font-weight: 600; color: #4f46e5;">#<?= $product['id'] ?></td>
                        <td>
                            <?php if (!empty($product['main_image'])): ?>
                                <img src="<?= BASE_URL . $product['main_image'] ?>" 
                                     alt="<?= Security::e($product['name']) ?>" 
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                            <?php else: ?>
                                <div style="width: 50px; height: 50px; background: #f1f5f9; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #1e293b;">
                                <?= Security::e($product['name']) ?>
                            </div>
                            <div style="font-size: 0.75rem; color: #94a3b8;">
                                <?= Security::e($product['slug']) ?>
                            </div>
                        </td>
                        <td><?= Security::e($product['category_name'] ?? '-') ?></td>
                        <td style="font-weight: 600;">
                            <?php if ($product['discount_price'] > 0): ?>
                                <div style="color: #dc2626;">
                                    <?= number_format($product['discount_price']) ?> تومان
                                </div>
                                <div style="font-size: 0.75rem; color: #94a3b8; text-decoration: line-through;">
                                    <?= number_format($product['price']) ?>
                                </div>
                            <?php else: ?>
                                <?= number_format($product['price']) ?> تومان
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($product['stock_qty'] > 10): ?>
                                <span class="badge badge-success"><?= $product['stock_qty'] ?> عدد</span>
                            <?php elseif ($product['stock_qty'] > 0): ?>
                                <span class="badge badge-warning"><?= $product['stock_qty'] ?> عدد</span>
                            <?php else: ?>
                                <span class="badge badge-danger">ناموجود</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.25rem;">
                                <span style="color: #fbbf24;">★</span>
                                <span style="font-weight: 600;"><?= $product['rating_avg'] ?></span>
                                <span style="font-size: 0.75rem; color: #94a3b8;">(<?= $product['rating_count'] ?>)</span>
                            </div>
                        </td>
                        <td>
                            <?php if ($product['is_active']): ?>
                                <span class="badge badge-success">فعال</span>
                            <?php else: ?>
                                <span class="badge badge-danger">غیرفعال</span>
                            <?php endif; ?>
                            <?php if ($product['is_featured']): ?>
                                <span class="badge badge-info">ویژه</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size: 0.875rem; color: #64748b;">
                            <?= jdate('Y/m/d', strtotime($product['created_at'])) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" style="text-align: center; color: #94a3b8; padding: 3rem;">
                        هیچ محصولی یافت نشد
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
