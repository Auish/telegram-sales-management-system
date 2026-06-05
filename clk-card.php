 
<?php
include("./admin/include/conaction.php");

$message_grop = "";
$message_type_grop = "";

// الحصول على رصيد المستخدم
$current_balance = 0;
$user_id = isset($_COOKIE['id_user']) ? $_COOKIE['id_user'] : null;

if ($user_id) {
    $balance_stmt = $conn->prepare("SELECT balance FROM wallets WHERE id_user  = ?");
    $balance_stmt->bind_param("i", $user_id);
    $balance_stmt->execute();
    $balance_result = $balance_stmt->get_result();
    
    if ($balance_result->num_rows > 0) {
        $wallet = $balance_result->fetch_assoc();
        $current_balance = $wallet['balance'];
    }
    $balance_stmt->close();
}

// معالجة طلب السحب
if (isset($_POST['submit-bton'])) {
    // التحقق من تسجيل الدخول
    if (!isset($_COOKIE['id_user'])) {
        header("Location: login.php");
        exit();
    }
    
    $user_id = $_COOKIE['id_user'];
    $amount = trim($_POST['postal'] ?? '');
    $method = trim($_POST['country'] ?? '');
    $acount_number = trim($_POST['acouant-numper'] ?? '');
    
    // التحقق من البيانات المدخلة
    if (empty($amount) || empty($method) || empty($acount_number)) {
        $message_grop = "الرجاء تعبئة جميع الحقول المطلوبة";
        $message_type_grop = "error";
    } elseif (!is_numeric($amount) || $amount <= 0) {
        $message_grop = "المبلغ يجب أن يكون رقم صحيح أكبر من الصفر";
        $message_type_grop = "error";
    } else {
        // التحقق من رصيد المستخدم
        $wallet_stmt = $conn->prepare("SELECT balance FROM wallets WHERE id_user = ?");
        $wallet_stmt->bind_param("i", $user_id);
        $wallet_stmt->execute();
        $wallet_result = $wallet_stmt->get_result();
        
        if ($wallet_result->num_rows > 0) {
            $wallet = $wallet_result->fetch_assoc();
            $current_balance = $wallet['balance'];
            
            // حساب العمولة والمبلغ النهائي
            $commission = 5.00; // عمولة ثابتة
            $total_after_commission = $amount - $commission;
            $date = date('Y-m-d H:i:s'); // التاريخ والوقت الحالي
            // التحقق إذا كان الرصيد كافي بعد العمولة
            if ($current_balance >= $amount && $total_after_commission > 0) {
                // إدخال البيانات في جدول المعاملات
                $admin_note = "";
                $status = "قيد المراجعة";
                
                $stmt = $conn->prepare("INSERT INTO withdraw_requests (user_id,wallet_type,amount,status,wallet_number,created_at,admin_note) VALUES (?, ?, ?, ?, ?, ?,?)");
                
                if ($stmt) {
                    $stmt->bind_param("isdssss", $user_id, $method, $amount, $status, $acount_number, $date, $admin_note);
                    
                    if ($stmt->execute()) {
                        $message_grop = "تم إرسال طلب السحب بنجاح ✅ وسيتم مراجعته قريباً";
                        $message_type_grop = "success";
                        
                        // تحديث الرصيد الحالي
                        $current_balance = $current_balance - $amount;
                    } else {
                        $message_grop = "حدث خطأ أثناء إرسال طلب السحب: " . htmlspecialchars($stmt->error);
                        $message_type_grop = "error";
                    }
                    $stmt->close();
                } else {
                    $message_grop = "فشل إعداد الاستعلام: " . htmlspecialchars($conn->error);
                    $message_type_grop = "error";
                }
            } else {
                if ($total_after_commission <= 0) {
                    $message_grop = "المبلغ غير كافي بعد خصم العمولة. العمولة: $commission ر.س";
                } else {
                    $message_grop = "رصيدك غير كافي للسحب. رصيدك الحالي: " . number_format($current_balance, 2) . " ر.س";
                }
                $message_type_grop = "error";
            }
        } else {
            $message_grop = "لم يتم العثور على محفظة للمستخدم";
            $message_type_grop = "error";
        }
        $wallet_stmt->close();
    }
}
?>
<?php
include("./admin/include/conaction.php");

$message_serch = "";
$message_type_serch = "";

// معالجة طلب البيع
if (isset($_POST['submit'])) {
   
    // التحقق من وجود user_id في الكوكيز
    if (!isset($_COOKIE['id_user'])) {
        // إذا لم يكن مسجل الدخول، إعادة التوجيه لصفحة التسجيل
        header("Location: login.php");
        exit();
    }
    
    $user_id = $_COOKIE['id_user'];
    $group_link = trim($_POST['search'] ?? '');
    
    if (empty($group_link)) {
        $message_serch = "الرجاء إدخال رابط المجموعة";
        $message_type_serch = "error";
        
    } else {
        // إدخال البيانات في جدول الطلبات
        $status = "قيد المراجعة";
        
        $stmt = $conn->prepare("INSERT INTO orders (id_user,group_url, status) VALUES (?, ?, ?)");
        
        if ($stmt) {
            $stmt->bind_param("iss", $user_id, $group_link, $status);
            
            if ($stmt->execute()) {
                $message_serch = "تم إرسال طلبك بنجاح ✅ وسيتم مراجعته قريباً";
                $message_type_serch = "success";
               
                
                // تفريغ حقل الإدخال بعد النجاح
                $_POST['search'] = '';
            } else {
                $message_serch = "حدث خطأ أثناء إرسال الطلب: " . htmlspecialchars($stmt->error);
                $message_type_serch = "error";
               
            }
            $stmt->close();
        } else {
            $message_serch = "فشل إعداد الاستعلام: " . htmlspecialchars($conn->error);
            $message_type_serch = "error";
            
        }
    }
}
?>

