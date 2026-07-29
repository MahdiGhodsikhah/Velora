<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ' . BASE_URL . '/');
    exit;
}

$configPath = BASE_PATH . '/config/theme.php';
$config = require $configPath;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mode = $_POST['mode'] ?? 'automatic';
    $adminTheme = $_POST['admin_theme'] ?? null;
    
    $validModes = ['automatic', 'manual'];
    $validThemes = ['spring', 'summer', 'autumn', 'winter'];
    
    if (!in_array($mode, $validModes)) {
        $mode = 'automatic';
    }
    
    if ($mode === 'manual' && !in_array($adminTheme, $validThemes)) {
        $adminTheme = null;
        $mode = 'automatic';
    }
    
    if ($mode === 'automatic') {
        $adminTheme = null;
    }
    
    $newConfig = "<?php\n\nreturn [\n    'mode' => '$mode',\n    'admin_selected_theme' => " . 
                 ($adminTheme ? "'$adminTheme'" : 'null') . "\n];\n";
    
    file_put_contents($configPath, $newConfig);
    $_SESSION['success'] = 'تنظیمات تم با موفقیت ذخیره شد.';
    header('Location: ' . BASE_URL . '/admin/theme-settings');
    exit;
}

$pageTitle = 'تنظیمات تم سایت';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/base/variables.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/base/reset.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/base/typography.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/components/card.css">
</head>
<body style="background: #f5f5f5; padding: 40px 20px;">
    <div class="container" style="max-width: 800px;">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h3 class="mb-0">تنظیمات تم سایت</h3>
            </div>
            <div class="card-body p-4">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?= $_SESSION['success'] ?>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label fw-bold">حالت تم</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="mode" id="mode_auto" value="automatic" 
                                   <?= $config['mode'] === 'automatic' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="mode_auto">
                                خودکار (بر اساس فصل جاری)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="mode" id="mode_manual" value="manual"
                                   <?= $config['mode'] === 'manual' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="mode_manual">
                                دستی
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-4" id="theme_selector" style="<?= $config['mode'] === 'manual' ? '' : 'display:none;' ?>">
                        <label for="admin_theme" class="form-label fw-bold">انتخاب تم</label>
                        <select class="form-select" id="admin_theme" name="admin_theme">
                            <option value="spring" <?= ($config['admin_selected_theme'] ?? '') === 'spring' ? 'selected' : '' ?>>بهار</option>
                            <option value="summer" <?= ($config['admin_selected_theme'] ?? '') === 'summer' ? 'selected' : '' ?>>تابستان</option>
                            <option value="autumn" <?= ($config['admin_selected_theme'] ?? '') === 'autumn' ? 'selected' : '' ?>>پاییز</option>
                            <option value="winter" <?= ($config['admin_selected_theme'] ?? '') === 'winter' ? 'selected' : '' ?>>زمستان</option>
                        </select>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
                        <a href="<?= BASE_URL ?>/" class="btn btn-secondary">بازگشت</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        document.getElementById('mode_auto').addEventListener('change', function() {
            document.getElementById('theme_selector').style.display = 'none';
        });
        document.getElementById('mode_manual').addEventListener('change', function() {
            document.getElementById('theme_selector').style.display = 'block';
        });
    </script>
</body>
</html>
