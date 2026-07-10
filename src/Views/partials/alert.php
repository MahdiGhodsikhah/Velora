<?php
/**
 * کامپوننت پیام‌های flash
 * متغیرهای مورد نیاز: $error (string), $success (string)
 */
$error   = $error   ?? ($_SESSION['auth_error']   ?? '');
$success = $success ?? ($_SESSION['auth_success'] ?? '');
unset($_SESSION['auth_error'], $_SESSION['auth_success']);
?>

<?php if (!empty($error)): ?>
<div class="alert alert-error" role="alert" aria-live="assertive">
    <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
    <span><?= Security::e($error) ?></span>
    <button class="alert-close" aria-label="بستن پیام" type="button">&times;</button>
</div>
<?php endif; ?>

<?php if (!empty($success)): ?>
<div class="alert alert-success" role="status" aria-live="polite">
    <i class="fas fa-check-circle" aria-hidden="true"></i>
    <span><?= Security::e($success) ?></span>
    <button class="alert-close" aria-label="بستن پیام" type="button">&times;</button>
</div>
<?php endif; ?>