<?php
include("./admin/include/conaction.php");

$message = "";
$message_type = "";

// التحقق من وجود user_id في الكوكيز
if (!isset($_COOKIE['id_user'])) {
    $$message_serch = "الرجاء تسجيل الدخول أولاً";
    $message_type_serch = "error";
} else {
    $user_id = $_COOKIE['id_user'];
    
    // استعلام للحصول على الرصيد الإجمالي
    $balance_sql = "SELECT SUM(
        CASE 
            WHEN type = 'إيداع' THEN amount 
            WHEN type = 'سحب' THEN -amount 
            ELSE 0 
        END
    ) as total_balance FROM transactions WHERE id_user = ?";
    
    $balance_stmt = $conn->prepare($balance_sql);
    
    if ($balance_stmt) {
        $balance_stmt->bind_param("i", $user_id);
        $balance_stmt->execute();
        $balance_result = $balance_stmt->get_result();
        $balance_row = $balance_result->fetch_assoc();
        $total_balance = $balance_row['total_balance'] ?? 0;
        $balance_stmt->close();
    } else {
        $$message = "خطأ في استعلام الرصيد: " . htmlspecialchars($conn->error);
        $message_type= "error";
        $total_balance = 0;
    }
    
    // استعلام للحصول على عدد العمليات
    $count_sql = "SELECT COUNT(*) as total_transactions FROM transactions WHERE id_user = ?";
    $count_stmt = $conn->prepare($count_sql);
    
    if ($count_stmt) {
        $count_stmt->bind_param("i", $user_id);
        $count_stmt->execute();
        $count_result = $count_stmt->get_result();
        $count_row = $count_result->fetch_assoc();
        $total_transactions = $count_row['total_transactions'] ?? 0;
        $count_stmt->close();
    } else {
        $total_transactions = 0;
    }
    
    // استعلام للحصول على جميع العمليات
    $transactions_sql = "SELECT * FROM transactions WHERE id_user = ?";
    $transactions_stmt = $conn->prepare($transactions_sql);
    
    if ($transactions_stmt) {
        $transactions_stmt->bind_param("i", $user_id);
        $transactions_stmt->execute();
        $transactions_result = $transactions_stmt->get_result();
        $transactions = $transactions_result->fetch_all(MYSQLI_ASSOC);
        $transactions_stmt->close();
    } else {
        $$message = "خطأ في استعلام العمليات: " . htmlspecialchars($conn->error);
        $message_type = "error";
        $transactions = [];
    }
}
?>

<?php
include("./admin/include/conaction.php");

$user_id = $_COOKIE['id_user'] ?? 0;

if($user_id > 0) {
    $query = "SELECT SQL_CALC_FOUND_ROWS * 
    FROM withdraw_requests 
    WHERE user_id = ? AND status IN ('مقبول', 'مرفوض', 'مكتمل') 
    ORDER BY created_at DESC 
    LIMIT 2";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// جلب العدد الإجمالي بدون LIMIT
$count_result = $conn->query("SELECT FOUND_ROWS() as total_notifications");
$count_row = $count_result->fetch_assoc();
$total_notifications = $count_row['total_notifications'];

}
?>


<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Anon - eCommerce Website</title>

    <!--
    - favicon
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
  -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
    <link rel="shortcut icon" href="./assets/images/logo/photo_2025-10-31_16-58-01.jpg" type="image/x-icon" />

    <!--
    - custom css link
  -->
    <link rel="stylesheet" href="./assets/css/style-prefix.css" />

    <!--
    - google font link
  -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
</head>

