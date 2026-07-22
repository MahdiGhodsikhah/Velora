<?php
/**
 * اسکریپت به‌روزرسانی فصل محصولات موجود
 * این اسکریپت به شما کمک می‌کند تا فصل محصولات موجود را به صورت دستی تنظیم کنید
 */

require_once __DIR__ . '/database.php';

echo "<h2>به‌روزرسانی فصل محصولات</h2>";

// دریافت لیست محصولات
$products = db_fetch_all("SELECT id, name, season FROM products ORDER BY id");

if (empty($products)) {
    echo "<p>محصولی یافت نشد.</p>";
    exit;
}

// پردازش فرم
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_seasons'])) {
    $updated = 0;
    foreach ($_POST['seasons'] as $productId => $season) {
        $productId = (int)$productId;
        $season = db_escape($season);
        
        $validSeasons = ['spring', 'summer', 'autumn', 'winter', 'all'];
        if (in_array($season, $validSeasons)) {
            db_query("UPDATE products SET season = '$season' WHERE id = $productId");
            $updated++;
        }
    }
    
    echo "<div style='background: #d4edda; color: #155724; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
    echo "✓ $updated محصول با موفقیت به‌روزرسانی شد!";
    echo "</div>";
    
    // به‌روزرسانی لیست
    $products = db_fetch_all("SELECT id, name, season FROM products ORDER BY id");
}

?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>به‌روزرسانی فصل محصولات</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Tahoma, Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #d97706;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: right;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #d97706;
            color: white;
            font-weight: bold;
        }
        tr:hover {
            background: #f9f9f9;
        }
        select {
            width: 100%;
            padding: 8px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }
        select:focus {
            outline: none;
            border-color: #d97706;
        }
        .btn {
            background: #d97706;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
        }
        .btn:hover {
            background: #b45309;
        }
        .season-spring { background-color: #dcfce7; }
        .season-summer { background-color: #fef3c7; }
        .season-autumn { background-color: #fed7aa; }
        .season-winter { background-color: #dbeafe; }
        .season-all { background-color: #f3f4f6; }
        .info {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #2196f3;
        }
        .actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📋 مدیریت فصل محصولات</h2>
        
        <div class="info">
            <strong>ℹ️ راهنما:</strong>
            <ul style="margin: 10px 0 0 20px;">
                <li>فصل هر محصول را از منوی کشویی انتخاب کنید</li>
                <li>هنگام مشاهده صفحه محصول، تم سایت با فصل محصول تطبیق داده می‌شود</li>
                <li>گزینه "همه فصول" برای محصولاتی که مخصوص فصل خاصی نیستند</li>
            </ul>
        </div>

        <form method="POST">
            <table>
                <thead>
                    <tr>
                        <th style="width: 60px;">شماره</th>
                        <th>نام محصول</th>
                        <th style="width: 200px;">فصل</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr class="season-<?= htmlspecialchars($product['season'] ?? 'all') ?>">
                        <td><?= $product['id'] ?></td>
                        <td><strong><?= htmlspecialchars($product['name']) ?></strong></td>
                        <td>
                            <select name="seasons[<?= $product['id'] ?>]">
                                <option value="all" <?= ($product['season'] === 'all') ? 'selected' : '' ?>>
                                    🌍 همه فصول
                                </option>
                                <option value="spring" <?= ($product['season'] === 'spring') ? 'selected' : '' ?>>
                                    🌸 بهار
                                </option>
                                <option value="summer" <?= ($product['season'] === 'summer') ? 'selected' : '' ?>>
                                    ☀️ تابستان
                                </option>
                                <option value="autumn" <?= ($product['season'] === 'autumn') ? 'selected' : '' ?>>
                                    🍂 پاییز
                                </option>
                                <option value="winter" <?= ($product['season'] === 'winter') ? 'selected' : '' ?>>
                                    ❄️ زمستان
                                </option>
                            </select>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="actions">
                <button type="submit" name="update_seasons" class="btn">
                    💾 ذخیره تغییرات
                </button>
                <button type="button" onclick="location.reload()" class="btn btn-secondary">
                    🔄 بازخوانی
                </button>
                <button type="button" onclick="setAllTo('autumn')" class="btn btn-secondary">
                    🍂 همه پاییزی
                </button>
            </div>
        </form>
    </div>

    <script>
        // تنظیم همه محصولات به یک فصل
        function setAllTo(season) {
            const selects = document.querySelectorAll('select[name^="seasons"]');
            selects.forEach(select => {
                select.value = season;
                // تغییر رنگ ردیف
                const row = select.closest('tr');
                row.className = 'season-' + season;
            });
        }

        // تغییر رنگ ردیف با تغییر select
        document.querySelectorAll('select[name^="seasons"]').forEach(select => {
            select.addEventListener('change', function() {
                const row = this.closest('tr');
                row.className = 'season-' + this.value;
            });
        });

        // اعلان قبل از خروج در صورت وجود تغییرات
        let hasChanges = false;
        document.querySelectorAll('select[name^="seasons"]').forEach(select => {
            select.addEventListener('change', () => { hasChanges = true; });
        });
        
        window.addEventListener('beforeunload', (e) => {
            if (hasChanges) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
        
        document.querySelector('form').addEventListener('submit', () => {
            hasChanges = false;
        });
    </script>
</body>
</html>
