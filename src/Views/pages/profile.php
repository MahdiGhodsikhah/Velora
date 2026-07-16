<?php
/**
 * صفحه ویرایش پروفایل کاربری
 * @var array $user اطلاعات کاربر
 */

// تولید CSRF token اگر وجود ندارد
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<main class="dashboard-wrapper">
    <div class="container py-5">
        <div class="row">
            <!-- منوی پنل کاربری -->
            <div class="col-lg-3 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="avatar-circle mx-auto mb-3">
                                <i class="bi bi-person-fill fs-1"></i>
                            </div>
                            <h5 class="mb-1"><?= Security::e($user['full_name'] ?? $user['username']) ?></h5>
                            <p class="text-muted small mb-0"><?= Security::e($user['email']) ?></p>
                            <p class="text-muted small mt-2">
                                <i class="bi bi-calendar-check me-1"></i>
                                عضویت: <?php
                                    if (function_exists('jdf_strftime')) {
                                        echo jdf_strftime('%d %B %Y', strtotime($user['created_at']));
                                    } else {
                                        echo date('Y/m/d', strtotime($user['created_at']));
                                    }
                                ?>
                            </p>
                        </div>
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="<?= BASE_URL ?>/dashboard">
                                    <i class="bi bi-speedometer2 me-2"></i>
                                    داشبورد
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="<?= BASE_URL ?>/profile">
                                    <i class="bi bi-person-circle me-2"></i>
                                    ویرایش حساب
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= BASE_URL ?>/wishlist">
                                    <i class="bi bi-heart me-2"></i>
                                    علاقه‌مندی‌ها
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= BASE_URL ?>/orders">
                                    <i class="bi bi-bag-check me-2"></i>
                                    سفارشات من
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-danger" href="<?= BASE_URL ?>/logout">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    خروج از حساب
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- محتوای اصلی - فرم ویرایش -->
            <div class="col-lg-9">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h4 class="mb-0">
                            <i class="bi bi-pencil-square me-2"></i>
                            ویرایش اطلاعات حساب کاربری
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <?= $_SESSION['success'] ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <?= $_SESSION['error'] ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <form method="POST" action="<?= BASE_URL ?>/profile/update" id="profileForm">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                            <div class="row g-3">
                                <!-- نام و نام خانوادگی -->
                                <div class="col-md-6">
                                    <label for="full_name" class="form-label">
                                        نام و نام خانوادگی
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="full_name" 
                                           name="full_name" 
                                           value="<?= Security::e($user['full_name'] ?? '') ?>"
                                           required
                                           maxlength="100"
                                           placeholder="نام و نام خانوادگی خود را وارد کنید">
                                </div>

                                <!-- شغل -->
                                <div class="col-md-6">
                                    <label for="job" class="form-label">شغل</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="job" 
                                           name="job" 
                                           value="<?= Security::e($user['job'] ?? '') ?>"
                                           maxlength="100"
                                           placeholder="شغل خود را وارد کنید">
                                </div>

                                <!-- ایمیل -->
                                <div class="col-md-6">
                                    <label for="email" class="form-label">
                                        ایمیل
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" 
                                           class="form-control" 
                                           id="email" 
                                           name="email" 
                                           value="<?= Security::e($user['email']) ?>"
                                           required
                                           maxlength="150"
                                           placeholder="example@domain.com">
                                </div>

                                <!-- شماره موبایل -->
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">شماره موبایل</label>
                                    <input type="tel" 
                                           class="form-control" 
                                           id="phone" 
                                           name="phone" 
                                           value="<?= Security::e($user['phone'] ?? '') ?>"
                                           maxlength="11"
                                           pattern="09[0-9]{9}"
                                           placeholder="09123456789">
                                    <small class="text-muted">فرمت: 09123456789</small>
                                </div>

                                <!-- تاریخ تولد -->
                                <div class="col-md-6">
                                    <label for="birth_date" class="form-label">تاریخ تولد</label>
                                    <input type="text" 
                                           class="form-control persian-datepicker" 
                                           id="birth_date" 
                                           name="birth_date" 
                                           value="<?= Security::e($user['birth_date'] ?? '') ?>"
                                           placeholder="تاریخ تولد خود را انتخاب کنید"
                                           autocomplete="off">
                                    <small class="text-muted">تاریخ شمسی</small>
                                </div>

                                <!-- کد پستی -->
                                <div class="col-md-6">
                                    <label for="postal_code" class="form-label">کد پستی</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="postal_code" 
                                           name="postal_code" 
                                           value="<?= Security::e($user['postal_code'] ?? '') ?>"
                                           maxlength="10"
                                           pattern="[0-9]{10}"
                                           placeholder="1234567890">
                                    <small class="text-muted">کد پستی 10 رقمی</small>
                                </div>

                                <!-- آدرس -->
                                <div class="col-12">
                                    <label for="address" class="form-label">آدرس کامل</label>
                                    <textarea class="form-control" 
                                              id="address" 
                                              name="address" 
                                              rows="3"
                                              maxlength="500"
                                              placeholder="آدرس کامل پستی خود را وارد کنید"><?= Security::e($user['address'] ?? '') ?></textarea>
                                    <small class="text-muted">این آدرس برای ارسال سفارشات استفاده خواهد شد</small>
                                </div>

                                <!-- بخش تغییر رمز عبور -->
                                <div class="col-12">
                                    <hr class="my-4">
                                    <h5 class="mb-3">
                                        <i class="bi bi-shield-lock me-2"></i>
                                        تغییر رمز عبور
                                    </h5>
                                    <p class="text-muted mb-3">برای تغییر رمز عبور، از دکمه زیر استفاده کنید:</p>
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                        <i class="bi bi-key me-1"></i>
                                        تغییر رمز عبور
                                    </button>
                                </div>

                                <!-- دکمه‌ها -->
                                <div class="col-12">
                                    <hr class="my-4">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="<?= BASE_URL ?>/dashboard" class="btn btn-secondary">
                                            <i class="bi bi-x-circle me-1"></i>
                                            انصراف
                                        </a>
                                        <button type="submit" class="btn btn-primary" id="submitProfileBtn">
                                            <i class="bi bi-check-circle me-1"></i>
                                            ذخیره تغییرات
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- مدال تغییر رمز عبور -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">
                    <i class="bi bi-shield-lock me-2"></i>
                    تغییر رمز عبور
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        رمز عبور باید حداقل 8 کاراکتر، شامل حرف و عدد باشد.
                    </div>
                    
                    <div id="passwordChangeMessage" class="alert d-none" role="alert"></div>
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">
                            رمز عبور فعلی
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control" 
                                   id="current_password" 
                                   name="current_password" 
                                   required
                                   placeholder="رمز عبور فعلی خود را وارد کنید">
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label">
                            رمز عبور جدید
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control" 
                                   id="new_password" 
                                   name="new_password" 
                                   required
                                   minlength="8"
                                   placeholder="رمز عبور جدید">
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">
                            تکرار رمز عبور جدید
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control" 
                                   id="confirm_password" 
                                   name="confirm_password" 
                                   required
                                   minlength="8"
                                   placeholder="رمز عبور جدید را دوباره وارد کنید">
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="confirm_password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>
                    انصراف
                </button>
                <button type="button" class="btn btn-primary" id="submitPasswordChange">
                    <i class="bi bi-check-circle me-1"></i>
                    تغییر رمز عبور
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Persian Date Picker CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">

