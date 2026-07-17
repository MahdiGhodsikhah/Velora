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

        <?php 
        require BASE_PATH . '/src/Views/partials/alert.php';
        
        // بازیابی داده‌های فرم
        $formData = $_SESSION['form_data'] ?? [];
        $username = $formData['username'] ?? '';
        $phone = $formData['phone'] ?? '';
        unset($_SESSION['form_data']);
        ?>

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
                       value="<?= htmlspecialchars($username) ?>"
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
                <label for="reg-phone" class="sr-only">شماره موبایل</label>
                <input type="tel"
                       id="reg-phone"
                       name="phone"
                       value="<?= htmlspecialchars($phone) ?>"
                       placeholder="شماره موبایل (اختیاری)"
                       maxlength="11"
                       pattern="09[0-9]{9}"
                       autocomplete="tel"
                       aria-describedby="phone-hint">
                <small id="phone-hint" class="input-hint">مثال: ۰۹۱۲۱۲۳۴۵۶۷</small>
            </div>

            <div class="inputBox password-field">
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
                <span class="toggle-password" onclick="togglePassword('reg-password', this)" aria-label="نمایش/مخفی کردن رمز عبور">
                    <i class="far fa-eye"></i>
                </span>
                <small id="pass-hint" class="input-hint">حداقل ۸ کاراکتر، شامل حرف و عدد</small>
            </div>

            <div class="inputBox password-field">
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
                <span class="toggle-password" onclick="togglePassword('reg-password2', this)" aria-label="نمایش/مخفی کردن رمز عبور">
                    <i class="far fa-eye"></i>
                </span>
            </div>

            <div class="inputBox">
                <input type="submit" value="ثبت نام" aria-label="ارسال فرم ثبت‌نام">
            </div>

            <div class="remember">
                <label>
                    <input type="checkbox" name="terms" id="terms-checkbox" required aria-required="true"> 
                    <a href="#" id="show-terms" onclick="showTermsModal(event)">قوانین و مقررات</a> را می‌پذیرم
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

<!-- مدال قوانین و مقررات -->
<div id="terms-modal" class="modal" onclick="closeModalOnOutside(event)">
    <div class="modal-content">
        <div class="modal-header">
            <h2>قوانین و مقررات</h2>
            <span class="close-modal" onclick="closeTermsModal()">&times;</span>
        </div>
        <div class="modal-body">
            <h3>۱. پذیرش شرایط</h3>
            <p>با استفاده از این وب‌سایت، شما تمام شرایط و قوانین ذکر شده را می‌پذیرید. این قوانین برای محافظت از حقوق شما و سایر کاربران تدوین شده است.</p>
            
            <h3>۲. حریم خصوصی و امنیت اطلاعات</h3>
            <p>ما متعهد به حفظ حریم خصوصی شما هستیم. تمام اطلاعات شخصی شما به صورت رمزنگاری شده ذخیره می‌شود و هیچ‌گاه بدون اجازه صریح شما به اشخاص ثالث منتقل نخواهد شد. ما از آخرین استانداردهای امنیتی برای محافظت از داده‌های شما استفاده می‌کنیم.</p>
            
            <h3>۳. مسئولیت کاربر</h3>
            <p>شما مسئول حفظ امنیت حساب کاربری و رمز عبور خود هستید. لطفاً رمز عبور قوی انتخاب کنید و آن را در اختیار دیگران قرار ندهید. هرگونه فعالیت انجام شده تحت حساب کاربری شما، مسئولیت خود شماست. در صورت مشاهده فعالیت مشکوک، بلافاصله به ما اطلاع دهید.</p>
            
            <h3>۴. محتوای کاربران و رفتار مناسب</h3>
            <p>کاربران نباید محتوایی که نقض قوانین کشور، توهین‌آمیز، تبلیغاتی، یا غیرقانونی است را منتشر کنند. احترام به سایر کاربران و رعایت اصول اخلاقی در استفاده از خدمات ما الزامی است. ما حق حذف هر محتوایی که نامناسب تشخیص دهیم را داریم.</p>
            
            <h3>۵. خرید، پرداخت و بازگشت کالا</h3>
            <p>تمام خریدها از طریق درگاه‌های پرداخت معتبر و امن انجام می‌شود. محصولات دارای گارانتی اصالت و کیفیت هستند. در صورت وجود مشکل در محصول، می‌توانید طبق سیاست بازگشت کالا (تا ۷ روز پس از دریافت) درخواست مرجوعی ثبت کنید. هزینه ارسال بازگشتی در صورت مشکل از سمت فروشگاه، به عهده ماست.</p>
            
            <h3>۶. تغییرات در قوانین و شرایط</h3>
            <p>ما حق تغییر، اصلاح یا به‌روزرسانی این قوانین را در هر زمان محفوظ می‌داریم. تغییرات مهم از طریق ایمیل یا اعلان در سایت به اطلاع شما خواهد رسید. استفاده مداوم از سایت پس از اعمال تغییرات، به معنای پذیرش آن‌ها تلقی می‌شود.</p>
            
            <h3>۷. لغو یا تعلیق حساب کاربری</h3>
            <p>ما حق لغو یا تعلیق حساب کاربران را در صورت نقض قوانین، فعالیت‌های مشکوک، یا سوء استفاده از خدمات داریم. شما نیز می‌توانید در هر زمان درخواست حذف حساب کاربری خود را ثبت کنید. توجه داشته باشید که پس از حذف حساب، اطلاعات شما به طور کامل پاک می‌شود.</p>
            
            <h3>۸. پشتیبانی و ارتباط با ما</h3>
            <p>تیم پشتیبانی ما آماده پاسخگویی به سوالات و مشکلات شماست. برای تماس با ما می‌توانید از فرم تماس، ایمیل یا شماره تلفن پشتیبانی استفاده کنید. ما تلاش می‌کنیم در کوتاه‌ترین زمان ممکن به درخواست‌های شما رسیدگی کنیم.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-close" onclick="closeTermsModal()">بستن</button>
            <button type="button" class="btn-accept" onclick="acceptTerms()">می‌پذیرم</button>
        </div>
    </div>
</div>

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

function showTermsModal(event) {
    event.preventDefault();
    document.getElementById('terms-modal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeTermsModal() {
    document.getElementById('terms-modal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function closeModalOnOutside(event) {
    if (event.target.id === 'terms-modal') {
        closeTermsModal();
    }
}

function acceptTerms() {
    document.getElementById('terms-checkbox').checked = true;
    closeTermsModal();
}

// بستن مدال با کلید Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('terms-modal');
        if (modal.style.display === 'flex') {
            closeTermsModal();
        }
    }
});
</script>
