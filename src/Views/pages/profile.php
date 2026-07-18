<?php
/**
 * صفحه ویرایش پروفایل کاربری
 * @var array $user اطلاعات کاربر
 */
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
                                <?php if (!empty($user['profile_image'])): ?>
                                    <img src="<?= BASE_URL . $user['profile_image'] ?>" 
                                         alt="<?= Security::e($user['full_name'] ?? $user['username']) ?>"
                                         class="profile-avatar-img"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <i class="bi bi-person-fill fs-1" style="display: none;"></i>
                                <?php else: ?>
                                    <i class="bi bi-person-fill fs-1"></i>
                                <?php endif; ?>
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
                        <form method="POST" action="<?= BASE_URL ?>/profile/update" id="profileForm">

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

                                <!-- نام کاربری -->
                                <div class="col-md-6">
                                    <label for="username" class="form-label">
                                        نام کاربری
                                        <span class="text-muted small">(قابل تغییر)</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="username" 
                                           name="username" 
                                           value="<?= Security::e($user['username']) ?>"
                                           maxlength="50"
                                           pattern="[a-zA-Z0-9_\-\.]{3,50}"
                                           placeholder="نام کاربری خود را وارد کنید">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        ۳ تا ۵۰ کاراکتر انگلیسی، عدد، خط تیره یا نقطه
                                    </small>
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
                                    <input type="date" 
                                           class="form-control" 
                                           id="birth_date" 
                                           name="birth_date" 
                                           value="<?= Security::e($user['birth_date'] ?? '') ?>"
                                           max="<?= date('Y-m-d') ?>">
                                    <small class="text-muted">تاریخ میلادی</small>
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

                                <!-- بخش تغییر رمز عبور و آپلود عکس پروفایل -->
                                <div class="col-12">
                                    <hr class="my-4">
                                    <div class="row">
                                        <!-- تغییر رمز عبور -->
                                        <div class="col-md-6">
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

                                        <!-- آپلود عکس پروفایل -->
                                        <div class="col-md-6">
                                            <h5 class="mb-3">
                                                <i class="bi bi-image me-2"></i>
                                                عکس پروفایل
                                            </h5>
                                            <p class="text-muted mb-3">برای تغییر یا آپلود عکس پروفایل، از دکمه زیر استفاده کنید:</p>
                                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadProfileImageModal">
                                                <i class="bi bi-camera me-1"></i>
                                                آپلود عکس پروفایل
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- دکمه‌ها -->
                                <div class="col-12">
                                    <hr class="my-4">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="<?= BASE_URL ?>/dashboard" class="btn btn-secondary">
                                            <i class="bi bi-x-circle me-1"></i>
                                            انصراف
                                        </a>
                                        <button type="submit" class="btn btn-primary">
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

<!-- مدال آپلود عکس پروفایل -->
<div class="modal fade" id="uploadProfileImageModal" tabindex="-1" aria-labelledby="uploadProfileImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadProfileImageModalLabel">
                    <i class="bi bi-camera me-2"></i>
                    آپلود عکس پروفایل
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="uploadProfileImageForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        فرمت‌های مجاز: JPG, JPEG, PNG, WEBP | حداکثر حجم: 1MB
                    </div>
                    
                    <div class="profile-image-upload-modal">
                        <div class="current-profile-image-modal">
                            <?php if (!empty($user['profile_image'])): ?>
                                <img src="<?= BASE_URL . $user['profile_image'] ?>" 
                                     alt="Profile" 
                                     id="profileImagePreviewModal"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="default-avatar-modal" style="display: none;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            <?php else: ?>
                                <div class="default-avatar-modal">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="profile-upload-controls-modal">
                            <label for="profile_image_modal" class="btn btn-outline-primary w-100">
                                <i class="bi bi-upload me-2"></i>
                                انتخاب عکس جدید
                            </label>
                            <input type="file" 
                                   class="d-none" 
                                   id="profile_image_modal" 
                                   name="profile_image" 
                                   accept="image/jpeg,image/jpg,image/png,image/webp"
                                   onchange="previewProfileImageModal(this)">
                            
                            <?php if (!empty($user['profile_image'])): ?>
                                <button type="button" class="btn btn-outline-danger w-100 mt-2" onclick="removeProfileImageModal()">
                                    <i class="bi bi-trash me-1"></i>
                                    حذف عکس فعلی
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>
                        انصراف
                    </button>
                    <button type="button" class="btn btn-primary" id="submitProfileImage">
                        <i class="bi bi-check-circle me-1"></i>
                        ذخیره تغییرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// پیش‌نمایش عکس پروفایل در مدال
