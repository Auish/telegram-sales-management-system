
<?php
include("./admin/include/conaction.php");

$message_grop = "";
$message_type_grop = "";

// معالجة طلب البيع
if (isset($_POST['send-grop'])) {

    // التحقق من تسجيل الدخول عبر الكوكيز
    if (!isset($_COOKIE['id_user'])) {
        header("Location: login.php");
        exit();
    }

    $user_id     = $_COOKIE['id_user'];
    $task_name   = trim($_POST['task_name'] ?? '');
    $link_type   = trim($_POST['link_type'] ?? '');
    $group_links = trim($_POST['group_links'] ?? '');
    $date        = date('Y-m-d H:i:s');

    // التحقق من الحقول
    if (empty($task_name)) {
        $message_grop = "Please enter the task name";
        $message_type_grop = "error";

    } elseif (empty($group_links)) {
        $message_grop = "Please enter the group links";
        $message_type_grop = "error";

    } else {

        // تنظيف وتحليل الروابط
        $group_links_clean = trim($group_links);
        $links_array = explode("\n", $group_links_clean);
        $links_array = array_map('trim', $links_array);
        $links_array = array_filter($links_array); // إزالة الأسطر الفارغة

        // التحقق من وجود الروابط مسبقاً في قاعدة البيانات
        $existing_links = [];
        $placeholders = str_repeat('?,', count($links_array) - 1) . '?';
        
        $check_stmt = $conn->prepare("
            SELECT group_url FROM orders 
            WHERE group_url IN ($placeholders)
        ");
        
        if ($check_stmt) {
            // ربط المعاملات
            $types = str_repeat('s', count($links_array));
            $check_stmt->bind_param($types, ...$links_array);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $existing_links[] = $row['group_url'];
            }
            $check_stmt->close();
        }

        // إذا وجد روابط مكررة
        if (!empty($existing_links)) {
            $message_grop = "The following links already exist in the database.:<br>" . 
                           implode("<br>", array_slice($existing_links, 0, 5)); // عرض أول 5 روابط فقط
            if (count($existing_links) > 5) {
                $message_grop .= "<br>...و " . (count($existing_links) - 5) . " Other links";
            }
            $message_type_grop = "error";
            
        } else {
            // لا توجد روابط مكررة، متابعة الإدخال
            $status = "قيد المراجعة";

            // إدخال كل رابط في سجل منفصل
            $success_count = 0;
            $error_count = 0;
            
            $stmt = $conn->prepare("
                INSERT INTO orders (id_user, Task, type, group_url, status, order_date)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            if ($stmt) {
                foreach ($links_array as $link) {
                    $stmt->bind_param(
                        "isssss",
                        $user_id,
                        $task_name,
                        $link_type,
                        $link,
                        $status,
                        $date
                    );

                    if ($stmt->execute()) {
                        $success_count++;
                    } else {
                        $error_count++;
                    }
                }
                $stmt->close();

                if ($success_count > 0) {
                    $message_grop = "Sent $success_count Request successful ✅ and will be reviewed soon";
                    if ($error_count > 0) {
                        $message_grop .= "<br> Send failed $error_count request";
                    }
                    $message_type_grop = "success";
                    
                    // إعادة التوجيه بعد 5 ثواني
                    header("Refresh: 5; url=index.php");
                    
                } else {
                    $message_grop = "All requests failed to be sent";
                    $message_type_grop = "error";
                }

            } else {
                $message_grop = "Query setup failed: " . htmlspecialchars($conn->error);
                $message_type_grop = "error";
            }
        }
    }
}
?>
<?php
// استدعاء الاتصال بقاعدة البيانات
include("./admin/include/conaction.php");

 
// تأكد أن المستخدم مسجّل دخول
if (isset($_COOKIE['id_user'])) {
    $user_id = $_COOKIE['id_user'];

    // جلب آخر طلب للمستخدم
    $stmt_last = $conn->prepare("
        SELECT Task,type,group_url,status,order_date
        FROM orders
        WHERE id_user = ?
        ORDER BY id DESC
        LIMIT 1
    ");
    $stmt_last->bind_param("i", $user_id);
    $stmt_last->execute();
    $result_last = $stmt_last->get_result();

    if ($result_last->num_rows > 0) {
        $row_last = $result_last->fetch_assoc();

        $latest_order = [
            "task_name"   => $row_last['Task'],
            "group_count" => substr_count(trim($row_last['group_url']), "\n") + 1,
            "date"        => date("Y/m/d", strtotime($row_last['order_date'])),
            "status"      => $row_last['status']
        ];

        $order_exists = true;
    }
}

?>

<?php
// استدعاء الاتصال بقاعدة البيانات
include("./admin/include/conaction.php");

session_start();
if (isset($_COOKIE['id_user'])) {
    $user_id = $_COOKIE['id_user'];

    // استعلام لجلب حالة الطلب الخاصة بالمستخدم
    $query = "SELECT status FROM orders WHERE id_user = ? ORDER BY id DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    
    $result = $stmt->get_result();

    $status_grop = "";
    if ($row = $result->fetch_assoc()) {
        $status_grop = $row['status'];
    }

    // استعلام جديد لعد الطلبات المكتملة
    $completed_query = "SELECT COUNT(*) as completed_count FROM orders WHERE id_user = ? AND status = 'مكتمل'";
    $completed_stmt = $conn->prepare($completed_query);
    $completed_stmt->bind_param("i", $user_id);
    $completed_stmt->execute();
    
    $completed_result = $completed_stmt->get_result();
    $completed_data = $completed_result->fetch_assoc();
    $completed_count = $completed_data['completed_count'];

    // إغلاق statements
    $stmt->close();
    $completed_stmt->close();

    // // استخدام البيانات
    // echo "حالة الطلب الحالي: " . $status_grop . "<br>";
    // echo "عدد الطلبات المكتملة: " . $completed_count;
} else {
    
}
?>


<?php
include("./admin/include/conaction.php");

$message = "";
$message_type = "";

// التحقق من وجود user_id في الكوكيز
if (!isset($_COOKIE['id_user'])) {
    $message = "Please log in first";
    $message_type = "error";
} else {
    $user_id = $_COOKIE['id_user'];
    $wallet = ['balance' => 0.00]; // تعريف مبدئي لتجنب undefined
    $current_balance = 0.00;
    
    if (isset($user_id) && !empty($user_id)) {
        $balance_stmt = $conn->prepare("SELECT balance FROM wallets WHERE id_user = ?");
        if ($balance_stmt) {
            $balance_stmt->bind_param("i", $user_id);
            $balance_stmt->execute();
            $balance_result = $balance_stmt->get_result();
    
            if ($balance_result && $balance_result->num_rows > 0) {
                $wallet = $balance_result->fetch_assoc();
                $current_balance = $wallet['balance'];
            }
    
            $balance_stmt->close();
        }
    }
    
    // استعلام للحصول على الرصيد الإجمالي
    // $balance_sql = "SELECT SUM(
    //     CASE 
    //         WHEN type = 'إيداع' THEN amount 
    //         WHEN type = 'سحب' THEN -amount 
    //         ELSE 0 
    //     END
    // ) as total_balance FROM transactions WHERE id_user = ?";
    
    // $balance_stmt = $conn->prepare($balance_sql);
    
    // if ($balance_stmt) {
    //     $balance_stmt->bind_param("i", $user_id);
    //     $balance_stmt->execute();
    //     $balance_result = $balance_stmt->get_result();
    //     $balance_row = $balance_result->fetch_assoc();
    //     $total_balance = $balance_row['total_balance'] ?? 0;
    //     $balance_stmt->close();
    // } else {
    //     $message = "خطأ في استعلام الرصيد: " . htmlspecialchars($conn->error);
    //     $message_type = "error";
    //     $total_balance = 0;
    // }
    
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
    $transactions_sql = "SELECT * FROM withdraw_requests WHERE user_id  = ?";
    $transactions_stmt = $conn->prepare($transactions_sql);
    
    if ($transactions_stmt) {
        $transactions_stmt->bind_param("i", $user_id);
        $transactions_stmt->execute();
        $transactions_result = $transactions_stmt->get_result();
        $transactions = $transactions_result->fetch_all(MYSQLI_ASSOC);
        $transactions_stmt->close();
    } else {
        $message = "Operations query error: " . htmlspecialchars($conn->error);
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

<?php
if (isset($_POST['ajax_logout'])) {

    // حذف الكوكيز
    setcookie("id_user", "", time() - 3600, "/");

    // تأكيد الخروج
    echo "LOGOUT_OK";
    exit;
}
?>

<?php 
// معالجة طلب السحب
if (isset($_POST['submit-bton'])) {
    if (!isset($_COOKIE['id_user'])) {
        header("Location: login.php");
        exit();
    }

    $user_id = $_COOKIE['id_user'];

    // جلب البيانات من النموذج الجديد
    $amount         = trim($_POST['amount'] ?? '');
    $wallet_type    = trim($_POST['wallet_type'] ?? '');
    $wallet_number  = trim($_POST['wallet_number'] ?? '');

    // التحقق من الحقول
    if (empty($amount) || empty($wallet_type) || empty($wallet_number)) {
        $message_grop = "Please fill in all required fields";
        $message_type_grop = "error";

    } elseif (!is_numeric($amount) || $amount <= 0) {
        $message_grop = "The amount must be a larger number than 0";
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

            $commission = 0.5; 
            $total_after_commission = $amount - $commission;
            $date = date('Y-m-d H:i:s');

            if ($current_balance >= $amount && $total_after_commission > 0) {

                $status = "قيد المراجعة";
                $admin_note = "";

                // إدخال البيانات
                $stmt = $conn->prepare("
                    INSERT INTO withdraw_requests (user_id,wallet_type,amount,status,wallet_number,created_at,admin_note)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");

                if ($stmt) {
                    $stmt->bind_param(
                        "isdssss",
                        $user_id,
                        $wallet_type,
                        $amount,
                        $status,
                        $wallet_number,
                        $date,
                        $admin_note
                    );

                    if ($stmt->execute()) {
                        $message_grop = "The withdrawal request has been successfully submitted and will be reviewed soon.";
                        $message_type_grop = "success";

                        // خصم المبلغ
                        $new_balance = $current_balance - $amount;
                        $update_stmt = $conn->prepare("UPDATE wallets SET balance = ? WHERE id_user = ?");
                        $update_stmt->bind_param("di", $new_balance, $user_id);
                        $update_stmt->execute();
                        $update_stmt->close();

                    } else {
                        $message_grop = "error: " . $stmt->error;
                        $message_type_grop = "error";
                    }

                    $stmt->close();

                } else {
                    $message_grop = "error of SQL: " . $conn->error;
                    $message_type_grop = "error";
                }

            } else {
                $message_grop = "Insufficient balance";
                $message_type_grop = "error";
            }

        } else {
            $message_grop = "No wallet was found for the user.";
            $message_type_grop = "error";
        }

        $wallet_stmt->close();
    }
}


?>

 
<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TG</title>

    <!--
    - favicon
  -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="shortcut icon" href="./assets/images/logo/photo_2025-10-31_16-58-01.jpg" type="image/x-icon" />
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
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
          <img src="./new-loader.gif" alt="" />
        </div>
        <div class="loader-progress" id="progress_div">
          <div class="bar" id="bar1"></div>
        </div>
        <div class="percent" id="percent1">0%</div>
        <div class="loading-text">Loading...</div>
      </div>
    </div>
    <div class="overlay" data-overlay></div> -->

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
    </div>-->
    <div class="overlay" data-overlay></div> 
 
    <header>
        <div class="header-top">
        <div class="container">
        <?php if (!isset($_COOKIE['id_user'])): ?>

            <div class="logo">
                <a href="login.php" class="banner-btn login">تسجيل الدخول</a>
            </div>

            <?php else: ?>
        <div class="logo">
            <a href="#" class="header-logo" id="open-buttton-logut">
                <img src="./assets/images/my/png-clipart-user-profile-computer-icons-avatar-profile-s-free-angle-rectangle-thumbnail.png"
                    alt="User Logo"
                    width="40"
                    height="40"
                    style="border-radius: 50%;" />
            </a>
              <!-- <p class="logout" id="logout"  >تسجيل الخروج</p> -->
              <input type="submit" class="logout"  id="logout" value="تسجيل الخروج">
        </div>
        <?php endif; ?>

            <div class="logo">
            <!-- <a href="#" class="header-logo" id="open-buttton-logut" >
                        <img src="./assets/images/my/png-clipart-user-profile-computer-icons-avatar-profile-s-free-angle-rectangle-thumbnail.png" alt="Anon's logo" width="40" height="40" style="border-radius: 54%" />
                    </a> -->
                    
                    </div>  
              
                <div class="header-user-actions">
                    <a href="#" class="header-logo">
                        <img src="./assets/images/logo/photo_2025-10-31_16-58-01.jpg" alt="Anon's logo" width="40" height="40" style="border-radius: 54%" />
                    </a>
                </div>
            </div>
            </div>
            <!-- <div class="header-main">
              <?php if (!isset($_COOKIE['id_user'])): ?>
                    <div>
                        <a href="login.php" class="banner-btn login">تسجيل الدخول</a>
                    </div>
            <?php endif; ?> -->
        
          
        </div>  
        <div class="cart-c" dir="rtl">
        <div class="card-tit">Transactions</div>
<div class="card-header">
    <form action="" method="POST" action="index.php">
        <select class="select-task" id="statusFilter" name="select_stat">
            <option selected value="all">All</option>
            <option value="last">Last Transaction</option>
            <option value="completed">Completed</option>
            <option value="rejected">Failed</option>
            <option value="under_review">Under Review</option>
        </select>
    </form>
</div>
<div class="cart-content" id="transactionsContainer">

<?php if (!empty($transactions) && is_array($transactions)): ?>

    <?php foreach ($transactions as $transaction): ?>

        <?php 
            $amount = isset($transaction['amount']) ? number_format($transaction['amount'], 2) : '0.00';
            $date   = isset($transaction['created_at']) ? date('d/m/Y', strtotime($transaction['created_at'])) : date('d/m/Y');
            $type   = $transaction['type'] ?? 'Withdrawal';
            $status = $transaction['status'] ?? 'Unknown';
            $wallet = $transaction['wallet_number'] ?? 'Not Available';
            $receipt = $transaction['admin_note'] ?? '—';
            $reject_reason = $transaction['reject_reason'] ?? '—';
        ?>
                 
        <div class="card improved-card" style="margin-bottom: 15px">

            <div class="task-content">

                <div class="task-row">
                    <span>Transaction:</span>
                    <strong><?= htmlspecialchars($type) ?></strong>
                </div>

                <div class="task-row">
                    <span>Time:</span>
                    <strong><?= $date ?></strong>
                </div>

                <div class="task-row">
    <span>Status:</span>

    <?php 
    // تحويل الحالة إلى نص مقروء مع دعم جميع الحالات
    $status_text = '';
    $status_class = '';
    
    switch(strtolower($status)) {
        case 'completed':
        case 'مكتمل':
        case 'مكتملة':
            $status_text = 'مكتمل';
            $status_class = 'secss';
            break;
            
        case 'under_review':
        case 'قيد المراجعة':
        case 'قيد_المراجعة':
        case 'مراجعة':
            $status_text = 'قيد المراجعة';
            $status_class = 'review';
            break;
            
        case 'rejected':
        case 'مرفوض':
        case 'مرفوضة':
        case 'مرفوض':
            $status_text = 'مرفوض';
            $status_class = 'failed';
            break;
            
        case 'accepted':
        case 'مقبول':
        case 'مقبولة':
        case 'مقبول':
            $status_text = 'مقبول';
            $status_class = 'secss';
            break;
            
        case 'pending':
        case 'قيد الانتظار':
        case 'انتظار':
            $status_text = 'قيد الانتظار';
            $status_class = 'pending';
            break;
            
        case 'processing':
        case 'قيد المعالجة':
        case 'معالجة':
            $status_text = 'قيد المعالجة';
            $status_class = 'processing';
            break;
            
        default:
            $status_text = htmlspecialchars($status);
            $status_class = 'unknown';
            break;
    }
    ?>
    
    <span class="status <?= $status_class ?>"><?= $status_text ?></span>
</div>

                <div class="task-row">
                    <span>Wallet:</span>
                    <strong><?= htmlspecialchars($wallet) ?></strong>
                </div>

                <div class="task-row">
                    <span>Receipt:</span>
                    <strong><?= htmlspecialchars($receipt) ? "" : "__" ?></strong>
                </div>

                <?php if ($status === "rejected"): ?>
                <div class="task-row">
                    <span>Reject Reason:</span>
                    <span class="status failed"><?= htmlspecialchars($reject_reason) ? "" : "__" ?></span>
                </div>
                <?php endif; ?>

                <div class="task-row">
                    <span>Amount (USDT):</span>
                    <strong><?= $amount ?></strong>
                </div> 

            </div>
        </div>

    <?php endforeach; ?>

<?php else: ?> 
    <p style="text-align:center;color:gray;">No transactions found.</p>
<?php endif; ?>

</div>

<!-- 
    <div class="icon-txt">
        <div class="main-text">الرصيد</div>
        <div class="cart-total"><?php echo isset($wallet['balance']) ? number_format($wallet['balance'], 2) : '0.00'; ?></div>
    </div>

    <a href="clk-card.php">
        <div class="bramer-boutt add-cart-btn bramer-boutt-shop">سحب</div>
    </a> -->

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
            <button class="action-btn"  >
          <ion-icon
            name="menu-outline"
            role="img"
            class="md hydrated"
            aria-label="menu outline"
          ></ion-icon>
        </button>

            <button class="action-btn card-icon">
              <ion-icon
              name="wallet-outline"
              role="img"
              class="md hydrated"
              aria-label="wallet outline"
            ></ion-icon>
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
              <ion-icon
              name="notifications-outline"
              role="img"
              class="md hydrated"
              aria-label="notifications outline"
            ></ion-icon>

            <span class="count" data-quantity="0"><?php echo isset($total_notifications) ? $total_notifications : 0; ?></span>
        </button>


        </div>

        <nav class="mobile-navigation-menu has-scrollbar" data-mobile-menu="">
    <div class="menu-top">
      <h2 class="menu-title">Tasks</h2>

      <button class="menu-close-btn" data-mobile-menu-close-btn="">
        <ion-icon name="close-outline" role="img" class="md hydrated" aria-label="close outline"></ion-icon>
      </button>
    </div>

    <div class="card-header">
        <!-- <i class="icon-task">📋</i>
        <img src="./assets/images/logo/task.svg" class="icon-task" alt="">
        <span>Task List</span>
        <a href="#" class="view-all" id="card-icon">All »</a> -->
        <select name="" id="" class="select-task">
            <option value="">Last Transaction</option>
            <option value="">All</option>
            <option value="">Completed</option>
            <option value="">Failed</option>
            <option value="">Under Review</option>
        </select>
    </div>

    <!-- Task List -->
    <?php if (!empty($order_exists)): ?>
    <div class="card improved-card fade-in" style="margin-top:20px">

        <div class="card-header">
            <img src="./assets/images/logo/task.svg" class="icon-task" alt="">
            <span>New Order Details</span>
            <!-- <a href="#" class="view-all" data-mobile-menu-open-btn="">All »</a> -->
        </div>

        <div class="task-content">
            <div class="task-row">
                <span>Task:</span>
                <strong><?= htmlspecialchars($latest_order['task_name']) ?></strong>
            </div>

            <div class="task-row">
                <span>Number of Groups:</span>
                <strong><?= $latest_order['group_count'] ?></strong>
            </div>

            <div class="task-row">
                <span>Estimated Earnings (USDT):</span>
                <strong>0</strong>
            </div>

            <div class="task-row">
                <span>Status:</span>
                <?php
                // جلب الحالة الفعلية من البيانات
                $status = $latest_order['status'] ?? 'Under Review';
                
                // تحديد كلاس CSS حسب الحالة
                $status_class = '';
                if ($status === 'Completed' || $status === 'مكتمل') {
                    $status_class = 'secss';
                } elseif ($status === 'secss' || $status === 'مقبول') {
                    $status_class = 'failed';
                } elseif ($status === 'secss' || $status === 'في انتظار المالك') {
                    $status_class = 'failed';
                } elseif ($status === 'Rejected' || $status === 'مرفوض') {
                    $status_class = 'failed';
                } else {
                    $status_class = 'review';
                }
                ?>
                <span class="status <?= $status_class ?>"><?= htmlspecialchars($status) ?></span>
            </div>

            <div class="task-row">
                <span>Time:</span>
                <strong><?= $latest_order['date'] ?></strong>
            </div>
        </div>
    </div>
    <?php endif; ?>

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

       
        <!--
        - BANNER
      -->

        <!--
        - CATEGORY
      -->
        <br />
        <!--
        - PRODUCT
      -->
      <div class="banner" dir="rtl">
        <div class="container">
          <div class="slider-container has-scrollbar">
            <div class="slider-item">
              <img src="./assets/images/logo/photo_2025-10-31_16-58-01.jpg" alt="women's latest fashion sale" class="banner-img">

              <center>
              <div class="banner-content">
             
             <h2 class="banner-title"> WELCOME TO  <span>'TG'</span> </h2>
             <p class="banner-text">
             Your professional, efficient and reliable Telegram Group partner
                     </p>
                     <center>
                     <a href="#" class="banner-btn" id="banner-btn-seand">Sell Telegram
             <i class="icon uil uil-telegram-alt"></i>
             </a> 
             <p class="banner-subtitle">How to sale my Telegram Group?</p>
                     </center>
           </div>
              </center>
             
            </div>
         <!-- المودال -->
 <!-- النموذج الثاني -->
 <div id="customModal" class="modal-overlay" style="display: none;">
        <div class="modal-box">
            <span class="close-modal">&times;</span>
            
            <center>
            <img src="./assets/images/my/home-bg.png" alt="women's latest fashion sale" class="banner-img" width="400px" height="400px">
            </center>
            
            <h2 class="modal-title">How Can I Sell My Telegram Group?</h2>

            <ul class="modal-text">
                <li>You must be the original creator of the group.</li>
                <li>The group must be set as a "Private Group".</li>
                <li>In the group settings, the option "Chat history for new members" must be enabled (set to "Visible").</li>
                <li>The group creation date must fall within the time range specified in the pricing list.</li>
                <li>Please provide the group’s invite link so we can verify your ownership through it.</li>
                <li>After that, you will need to transfer the ownership of the group to us.</li>
                <li>We will then conduct a full review.</li>
                <li>Congratulations! You will now be eligible to receive the payment.</li>
            </ul>

        </div>
    </div>


<!-- المودال الخاص بالنموذج -->
<div id="requestModal" class="modal-overlay" style="display: none;">
    <div class="modal-box form-modal">
        <span class="close-modal">&times;</span>

        <h2 class="form-title">
            Create Request
        </h2>

        <p class="form-alert">
            (You must enable permission for group members to send messages first)
        </p>

        <form id="requestForm" method="POST" action="index.php">

            <div class="form-group">
                <label class="form-label">Task Name</label>
                <input type="text" class="form-input" name="task_name" placeholder="Enter your task name" required>
            </div>

            <div class="form-group">
                <label class="form-label">Invite Link Type</label>
                <div class="radio-group">
                    <label class="radio-option selected">
                        <input type="radio" name="link_type" value="single" checked>
                        <span>Single</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="link_type" value="multiple">
                        <span>Multiple</span>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Group Link</label>
                <textarea class="form-textarea" name="group_links" placeholder="Enter group links, one per line." required></textarea>
            </div>

            <div class="form-btns">
                <button type="button" class="btn-cancel">Cancel</button>
                <button type="submit" class="btn-submit" name="send-grop">Submit</button>
            </div>

        </form>

    </div>
</div>


            
          
          </div>
        </div>
      </div>
        <div class="product-container">
         
            <div class="container">
                <!--
              - SIDEBAR
            -->

                <div class="product-box">
                    <!--
                        - PRODUCT MINIMAL
                        -->
                    <div class="product-container">
                        <div class="container" dir="rtl">
                            <!--
                            - SIDEBAR
                          -->

                            <div class="product-box">
                                <!--
                          - PRODUCT MINIMAL
                          -->

                                <div class="product-minimal">
                                    <div class="product-showcase">
                                        <h2 class="title">wallet</h2>

                                        <div class="showcase-wrapper has-scrollbar" dir="rtl">
                                            <div class="showcase-container">
                                                <div class="showcase">
                                                    <a href="#" class="showcase-img-box">
                                                        <img src="./assets/images/logo/amount.png" alt="" width="50" height="50" class="showcase-img">
                                                    </a>

                                                    <div class="showcase-content" dir="rtl">
                                                        <!-- <a href="#">
                                                            <h4 class="showcase-title"> </h4>
                                                        </a> -->
                                                        <?php
include("./admin/include/conaction.php");

// التحقق من وجود user_id في الكوكيز
$user_id = $_COOKIE['id_user'] ?? 0;

if ($user_id > 0) {
    // استعلام للحصول على بيانات المحفظة
    $stmt = $conn->prepare("SELECT * FROM wallets WHERE id_user = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $wallet = $result && $result->num_rows > 0 ? $result->fetch_assoc() : null;

        ?>
        <div class="showcase-content" dir="rtl">
            <!-- <a href="#" class="showcase-category">
                ID( )
            </a> -->

            <div class="price-box">
                <p class="price">
                Amount <?php echo isset($wallet['balance']) ? number_format($wallet['balance'], 2) : '0.00'; ?> (USDT)
                </p>
            <div class="buttons-container"> 
                <a href="#" 
                   class="banner-btn" 
                   id="butoon-seand"
                   style="<?php echo $wallet ? '' : 'background-color: #ccc; cursor: not-allowed;'; ?>">
                   Withdraw
                </a>
                <a href="#" 
                class="banner-btn" 
                id="butoon-seand-mor"
                  
                style="<?php echo $wallet ? '' : 'background-color: #ccc; cursor: not-allowed;'; ?>">
                Record
                </a>
            </div>
            </div>

            <?php if (!$wallet): ?>
                <p style="color: #ff0000; font-size: 12px; margin-top: 10px;">Not a simple credit card</p>
            <?php endif; ?>
        </div>
        <?php
        $stmt->close();
    } else {
        echo "<p style='color:red;'>خطأ في استعلام المحفظة: " . htmlspecialchars($conn->error) . "</p>";
    }
} else {
    // في حالة عدم تسجيل الدخول
    ?>
<div class="showcase-content" dir="rtl">
    <div class="price-box">
        <p class="price">Amount 0.00 <span>(USDT)</span></p>
        <div class="buttons-container">
            <a href="#" class="banner-btn" id="butoon-seand">Withdraw </a>
            <a href="#" class="banner-btn" id="butoon-seand-mor">Record  
</a>
            <!-- <a href="#" class="banner-btn"  id="card-icon">السجل</a> -->
        </div>
    </div>
</div>
    <?php
}
?>
<!-- المــودل -->
<div class="modal-custom" id="walletModal">
    <div class="modal-content-custom">
        <span class="close-modal" id="closeModal">&times;</span>

        <h3>Wallet</h3>
        
        <div class="time-notice">
            Withdrawal arrival time (Beijing Time): 10:00 - 22:00
        </div>

        <!-- Start Form -->
        <form method="POST">

            <div class="form-group">
                <label><strong>Wallet Type:</strong></label>
                <div class="radio-group">
                    <label class="radio-option selected">
                        <input type="radio" name="wallet_type" value="TRC20" checked>
                        <span>TRC20</span>
                    </label>

                    <label class="radio-option">
                        <input type="radio" name="wallet_type" value="BINANCE">
                        <span>Binance</span>
                    </label>
                </div>
            </div>

            <div class="fee-notice">
                <p><strong>Note:</strong> No withdrawal fee is applied when the withdrawal amount is ≥ 30 USDT.</p>
                <p><strong>Withdrawal Fee:</strong> 0.5 USDT.</p>
            </div>

            <div class="form-group">
                <label for="walletInput"><strong>Wallet Address:</strong></label>
                <input 
                    type="text" 
                    name="wallet_number" 
                    id="walletInput" 
                    class="wallet-input" 
                    placeholder="Please enter your wallet address..."
                >
                <span class="error">Please enter your wallet address!</span>
            </div>

            <div class="form-group">
                <label><strong>Amount:</strong></label>
                <input 
                    type="number" 
                    name="amount" 
                    class="wallet-input" 
                    placeholder="Enter withdrawal amount"
                >
            </div>

            <button class="confirm-btn" type="submit" name="submit-bton">Confirm</button>

        </form>
        <!-- End Form -->

    </div>
</div>



<!-- المــودل -->
<div class="modal-2" id="recordModal">
    <div class="modal-content-2">

        <!-- زر الإغلاق -->
        <span class="close-2" id="closeModal2">&times;</span>

        <h3 style="margin:0 0 15px;">Record (Telegram Customer: @Pandorasan)</h3>

        <div class="table-box">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Amount (USDT)</th>
                        <th>Wallet</th>
                        <th>Status</th>
                        <th>Create Time</th>
                        <th>Complete Time</th>
                        <th>Rejection Reason</th>
                        <th>Receipt</th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                        <th>#</th>
                        <th>Amount (USDT)</th>
                        <th>Wallet</th>
                        <th>Status</th>
                        <th>Create Time</th>
                        <th>Complete Time</th>
                        <th>Rejection Reason</th>
                        <th>Receipt</th>
                    </tr>
            
                </tbody>
            </table>
            <!-- <div class="no-data-box">
            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486740.png" width="60">
            <p>No data</p>
        </div> -->
        <!-- <div class="pagination">
    <span class="arrow">&lt;</span>
    <span class="page-number">1</span>
    <span class="arrow">&gt;</span>
</div> -->

        
        </div>

     

    </div>
</div>
                                                        <!-- <a href="#" class="showcase-category">ID(7883274)</a>

                                                        <div class="price-box">
                                                            <p class="price">الرصيد 23.00(USDT)</p>
                                                            <a href="clk-card.html" class="banner-btn"> سحب'</a>
                                                            <a href="#" class="banner-btn" id="car-open">السجل</a>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="product-showcase">
                                        <h2 class="title">Today</h2>

                                        <div class="showcase-wrapper has-scrollbar" dir="rtl">
                                            <div class="showcase-container">
                                                <div class="showcase">
                                                    <a href="#" class="showcase-img-box">
                                                        <img src="./assets/images/logo/today.png" alt="أحذية للجري والمشي - أبيض" class="showcase-img" width="50" height="50" >
                                                    </a>

                                                    <div class="showcase-content" dir="rtl">
                                                        <a href="#" class="showcase-category">Total Sold Groups</a>

                                                        <div class="price-box">
                                                        <p class="price"><?php echo isset($completed_count) && !empty($completed_count) ? $completed_count :"__"; ?></p>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--
                              - PRODUCT FEATURED
                            -->

                                <!--
                              - PRODUCT GRID
                            -->
                            </div>
                        </div>
                    </div>

                    <div class="product-box">
 
<!-- Task List -->
<?php if (!empty($order_exists)): ?>
    <div class="card improved-card fade-in" style="margin-top:20px">

<div class="card-header">
    <img src="./assets/images/logo/task.svg" class="icon-task" alt="">
    <span>New Order Details</span>
    <a href="#" class="view-all" data-mobile-menu-open-btn="">View All »</a>
</div>

<div class="task-content">
    <div class="task-row">
        <span>Task:</span>
        <strong><?= htmlspecialchars($latest_order['task_name']) ?></strong>
    </div>

    <div class="task-row">
        <span>Number of Groups:</span>
        <strong><?= $latest_order['group_count'] ?></strong>
    </div>

    <div class="task-row">
        <span>Estimated Earnings (USDT):</span>
        <strong>0</strong>
    </div>

    <div class="task-row">
                <span>Status:</span>
                <?php
                // جلب الحالة الفعلية من البيانات
                $status = $latest_order['status'] ?? 'Under Review';
                
                // تحديد كلاس CSS حسب الحالة
                $status_class = '';
                if ($status === 'Completed' || $status === 'مكتمل') {
                    $status_class = 'secss';
                } elseif ($status === 'Rejected' || $status === 'مرفوض') {
                    $status_class = 'failed';
                } else {
                    $status_class = 'review';
                }
                ?>
                <span class="status <?= $status_class ?>"><?= htmlspecialchars($status) ?></span>
            </div>

    <div class="task-row">
        <span>Time:</span>
        <strong><?= $latest_order['date'] ?></strong>
    </div>
</div>
</div>

<?php endif; ?>



<!-- Contact Us -->
<div class="card improved-card contact-card">
    <div class="card-header">
        <!-- <i class="icon-message">💬</i> -->
        <img src="./assets/images/logo/contactUs.svg" class="icon-message" alt="">
        <span>Contact Us</span>
    </div>

    <div class="contact-row">
        <div class="left">
            <!-- <i class="contact-icon">📨</i>] -->
            <img src="./assets/images/logo/telegram.svg" class="contact-icon" alt="">
            <div>
                <div class="contact-title">Telegram</div>
                <div class="contact-user">@Buying_dollars</div>
            </div>
        </div>
        <button class="copy-btn">📋</button>
    </div>

    <hr>

    <div class="contact-row">
        <div class="left">
            <!-- <i class="contact-icon">✉️</i> -->
            <img src="./assets/images/logo/email.svg" class="contact-icon" alt="">
            <div>
                <div class="contact-title">Email</div>
                <div class="contact-user">osamaalnaqeeb7@gmail.com</div>
            </div>
        </div>
        <button class="copy-btn">📋</button>
    </div>
</div>



    <?php 
    include("./admin/include/conaction.php");
    $query = "SELECT * FROM groups";
    $resl = $conn->query($query);
    ?>

<li class="has-child expand">
    <h1 class="icon-small" style="text-align: center">Group Prices</h1>
    <div class="content">
        <?php if ($resl && $resl->num_rows > 0) { ?>
            <table dir="ltr">
                <thead   >
                    <tr>
                        <th>Price</th>
                        <th>Currency</th>
                        <th>Date</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody dir="ltr">
    <?php 
    $n = 1;
    while ($row = $resl->fetch_assoc()) {

            // تجهيز التاريخ حسب القيم
            $year = $row['date_group'] ?? '----';
            $start = $row['month_start'] ?? 0;
            $end   = $row['month_end'] ?? 0;

            // إذا كانت القيم صفر → اطبع السنة فقط
            if ($start == 0 && $end == 0) {
                $date_group = htmlspecialchars($year);
            } else {
                // اطبع بالشكل: 2024 (10 ~ 12)
                $date_group = htmlspecialchars("$year ($start ~ $end)");
            }


        echo '<tr>
            <td>'. htmlspecialchars($row['price'] ?? '0.00') .'</td>
            <td>'. htmlspecialchars($row['coin'] ?? '-') .'</td>
            <td>'. $date_group .'</td>
            <td>'. $n .'</td>
        </tr>';

        $n++;
    } 
    ?>
</tbody>

            </table>
        <?php } else { ?>
            <p style="text-align:center;color:gray;">No group data available yet.</p>
        <?php } ?>
    </div>
</li>

</div>

                </div>
                <!--
                - PRODUCT FEATURED
              -->

                <!--
                - PRODUCT GRID
              -->
            </div>
            <!-- <div class="service" id="shoping">
                <h2 class="title">طريقة بيع القروب</h2>

                <div class="service-container" dir="ltr">
                    <a href="#" class="service-item">
                        <div class="service-icon">
                            <ion-icon name="chatbubbles-outline" role="img" class="md hydrated" aria-label="chatbubbles outline"></ion-icon>
                        </div>

                        <div class="service-content" dir="rtl">
                            <h3 class="service-title">طريقة بيع القروب</h3>
                            <p class="service-desc">
                                أولاً، قم بتسجيل الدخول إلى الموقع.<br> بعد ذلك، أدخل رابط القروب في مربع النص الموجود بالأعلى.<br> سيتواصل معك المالك لتأكيد عملية النقل.<br> بعد التأكد من إتمام النقل، سيتم إيداع المبلغ في
                                <b>محفظة باي تلي (PayTely)</b> الخاصة بالقروب.<br> يمكنك بعدها سحب أموالك من المحفظة بالطريقة التي تناسبك.
                            </p>
                        </div>
                    </a>

                    <a href="#" class="service-item">
                        <div class="service-icon">
                            <ion-icon name="cash-outline" role="img" class="md hydrated" aria-label="cash outline"></ion-icon>
                        </div>

                        <div class="service-content" dir="rtl">
                            <h3 class="service-title">محفظة باي تلي</h3>
                            <p class="service-desc">
                                نظام محفظة إلكترونية آمن يتيح لك استقبال الأرباح وسحبها بسهولة بعد بيع القروب أو إتمام أي معاملة داخل الموقع.
                            </p>
                        </div>
                    </a>

                    <a href="#" class="service-item">
                        <div class="service-icon">
                            <ion-icon name="call-outline" role="img" class="md hydrated" aria-label="call outline"></ion-icon>
                        </div>

                        <div class="service-content" dir="rtl">
                            <h3 class="service-title">دعم العملاء</h3>
                            <p class="service-desc">
                                فريق دعم متواجد دائمًا لمساعدتك في أي استفسار أو مشكلة أثناء عملية البيع أو التحويل.
                            </p>
                        </div>
                    </a>

                    <a href="#" class="service-item">
                        <div class="service-icon">
                            <ion-icon name="shield-checkmark-outline" role="img" class="md hydrated" aria-label="shield checkmark outline"></ion-icon>
                        </div>

                        <div class="service-content" dir="rtl">
                            <h3 class="service-title">أمان المعاملات</h3>
                            <p class="service-desc">
                                نحن نضمن أن جميع المعاملات تتم بأمان تام، ويتم تحويل الأموال فقط بعد التأكد من عملية نقل القروب بنجاح.
                            </p>
                        </div>
                    </a>
                </div>
            </div> -->
            <div></div>
        </div>

        <!--
        - TESTIMONIALS, CTA & SERVICE
      -->

        <!--
        - BLOG
      -->
    </main>

    <footer>
        <div class="footer-nav">
            <div class="container">
                <ul class="footer-nav-list" dir="ltr">
                    <li class="footer-nav-item">
                        <h2 class="nav-title">For contact</h2>
                    </li>

                    <li class="footer-nav-item flex">
                        <div class="icon-box">
                            <ion-icon name="person-circle-outline"></ion-icon>
                        </div>

                        <address class="content">
                          <a href="https://t.me/Buying_dollars" class="footer-nav-link">@ Buying_dollars</a>
                          
                       </address>
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

              <a href="mailto:osamaalnaqeeb7@gmail.com" class="footer-nav-link"
                >osamaalnaqeeb7@gmail.com</a
              >
            </li>
          </ul> 
            </div>
        </div>

     
            <div class="container">
                <p class="copyright">
                    Copyright © <a href="#">TG</a> all rights reserved @2025
                        </p>
            </div>
      
    </footer>
    
<script>
// منع إعادة الإرسال عند التحديث
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

// التحقق من النموذج قبل الإرسال
function validateForm() {
    const submitBtn = document.getElementById('submitBtn');
    const groupLinks = document.querySelector('textarea[name="group_links"]');
    
    // تعطيل الزر لمنع الإرسال المتعدد
    submitBtn.disabled = true;
    submitBtn.innerHTML = 'جاري الإرسال...';
    
    // تنظيف الروابط من المسافات الزائدة
    if (groupLinks) {
        const links = groupLinks.value.split('\n')
            .map(link => link.trim())
            .filter(link => link !== '');
        groupLinks.value = links.join('\n');
    }
    
    return true;
}

// عد تنازلي للإعادة التوجيه
<?php if (isset($message_type_grop) && $message_type_grop === "success"): ?>
let seconds = 5;
const countdownElement = document.getElementById('countdown');
const countdownInterval = setInterval(() => {
    seconds--;
    if (countdownElement) {
        countdownElement.textContent = seconds;
    }
    if (seconds <= 0) {
        clearInterval(countdownInterval);
    }
}, 1000);
<?php endif; ?>

// إعادة تمكين الزر إذا كان هناك خطأ (لحالة فشل الإرسال)
<?php if (isset($message_type_grop) && $message_type_grop === "error"): ?>
document.addEventListener('DOMContentLoaded', function() {
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'إرسال الطلب';
    }
});
<?php endif; ?>
</script>
    <script>
document.getElementById('logout').addEventListener('click', function (e) {
    e.preventDefault();

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "", true); // نفس الصفحة
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (this.responseText.trim() === "LOGOUT_OK") {
            alert("تم تسجيل الخروج بنجاح");
            window.location.href = "index.php"; // غيّرها لصفحة تسجيل الدخول
        }
    };

    xhr.send("ajax_logout=1");
});
</script>
 

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="./assets/js/sc.js"></script>

    <!--
    - ionicon link
  -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>

    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>