<!-- Persian Date Picker JS -->
<script src="https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>

<script>
// فیلد تاریخ تولد با Persian DatePicker
$(document).ready(function() {
    // تبدیل تاریخ میلادی به شمسی اگر وجود دارد
    var gregorianDate = $('#birth_date').val();
    var persianDateValue = '';
    
    if (gregorianDate) {
        try {
            // تبدیل تاریخ میلادی به شمسی
            var parts = gregorianDate.split('-');
            if (parts.length === 3) {
                var pd = new persianDate([parseInt(parts[0]), parseInt(parts[1]), parseInt(parts[2])]);
                persianDateValue = pd.format('YYYY/MM/DD');
            }
        } catch (e) {
            console.error('خطا در تبدیل تاریخ:', e);
        }
    }
    
    // Initialize Persian DatePicker
    $('.persian-datepicker').persianDatepicker({
        initialValue: persianDateValue !== '' ? true : false,
        initialValueType: 'persian',
        format: 'YYYY/MM/DD',
        autoClose: true,
        calendar: {
            persian: {
                locale: 'fa',
                showHint: true,
                leapYearMode: 'algorithmic'
            }
        },
        observer: true,
        altField: '#birth_date_hidden',
        altFormat: 'YYYY-MM-DD',
        altFieldFormatter: function(unixDate) {
            var pd = new persianDate(unixDate);
            var gd = pd.toCalendar('gregorian');
            return gd.format('YYYY-MM-DD');
        }
    });
    
    // اگر مقدار اولیه داشتیم، set کنیم
    if (persianDateValue) {
        $('.persian-datepicker').val(persianDateValue);
    }
});

