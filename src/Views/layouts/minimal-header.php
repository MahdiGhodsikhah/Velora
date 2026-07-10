<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= Security::e($pageDesc ?? 'فروشگاه پاییزی شگفت‌انگیز') ?>">
    <meta name="robots" content="index,follow">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php if (!isset($_SESSION['csrf_token'])): $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); endif; ?>
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">

    <title><?= Security::e($pageTitle ?? 'فروشگاه پاییزی') ?></title>

    <!-- فونت وزیرمتن -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- Bootstrap RTL -->
    <link rel="stylesheet" href="<?= defined('BASE_URL') ? BASE_URL : '' ?>/assets/css/bootstrap.min.css">

    <!-- Slick Carousel -->
    <link rel="stylesheet" href="<?= defined('BASE_URL') ? BASE_URL : '' ?>/assets/css/slick.css">
    <link rel="stylesheet" href="<?= defined('BASE_URL') ? BASE_URL : '' ?>/assets/css/slick-theme.css">

    <!-- استایل اصلی -->
    <link rel="stylesheet" href="<?= defined('BASE_URL') ? BASE_URL : '' ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?= defined('BASE_URL') ? BASE_URL : '' ?>/assets/css/header-animated.css">
    <link rel="stylesheet" href="<?= defined('BASE_URL') ? BASE_URL : '' ?>/assets/css/carousel.css">
    
    <!-- تعریف BASE_URL برای JavaScript -->
    <script>
        window.BASE_URL = '<?= defined('BASE_URL') ? BASE_URL : '' ?>';
        console.log('🌐 BASE_URL loaded:', window.BASE_URL);
        
        // تست AJAX
        $(document).ready(function() {
            console.log('✅ jQuery loaded');
            console.log('🔗 Current URL:', window.location.href);
            console.log('🎯 BASE_URL:', window.BASE_URL);
        });
    </script>
</head>
<body>
