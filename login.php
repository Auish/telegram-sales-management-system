<?php
include("./admin/include/conaction.php");

$message = "";
$message_type = "";

if (isset($_POST['sing_up'])) {
    
    // 🔹 التحقق مما إذا كان المستخدم مسجل دخول بالفعل
    if (isset($_COOKIE['id_user'])) {
        header("Location: index.php");
        exit();
    }
    
    $username = trim($_POST['usernam'] ?? '');
    $email    = trim($_POST['emali'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $stat     = "user";

    // 🔹 إضافة تصحيح للأخطاء لمعرفة البيانات المستلمة
    error_log("بيانات التسجيل المستلمة - username: $username, email: $email, password: " . strlen($password) . " حرف");

    // 🔹 التحقق من الحقول الفارغة
    if ($username === "" || $email === "" || $password === "") {
        $message = "الرجاء تعبئة جميع الحقول المطلوبة.";
        $message_type = "error";
        error_log("خطأ: حقول فارغة في التسجيل");
    
    // 🔹 التحقق من صحة البريد الإلكتروني
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "بريد إلكتروني غير صالح.";
        $message_type = "error";
        error_log("خطأ: بريد إلكتروني غير صالح: $email");
    
    // 🔹 التحقق من صحة اسم المستخدم
    } elseif (!preg_match('/^[a-zA-Z0-9]{5,20}$/', $username)) {
        $message = "اسم المستخدم يجب أن يحتوي على 5-20 حرفاً (إنجليزية وأرقام فقط)";
        $message_type = "error";
        error_log("خطأ: اسم المستخدم غير صالح: $username");
    
    // 🔹 التحقق من طول كلمة المرور
    } elseif (strlen($password) < 6) {
        $message = "كلمة المرور يجب أن تكون 6 أحرف على الأقل.";
        $message_type = "error";
        error_log("خطأ: كلمة المرور قصيرة جداً");
    
    } else {
        // 🔹 التحقق من عدم وجود اسم المستخدم مسبقاً
        $check_username_stmt = $conn->prepare("SELECT id FROM user WHERE username = ?");
        $check_username_stmt->bind_param("s", $username);
        $check_username_stmt->execute();
        $check_username_stmt->store_result();
        
        if ($check_username_stmt->num_rows > 0) {
            $message = "اسم المستخدم '$username' محجوز مسبقاً. الرجاء اختيار اسم آخر.";
            $message_type = "error";
            $check_username_stmt->close();
            error_log("خطأ: اسم المستخدم محجوز مسبقاً: $username");
        
        } else {
            $check_username_stmt->close();
            
            // 🔹 التحقق من عدم وجود البريد الإلكتروني مسبقاً
            $check_email_stmt = $conn->prepare("SELECT id FROM user WHERE email = ?");
            $check_email_stmt->bind_param("s", $email);
            $check_email_stmt->execute();
            $check_email_stmt->store_result();
            
            if ($check_email_stmt->num_rows > 0) {
                $message = "البريد الإلكتروني مسجل مسبقاً.";
                $message_type = "error";
                $check_email_stmt->close();
                error_log("خطأ: البريد الإلكتروني مسجل مسبقاً: $email");
            
            } else {
                $check_email_stmt->close();
                
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // 🔹 التحقق من أن التشفير نجح
                if (!$hashed_password) {
                    $message = "خطأ في تشفير كلمة المرور.";
                    $message_type = "error";
                    error_log("خطأ: فشل في تشفير كلمة المرور");
                
                } else {
                    // 🔹 بدء معاملة قاعدة البيانات لضمان تكامل البيانات
                    $conn->begin_transaction();
                    
                    try {
                        $stmt = $conn->prepare("INSERT INTO user (username, email, password, status) VALUES (?, ?, ?, ?)");

                        if ($stmt) {
                            $stmt->bind_param("ssss", $username, $email, $hashed_password, $stat);
                            
                            if ($stmt->execute()) {
                                error_log("تم إنشاء المستخدم بنجاح: $username");
                                
                                // الحصول على ID المستخدم الذي تم إنشاؤه حديثاً
                                $user_id = $stmt->insert_id;
                                $stmt->close();
                                
                                // إنشاء رقم بطاقة عشوائي فريد
                                $card_number = generateUniqueCardNumber($conn);
                                error_log("تم إنشاء رقم البطاقة: $card_number");
                                
                                // إنشاء محفظة جديدة للمستخدم
                                if (createWallet($conn, $user_id, $card_number)) {
                                    // 🔹 تأكيد المعاملة
                                    $conn->commit();
                                    
                                    // 🔹 حفظ الكوكي والجلسة
                                    setcookie("id_user", $user_id, time() + 2592000, "/");
                                    $_SESSION['user_id'] = $user_id;
                                    $_SESSION['username'] = $username;
                                    $_SESSION['status'] = $stat;
                                    
                                    $message = "تم إنشاء الحساب والمحفظة بنجاح ✅";
                                    $message_type = "success";
                                    error_log("تم إنشاء المحفظة بنجاح للمستخدم: $user_id");
                                    
                                    // 🔹 إعادة توجيه بعد النجاح
                                    header("Refresh: 2; url=index.php");
                                    $show_redirect = true;
                                
                                } else {
                                    throw new Exception("فشل في إنشاء المحفظة");
                                }
                            
                            } else {
                                throw new Exception("فشل في إنشاء الحساب: " . $stmt->error);
                            }
                        
                        } else {
                            throw new Exception("فشل إعداد استعلام الإدخال: " . $conn->error);
                        }
                    
                    } catch (Exception $e) {
                        // 🔹 التراجع عن المعاملة في حالة الخطأ
                        $conn->rollback();
                        $message = "حدث خطأ أثناء إنشاء الحساب: " . $e->getMessage();
                        $message_type = "error";
                        error_log("خطأ في المعاملة: " . $e->getMessage());
                    }
                }
            }
        }
    }
}
 

/**
 * دالة لإنشاء رقم بطاقة عشوائي فريد
 */
function generateUniqueCardNumber($conn) {
    $is_unique = false;
    $card_number = '';
    
    while (!$is_unique) {
        // إنشاء رقم بطاقة عشوائي مكون من 16 رقم
        $card_number = '';
        for ($i = 0; $i < 16; $i++) {
            $card_number .= mt_rand(0, 9);
        }
        
        // التحقق من أن الرقم غير موجود مسبقاً
        $check_stmt = $conn->prepare("SELECT id FROM wallets WHERE namber = ?");
        $check_stmt->bind_param("s", $card_number);
        $check_stmt->execute();
        $check_stmt->store_result();
        
        if ($check_stmt->num_rows === 0) {
            $is_unique = true;
        }
        $check_stmt->close();
    }
    
    return $card_number;
}

/**
 * دالة لإنشاء محفظة جديدة
 */
function createWallet($conn, $user_id, $card_number) {
    $initial_balance = 0; // الرصيد الابتدائي
    
    $stmt = $conn->prepare("INSERT INTO wallets (id_user,namber,balance) VALUES (?, ?, ?)");
    
    if ($stmt) {
        $stmt->bind_param("isd", $user_id, $card_number, $initial_balance);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    return false;
}
  session_start();
 

if (isset($_POST['sing_in'])) {

    // استلام البيانات من النموذج
    $login_input = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // التحقق من الحقول الفارغة
    if ($login_input === "" || $password === "") {
        $message = "يرجى تعبئة جميع الحقول المطلوبة.";
        $message_type = "error";
    } 
    else {
        // التحقق من وجود المستخدم باستخدام اسم المستخدم أو البريد الإلكتروني
        $stmt = $conn->prepare("SELECT id, username, email, password, status FROM user WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $login_input, $login_input);
        $stmt->execute();
        $result = $stmt->get_result();

        // إذا وجد المستخدم
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // التحقق من كلمة المرور
            if (password_verify($password, $user['password'])) {

                // حفظ بيانات الجلسة
                $_SESSION['id_user'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['status'] = $user['status'];

                // حفظ الكوكي
                setcookie("id_user", $user['id'], time() + 2592000, "/"); // 30 يوم

                // التحقق من نوع المستخدم
                if ($user['status'] === 'admin') {
                    header("Location: ./admin/cratadmin.php");
                    exit();
                } else {
                    header("Location: ./index.php");
                    exit();
                }
            } else {
                $message = "كلمة المرور غير صحيحة.";
                $message_type = "error";
            }
        } else {
            $message = "اسم المستخدم أو البريد الإلكتروني غير موجود في النظام.";
            $message_type = "error";
        }

        $stmt->close();
    }
}

?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SignIn&SignUp</title>
    <link rel="stylesheet" type="text/css" href="./style.css" />
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"
    ></script>
  </head>

  <body>
  <style>
    /* صندوق التنبيه في أعلى الصفحة */
    .alert-box {
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 9999;
      min-width: 280px;
      max-width: 94%;
      padding: 14px 18px;
      border-radius: 8px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.12);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      font-family: system-ui, "Segoe UI", Roboto, "Noto Sans", "Helvetica Neue", Arial;
    }
    .alert-box.success { background: linear-gradient(90deg,#2ecc71,#27ae60); }
    .alert-box.error   { background: linear-gradient(90deg,#ff6b6b,#e74c3c); }

    .alert-box .msg {
      flex: 1;
      font-size: 14px;
      line-height: 1.3;
      text-align: right;
      direction: rtl;
    }

    .alert-box .close-btn {
      cursor: pointer;
      background: rgba(255,255,255,0.12);
      border: none;
      color: #fff;
      padding: 6px 10px;
      border-radius: 6px;
      font-weight: 600;
    }

    /* استجابة على الشاشات الصغيرة */
    @media (max-width: 480px) {
      .alert-box { padding: 12px; font-size: 13px; top: 12px; }
      .alert-box .close-btn { padding: 5px 8px; font-size: 13px; }
    }

    /* تنسيق بسيط للفورم */
    .container { max-width: 720px; margin: 120px auto 40px; padding: 18px; }
    label { display:block; margin-bottom:6px; font-weight:600; }
    input { width:100%; padding:10px; margin-bottom:12px; border-radius:6px; border:1px solid #ddd; }
    button.primary { background:#0077ff; color:#fff; padding:10px 14px; border:none; border-radius:6px; cursor:pointer; }
  </style>

  <!-- هنا نعرض صندوق التنبيه لو كانت هناك رسالة -->
  <?php if (!empty($message)): ?>
    <div id="alertBox" class="alert-box <?= $message_type === 'success' ? 'success' : 'error' ?>">
      <div class="msg"><?= nl2br(htmlspecialchars($message)) ?></div>
      <button class="close-btn" id="closeAlert">اغلاق ✕</button>
    </div>
  <?php endif; ?>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          <form action="" class="sign-in-form" method="post">
            <h2 class="title">تسجيل الدخول</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text"  placeholder="اسم المستخدم أو البريد الإلكتروني"  name="username"/>
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" placeholder="Password"  name="password"/>
            </div>
            <input type="submit" value="Login" class="btn solid" name="sing_in" />
          </form>

          <form action="" class="sign-up-form" method="post">
            <h2 class="title">إنشاء حساب</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" placeholder="Username" name="usernam"/>
            </div>
            <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input type="email" placeholder="Email" name="emali" />
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" placeholder="Password" name="password" />
            </div>
            <input type="submit" value="إنشاء حساب" class="btn solid" name="sing_up" />
          </form>
        </div>
      </div>
      <div class="panels-container">
        <div class="panel left-panel">
          <div class="content">
            <h3>هل تريد انشاء حساب جدبد؟</h3>
            <p></p>
            <button class="btn transparent" id="sign-up-btn">إنشاء حساب</button>
          </div>
         
        </div>

        <div class="panel right-panel">
          <div class="content">
            <h3>هل لديك حساب بالفعل؟</h3>
            <p></p>
            <button class="btn transparent" id="sign-in-btn">
              تسجيل الدخول
            </button>
          </div>
          
        </div>
      </div>
    </div>
    <script>
  // إغلاق التنبيه بالزر أو اختفاؤه بعد 6 ثواني تلقائياً
  (function(){
    const alert = document.getElementById('alertBox');
    if (!alert) return;

    const closeBtn = document.getElementById('closeAlert');
    let autoHideTimer = setTimeout(() => hideAlert(), 6000);

    closeBtn.addEventListener('click', () => hideAlert());

    function hideAlert() {
      if (!alert) return;
      alert.style.transition = 'opacity 300ms ease, transform 300ms ease';
      alert.style.opacity = '0';
      alert.style.transform = 'translateX(-50%) translateY(-10px)';
      setTimeout(() => { alert.remove(); }, 320);
      clearTimeout(autoHideTimer);
    }
  })();
</script>
    <script src="./app.js"></script>
  </body>
</html>
