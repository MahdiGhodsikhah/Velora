<?php
/**
 * دیباگ Session - برای بررسی مقادیر session
 */

session_start();

?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دیباگ Session</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            padding: 20px;
            background: #1e1e1e;
            color: #d4d4d4;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #252526;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.5);
        }
        h1 {
            color: #4ec9b0;
            border-bottom: 2px solid #4ec9b0;
            padding-bottom: 10px;
        }
        .section {
            margin: 20px 0;
            padding: 15px;
            background: #1e1e1e;
            border-radius: 5px;
            border-left: 4px solid #569cd6;
        }
        .key {
            color: #9cdcfe;
            font-weight: bold;
        }
        .value {
            color: #ce9178;
        }
        .empty {
            color: #6a9955;
            font-style: italic;
        }
        .check {
            background: #1e1e1e;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .success {
            color: #4ec9b0;
        }
        .error {
            color: #f48771;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 10px;
            text-align: right;
            border-bottom: 1px solid #3e3e42;
        }
        th {
            background: #1e1e1e;
            color: #4ec9b0;
        }
        .btn {
            background: #0e639c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }
        .btn:hover {
            background: #1177bb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 دیباگ Session</h1>
        
        <div class="section">
            <h2>📊 اطلاعات Session</h2>
            <?php if (empty($_SESSION)): ?>
                <p class="empty">⚠️ Session خالی است یا شروع نشده</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>کلید</th>
                        <th>مقدار</th>
                        <th>نوع</th>
                    </tr>
                    <?php foreach ($_SESSION as $key => $value): ?>
                    <tr>
                        <td class="key"><?= htmlspecialchars($key) ?></td>
                        <td class="value">
                            <?php 
                            if (is_array($value)) {
                                echo '<pre>' . htmlspecialchars(print_r($value, true)) . '</pre>';
                            } else {
                                echo htmlspecialchars($value);
                            }
                            ?>
                        </td>
                        <td><?= gettype($value) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>

        <div class="section">
            <h2>✅ بررسی دسترسی ادمین</h2>
            
            <div class="check">
                <strong>user_id:</strong>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="success">✓ موجود: <?= $_SESSION['user_id'] ?></span>
                <?php else: ?>
                    <span class="error">✗ موجود نیست</span>
                <?php endif; ?>
            </div>

            <div class="check">
                <strong>role:</strong>
                <?php if (isset($_SESSION['role'])): ?>
                    <span class="success">✓ موجود: <?= $_SESSION['role'] ?></span>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <span class="success"> ← ادمین است ✓</span>
                    <?php else: ?>
                        <span class="error"> ← ادمین نیست ✗</span>
                    <?php endif; ?>
                <?php else: ?>
                    <span class="error">✗ موجود نیست</span>
                <?php endif; ?>
            </div>

            <div class="check">
                <strong>user_role:</strong>
                <?php if (isset($_SESSION['user_role'])): ?>
                    <span class="success">✓ موجود: <?= $_SESSION['user_role'] ?></span>
                <?php else: ?>
                    <span class="error">✗ موجود نیست</span>
                <?php endif; ?>
            </div>

            <div class="check">
                <strong>logged_in:</strong>
                <?php if (isset($_SESSION['logged_in'])): ?>
                    <span class="success">✓ موجود: <?= $_SESSION['logged_in'] ? 'true' : 'false' ?></span>
                <?php else: ?>
                    <span class="error">✗ موجود نیست</span>
                <?php endif; ?>
            </div>

            <div class="check">
                <strong>نتیجه نهایی:</strong>
                <?php if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <span class="success">✅ دسترسی به پنل ادمین دارید</span>
                <?php else: ?>
                    <span class="error">❌ دسترسی به پنل ادمین ندارید</span>
                    <br><small>برای دسترسی به پنل ادمین، باید با حساب ادمین وارد شوید</small>
                <?php endif; ?>
            </div>
        </div>

        <div class="section">
            <h2>ℹ️ اطلاعات دیگر</h2>
            <div class="check">
                <strong>Session ID:</strong> <?= session_id() ?>
            </div>
            <div class="check">
                <strong>Session Name:</strong> <?= session_name() ?>
            </div>
            <div class="check">
                <strong>Session Status:</strong> 
                <?php 
                $status = session_status();
                if ($status === PHP_SESSION_ACTIVE) {
                    echo '<span class="success">فعال</span>';
                } elseif ($status === PHP_SESSION_NONE) {
                    echo '<span class="error">غیرفعال</span>';
                } else {
                    echo '<span class="error">معلق</span>';
                }
                ?>
            </div>
        </div>

        <div style="margin-top: 30px; text-align: center;">
            <a href="login" class="btn">ورود به سیستم</a>
            <a href="admin/dashboard" class="btn">پنل ادمین</a>
            <a href="./" class="btn">صفحه اصلی</a>
            <button onclick="location.reload()" class="btn">رفرش</button>
        </div>

        <div style="margin-top: 20px; padding: 15px; background: #1e1e1e; border-radius: 5px; font-size: 0.9rem;">
            <strong>💡 نکته:</strong> اگر role یا user_id موجود نیست، باید دوباره وارد سیستم شوید.
        </div>
    </div>
</body>
</html>
