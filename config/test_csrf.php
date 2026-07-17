<?php
/**
 * تست CSRF Token
 */
session_start();

// تولید token اگر وجود ندارد
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تست CSRF Token</title>
    <style>
        body { font-family: Tahoma, Arial; padding: 20px; background: #f5f5f5; }
        .box { background: white; padding: 20px; border-radius: 8px; max-width: 600px; margin: 20px auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: green; background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .error { color: red; background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .info { color: blue; background: #d1ecf1; padding: 10px; border-radius: 5px; margin: 10px 0; }
        code { background: #f4f4f4; padding: 2px 5px; border-radius: 3px; font-family: monospace; }
        button { background: #667eea; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
        button:hover { background: #5568d3; }
    </style>
</head>
<body>

<div class="box">
    <h2>🔍 تست CSRF Token</h2>
    <hr>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <h3>نتیجه ارسال فرم:</h3>
        
        <?php
        $postToken = $_POST['csrf_token'] ?? '';
        $sessionToken = $_SESSION['csrf_token'] ?? '';
        
        echo "<div class='info'>";
        echo "<strong>Token ارسال شده:</strong><br>";
        echo "<code>" . htmlspecialchars(substr($postToken, 0, 50)) . "...</code><br><br>";
        echo "<strong>Token در Session:</strong><br>";
        echo "<code>" . htmlspecialchars(substr($sessionToken, 0, 50)) . "...</code>";
        echo "</div>";
        
        if (empty($postToken)) {
            echo "<div class='error'>❌ Token در POST وجود ندارد!</div>";
        } elseif (empty($sessionToken)) {
            echo "<div class='error'>❌ Token در Session وجود ندارد!</div>";
        } elseif ($postToken === $sessionToken) {
            echo "<div class='success'>✅ Token‌ها مطابقت دارند! CSRF Token کار می‌کند.</div>";
        } else {
            echo "<div class='error'>❌ Token‌ها مطابقت ندارند!</div>";
        }
        ?>
        
        <br>
        <a href="test_csrf.php">← بازگشت و تست مجدد</a>
        
    <?php else: ?>
        
        <div class="info">
            <strong>Token فعلی Session:</strong><br>
            <code><?= htmlspecialchars($_SESSION['csrf_token']) ?></code>
        </div>
        
        <h3>فرم تست:</h3>
        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
            <p>این فرم شامل یک CSRF Token مخفی است.</p>
            <p>با کلیک روی دکمه، Token ارسال و بررسی می‌شود.</p>
            
            <button type="submit">ارسال فرم</button>
        </form>
        
        <hr>
        
        <h3>بررسی دستی:</h3>
        <button onclick="checkToken()">نمایش Token در Console</button>
        
        <script>
        function checkToken() {
            const token = document.querySelector('input[name="csrf_token"]').value;
            console.log('CSRF Token:', token);
            alert('Token در Console نمایش داده شد (F12 را بزنید)');
        }
        </script>
        
    <?php endif; ?>
</div>

<div class="box">
    <h3>📚 راهنما:</h3>
    <ol>
        <li>این صفحه یک فرم ساده با CSRF Token دارد</li>
        <li>روی دکمه "ارسال فرم" کلیک کنید</li>
        <li>اگر پیام "✅ Token‌ها مطابقت دارند" آمد، همه چیز درست است</li>
        <li>اگر خطا آمد، مشکلی در Session یا Token هست</li>
    </ol>
    
    <h4>Session Info:</h4>
    <ul>
        <li><strong>Session ID:</strong> <code><?= session_id() ?></code></li>
        <li><strong>Session Status:</strong> <?= session_status() === PHP_SESSION_ACTIVE ? '✅ فعال' : '❌ غیرفعال' ?></li>
        <li><strong>CSRF Token موجود:</strong> <?= isset($_SESSION['csrf_token']) ? '✅ بله' : '❌ خیر' ?></li>
    </ul>
</div>

</body>
</html>
