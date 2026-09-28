<?php
/**
 * کلاس کمکی برای آپلود و مدیریت تصاویر
 */
class ImageUploader {
    
    private const MAX_SIZE = 1 * 1024 * 1024; // 1MB

    // چون mime اینطوری برمیگردونه
    // [
    // 'image/jpeg',
    // 'image/jpg',
    // 'image/png',
    // 'image/webp'
    // ]
    private const ALLOWED_TYPES = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    
    /**
     * آپلود عکس پروفایل
     */
    public static function uploadProfileImage(array $file, int $userId, string $username = ''): array {
        // $_FILES['profile_image']   array super global (files)
        // name
        // size
        // tmp_name
        // error
        // type

        // بررسی خطاهای آپلود
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'خطا در آپلود فایل'];
        }

        // بررسی حجم
        if ($file['size'] > self::MAX_SIZE) {
            return ['success' => false, 'error' => 'حجم فایل نباید بیشتر از 1 مگابایت باشد'];
        }

        // بررسی نوع فایل
        //finfo_file محتوای فایل رو بررسی میکنه
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, self::ALLOWED_TYPES)) {
            return ['success' => false, 'error' => 'فقط فایل‌های تصویری (JPG, JPEG, PNG, WEBP) مجاز هستند'];
        }

        // بررسی اینکه واقعاً تصویر است
        //getimagesize  = width - height - type - mime
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            return ['success' => false, 'error' => 'فایل آپلود شده یک تصویر معتبر نیست'];
        }

        // تولید نام فایل یکتا با نام کاربر
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION); // گرفتن پسوند فایل
        $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $username); // حذف کاراکترهای غیرمجاز
        if (empty($safeName)) {
            $safeName = 'user';
        }
        $fileName = 'profile_' . $userId . '_' . $safeName . '_' . time() . '.' . $extension;
        
        // مسیر ذخیره
        $uploadDir = BASE_PATH . '/public/uploads/profiles';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);// true پوشه های والد اگر وجود ندارن بسازشون
        }
        // ساخت مسیر کامل فایل
        $filePath = $uploadDir . '/' . $fileName;
        
        // انتقال فایل
        // فایل آپلود شده ابتدا در یک فایل موقت قرار دارد: $file['tmp_name']
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // تغییر اندازه تصویر
            self::resizeImage($filePath, 300, 300);
            
            return [
                'success' => true,
                'path' => '/uploads/profiles/' . $fileName
            ];
        }

        return ['success' => false, 'error' => 'خطا در ذخیره فایل'];
    }
    
    /**
     * حذف عکس پروفایل
     */
    public static function deleteProfileImage(?string $imagePath): bool {
        if (empty($imagePath)) {
            return false;
        }

        $fullPath = BASE_PATH . '/public' . $imagePath;
        if (file_exists($fullPath)) {
            return @unlink($fullPath); // تابع unlink() برای حذف فایل از سرور است.
        }

        return false;
    }
    
    /**
     * تغییر اندازه تصویر
     */
    private static function resizeImage(string $filePath, int $width, int $height): bool {
        $imageInfo = getimagesize($filePath);
        if ($imageInfo === false) {
            return false;
        }

        list($origWidth, $origHeight, $imageType) = $imageInfo;

        // ایجاد تصویر از فایل اصلی
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $srcImage = imagecreatefromjpeg($filePath);
                break;
            case IMAGETYPE_PNG:
                $srcImage = imagecreatefrompng($filePath);
                break;
            case IMAGETYPE_GIF:
                $srcImage = imagecreatefromgif($filePath);
                break;
            case IMAGETYPE_WEBP:
                $srcImage = imagecreatefromwebp($filePath);
                break;
            default: // اگر نوع تصویر پشتیبانی نشود، عملیات متوقف می‌شود
                return false;
        }

        // محاسبه ابعاد جدید (حفظ نسبت)
        $ratio = min($width / $origWidth, $height / $origHeight);
        $newWidth = (int)($origWidth * $ratio);
        $newHeight = (int)($origHeight * $ratio);

        // ایجاد تصویر جدید
        $dstImage = imagecreatetruecolor($newWidth, $newHeight);

        // حفظ شفافیت برای PNG و GIF    (Transparency موقع ساخت فایل جدید حفظ میشه)
        if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
            imagealphablending($dstImage, false);
            imagesavealpha($dstImage, true);
        }

        // تغییر اندازه
        // $dstImage    → مقصد
        // $srcImage    → منبع
        // صفر ها مختصات شروع هستن
        // $newWidth    → عرض جدید
        // $newHeight   → ارتفاع جدید
        // $origWidth   → عرض اصلی
        // $origHeight  → ارتفاع اصلی
        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        // ذخیره تصویر
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                // 90 = کیفیت
                imagejpeg($dstImage, $filePath, 90);
                break;
            case IMAGETYPE_PNG:
                // 9 = سطح فشرده سازی
                imagepng($dstImage, $filePath, 9);
                break;
            case IMAGETYPE_GIF:
                imagegif($dstImage, $filePath);
                break;
            case IMAGETYPE_WEBP:
                // 90 = کیفیت
                imagewebp($dstImage, $filePath, 90);
                break;
        }
        // آزاد کردن حافظه
        imagedestroy($srcImage);
        imagedestroy($dstImage);

        return true;
    }
}
