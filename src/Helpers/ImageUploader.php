<?php
/**
 * کلاس کمکی برای آپلود و مدیریت تصاویر
 */
class ImageUploader {
    
    private const MAX_SIZE = 2 * 1024 * 1024; // 2MB
    private const ALLOWED_TYPES = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    
    /**
     * آپلود عکس پروفایل
     */
    public static function uploadProfileImage(array $file, int $userId): array {
        // بررسی خطاهای آپلود
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'خطا در آپلود فایل'];
        }

        // بررسی حجم
        if ($file['size'] > self::MAX_SIZE) {
            return ['success' => false, 'error' => 'حجم فایل نباید بیشتر از 2 مگابایت باشد'];
        }

        // بررسی نوع فایل
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, self::ALLOWED_TYPES)) {
            return ['success' => false, 'error' => 'فقط فایل‌های تصویری (JPG, PNG, GIF, WEBP) مجاز هستند'];
        }

        // بررسی اینکه واقعاً تصویر است
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            return ['success' => false, 'error' => 'فایل آپلود شده یک تصویر معتبر نیست'];
        }

        // تولید نام فایل یکتا
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = 'profile_' . $userId . '_' . time() . '.' . $extension;
        
        // مسیر ذخیره
        $uploadDir = BASE_PATH . '/public/uploads/profiles';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filePath = $uploadDir . '/' . $fileName;
        
        // انتقال فایل
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
            return @unlink($fullPath);
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
            default:
                return false;
        }

        // محاسبه ابعاد جدید (حفظ نسبت)
        $ratio = min($width / $origWidth, $height / $origHeight);
        $newWidth = (int)($origWidth * $ratio);
        $newHeight = (int)($origHeight * $ratio);

        // ایجاد تصویر جدید
        $dstImage = imagecreatetruecolor($newWidth, $newHeight);

        // حفظ شفافیت برای PNG و GIF
        if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
            imagealphablending($dstImage, false);
            imagesavealpha($dstImage, true);
        }

        // تغییر اندازه
        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        // ذخیره تصویر
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                imagejpeg($dstImage, $filePath, 90);
                break;
            case IMAGETYPE_PNG:
                imagepng($dstImage, $filePath, 9);
                break;
            case IMAGETYPE_GIF:
                imagegif($dstImage, $filePath);
                break;
            case IMAGETYPE_WEBP:
                imagewebp($dstImage, $filePath, 90);
                break;
        }

        imagedestroy($srcImage);
        imagedestroy($dstImage);

        return true;
    }
    
    /**
     * دریافت URL کامل عکس پروفایل
     */
    public static function getProfileImageUrl(?string $imagePath): string {
        if (empty($imagePath)) {
            return BASE_URL . '/assets/images/default-avatar.png';
        }

        return BASE_URL . $imagePath;
    }
}
