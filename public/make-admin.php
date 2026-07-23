<?php
/**
 * تبدیل کاربر به ادمین
 * برای تست و راه‌اندازی اولیه
 */

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/config/database.php';

$message = '';
$type = 'info';

// پردازش فرم
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = db_escape($_POST['username']);
    
    // بررسی وجود کاربر
    $user = db_fetch_one("SELECT * FROM users WHERE username = '$username'");
    
    if ($user) {
        // تبدیل به ادمین
        $userId = (int)$user['id'];
        $sql = "UPDATE users SET role = 'admin' WHERE id = $userId";
        
        if (db_query($sql)) {
            $message = "کاربر '{$user['username']}' با موفقیت به ادمین تبدیل شد!";
            $type = 'success';
        } else {
            $message = "خطا در تبدیل کاربر به ادمین";
            $type = 'error';
        }
    } else {
        $message = "کاربر با نام '$username' یافت نشد";
        $type = 'error';
    }
}

// دریافت لیست کاربران
$users = db_fetch_all("SELECT id, username, email, phone, role, is_active FROM users ORDER BY id");

?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت نقش کاربران</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Tahoma, Arial, sans-serif;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
        }
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-right: 4px solid;
        }
        .alert-success {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
        }
        .alert-error {
            background: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }
        .alert-info {
            background: #d1ecf1;
            border-color: #0dcaf0;
            color: #055160;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        input, select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        th, td {
            padding: 12px;
            text-align: right;
            border-bottom: 1px solid #e9ecef;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        tr:hover {
            background: #f8f9fa;
        }
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        .badge-admin {
            background: #667eea;
            color: white;
        }
        .badge-customer {
            background: #6c757d;
            color: white;
        }
        .quick-select {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            flex-wrap: wrap;
        }
        .quick-btn {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .quick-btn:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>👑 مدیریت نقش کاربران</h1>
        <p class="subtitle">تبدیل کاربران به ادمین یا تغییر نقش آن‌ها</p>

        <?php if ($message): ?>
            <div class="alert alert-<?= $type ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="alert alert-info">
            <strong>ℹ️ راهنما:</strong>
            <ul style="margin: 10px 0 0 20px;">
                <li>برای دسترسی به پنل ادمین، کاربر باید نقش "admin" داشته باشد</li>
                <li>می‌توانید نام کاربری را وارد کنید یا از لیست زیر انتخاب کنید</li>
                <li>بعد از تبدیل، باید دوباره وارد سیستم شوید</li>
            </ul>
        </div>

        <form method="POST">
            <div class="form-group">
                <label for="username">نام کاربری:</label>
                <input type="text" id="username" name="username" required 
                       placeholder="مثال: admin یا mahdi">
                <div class="quick-select">
                    <strong style="line-height: 2.5;">انتخاب سریع:</strong>
                    <?php foreach ($users as $u): ?>
                        <button type="button" class="quick-btn" onclick="document.getElementById('username').value='<?= htmlspecialchars($u['username']) ?>'">
                            <?= htmlspecialchars($u['username']) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="submit" class="btn">
                👑 تبدیل به ادمین
            </button>
        </form>

        <h2 style="margin-top: 40px; color: #333;">📋 لیست کاربران</h2>
        <table>
            <thead>
                <tr>
                    <th>شماره</th>
                    <th>نام کاربری</th>
                    <th>ایمیل</th>
                    <th>موبایل</th>
                    <th>نقش</th>
                    <th>وضعیت</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 30px;">
                            کاربری یافت نشد
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><strong><?= htmlspecialchars($user['username']) ?></strong></td>
                        <td><?= htmlspecialchars($user['email'] ?: '-') ?></td>
                        <td><?= htmlspecialchars($user['phone']) ?></td>
                        <td>
                            <span class="badge badge-<?= $user['role'] === 'admin' ? 'admin' : 'customer' ?>">
                                <?= $user['role'] === 'admin' ? '👑 ادمین' : '👤 کاربر' ?>
                            </span>
                        </td>
                        <td>
                            <?= $user['is_active'] ? '✅ فعال' : '❌ غیرفعال' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div style="margin-top: 30px; text-align: center;">
            <a href="debug-session.php" class="btn" style="text-decoration: none; display: inline-block; margin: 5px;">
                🔍 دیباگ Session
            </a>
            <a href="login" class="btn" style="text-decoration: none; display: inline-block; margin: 5px; background: #28a745;">
                🔐 ورود به سیستم
            </a>
            <a href="./" class="btn" style="text-decoration: none; display: inline-block; margin: 5px; background: #6c757d;">
                🏠 صفحه اصلی
            </a>
        </div>

        <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border-radius: 8px; border-right: 4px solid #ffc107;">
            <strong>⚠️ هشدار امنیتی:</strong> این صفحه فقط برای راه‌اندازی اولیه است. 
            بعد از تنظیم ادمین، حتماً آن را حذف کنید!
        </div>
    </div>
</body>
</html>