<body>
<!-- <div class="pre-loader">
      <div class="pre-loader-box">
        <div class="loader-logo">
          <img src="./assets/images/logo/photo_2025-10-31_16-58-01.jpg" width="100px" height="100px" alt="" />
        </div>
        <div class="loader-progress" id="progress_div">
          <div class="bar" id="bar1"></div>
        </div>
        <div class="percent" id="percent1">0%</div>
        <div class="loading-text">Loading...</div>
      </div>
    </div>
    <div class="overlay" data-overlay></div> -->
    <style>
        a {
            text-decoration: none;
            color: inherit;
            -webkit-tap-highlight-color: transparent;
        }
        
        select {
            width: 100%;
        }
        
        ul {
            list-style: none;
        }
        
        img {
            max-width: 100%;
            vertical-align: middle;
        }
        
        strong {
            font-weight: 800;
        }
        
        table {
            border-collapse: collapse;
            border-spacing: 0;
            direction: rtl;
        }
        
        input::placeholder {
            font: inherit;
        }
        
        h1,
        h2,
        h3,
        h4 {
            font-family: "Poppins";
        }
        
        h1 {
            font-size: calc(1.3em + 1vw);
            font-weight: 800;
            line-height: 1;
        }
        
        h2 {
            font-size: 2.5em;
        }
        
        h3 {
            font-size: 1.2em;
            font-weight: 700;
        }
        
        h4 {
            font-size: 1em;
            font-weight: 600;
        }
        /* ---------------
        * REUSABLE SELECTOR
        * -------------- */
        
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 0.938em;
        }
        
        .secondary-button {
            background-color: #453c5c;
            color: #fff;
        }
        
        .submit-bton {
            background-color: #453c5c;
            color: #fff;
        }
        
        .submit-bton {
            font-size: 13px;
            padding: 0.9em 2em;
            height: auto;
            width: 100%;
            border-radius: 2em;
            transition: 0.3s, color 0.3s;
        }
        
        .products.one .item {
            flex-direction: column;
        }
        
        .secondary-button:hover {
            background-color: #f2f3f5;
            color: #453c5c;
        }
        
        .submit-bton:hover {
            background-color: #f2f3f5;
            color: #453c5c;
        }
        
        .primary-button,
        .secondary-button,
        .light-button {
            font-size: 13px;
            padding: 0.9em 2em;
            height: auto;
            width: fit-content;
            border-radius: 2em;
            transition: 0.3s, color 0.3s;
        }
        
        .column {
            margin-left: -0.938em;
            margin-right: -0.938em;
        }
        
        .column .row {
            padding: 0 0.938em;
        }
        
        .flexwrap {
            display: flex;
            flex-wrap: wrap;
        }
        
        .mini-text {
            font-size: 11px;
            color: #7c899a;
            display: inline-block;
        }
        
        .flexcenter {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .flexitem {
            display: flex;
            align-items: center;
        }
        
        .flexcol {
            display: flex;
            flex-direction: column;
            gap: 1em;
        }
        
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 0.938em;
        }
        
        .products :where(.image, .thumbnail) img {
            transition: transform 0.3s;
        }
        
        .products .qty-control {
            width: fit-content;
            padding: 0.5em;
            border: 1px solid #e5e8ec;
            margin: 0 2em 2em 0;
        }
        
        .products .qty-control button::before {
            background-color: transparent;
        }
        
        .products :where(.image, .thumbnail):hover img {
            transform: scale(1.1);
        }
        
         :where(.mini-cart, .products.cart, .checkout) .thumbnail {
            position: relative;
            width: 80px;
            height: 80px;
            flex-shrink: 0;
            margin-right: 1em;
        }
        
         :is(.mini-cart .products, .products.cart, .checkout .products) .thumbnail img {
            transform: none;
        }
        
        .products.cart form {
            width: 100%;
        }
        
        .products.cart table thead th {
            vertical-align: middle;
            background-color: #f2f3f5;
        }
        
        .products.cart table :where(th, td) {
            padding: 2em 1em;
            text-align: center;
        }
        
        .products.cart table :where(th, td):first-child {
            text-align: right;
            padding-right: 2em;
        }
        
        .products.cart table tbody td:first-child {
            padding-right: 0;
            direction: ltr;
        }
        
        .products.cart table td :where(.content, .qty-control, p) {
            margin: 0;
            padding: 0;
        }
        
        .products.cart .qty-control :where(button, input) {
            width: 32px;
            height: 32px;
            padding: 0.25em;
            text-align: center;
            outline: 0;
            border: 0;
            background-color: transparent;
        }
        
        .products.cart .qty-control button {
            cursor: pointer;
        }
        
        .products.cart table tbody tr {
            border-bottom: 1px solid #e5e8ec;
        }
        
        .cart-summary {
            width: 100%;
            margin: 2.5em 0 4em;
        }
        
        .cart-summary .item {
            background-color: #f2f3f5;
            direction: rtl;
        }
        
        .cart-summary .coupon {
            position: relative;
        }
        
        .cart-summary .coupon input {
            font-size: 1.1em;
            outline: 0;
            width: 100%;
            padding: 0 1.5em;
            line-height: 50px;
            background-color: #fff;
            border: 3px solid#0a021c;
        }
        
        .cart-summary .coupon button {
            position: absolute;
            top: 0;
            left: 0;
            border: 0;
            outline: 0;
            font-size: 1em;
            padding: 0 2.5em;
            line-height: 53px;
            background-color: #0a021c;
            color: #fff;
            cursor: pointer;
        }
        
        .cart-summary .shipping-rate {
            padding: 0 2em 0 1em;
        }
        
        .products .content {
            display: flex;
            flex-direction: column;
        }
        
        .icon-small,
        .icon-large {
            display: flex;
            align-items: center;
            padding: 0 0.25em;
            font-weight: normal;
        }
        
        .icon-small {
            font-size: 1.25em;
            margin-left: auto;
        }
        
        .circle {
            position: absolute;
            top: -15px;
            left: 0;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: #f2f3f5;
            z-index: -1;
        }
        
        .input-number {
            padding: 0.75em;
            outline: 0;
            background-color: #fff;
            border-width: 0 0 3px 0;
            border-style: solid;
            border-color: #e5e8ec;
        }
        
        .input-select {
            padding: 0.75em;
            outline: 0;
            background-color: #fff;
            border-width: 0 0 3px 0;
            border-style: solid;
            border-color: #e5e8ec;
        }
        
        .circle::before {
            content: "";
            position: absolute;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background-color: #794afa;
            bottom: 5px;
            right: 5px;
            opacity: 0.4;
        }
        
        .products:where(.big, .main, .one) .content {
            gap: 1em;
            margin-top: 1.25em;
        }
        
        .cart-summary .shipping-rate .has-child>a {
            font-size: 1em;
            margin: 2em 0;
            border: 0;
        }
        
        .cart-summary .content form {
            display: flex;
            flex-direction: column;
            margin-bottom: 1em;
        }
        /* .styled :where(input, select, textarea) {
            padding: 0.75em;
            outline: 0;
            background-color: #fff;
            border-width: 0 0 3px 0;
            border-style: solid;
            border-color: #e5e8ec;
        } */
        
        .products .variant form {
            display: flex;
            margin-top: 0.5em;
        }
        
        .products :where(.variant, .actions) .circle {
            display: block;
            position: static;
            top: 0;
            margin: 0;
            cursor: pointer;
            z-index: 1;
        }
        
         :where(.products .variant, .filter-block:not(.pricing)) input {
            clip: rect(0, 0, 0, 0);
            overflow: hidden;
            position: absolute;
            height: 0;
            width: 0;
        }
        
        .products .colors .variant label::before {
            opacity: 1;
        }
        
        .products .variant label[for="cogrey"]::before {
            background-color: #576574;
        }
        
        .products .variant label[for="coblue"]::before {
            background-color: #45a0ff;
        }
        
        .products .variant label[for="cogreen"]::before {
            background-color: #1dd1a1;
        }
        
        .single-product .variant form p input:checked+label {
            background-color: transparent;
            border: 2px solid#0a021c;
            color: #fff;
        }
        
        .products .sizes .variant label::before {
            background-color: #fff;
        }
        
        .products .sizes .variant label span {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            font-size: 0.85em;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .single-product .sizes .variant form p input:checked+label::before {
            background-color: #0a021c;
            opacity: 1;
        }
        
        .products .variant form p {
            position: relative;
            margin: 0 0.5em 0.5em;
        }
        
        .products .cart-summary .variant form label {
            position: relative;
            border: 0;
            background-color: #fff;
            transform: scale(0.5) translateX(-25px);
        }
        
        .cart-summary .variant p span {
            position: absolute;
            top: 0;
            left: 20px;
            line-height: 3;
        }
        
        .products .colors .variant label::before {
            opacity: 1;
        }
        
        .cart-summary .variant input:checked+label::before {
            opacity: 1;
        }
        
        .products.cart .cart-total table tr>* {
            padding: 0;
        }
        
        .products .collapse .has-child>a {
            position: relative;
            font-weight: 700;
            text-transform: uppercase;
            padding: 1em 1.25em;
            border-top: 1px solid #e5e8ec;
            gap: 1em;
            align-items: flex-start;
        }
        
        .products .collapse .has-child>a::before {
            content: "+";
            position: absolute;
            right: 0;
        }
        
        .products .collapse .content {
            margin: 0 0 1.5em 2em;
            font-size: 13px;
        }
        
        .products .collapse .content li span:first-child {
            min-width: 100px;
            display: inline-flex;
            font-weight: 700;
            text-transform: uppercase;
        }
        
        .products .collapse table {
            line-height: 3em;
        }
        
        .products .collapse table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid#0a021c;
        }
        
        .products .collapse table :where(th, td) {
            border-bottom: 1px solid #e5e8ec;
            padding-left: 2em;
        }
        
        .products .collapse .content {
            display: none;
        }
        
        .products .collapse .expand .content {
            display: flex;
        }
        
        .products .collapse .expand>a::before {
            content: "-";
        }
        
        .products.cart .cart-total table td {
            text-align: right;
        }
        
        .cart-summary .cart-total {
            padding: 2em;
            background-color: #e5e8ec;
            line-height: 2em;
        }
        
        .cart-summary .cart-total table {
            width: 100%;
        }
        
        .cart-summary .cart-total table tr {
            display: flex;
            justify-content: space-between;
        }
        
        .cart-summary .grand-total td {
            font-size: 2em;
            font-family: "Poppins";
            font-weight: 800;
            line-height: 2em;
        }
        
        .cart-summary .cart-total>a {
            width: 100%;
            display: block;
            text-align: center;
            margin-top: 2em;
            font-size: 1.25em;
        }
        
        .object-cover img {
            position: absolute;
            object-fit: cover;
            width: 100%;
            height: 100%;
            display: block;
        }
        
        @media screen and (max-width: 639px) {
            #cart-table thead {
                display: none;
            }
            #cart-table tbody tr {
                display: flex;
                flex-wrap: wrap;
                position: relative;
                align-items: center;
            }
            #cart-table tbody tr td:first-child {
                width: 100%;
                min-width: 100%;
                position: relative;
            }
            #cart-table tbody tr td:not(:first-child) {
                flex-basis: 0;
                flex-grow: 1;
                max-width: 100%;
            }
            #cart-table tbody tr td:last-child {
                position: absolute;
                top: 0;
                right: 0;
            }
        }
        
        @media screen and (min-width: 992px) {
            .trending .products,
            .product-categories .row {
                flex: 0 0 33.3333%;
            }
            .products.big .media {
                max-height: 373px;
            }
            .products.main .item,
            .widgets .row {
                flex: 0 0 25%;
            }
            .products.cart .form-cart {
                width: 66%;
            }
            .products.cart .cart-summary {
                width: 100%;
                padding-left: 2.5em;
                margin-top: 0;
            }
            .products.one .flexwrap>.row:last-child>item {
                padding-left: 2em;
            }
        }
        
        @media screen and (min-width: 481px) {
            .products.main .item {
                flex: 0 0 50%;
            }
            /* page single */
            .products.one .big-image {
                margin-bottom: 2em;
            }
            .products.one .image-show {
                height: 680px;
            }
            .products.one .thumbnail-show {
                height: 160px;
            }
        }
    </style>
    <header>
        <a href="#shoping" class="banner-btn login">تسجيل الدخول</a>
        <div class="header-top">
            <div class="container">


                <div class="header-alert-news">
                    <p><b> </b></p>
                </div>

                <div class="header-top-actions">
                    <select name="currency">
          <option value="usd">الوضع</option>
          <option value="usd">العادي</option>
          <option value="eur">اليلي</option>
        </select>

                    <select name="language">
          <option value="en-US">حسابي</option>
          <option value="es-ES">تسجيل حروج</option>
          <option value="fr">ترقية</option>
        </select>
                </div>
            </div>
        </div>
        <div class="header-main">
            <div class="container">
                <a href="#" class="header-logo">
                    <img src="./assets/images/logo/photo_2025-10-31_16-58-01.jpg" alt="Anon's logo" width="70" height="70" style="border-radius: 54%">
                </a>

                <form action="" method="post"> 
                    <div class="header-search-container">
                        <input type="search" name="search" class="search-field" placeholder="أدخل رابط القروب هنا" />

                                <button class="search-btn">
                        <ion-icon
                            name="search-outline"
                            role="img"
                            class="md hydrated"
                            aria-label="search outline"
                        ></ion-icon>
                        </button>
                        <input type="submit" class="banner-btn" value="بيع الان" name="submit">
                        <select name="select-type" id="" class="nav-select">
                            <option value="">اختيار</option>
                            <option value="مجلد">مجلد</option>
                            <option value="قروب">قروب</option>
                        </select>
                    </div>
                </form>

                <div class="header-user-actions">
                <a href="index.php">
                <button class="action-btn">
                            <ion-icon
                            name="home-outline"
                            role="img"
                            class="md hydrated"
                            aria-label="home outline"
                          ></ion-icon>
                        </button>
                </a>
    

                    <button class="action-btn" id="lov-icon">
                  <ion-icon name="notifications-outline" role="img" class="md hydrated" aria-label="notifications outline"></ion-icon>
                  <span class="count" data-quantity="0"><?php echo isset($total_notifications) ? $total_notifications : 0; ?></span>
            </button>
        </button>

                    <button class="action-btn" id="card-icon">
                  <ion-icon name="wallet-outline" role="img" class="md hydrated" aria-label="wallet outline"></ion-icon>
                  <span class="count" data-quantity="0"><?php echo isset($total_transactions) ? $total_transactions : 0; ?></span>
        </button>
                </div>
            </div>
        </div>
        <div class="cart-c" dir="rtl">
    <div class="card-tit">العمليات</div>
    <div class="cart-content">

        <!-- عرض العمليات -->
        <?php if (!empty($transactions) && is_array($transactions)): ?>
            <?php foreach ($transactions as $transaction): ?>
                <?php 
                $amount = isset($transaction['amount']) ? number_format($transaction['amount'], 2) : '0.00';
                $date = isset($transaction['wallels_date']) ? date('d/m/Y', strtotime($transaction['wallels_date'])) : date('d/m/Y');
                $type = $transaction['type'] ?? '';
                ?>
                
                <?php if ($type === 'سحب'): ?>
                    <!-- قالب السحب -->
                    <div class="cart-cox">
                        <img src="./assets/images/my/arrow-goes-down-chart-business-finance-vector-background_566734-312.jpg" alt="سحب" class="cart-img" />
                        <div class="data-bpx">
                            <div class="cart-prodect-titil">سحب من المحفظة</div>
                            <a href="#" class="showcase-category"><?php echo $date; ?></a>
                        </div>
                        <div class="data-bpx">
                            <div class="cart-pric"> - <?php echo $amount; ?></div>
                            <a href="#" class="showcase-category">(USDI)</a>
                        </div>
                    </div>
                
                <?php elseif ($type === 'إيداع'): ?>
                    <!-- قالب الإيداع -->
                    <div class="cart-cox">
                        <img src="./assets/images/my/png-transparent-stock-market-design-elements-flat-arrows-line-chart-sketch-thumbnail.png" alt="إيداع" class="cart-img" />
                        <div class="data-bpx">
                            <div class="cart-prodect-titil">إيداع إلى المحفظة</div>
                            <a href="#" class="showcase-category"><?php echo $date; ?></a>
                        </div>
                        <div class="data-bpx">
                            <div class="cart-pric add"> + <?php echo $amount; ?></div>
                            <a href="#" class="showcase-category">(USDI)</a>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-transactions">لا توجد عمليات حتى الآن</div>
        <?php endif; ?>
    </div>

    <div class="icon-txt">
        <div class="main-text">الرصيد</div>
        <div class="cart-total"><?php echo isset($wallet['balance']) ? number_format($wallet['balance'], 2) : '0.00'; ?></div>
    </div>

    <a href="clk-card.php">
        <div class="bramer-boutt add-cart-btn bramer-boutt-shop">سحب</div>
    </a>

    <div class="clos-ca" id="clos-ca1">
        <i class="ri-close-line"></i>
    </div>
