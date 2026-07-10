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

                        <form method="POST" action="<?= BASE_URL ?>/profile/update">
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
                                <div class="col-md-12">
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
                                    <a href="<?= BASE_URL ?>/change-password" class="btn btn-outline-primary">
                                        <i class="bi bi-key me-1"></i>
                                        تغییر رمز عبور
                                    </a>
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
</style>
