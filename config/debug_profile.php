<?php
/**
 * Debug فرم پروفایل
 */
session_start();
define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', '/Velora/public');

header('Content-Type: text/html; charset=utf-8');

// شبیه‌سازی ورود کاربر برای تست
if (empty($_SESSION['user_id'])) {
    echo "<h2>⚠️ شما وارد سیستم نشده‌اید!</h2>";
    echo "<p><a href='/Velora/public/login'>ورود به سیستم</a></p>";
    exit;
}

// تولید token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<!DOCTYPE html><html lang='fa' dir='rtl'><head><meta charset='UTF-8'><title>Debug Result</title>";
    echo "<style>body{font-family:Tahoma;padding:20px}pre{background:#f4f4f4;padding:10px;border-radius:5px}.success{color:green}.error{color:red}</style></head><body>";
    
    echo "<h2>🔍 اطلاعات دریافتی:</h2>";
    
    echo "<h3>POST Data:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    echo "<h3>FILES Data:</h3>";
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";
    
    echo "<h3>CSRF Token Check:</h3>";
    $postToken = $_POST['csrf_token'] ?? '';
    $sessionToken = $_SESSION['csrf_token'] ?? '';
    
    echo "<table border='1' cellpadding='10' style='border-collapse:collapse'>";
    echo "<tr><th>منبع</th><th>مقدار</th></tr>";
    echo "<tr><td>POST Token</td><td><code>" . htmlspecialchars(substr($postToken, 0, 30)) . "...</code></td></tr>";
    echo "<tr><td>Session Token</td><td><code>" . htmlspecialchars(substr($sessionToken, 0, 30)) . "...</code></td></tr>";
    echo "<tr><td>مطابقت</td><td class='" . ($postToken === $sessionToken ? 'success' : 'error') . "'>";
    echo ($postToken === $sessionToken ? '✅ بله' : '❌ خیر');
    echo "</td></tr>";
    echo "</table>";
    
    echo "<br><a href='debug_profile.php'>← بازگشت</a>";
    echo "</body></html>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>Debug فرم پروفایل</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { padding: 20px; }
        .info-box { background: #e7f3ff; padding: 15px; border-radius: 8px; margin: 20px 0; }
    </style>
</head>
<body>

<div class="container">
    <h2>🔧 Debug فرم پروفایل</h2>
    <hr>
    
    <div class="info-box">
        <h5>اطلاعات Session:</h5>
        <ul>
            <li><strong>User ID:</strong> <?= $_SESSION['user_id'] ?? 'NOT SET' ?></li>
            <li><strong>Username:</strong> <?= $_SESSION['username'] ?? 'NOT SET' ?></li>
            <li><strong>CSRF Token:</strong> <code><?= substr($_SESSION['csrf_token'] ?? '', 0, 30) ?>...</code></li>
        </ul>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h4>فرم تست (دقیقاً مثل فرم پروفایل)</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <div class="mb-3">
                    <label class="form-label">نام و نام خانوادگی</label>
                    <input type="text" class="form-control" name="full_name" value="تست کاربر" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">ایمیل</label>
                    <input type="email" class="form-control" name="email" value="test@test.com" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">تلفن</label>
                    <input type="tel" class="form-control" name="phone" value="09123456789">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">آدرس</label>
                    <textarea class="form-control" name="address" rows="2">تهران</textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">عکس پروفایل (اختیاری)</label>
                    <input type="file" class="form-control" name="profile_image" accept="image/*">
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i>
                    ارسال (Debug)
                </button>
            </form>
        </div>
    </div>
    
    <div class="alert alert-info mt-4">
        <strong>توجه:</strong> این فرم فقط برای تست است. اطلاعات را چاپ می‌کند اما در دیتابیس ذخیره نمی‌کند.
    </div>
</div>

</body>
</html>