</div>
<div class="cart-c1" dir="rtl">
    <div class="card-tit">الاشعارات</div>
    <div class="cart-content2">
        <?php if (isset($result) && $result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): 
                $amount = isset($row['amount']) ? number_format($row['amount'], 2) : '0.00';
                $message = isset($row['admin_note']) ? htmlspecialchars($row['admin_note']) : 'لا توجد رسالة';
                
                // اختيار الصورة حسب الحالة
                $img_src = '';
                $status = $row['status'] ?? '';
                if ($status === 'مقبول') {
                    $img_src = './assets/images/my/images (2).png';
                } elseif ($status === 'مرفوض') {
                    $img_src = './assets/images/my/arrow-goes-down-chart-business-finance-vector-background_566734-312.jpg';
                } elseif ($status === 'مكتمل') {
                    $img_src = './assets/images/my/png-transparent-stock-market-design-elements-flat-arrows-line-chart-sketch-thumbnail.png';
                } else {
                    $img_src = './assets/images/my/default.png'; // صورة افتراضية لأي حالة غير معروفة
                }
            ?>
                <div class="cart-cox1">
                    <img src="<?php echo $img_src; ?>" alt="" class="cart-img" />
                    <div class="data-bpx">
                        <div class="cart-prodect-titil"><?php echo $message; ?></div>
                        <?php if ($status === 'مكتمل'): ?>
                            <div class="cart-pric add">+ <?php echo $amount; ?></div>
                        <?php endif; ?>
                    </div>
                    <i class="ri-delete-bin-7-line cart-remove"></i>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="cart-cox1">
                <div class="data-bpx">
                    <div class="cart-prodect-titil">لا توجد طلبات سحب حتى الآن.</div>
                </div>
            </div>
        <?php endif; ?>
        <div class="icon-txt"></div>
        <div class="clos-ca clos-lov">
            <i class="ri-close-line"></i>
        </div>
    </div>
