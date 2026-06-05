<?php
// الاتصال بقاعدة البيانات
include("./conaction.php");

// فحص وجود الكوكيز
if (!isset($_COOKIE['id_user'])) {
    header("Location: login.php");
    exit();
}

// جلب قيمة الكوكيز (رقم المستخدم)
$user_id = intval($_COOKIE['id_user']);

// التحقق من المستخدم في قاعدة البيانات
$stmt = $conn->prepare("SELECT status FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// التحقق من وجود المستخدم
if ($result->num_rows === 0) {
    header("Location: Eroor-430.php");
    exit();
}

$user = $result->fetch_assoc();

// التحقق من نوع المستخدم
if ($user['status'] === 'admin'|| $user['status'] === 'root_admin') {
    // ✅ تأكد أننا لا نحاول إعادة توجيه من نفس الصفحة
    if (basename($_SERVER['PHP_SELF']) !== 'cratadmin.php') {
        header("Location: cratadmin.php");
        exit();
    }
} else {
    header("Location: Eroor-430.php");
    exit();
}
?>
