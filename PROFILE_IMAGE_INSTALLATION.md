# راهنمای نصب قابلیت عکس پروفایل

## مراحل نصب:

### 1. اجرای فایل دیتابیس
مراجعه به:
```
http://localhost/Velora/config/setup_database_v7.php
```

این فایل:
- فیلد `profile_image` را به جدول `users` اضافه می‌کند
- پوشه `public/uploads/profiles` را ایجاد می‌کند  
- فایل `.htaccess` برای امنیت ایجاد می‌کند

### 2. به‌روزرسانی public/index.php
در فایل `public/index.php` بعد از خط:
```php
require_once BASE_PATH . '/src/Models/ProductModel.php';
```

این خط را اضافه کنید:
```php
require_once BASE_PATH . '/src/Helpers/ImageUploader.php';
```

### 3. به‌روزرسانی UserController.php  
فایل `src/Controllers/UserController.php` را باز کنید و متد `updateProfile` را با کد زیر جایگزین کنید:

```php
public function updateProfile(): void {
    $this->checkAuth();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . BASE_URL . '/profile');
        exit;
    }

    // بررسی CSRF Token
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (empty($csrfToken) || !isset($_SESSION['csrf_token']) || $csrfToken !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = 'درخواست نامعتبر است.';
        header('Location: ' . BASE_URL . '/profile');
        exit;
    }

    $userId   = (int)$_SESSION['user_id'];
    
    // پردازش آپلود عکس
    $profileImage = null;
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadResult = ImageUploader::uploadProfileImage($_FILES['profile_image'], $userId);
        if ($uploadResult['success']) {
            // حذف عکس قدیمی
            $oldImage = $this->userModel->deleteOldProfileImage($userId);
            if ($oldImage) {
                ImageUploader::deleteProfileImage($oldImage);
            }
            $profileImage = $uploadResult['path'];
        } else {
            $_SESSION['error'] = $uploadResult['error'];
            header('Location: ' . BASE_URL . '/profile');
            exit;
        }
    }

    $fullName = trim($_POST['full_name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $job      = trim($_POST['job'] ?? '');
    $birthDate = trim($_POST['birth_date'] ?? '');
    $postalCode = trim($_POST['postal_code'] ?? '');

    // اعتبارسنجی... (کد قبلی)
    // ...

    $data = [
        'full_name' => $fullName,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'job' => $job,
        'birth_date' => $birthDate,
        'postal_code' => $postalCode
    ];
    
    if ($profileImage) {
        $data['profile_image'] = $profileImage;
    }
    
    $result = $this->userModel->updateProfile($userId, $data);

    if ($result) {
        $_SESSION['success'] = 'اطلاعات شما با موفقیت به‌روزرسانی شد.';
    } else {
        $_SESSION['error'] = 'خطا در به‌روزرسانی اطلاعات.';
    }

    header('Location: ' . BASE_URL . '/profile');
    exit;
}
```

### 4. به‌روزرسانی صفحه profile.php
در فایل `src/Views/pages/profile.php`:

**الف) تغییر form tag:**
```php
<form method="POST" action="<?= BASE_URL ?>/profile/update" id="profileForm" enctype="multipart/form-data">
```

**ب) افزودن بخش آپلود عکس** (قبل از فیلد نام و نام خانوادگی):
```php
<!-- آپلود عکس پروفایل -->
<div class="col-12 mb-4">
    <label class="form-label">عکس پروفایل</label>
    <div class="profile-image-upload">
        <div class="current-profile-image">
            <?php if (!empty($user['profile_image'])): ?>
                <img src="<?= BASE_URL . Security::e($user['profile_image']) ?>" alt="Profile" id="profileImagePreview">
            <?php else: ?>
                <img src="<?= BASE_URL ?>/assets/images/default-avatar.png" alt="Default" id="profileImagePreview">
            <?php endif; ?>
        </div>
        <div class="profile-upload-controls">
            <input type="file" 
                   class="form-control" 
                   id="profile_image" 
                   name="profile_image" 
                   accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                   onchange="previewProfileImage(this)">
            <small class="text-muted">فرمت: JPG, PNG, GIF, WEBP | حداکثر: 2MB</small>
        </div>
    </div>
</div>
```

**ج) افزودن CSS** (در قسمت style):
```css
.profile-image-upload {
    display: flex;
    align-items: center;
    gap: 20px;
}

.current-profile-image {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.current-profile-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-upload-controls {
    flex: 1;
}
```

**د) افزودن JavaScript** (قبل از تگ </script> آخر):
```javascript
// Preview عکس پروفایل
function previewProfileImage(input) {
    if (input.files && input.files[0]) {
        // بررسی حجم
        if (input.files[0].size > 2 * 1024 * 1024) {
            alert('حجم فایل نباید بیشتر از 2 مگابایت باشد');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profileImagePreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
```

### 5. به‌روزرسانی منوی سمت چپ profile
در بخش avatar، عکس را نمایش دهید:
```php
<div class="avatar-circle mx-auto mb-3">
    <?php if (!empty($user['profile_image'])): ?>
        <img src="<?= BASE_URL . Security::e($user['profile_image']) ?>" 
             alt="<?= Security::e($user['full_name'] ?? $user['username']) ?>"
             style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
    <?php else: ?>
        <i class="bi bi-person-fill fs-1"></i>
    <?php endif; ?>
</div>
```

### 6. به‌روزرسانی navbar.php
در منوی کاربر، عکس پروفایل را نمایش دهید:
```php
<?php if (!empty($_SESSION['logged_in'])): ?>
    <li class="has-dropdown user-menu-dropdown">
        <button class="nav-link-btn" aria-expanded="false">
            <?php 
            $userModel = new UserModel();
            $currentUser = $userModel->getById((int)$_SESSION['user_id']);
            ?>
            <?php if (!empty($currentUser['profile_image'])): ?>
                <img src="<?= BASE_URL . $currentUser['profile_image'] ?>" 
                     alt="Profile" 
                     class="user-avatar-small">
            <?php else: ?>
                <i class="fas fa-user" aria-hidden="true"></i>
            <?php endif; ?>
            <span><?= Security::e($_SESSION['username']) ?></span>
            <i class="fas fa-chevron-down" aria-hidden="true"></i>
        </button>
        <ul class="dropdown-menu">
            ...
        </ul>
    </li>
<?php endif; ?>
```

و CSS برای آواتار کوچک:
```css
.user-avatar-small {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    margin-left: 8px;
}
```

### 7. ایجاد تصویر پیش‌فرض
یک تصویر پیش‌فرض در:
```
public/assets/images/default-avatar.png
```

یا از این SVG استفاده کنید (data URI):
```php
$defaultAvatar = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48Y2lyY2xlIGN4PSI1MCIgY3k9IjUwIiByPSI1MCIgZmlsbD0iIzY2N2VlYSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTUlIiBmb250LXNpemU9IjQwIiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+8J+RpDwvdGV4dD48L3N2Zz4=';
```

## تست:
1. لاگین کنید
2. به صفحه پروفایل بروید
3. عکسی را انتخاب کنید
4. ذخیره کنید
5. عکس باید در منوی سمت چپ و در هدر نمایش داده شود

## امنیت:
✅ فقط فایل‌های تصویری مجاز  
✅ حداکثر 2MB  
✅ تغییر نام فایل برای امنیت  
✅ تغییر اندازه خودکار به 300x300  
✅ .htaccess برای جلوگیری از اجرای PHP  
✅ حذف خودکار عکس قدیمی  

## پشتیبانی:
در صورت بروز مشکل، لاگ‌ها را در `logs/error.log` بررسی کنید.