// جلوگیری از ارسال مکرر فرم پروفایل
var isProfileSubmitting = false;
var lastProfileSubmitTime = 0;
var MIN_SUBMIT_INTERVAL = 3000; // 3 ثانیه

$('#profileForm').on('submit', function(e) {
    var now = Date.now();
    var timeSinceLastSubmit = now - lastProfileSubmitTime;
    
    // بررسی زمان از آخرین ارسال
    if (timeSinceLastSubmit < MIN_SUBMIT_INTERVAL) {
        e.preventDefault();
        var remainingTime = Math.ceil((MIN_SUBMIT_INTERVAL - timeSinceLastSubmit) / 1000);
        
        if (typeof showNotification === 'function') {
            showNotification('لطفاً ' + remainingTime + ' ثانیه صبر کنید', 'error');
        } else {
            alert('لطفاً ' + remainingTime + ' ثانیه صبر کنید');
        }
        return false;
    }
    
    // اگر در حال ارسال است
    if (isProfileSubmitting) {
        e.preventDefault();
        return false;
    }
    
    // تبدیل تاریخ شمسی به میلادی برای ارسال
    var persianDate = $('#birth_date').val();
    if (persianDate) {
        try {
            var parts = persianDate.split('/');
            if (parts.length === 3) {
                var pd = new persianDate(parseInt(parts[0]), parseInt(parts[1]), parseInt(parts[2]));
                var gd = pd.toCalendar('gregorian');
                var gregorianDate = gd.format('YYYY-MM-DD');
                
                // اضافه کردن فیلد مخفی برای تاریخ میلادی
                $('<input>').attr({
                    type: 'hidden',
                    name: 'birth_date',
                    value: gregorianDate
                }).appendTo('#profileForm');
                
                // غیرفعال کردن فیلد اصلی
                $('#birth_date').prop('disabled', true);
            }
        } catch (error) {
            console.error('خطا در تبدیل تاریخ:', error);
        }
    }
    
    // علامت‌گذاری که در حال ارسال است
    isProfileSubmitting = true;
    lastProfileSubmitTime = now;
    
    // غیرفعال کردن دکمه
    var $btn = $('#submitProfileBtn');
    $btn.prop('disabled', true)
        .html('<i class="bi bi-hourglass-split me-1"></i> در حال ذخیره...');
    
    // بعد از 3 ثانیه دوباره فعال شود (برای safety)
    setTimeout(function() {
        isProfileSubmitting = false;
        $btn.prop('disabled', false)
            .html('<i class="bi bi-check-circle me-1"></i> ذخیره تغییرات');
    }, 3000);
});

