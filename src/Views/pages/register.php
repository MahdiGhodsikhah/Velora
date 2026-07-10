<?php
$base = defined('BASE_URL') ? BASE_URL : '';
?>

<div class="leaves" aria-hidden="true">
    <div class="set">
        <div style="left:20%"><img src="<?= $base ?>/assets/images/leaves/leaf_01.png" alt=""></div>
        <div style="left:50%"><img src="<?= $base ?>/assets/images/leaves/leaf_02.png" alt=""></div>
        <div style="left:70%"><img src="<?= $base ?>/assets/images/leaves/leaf_03.png" alt=""></div>
        <div style="left:10%"><img src="<?= $base ?>/assets/images/leaves/leaf_04.png" alt=""></div>
    </div>
</div>

<section class="auth-section">
    <img src="<?= $base ?>/assets/images/auth/bg.jpg" alt="" class="bg" aria-hidden="true">
    <img src="<?= $base ?>/assets/images/auth/trees.png" alt="" class="trees" aria-hidden="true">
    <img src="<?= $base ?>/assets/images/auth/girl.png" alt="" class="girl" aria-hidden="true">

    <div class="auth-card" role="main">
        <h1>ایجاد حساب کاربری</h1>

        <?php require BASE_PATH . '/src/Views/partials/alert.php'; ?>

        <form method="POST"
              action="<?= $base ?>/register"
              class="auth-form"
              novalidate
              autocomplete="off"
              aria-label="فرم ثبت‌نام">

            <?= Security::csrf_field() ?>

            <div class="inputBox">
                <label for="reg-username" class="sr-only">نام کاربری</label>
                <input type="text"
                       id="reg-username"
                       name="username"
                       placeholder="نام کاربری (انگلیسی)"
                       required
                       minlength="3"
                       maxlength="50"
                       autocomplete="username"
                       pattern="[a-zA-Z0-9_\-\.]{3,50}"
                       aria-required="true"
                       aria-describedby="username-hint">
                <small id="username-hint" class="input-hint">۳ تا ۵۰ کاراکتر انگلیسی، عدد، خط تیره یا نقطه</small>
            </div>

            <div class="inputBox">
                <label for="reg-email" class="sr-only">ایمیل</label>
                <input type="email"
                       id="reg-email"
                       name="email"
                       placeholder="آدرس ایمیل"
                       required
                       maxlength="150"
                       autocomplete="email"
                       aria-required="true">
            </div>

            <div class="inputBox">
                <label for="reg-phone" class="sr-only">شماره موبایل</label>
                <input type="tel"
                       id="reg-phone"
                       name="phone"
                       placeholder="شماره موبایل (اختیاری)"
                       maxlength="11"
                       pattern="09[0-9]{9}"
                       autocomplete="tel"
                       aria-describedby="phone-hint">
                <small id="phone-hint" class="input-hint">مثال: ۰۹۱۲۱۲۳۴۵۶۷</small>
            </div>

            <div class="inputBox">
                <label for="reg-password" class="sr-only">رمز عبور</label>
                <input type="password"
                       id="reg-password"
                       name="password"
                       placeholder="رمز عبور"
                       required
                       minlength="8"
                       maxlength="100"
                       autocomplete="new-password"
                       aria-required="true"
                       aria-describedby="pass-hint">
                <small id="pass-hint" class="input-hint">حداقل ۸ کاراکتر، شامل حرف و عدد</small>
            </div>

            <div class="inputBox">
                <label for="reg-password2" class="sr-only">تکرار رمز عبور</label>
                <input type="password"
                       id="reg-password2"
                       name="password2"
                       placeholder="تکرار رمز عبور"
                       required
                       minlength="8"
                       maxlength="100"
                       autocomplete="new-password"
                       aria-required="true">
            </div>

            <div class="inputBox">
                <input type="submit" value="ثبت نام" aria-label="ارسال فرم ثبت‌نام">
            </div>

            <div class="remember">
                <label>
                    <input type="checkbox" name="terms" required aria-required="true"> قوانین و مقررات را می‌پذیرم
                </label>
            </div>

            <div class="group" style="justify-content:center">
                <a href="<?= $base ?>/login">ورود به حساب کاربری</a>
            </div>

            <div class="social">
                <p>ثبت نام با حساب‌های دیگر</p>
                <div class="icons">
                    <a href="#" aria-label="ثبت‌نام با گوگل"><i class="fab fa-google" aria-hidden="true"></i></a>
                    <a href="#" aria-label="ثبت‌نام با اپل"><i class="fab fa-apple" aria-hidden="true"></i></a>
                    <a href="#" aria-label="ثبت‌نام با گیت‌هاب"><i class="fab fa-github" aria-hidden="true"></i></a>
                </div>
            </div>
        </form>
    </div>
</section>
