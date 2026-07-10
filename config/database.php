<?php
/**
 * تنظیمات و اتصال به پایگاه داده
 * بدون PDO - استفاده از mysqli
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'autumn_shop');
define('DB_CHARSET', 'utf8mb4');

/**
 * ایجاد اتصال به پایگاه داده
 */
function db_connect() {
    static $conn = null;
    if ($conn === null) {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$conn) {
            error_log('DB Connection failed: ' . mysqli_connect_error());
            die(json_encode(['error' => 'خطا در اتصال به پایگاه داده']));
        }
        mysqli_set_charset($conn, DB_CHARSET);
        mysqli_query($conn, "SET NAMES 'utf8mb4'");
        mysqli_query($conn, "SET CHARACTER SET utf8mb4");
    }
    return $conn;
}

/**
 * فرار از رشته برای جلوگیری از SQL Injection
 */
function db_escape($value) {
    $conn = db_connect();
    return mysqli_real_escape_string($conn, trim((string)$value));
}

/**
 * اجرای کوئری با مدیریت خطا
 */
function db_query($sql) {
    $conn = db_connect();
    $result = mysqli_query($conn, $sql);
    if ($result === false) {
        error_log('DB Query Error: ' . mysqli_error($conn) . ' | SQL: ' . $sql);
        return false;
    }
    return $result;
}

/**
 * واکشی یک ردیف
 */
function db_fetch_one($sql) {
    $result = db_query($sql);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

/**
 * واکشی تمام ردیف‌ها
 */
function db_fetch_all($sql) {
    $result = db_query($sql);
    $rows = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        mysqli_free_result($result);
    }
    return $rows;
}

/**
 * درج داده و برگرداندن ID
 */
function db_insert($sql) {
    $conn = db_connect();
    if (db_query($sql)) {
        return mysqli_insert_id($conn);
    }
    return false;
}

/**
 * تعداد ردیف‌های affected
 */
function db_affected_rows() {
    $conn = db_connect();
    return mysqli_affected_rows($conn);
}