</div>
        <div class="mobile-bottom-navigation">
            <button class="action-btn" data-mobile-menu-open-btn="">
      <ion-icon name="menu-outline" role="img" class="md hydrated" aria-label="menu outline"></ion-icon>
    </button>

            <button class="action-btn card-icon">
          <ion-icon name="wallet-outline" role="img" class="md hydrated" aria-label="wallet outline"></ion-icon>
          <span class="count" data-quantity="0"><?php echo isset($total_transactions) ? $total_transactions : 0; ?></span>
    </button>

    <a href="index.php">
                        <button class="action-btn">
                            <ion-icon
                            name="home-outline"
                            role="img"
                            class="md hydrated"
                            aria-label="home outline"
                          ></ion-icon>
                        </button>
                    </a>

            <button class="action-btn lov-icon">
          <ion-icon name="notifications-outline" role="img" class="md hydrated" aria-label="notifications outline"></ion-icon>
          <span class="count" data-quantity="0"><?php echo isset($total_notifications) ? $total_notifications : 0; ?></span>
    </button>


        </div>

        <nav class="mobile-navigation-menu has-scrollbar" data-mobile-menu="">
            <div class="menu-top">
                <h2 class="menu-title">Menu</h2>

                <button class="menu-close-btn" data-mobile-menu-close-btn="">
        <ion-icon name="close-outline" role="img" class="md hydrated" aria-label="close outline"></ion-icon>
      </button>
            </div>


        </nav>
    </header>

    <main>
    <?php 
    if ($message_type_grop == "success") { 
    ?>
        <div class="notification-toast show" data-toast role="alert" aria-live="polite" aria-atomic="true">
            <button class="toast-close-btn" data-toast-close aria-label="إغلاق الإشعار">
                <ion-icon name="close-outline" role="img" class="md hydrated" aria-label="close outline"></ion-icon>
            </button>

            <div class="toast-banner">
                <img src="./assets/images/success.png" alt="نجاح العملية" width="80" height="70" />
            </div>
            
            <div class="toast-detail">
                <p class="toast-title"><?php echo htmlspecialchars($message_grop); ?></p>
            </div>
        </div> 
    <?php } ?>


    <?php 
    if ($message_type_grop == "error") { 
    ?>
       <div class="notification-lov show" data-toast="">
            <button class="toast-close-btn" data-toast-close="">
          <ion-icon
            name="close-outline"
            role="img"
            class="md hydrated"
            aria-label="close outline"
          ></ion-icon>
        </button>

            <div class="toast-banner">
                <img src="./assets/images/cancel.png" alt="Rose Gold Earrings" width="80" height="70" />
            </div>
            <br />
            <div class="toast-detail">
            <p class="toast-title"><?php echo htmlspecialchars($message_grop); ?></p>
            </div>
        </div>
    <?php } ?>
    <main>
        <!-- inpur_serch -->
    <?php 
    if ($message_type_serch == "success") { 
    ?>
        <div class="notification-toast show" data-toast role="alert" aria-live="polite" aria-atomic="true">
            <button class="toast-close-btn" data-toast-close aria-label="إغلاق الإشعار">
                <ion-icon name="close-outline" role="img" class="md hydrated" aria-label="close outline"></ion-icon>
            </button>

            <div class="toast-banner">
                <img src="./assets/images/success.png" alt="نجاح العملية" width="80" height="70" />
            </div>
            
            <div class="toast-detail">
                <p class="toast-title"><?php echo htmlspecialchars($message_serch); ?></p>
            </div>
        </div> 
    <?php } ?>


    <?php 
    if ($message_type_serch == "error") { 
    ?>
       <div class="notification-lov show" data-toast="">
            <button class="toast-close-btn" data-toast-close="">
          <ion-icon
            name="close-outline"
            role="img"
            class="md hydrated"
            aria-label="close outline"
          ></ion-icon>
        </button>

            <div class="toast-banner">
                <img src="./assets/images/cancel.png" alt="Rose Gold Earrings" width="80" height="70" />
            </div>
            <br />
            <div class="toast-detail">
            <p class="toast-title"><?php echo htmlspecialchars($message_serch); ?></p>
            </div>
        </div>
    <?php } ?>




        <!-- <div class="notification-toast" data-toast="">
            <button class="toast-close-btn" data-toast-close="">
          <ion-icon
            name="close-outline"
            role="img"
            class="md hydrated"
            aria-label="close outline"
          ></ion-icon>
        </button>

            <div class="toast-banner">
                <img src="./assets/images/success.png" alt="Rose Gold Earrings" width="80" height="70" />
            </div>
            <br />
            <div class="toast-detail">
                <p class="toast-title">تم الاضافه الئ قائمه الامنيات بنجاح</p>
            </div>
        </div>
        <div class="notification-lov" data-toast="">
            <button class="toast-close-btn" data-toast-close="">
          <ion-icon
            name="close-outline"
            role="img"
            class="md hydrated"
            aria-label="close outline"
          ></ion-icon>
        </button>

            <div class="toast-banner">
                <img src="./assets/images/cancel.png" alt="Rose Gold Earrings" width="80" height="70" />
            </div>
            <br />
            <div class="toast-detail">
                <p class="toast-title">
                    المنتج الذي تحاول اضافته الئ قائمه امنياتي موجود مسبقا!
                </p>
            </div>
        </div>
        <div class="notification-card" data-toast="">
            <button class="toast-close-btn" data-toast-close="">
          <ion-icon
            name="close-outline"
            role="img"
            class="md hydrated"
            aria-label="close outline"
          ></ion-icon>
        </button>

            <div class="toast-banner">
                <img src="./assets/images/success.png" alt="Rose Gold Earrings" width="80" height="70" />
            </div>
            <br />
            <div class="toast-detail">
                <p class="toast-title">تم الاضافه الئ السله بنجاح</p>
            </div>
        </div>
        <div class="notification-cardfols" data-toast="">
            <button class="toast-close-btn" data-toast-close="">
          <ion-icon
            name="close-outline"
            role="img"
            class="md hydrated"
            aria-label="close outline"
          ></ion-icon>
        </button>

            <div class="toast-banner">
                <img src="./assets/images/cancel.png" alt="Rose Gold Earrings" width="80" height="70" />
            </div>
            <br />
            <div class="toast-detail">
                <p class="toast-title">
                    المنتج الذي تحاول اضافته الى السله موجود مسبقا!
                </p>
            </div>
        </div> -->
        <div id="page" class="site page-cart">
            <!-- Header -->
            <main>
                <h2 class="title" id="car-open" dir="rtl">اكمال عمليه السحب</h2>

                <div class="single-cart">
                    <div class="container">
                        <div class="wrapper">
                            <div class="page-title">
                                <br />
                                <br />
                            </div>
                            <div class="products one cart">
                                <div class="flexwrap">
                                    <!-- الشريط الجانبي ملخص السلة -->
                                    <div class="cart-summary styled">
                                        <div class="item">
                                            <form action="" method="post">
                                                <!-- <div class="coupon">
                                                    <input type="text" placeholder=" أدخل ايدي الحساب او الاسم" name="acouant-numper">
                                                    <button>Apply</button>
                                                </div> -->

                                                <!-- بيانات الحجز -->
                                                <div class="shipping-rate collapse">
                                                    <div class="has-child expand">
                                                        <a href="#" class="icon-small">بيانات السحب</a>
                                                        <div class="content">
                                                            <div class="countries">
                                                                <label for="country">نوع الايداع</label>
                                                                <select name="country" id="country" class="input-select" onchange="toggleAccountNumber()">
                                                                    <option value=""></option>
                                                                    <option value="بينانس">بينانس</option>
                                                                    <option value="حيب">حيب</option>
                                                                    <option value="كريمي">كريمي</option>
                                                                    <option value="TON">TON</option>
                                                                </select>
                                                            </div>

                                                            <!-- حقل رقم الحساب الذي سيظهر عند الاختيار -->
                                                            <div class="account-number" id="accountNumberField" style="display: none;">
                                                            <label for="account_number">رقم الحساب</label>
                                                                <input type="text" name="acouant-numper" id="account_number" class="input-number" placeholder="أدخل رقم الحساب المستلم">
                                                                <!-- <input type="number" name="postal"id="account_number" class="input-number"  placeholder="أدخل رقم الحساب المستلم"> -->
                                                            </div>

                                                            <div class="postal-code">
                                                                <label for="postal">مبلغ السحب</label>
                                                                <input type="number" name="postal" id="postal" class="input-number">
                                                            </div>

                                                            <div class="rate-options variant">
                                                                <!-- يمكن إضافة خيارات هنا -->
                                                            </div>
                                                            <input type="submit" value="طلب سحب" class="submit-bton" name="submit-bton">
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                            <!-- المجموع النهائي -->
                                            <div class="cart-total">
                                                <table>
                                                <tbody>
                                                    <tr>
                                                        <th>المبلغ كامل في المحفظة</th>
                                                        <td><?php echo $current_balance; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <th>الخصم</th>
                                                        <td>-$5.00</td> -->
                                                    </tr>
                                                    <tr class="grand-total">
                                                        <th>المجموع</th>
                                                        <td><strong id="net-amount">0.00 ر.س</strong></td>
                                                    </tr>
                                              </tbody>
                                                </table>

                                                <!-- <a href="#" class="secondary-button">طلب سحب</a> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <!-- Main -->
        </div>
    </main>

    <!-- <script src="./assets/js/script.js"></script> -->
    <footer>
        <div class="footer-nav">
            <div class="container">
                <ul class="footer-nav-list" dir="ltr">
                    <li class="footer-nav-item">
                        <h2 class="nav-title">للتواصل</h2>
                    </li>

                    <li class="footer-nav-item flex">
                        <div class="icon-box">
                            <ion-icon name="person-circle-outline"></ion-icon>
                        </div>

                        <address class="content">
                          <a href="https://t.me/K_9_q" class="footer-nav-link">@ K_9_q</a>
                          
                       </address>
                    </li>

                    <li class="footer-nav-item flex">
                        <div class="icon-box">
                            <ion-icon name="call-outline" role="img" class="md hydrated" aria-label="call outline"></ion-icon>
                        </div>

                        <a href="tel: +967 (716) 280-472" class="footer-nav-link">+967 (716) 280-472</a
              >
            </li>

            <li class="footer-nav-item flex">
              <div class="icon-box">
                <ion-icon
                  name="mail-outline"
                  role="img"
                  class="md hydrated"
                  aria-label="mail outline"
                ></ion-icon>
              </div>

              <a href="mailto:auishsatk716@gmail.com" class="footer-nav-link"
                >auishsatk716@gmail.com</a
              >
            </li>
          </ul> 
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <p class="copyright">
                    Copyright © <a href="#">Telegram</a> all rights reserved.
                        </p>
            </div>
        </div>
    </footer>
    <!--
    - ionicon link
  -->
  <script>
