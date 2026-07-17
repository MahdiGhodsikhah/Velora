<?php
session_start();
define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', '/Velora/public');

require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/src/Libs/Security.php';

header('Content-Type: text/html; charset=utf-8');

if (empty($_SESSION['user_id'])) {
    die('لطفا وارد شوید!');
}

$userId = (int)$_SESSION['user_id'];

// دریافت اطلاعات کاربر
$sql = "SELECT id, username, full_name, profile_image FROM users WHERE id = ?";
$stmt = db_prepare($sql);
mysqli_stmt_bind_param($stmt, 'i', $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>Debug - عکس پروفایل</title>
    <style>
        body { font-family: Tahoma, Arial; padding: 20px; background: #f5f5f5; }
        .box { background: white; padding: 20px; border-radius: 8px; margin: 20px auto; max-width: 800px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: right; }
        th { background: #667eea; color: white; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        img { max-width: 200px; border: 2px solid #667eea; border-radius: 8px; }
        code { background: #f4f4f4; padding: 2px 5px; border-radius: 3px; }
    </style>
</head>
<body>

<div class="box">
    <h2>🔍 Debug - عکس پروفایل کاربر</h2>
    <hr>
    
    <h3>اطلاعات کاربر:</h3>
    <table>
        <tr>
            <th>فیلد</th>
            <th>مقدار</th>
        </tr>
        <tr>
            <td>User ID</td>
            <td><?= $user['id'] ?></td>
        </tr>
        <tr>
            <td>Username</td>
            <td><?= htmlspecialchars($user['username']) ?></td>
        </tr>
        <tr>
            <td>Full Name</td>
            <td><?= htmlspecialchars($user['full_name'] ?? 'خالی') ?></td>
        </tr>
        <tr>
            <td>Profile Image (DB)</td>
            <td>
                <?php if (empty($user['profile_image'])): ?>
                    <span class="warning">❌ خالی است!</span>
                <?php else: ?>
                    <code><?= htmlspecialchars($user['profile_image']) ?></code>
                <?php endif; ?>
            </td>
        </tr>
    </table>
    
    <h3>بررسی مسیر عکس:</h3>
    <?php if (!empty($user['profile_image'])): ?>
        <?php
        $imagePath = $user['profile_image'];
        $fullPath = BASE_PATH . '/public' . $imagePath;
        $urlPath = BASE_URL . $imagePath;
        ?>
        
        <table>
            <tr>
                <th>نوع مسیر</th>
                <th>مقدار</th>
                <th>وضعیت</th>
            </tr>
            <tr>
                <td>DB Path</td>
                <td><code><?= htmlspecialchars($imagePath) ?></code></td>
                <td>-</td>
            </tr>
            <tr>
                <td>Full Server Path</td>
                <td><code><?= htmlspecialchars($fullPath) ?></code></td>
                <td>
                    <?php if (file_exists($fullPath)): ?>
                        <span class="success">✅ فایل وجود دارد</span>
                    <?php else: ?>
                        <span class="error">❌ فایل وجود ندارد!</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>URL Path</td>
                <td><code><?= htmlspecialchars($urlPath) ?></code></td>
                <td>-</td>
            </tr>
        </table>
        
        <h3>پیش‌نمایش عکس:</h3>
        
        <h4>1. با BASE_URL + profile_image:</h4>
        <p>کد: <code>&lt;img src="&lt;?= BASE_URL . $user['profile_image'] ?&gt;"&gt;</code></p>
        <img src="<?= BASE_URL . $user['profile_image'] ?>" alt="Test 1" onerror="this.style.border='2px solid red'; this.alt='❌ خطا در بارگذاری'">
        
        <h4>2. با مسیر مستقیم:</h4>
        <p>کد: <code>&lt;img src="<?= htmlspecialchars($urlPath) ?>"&gt;</code></p>
        <img src="<?= $urlPath ?>" alt="Test 2" onerror="this.style.border='2px solid red'; this.alt='❌ خطا در بارگذاری'">
        
        <h4>3. با مسیر نسبی:</h4>
        <p>کد: <code>&lt;img src="/Velora/public<?= htmlspecialchars($imagePath) ?>"&gt;</code></p>
        <img src="/Velora/public<?= $imagePath ?>" alt="Test 3" onerror="this.style.border='2px solid red'; this.alt='❌ خطا در بارگذاری'">
        
    <?php else: ?>
        <div class="warning">
            <p>⚠️ هیچ عکسی آپلود نشده است!</p>
            <p>لطفا به صفحه پروفایل بروید و یک عکس آپلود کنید.</p>
        </div>
    <?php endif; ?>
    
    <hr>
    
    <h3>بررسی پوشه uploads:</h3>
    <?php
    $uploadsDir = BASE_PATH . '/public/uploads/profiles';
    ?>
    <table>
        <tr>
            <th>بررسی</th>
            <th>نتیجه</th>
        </tr>
        <tr>
            <td>آیا پوشه وجود دارد؟</td>
            <td>
                <?php if (is_dir($uploadsDir)): ?>
                    <span class="success">✅ بله</span>
                <?php else: ?>
                    <span class="error">❌ خیر</span>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>آیا قابل خواندن است؟</td>
            <td>
                <?php if (is_readable($uploadsDir)): ?>
                    <span class="success">✅ بله</span>
                <?php else: ?>
                    <span class="error">❌ خیر</span>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>تعداد فایل‌ها</td>
            <td>
                <?php
                if (is_dir($uploadsDir)) {
                    $files = glob($uploadsDir . '/*');
                    echo count($files) . ' فایل';
                    
                    if (count($files) > 0) {
                        echo '<ul style="margin:10px 0; text-align:left;">';
                        foreach ($files as $file) {
                            $fileName = basename($file);
                            $fileSize = filesize($file);
                            echo '<li><code>' . htmlspecialchars($fileName) . '</code> (' . number_format($fileSize / 1024, 2) . ' KB)</li>';
                        }
                        echo '</ul>';
                    }
                } else {
                    echo '<span class="error">پوشه وجود ندارد</span>';
                }
                ?>
            </td>
        </tr>
    </table>
    
    <hr>
    <p><a href="/Velora/public/profile">← بازگشت به صفحه پروفایل</a></p>
</div>

</body>
</html>