function previewProfileImageModal(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // بررسی نوع فایل
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            showNotification('فقط فایل‌های تصویری (JPG, JPEG, PNG, WEBP) مجاز هستند', 'error');
            input.value = '';
            return;
        }
        
        // بررسی حجم فایل (1MB)
        if (file.size > 1 * 1024 * 1024) {
            showNotification('حجم فایل نباید بیشتر از 1 مگابایت باشد', 'error');
            input.value = '';
            return;
        }
        
        // نمایش پیش‌نمایش
        const reader = new FileReader();
        reader.onload = function(e) {
            const imageContainer = document.querySelector('.current-profile-image-modal');
            imageContainer.innerHTML = '<img src="' + e.target.result + '" alt="پیش‌نمایش" id="profileImagePreviewModal">';
        };
        reader.readAsDataURL(file);
    }
}

// حذف عکس پروفایل در مدال
function removeProfileImageModal() {
    if (confirm('آیا مطمئن هستید که می‌خواهید عکس پروفایل خود را حذف کنید؟')) {
        const submitBtn = document.getElementById('submitProfileImage');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> در حال حذف...';
        
        // ارسال درخواست حذف
        fetch('<?= BASE_URL ?>/profile/remove-image', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message || 'عکس پروفایل با موفقیت حذف شد', 'success');
                
                // بستن مدال
                setTimeout(() => {
                    bootstrap.Modal.getInstance(document.getElementById('uploadProfileImageModal')).hide();
                    // رفرش صفحه
                    location.reload();
                }, 1500);
            } else {
                showNotification(data.message || 'خطا در حذف عکس', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>ذخیره تغییرات';
            }
        })
        .catch(error => {
            showNotification('خطا در ارتباط با سرور', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>ذخیره تغییرات';
        });
    }
}

// آپلود عکس پروفایل
document.getElementById('submitProfileImage').addEventListener('click', function() {
    const form = document.getElementById('uploadProfileImageForm');
    const formData = new FormData(form);
    const submitBtn = this;
    
    // بررسی انتخاب فایل
    const fileInput = document.getElementById('profile_image_modal');
    if (!fileInput.files || !fileInput.files[0]) {
        showNotification('لطفا یک تصویر انتخاب کنید', 'error');
        return;
    }
    
    // غیرفعال کردن دکمه
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> در حال آپلود...';
    
    // ارسال درخواست
    fetch('<?= BASE_URL ?>/profile/upload-image', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message || 'عکس پروفایل با موفقیت آپلود شد', 'success');
            
            // بستن مدال
            setTimeout(() => {
                bootstrap.Modal.getInstance(document.getElementById('uploadProfileImageModal')).hide();
                // رفرش صفحه
                location.reload();
            }, 1500);
        } else {
            showNotification(data.message || 'خطا در آپلود عکس', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>ذخیره تغییرات';
        }
    })
    .catch(error => {
        showNotification('خطا در ارتباط با سرور', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>ذخیره تغییرات';
    });
});

// تغییر رمز عبور با AJAX
document.getElementById('submitPasswordChange').addEventListener('click', function() {
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
            showNotification(data.message || 'رمز عبور با موفقیت تغییر یافت', 'success');
            
            // پاک کردن فرم
            form.reset();
            messageDiv.classList.add('d-none');
            
            // بستن مدال بعد از 2 ثانیه
            setTimeout(() => {
                bootstrap.Modal.getInstance(document.getElementById('changePasswordModal')).hide();
            }, 2000);
        } else {
            showNotification(data.message || 'خطا در تغییر رمز عبور', 'error');
        }
    })
    .catch(error => {
        showNotification('خطا در ارتباط با سرور', 'error');
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

// پاک کردن فرم‌ها هنگام بسته شدن مدال‌ها
document.getElementById('changePasswordModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('changePasswordForm').reset();
    document.getElementById('passwordChangeMessage').classList.add('d-none');
});

document.getElementById('uploadProfileImageModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('uploadProfileImageForm').reset();
});
</script>

<!-- نمایش پیغام‌های session -->
<?php if (isset($_SESSION['profile_success'])): ?>
<script>
window.addEventListener('load', function() {
    if (typeof showNotification === 'function') {
        showNotification('<?= addslashes($_SESSION['profile_success']) ?>', 'success');
    }
});
</script>
<?php unset($_SESSION['profile_success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['profile_error'])): ?>
<script>
window.addEventListener('load', function() {
    if (typeof showNotification === 'function') {
        showNotification('<?= addslashes($_SESSION['profile_error']) ?>', 'error');
    }
});
</script>
<?php unset($_SESSION['profile_error']); ?>
<?php endif; ?>

<!-- JavaScript محافظت غیرفعال شد - حالا فقط PHP کار می‌کند -->
<!-- <script src="<?= BASE_URL ?>/assets/js/profile-protection.js"></script> -->

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

/* استایل مدال آپلود عکس پروفایل */
.profile-image-upload-modal {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 24px;
    padding: 20px;
}

.current-profile-image-modal {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid #667eea;
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
    position: relative;
}

.current-profile-image-modal img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.default-avatar-modal {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 5rem;
}

.profile-upload-controls-modal {
    width: 100%;
    max-width: 300px;
}

.profile-avatar-img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border: solid black;
    object-fit: cover;
}
</style>