// تغییر رمز عبور با AJAX و Rate Limiting
var isPasswordChanging = false;
var lastPasswordChangeTime = 0;

document.getElementById('submitPasswordChange').addEventListener('click', function() {
    var now = Date.now();
    var timeSinceLastChange = now - lastPasswordChangeTime;
    
    // بررسی Rate Limiting
    if (timeSinceLastChange < MIN_SUBMIT_INTERVAL) {
        var remainingTime = Math.ceil((MIN_SUBMIT_INTERVAL - timeSinceLastChange) / 1000);
        var messageDiv = document.getElementById('passwordChangeMessage');
        messageDiv.className = 'alert alert-warning';
        messageDiv.textContent = 'لطفاً ' + remainingTime + ' ثانیه صبر کنید';
        messageDiv.classList.remove('d-none');
        return;
    }
    
    if (isPasswordChanging) {
        return;
    }
    
    const form = document.getElementById('changePasswordForm');
    const formData = new FormData(form);
    const messageDiv = document.getElementById('passwordChangeMessage');
    const submitBtn = this;
    
    // بررسی تطابق رمز عبورها
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (newPassword !== confirmPassword) {
        messageDiv.className = 'alert alert-danger';
        messageDiv.textContent = 'رمز عبور جدید و تکرار آن یکسان نیستند';
        messageDiv.classList.remove('d-none');
        return;
    }
    
    // علامت‌گذاری که در حال پردازش است
    isPasswordChanging = true;
    lastPasswordChangeTime = now;
    
    // غیرفعال کردن دکمه
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> در حال پردازش...';
    
    // ارسال درخواست
    fetch('<?= BASE_URL ?>/profile/change-password', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageDiv.className = 'alert alert-success';
            messageDiv.innerHTML = '<i class="bi bi-check-circle me-2"></i>' + data.message;
            messageDiv.classList.remove('d-none');
            
            // پاک کردن فرم
            form.reset();
            
            // بستن مدال بعد از 2 ثانیه
            setTimeout(() => {
                bootstrap.Modal.getInstance(document.getElementById('changePasswordModal')).hide();
                messageDiv.classList.add('d-none');
                isPasswordChanging = false;
            }, 2000);
        } else {
            messageDiv.className = 'alert alert-danger';
            messageDiv.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>' + data.message;
            messageDiv.classList.remove('d-none');
            isPasswordChanging = false;
        }
    })
    .catch(error => {
        messageDiv.className = 'alert alert-danger';
        messageDiv.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>خطا در ارتباط با سرور';
        messageDiv.classList.remove('d-none');
        isPasswordChanging = false;
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>تغییر رمز عبور';
    });
});

// نمایش/مخفی کردن رمز عبور
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const icon = this.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    });
});

// پاک کردن پیام‌ها هنگام بسته شدن مدال
document.getElementById('changePasswordModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('changePasswordForm').reset();
    document.getElementById('passwordChangeMessage').classList.add('d-none');
    isPasswordChanging = false;
});
</script>

<style>
.avatar-circle {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.nav-pills .nav-link {
    color: #6c757d;
    border-radius: 0.5rem;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
}

.nav-pills .nav-link:hover {
    background-color: #f8f9fa;
    color: #495057;
}

.nav-pills .nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.card {
    border-radius: 1rem;
}

.card-header {
    border-radius: 1rem 1rem 0 0 !important;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5568d3 0%, #63408a 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
}

/* استایل Persian DatePicker */
.pwt-btn-today,
.pwt-btn-submit,
.pwt-btn-cancel,
.pwt-btn-calendar {
    font-family: 'Vazirmatn', sans-serif !important;
}

.pwt-datepicker-input-element {
    direction: rtl !important;
    text-align: right !important;
}

.persian-datepicker {
    cursor: pointer;
}

.persian-datepicker:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

</style>