function toggleAccountNumber() {
    const countrySelect = document.getElementById('country');
    const accountNumberField = document.getElementById('accountNumberField');
    
    // إذا تم اختيار أي نوع إيداع، قم بإظهار حقل رقم الحساب
    if (countrySelect.value !== '') {
        accountNumberField.style.display = 'block';
    } else {
        accountNumberField.style.display = 'none';
    }
}

// تشغيل الدالة عند تحميل الصفحة للتحقق من القيمة الافتراضية
document.addEventListener('DOMContentLoaded', function() {
    toggleAccountNumber();
});
</script>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
      function animateValue(element, start, end, duration, easing = 'linear') {
    var startTime = performance.now();
    var barElement = $("#bar1");
    var preloader = $(".pre-loader");
    
    function updateAnimation(currentTime) {
        var elapsedTime = currentTime - startTime;
        var progress = Math.min(elapsedTime / duration, 1);
        
        // تطبيق تأثيرات مختلفة
        if (easing === 'easeOut') {
            progress = 1 - Math.pow(1 - progress, 3); // تأثير easeOut
        } else if (easing === 'easeInOut') {
            progress = progress < 0.5 ? 2 * progress * progress : 1 - Math.pow(-2 * progress + 2, 2) / 2;
        }
        
        var currentValue = Math.floor(progress * (end - start) + start);
        
        // تحديث النسبة المئوية
        element.text(currentValue + "%");
        
        // تحديث شريط التقدم
        if (barElement.length) {
            barElement.css('width', currentValue + '%');
        }
        
        // تحديث عنوان الصفحة (اختياري)
        document.title = currentValue + "% - جاري التحميل";
        
        if (progress < 1) {
            requestAnimationFrame(updateAnimation);
        } else {
            // الإجراءات بعد الاكتمال
            onAnimationComplete();
        }
    }
    
    function onAnimationComplete() {
        console.log("اكتمل التحميل!");
        
        // إخفاء شاشة التحميل بعد فترة
        setTimeout(function() {
            preloader.fadeOut(500, function() {
                // إخفاء الـ overlay أيضاً بعد انتهاء animation
                $(".overlay").fadeOut(300);
            });
        }, 500);
    }
    
    requestAnimationFrame(updateAnimation);
}

// الكود الرئيسي لتشغيل التحريك
$(document).ready(function() {
    var PercentageID = $("#percent1"),
        barID = $("#bar1"),
        preloader = $(".pre-loader"),
        time = 4000; // 4 ثواني

    // التحقق من وجود جميع العناصر
    if (PercentageID.length && barID.length && preloader.length) {
        var start = 0,
            end = 100,
            duration = time;

        // بدء التحريك مع تأثير easeOut
        animateValue(PercentageID, start, end, duration, 'easeOut');
    } else {
        console.warn("عناصر التحميل غير موجودة");
        
        // إذا لم توجد العناصر، إخفاء pre-loader فوراً
        preloader.hide();
        $(".overlay").hide();
    }
});
   </script>
  <script>
// حساب المبلغ الصافي تلقائياً عند إدخال المبلغ
document.getElementById('postal').addEventListener('input', function() {
    var amount = parseFloat(this.value) || 0;
    var commission = 5.00;
    var netAmount = amount ;
    
    if (netAmount < 0) {
        netAmount = 0;
    }
    
    document.getElementById('net-amount').textContent = netAmount.toFixed(2) + ' ر.س';
});
</script>
    <script src="./assets/js/sc.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>

    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>