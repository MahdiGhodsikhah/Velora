<?php
$base = defined('BASE_URL') ? BASE_URL : '';
?>

<div class="leaves" aria-hidden="true">
    <div class="set">
        <div style="left:20%"><img src="<?= $base ?>/assets/images/auth/autumn/leaf_01.png" alt=""></div>
        <div style="left:50%"><img src="<?= $base ?>/assets/images/auth/autumn/leaf_02.png" alt=""></div>
        <div style="left:70%"><img src="<?= $base ?>/assets/images/auth/autumn/leaf_03.png" alt=""></div>
        <div style="left:10%"><img src="<?= $base ?>/assets/images/auth/autumn/leaf_04.png" alt=""></div>
    </div>
</div>

<section class="auth-section">
    <img src="<?= $base ?>/assets/images/auth/autumn/bg.jpg" alt="" class="bg" aria-hidden="true">
    <img src="<?= $base ?>/assets/images/auth/autumn/trees.png" alt="" class="trees" aria-hidden="true">
    <img src="<?= $base ?>/assets/images/auth/autumn/girl.png" alt="" class="girl" aria-hidden="true">

    <div class="auth-card" role="main">
        <h1>ورود به حساب کاربری</h1>

        <?php require BASE_PATH . '/src/Views/partials/alert.php'; ?>

        <form method="POST"
              action="<?= $base ?>/login"
              class="auth-form"
              novalidate
              autocomplete="off"
              aria-label="فرم ورود">

            <?= Security::csrf_field() ?>

            <div class="inputBox">
                <label for="phone" class="sr-only">شماره موبایل</label>
                <input type="tel"
                       id="phone"
                       name="phone"
                       placeholder="شماره موبایل"
                       required
                       maxlength="11"
                       pattern="09[0-9]{9}"
                       autocomplete="tel"
                       aria-required="true">
            </div>

            <div class="inputBox password-field">
                <label for="password" class="sr-only">رمز عبور</label>
                <div class="password-wrapper">
                    <input type="password"
                           id="password"
                           name="password"
                           placeholder="رمز عبور"
                           required
                           maxlength="100"
                           autocomplete="current-password"
                           aria-required="true">
                    <span class="toggle-password" onclick="togglePassword('password', this)" aria-label="نمایش/مخفی کردن رمز عبور">
                        <i class="far fa-eye"></i>
                    </span>
                </div>
            </div>

            <div class="inputBox">
                <input type="submit" value="ورود" aria-label="ارسال فرم ورود">
            </div>

            <div class="remember">
                <label>
                    <input type="checkbox" name="remember" value="1"> مرا به خاطر بسپار
                </label>
            </div>

            <div class="group">
                <a href="#">فراموشی رمز عبور</a>
                <a href="<?= $base ?>/register">ثبت نام</a>
            </div>

            <div class="social">
                <p>ورود با حساب‌های دیگر</p>
                <div class="icons">
                    <a href="#" aria-label="ورود با گوگل"><i class="fab fa-google" aria-hidden="true"></i></a>
                    <a href="#" aria-label="ورود با اپل"><i class="fab fa-apple" aria-hidden="true"></i></a>
                    <a href="#" aria-label="ورود با گیت‌هاب"><i class="fab fa-github" aria-hidden="true"></i></a>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
function togglePassword(inputId, icon) {
    const input = document.getElementById(inputId);
    const iconElement = icon.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        iconElement.classList.remove('fa-eye');
        iconElement.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        iconElement.classList.remove('fa-eye-slash');
        iconElement.classList.add('fa-eye');
    }
}
</script>
