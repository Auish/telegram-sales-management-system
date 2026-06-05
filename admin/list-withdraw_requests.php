
<!--
 * CoreUI - Open Source Bootstrap Admin Template
 * @version v1.0.0-alpha.2
 * @link http://coreui.io
 * Copyright (c) 2016 creativeLabs Łukasz Holeczek
 * @license MIT
 -->
 <?php
include("./include/conaction.php");
// include("./include/check.php");
// الاستعلام على جدول الطلبات مع بيانات المستخدم
$query = "
SELECT 
    w.id AS withdraw_id,
    w.amount,
    w.status,
    w.wallet_number,
    w.created_at,
    w.admin_note,
    u.username,
    u.email,
    wallets.namber,
    wallets.balance
FROM withdraw_requests w
INNER JOIN user u ON w.user_id = u.id
INNER JOIN wallets ON wallets.id_user  = u.id
ORDER BY w.created_at DESC
";

$result = $conn->query($query);
?>

<?php
include("./include/conaction.php");

// معالجة تحديث حالة الطلب إذا كان هناك طلب POST
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order_status'])) {
    $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';
    
    if($order_id > 0 && !empty($status)) {
        $valid_statuses = ['قيد المراجعة', 'مقبول', 'مرفوض', 'مكتمل' ,'نقل الملكية'];
        
        if(in_array($status, $valid_statuses)) {
            $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $status, $order_id);
            
            if($stmt->execute()) {
                $message = "تم تحديث حالة الطلب بنجاح";
                $message_type = "success";
                echo "<script>
                setTimeout(function(){
                    window.location.href = 'list-withdraw_requests.php';
                }, 1500); // 1.5 ثانية قبل التحويل
              </script>";
            } else {
                $message = "خطأ في تحديث قاعدة البيانات";
                $message_type = "error";
            }
            $stmt->close();
        } else {
            $message = "حالة غير صالحة";
            $message_type = "error";
        }
    } else {
        $message = "بيانات غير مكتملة";
        $message_type = "error";
    }
}
 
?>
<?php
include("./include/conaction.php");

if(isset($_POST['update_withdraw_status']) && isset($_POST['withdraw_id']) && isset($_POST['status'])) {

    $withdraw_id = intval($_POST['withdraw_id']);
    $new_status = $_POST['status'];

    // جلب بيانات الطلب الحالي (خصوصا رقم الحساب)
    $stmt = $conn->prepare("SELECT wallet_number FROM withdraw_requests WHERE id = ?");
    $stmt->bind_param("i", $withdraw_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result && $row = $result->fetch_assoc()) {
        $wallet_number = $row['wallet_number'];

        // تحديد الملاحظة بناءً على الحالة الجديدة
        switch($new_status) {
            case "مقبول":
                $admin_note = "عزيزي العميل تم قبول طلبك وسوف نقوم بإيداع المبلغ على هذا الرقم: $wallet_number";
                break;
            case "مرفوض":
                $admin_note = "عزيزي العميل تم رفض طلب السحب الخاص بك. قد يكون السبب رقم الحساب خاطئ أو لم تكتمل عملية السحب.";
                break;
            case "مكتمل":
                $admin_note = "عزيزي العميل تم إرسال المبلغ إلى هذا الحساب: $wallet_number";
                break;
            default:
                $admin_note = "تم تحديث حالة الطلب.";
        }

        // تحديث الحالة والملاحظة
        $update_stmt = $conn->prepare("UPDATE withdraw_requests SET status = ?, admin_note = ? WHERE id = ?");
        $update_stmt->bind_param("ssi", $new_status, $admin_note, $withdraw_id);

        if($update_stmt->execute()) {
            echo "تم تحديث حالة الطلب بنجاح!";
        } else {
            echo "حدث خطأ أثناء تحديث الطلب: " . $update_stmt->error;
        }
        $update_stmt->close();
    } else {
        echo "لم يتم العثور على الطلب.";
    }
    $stmt->close();
}
?>

 <!DOCTYPE html>
<html lang="IR-fa" dir="rtl">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="description" content="CoreUI Bootstrap 4 Admin Template" />
    <meta name="author" content="Lukasz Holeczek" />
    <meta name="keyword" content="CoreUI Bootstrap 4 Admin Template" />
    <!-- <link rel="shortcut icon" href="assets/ico/favicon.png"> -->
    <title>CoreUI Bootstrap 4 Admin Template</title>
    <!-- Icons -->
    <link href="../css/font-awesome.min.css" rel="stylesheet" />
    <link href="../css/simple-line-icons.css" rel="stylesheet" />
    <!-- Main styles for this application -->
    <link href="../dest/style.css" rel="stylesheet" />
  </head>
  <!-- BODY options, add following classes to body to change options
          1. 'compact-nav'     	  - Switch sidebar to minified version (width 50px)
          2. 'sidebar-nav'		  - Navigation on the left
              2.1. 'sidebar-off-canvas'	- Off-Canvas
                  2.1.1 'sidebar-off-canvas-push'	- Off-Canvas which move content
                  2.1.2 'sidebar-off-canvas-with-shadow'	- Add shadow to body elements
          3. 'fixed-nav'			  - Fixed navigation
          4. 'navbar-fixed'		  - Fixed navbar
      -->

  <body class="navbar-fixed sidebar-nav fixed-nav">
    <style>
      /* تصميم المودال */

      .modal {
        display: none;
        /* إخفاء المودال بشكل افتراضي */
        position: fixed;
        z-index: 2;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
        /* خلفية شفافة */
      }
      /* تصميم محتوى المودال */

      .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 85%;
        border-radius: 10px;
      }
      /* زر الإغلاق */

      .close {
        position: relative;
        left: 30px;
        top: 28px;
        color: black;
        float: right;
        font-size: 28px;
        font-weight: bold;
        opacity: 1.2;
      }

      .close:hover,
      .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
      }

      .modals {
        display: none;
        /* إخفاء المودال بشكل افتراضي */
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
        /* خلفية شفافة */
      }
      /* تصميم محتوى المودال */

      .modal-contents {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 30%;
        border-radius: 10px;
        text-align: center;
      }
      /* زر الإغلاق */

      .closes {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
      }

      .closes:hover,
      .closes:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
      }
      /* تصميم الأزرار داخل المودال */

      .modal-actions {
        margin-top: 20px;
      }

      .modal-actions .btn {
        padding: 10px 20px;
        margin: 5px;
        border-radius: 5px;
        cursor: pointer;
      }

      .btn-danger {
        background-color: #dc3545;
        color: white;
        border: none;
      }

      .btn-secondary {
        background-color: #6c757d;
        color: white;
        border: none;
      }
      .status-select {
            padding: 5px 10px;
            border-radius: 15px;
            border: none;
            color: white;
            text-align: center;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
        }
        .status-select:focus {
            outline: none;
        }
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: center;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .update-form {
            margin: 0;
            padding: 0;
            display: inline;
        }
    </style>
    <header class="navbar">
 <!-- عرض الرسائل -->
<?php if(isset($message)): ?>
    <div class="message <?php echo $message_type; ?>" id="message">
        <?php echo $message; ?>
        <script>
        // إخفاء الرسالة بعد 3 ثواني
        setTimeout(() => {
            document.getElementById('message').style.display = 'none';
        }, 3000);
    </script>
<?php endif; ?>
    </div>
      <div class="container-fluid">
        <button
          class="navbar-toggler mobile-toggler hidden-lg-up"
          type="button"
        >
          &#9776;
        </button>
        <a class="navbar-brand" href="#"></a>
        <ul class="nav navbar-nav hidden-md-down">
          <li class="nav-item">
            <a class="nav-link navbar-toggler layout-toggler" href="#"
              >&#9776;</a
            >
          </li>

          <!--<li class="nav-item p-x-1">
                      <a class="nav-link" href="#">داشبورد</a>
                </li>
                <li class="nav-item p-x-1">
                    <a class="nav-link" href="#">Users</a>
                </li>
                <li class="nav-item p-x-1">
                    <a class="nav-link" href="#">Settings</a>
                </li>-->
        </ul>
        <ul class="nav navbar-nav pull-left hidden-md-down">
          <li class="nav-item">
            <a class="nav-link aside-toggle" href="#"
              ><i class="icon-bell"></i
              ><span class="tag tag-pill tag-danger">5</span></a
            >
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="icon-list"></i></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="icon-location-pin"></i></a>
          </li>
          <li class="nav-item dropdown">
            <a
              class="nav-link dropdown-toggle nav-link"
              data-toggle="dropdown"
              href="#"
              role="button"
              aria-haspopup="true"
              aria-expanded="false"
            >
              <img
                src="img/avatars/6.jpg"
                class="img-avatar"
                alt="admin@bootstrapmaster.com"
              />
              <span class="hidden-md-down">مدیر</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <div class="dropdown-header text-xs-center">
                <strong>تنظیمات</strong>
              </div>
              <a class="dropdown-item" href="#"
                ><i class="fa fa-user"></i> پروفایل</a
              >
              <a class="dropdown-item" href="#"
                ><i class="fa fa-wrench"></i> تنظیمات</a
              >
              <!--<a class="dropdown-item" href="#"><i class="fa fa-usd"></i> Payments<span class="tag tag-default">42</span></a>-->
              <div class="divider"></div>
              <a class="dropdown-item" href="#"
                ><i class="fa fa-lock"></i> خروج</a
              >
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link navbar-toggler aside-toggle" href="#">&#9776;</a>
          </li>
        </ul>
      </div>
    </header>
    <div class="sidebar">
      <nav class="sidebar-nav open">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="index.html"><i class="icon-speedometer"></i> Dashboard
              <span class="tag tag-info">NEW</span></a>
          </li>
          <li class="nav-title">UI Elements</li>
          <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-puzzle"></i> المستحدم</a>
            <ul class="nav-dropdown-items">
              <li class="nav-item">
                <a class="nav-link" href="cratadmin.php"><i class="icon-puzzle"></i> اضافه</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="list-admin.php"><i class="icon-puzzle"></i> عرض</a>
              </li>
            </ul>
          </li>
          <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-puzzle"></i> الجروب</a>
            <ul class="nav-dropdown-items">
              <li class="nav-item">
                <a class="nav-link" href="catgrio.php"><i class="icon-puzzle"></i> اضافه</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="list-catgro.php"><i class="icon-puzzle"></i> عرض</a>
              </li>
            </ul>
          </li>
          <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-puzzle"></i> المحفظة</a>
            <ul class="nav-dropdown-items">
              <li class="nav-item">
                <a class="nav-link" href="sup-catgro.php"><i class="icon-puzzle"></i> عرض</a>
              </li>
              <!-- <li class="nav-item">
                <a class="nav-link" href="list-sup-catgro.php"><i class="icon-puzzle"></i> عرض</a>
              </li> -->
            </ul>
          </li> 
          <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-star"></i> الطلبات</a>
            <ul class="nav-dropdown-items">
              <li class="nav-item">
                <a class="nav-link" href="list-ordar.php"><i class="icon-star"></i>عرض</a>
              </li>
            </ul>
          </li>
          <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-star"></i>طلب سحب</a>
            <ul class="nav-dropdown-items">
              <li class="nav-item">
                <a class="nav-link" href="list-withdraw_requests.php"><i class="icon-star"></i>عرض</a>
              </li>
            </ul>
          </li>
          <li class="divider"></li>
          <li class="nav-title">Extras</li>
          <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-star"></i> Pages</a>
            <ul class="nav-dropdown-items">
              <li class="nav-item">
                <a class="nav-link" href="pages-login.html" target="_top"><i class="icon-star"></i> Login</a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
    </div>
    <!-- Main content -->
    <main class="main">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item"><a href="#">Admin</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
        <!-- Breadcrumb Menu-->
        <li class="breadcrumb-menu">
          <div
            class="btn-group"
            role="group"
            aria-label="Button group with nested dropdown"
          >
            <a class="btn btn-secondary" href="#"
              ><i class="icon-speech"></i
            ></a>
            <a class="btn btn-secondary" href="./"
              ><i class="icon-graph"></i> &nbsp;Dashboard</a
            >
            <a class="btn btn-secondary" href="#"
              ><i class="icon-settings"></i> &nbsp;Settings</a
            >
          </div>
        </li>
      </ol>
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <i class="fa fa-align-justify"></i> عرض الفئة الفرعيه
            </div>
            <div class="card-block">
            <table border="1" style="width: 100%; border-collapse: collapse; text-align: center;">
    <thead style="background-color: #f8f9fa;">
        <tr>
            <th>#</th>
            <th>اسم المستخدم</th>
            <th>رقم الحساب</th>
            <th>المبلغ كامل</th>
            <th>المبلغ المسحوب</th>
            <th>ناريخ الطلب</th>
            <th>حالة الطلب</th>
          
        </tr>
    </thead>
    <tbody>
                    <?php 
                if($result && $result->num_rows > 0) {
                    $n = 1;
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $n . "</td>";
                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['wallet_number']) . "</td>";
                        echo "<td>" . htmlspecialchars(number_format($row['balance'], 2)) . " ر.س</td>";
                        echo "<td>" . htmlspecialchars(number_format($row['amount'], 2)) . " ر.س</td>"; 
                        echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                        
                        // نموذج لتحديث حالة الطلب
                        echo "<td>
                                <form method='post' class='update-form' onsubmit='return updateOrderStatus(this)'>
                                    <input type='hidden' name='update_withdraw_status' value='1'>
                                    <input type='hidden' name='withdraw_id' value='" . $row['withdraw_id'] . "'>
                                    <select name='status' onchange='this.form.submit()' 
                                        style='background-color: " . getStatusColor($row['status']) . ";'>
                                        <option value='قيد المراجعة' " . ($row['status'] == 'قيد المراجعة' ? 'selected' : '') . ">قيد المراجعة</option>
                                        <option value='مقبول' " . ($row['status'] == 'مقبول' ? 'selected' : '') . ">مقبول</option>
                                        <option value='مرفوض' " . ($row['status'] == 'مرفوض' ? 'selected' : '') . ">مرفوض</option>
                                        <option value='مكتمل' " . ($row['status'] == 'مكتمل' ? 'selected' : '') . ">مكتمل</option>
                                    </select>
                                </form>
                            </td>"; 
                        echo "</tr>";
                        $n++;
                    }
                } else {
                    echo "<tr><td colspan='8' style='text-align: center;'>لا توجد طلبات سحب</td></tr>";
                }
                ?>

    </tbody>
</table>
<?php
// دالة لتحديد لون الحالة
function getStatusColor($status) {
    switch($status) {
        case 'قيد المراجعة':
            return '#ffc107'; // أصفر
        case 'مقبول':
        case 'مكتمل':
            return '#28a745'; // أخضر
        case 'نقل الملكية':
            return '#28a745'; // أخضر
        case 'مرفوض':
            return '#dc3545'; // أحمر
        case 'ملغي':
            return '#6c757d'; // رمادي
        default:
            return '#17a2b8'; // أزرق فاتح
    }
}
?>
              <nav>
                <ul class="pagination">
                  <li class="page-item">
                    <a class="page-link" href="#">Prev</a>
                  </li>
                  <li class="page-item active">
                    <a class="page-link" href="#">1</a>
                  </li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item"><a class="page-link" href="#">4</a></li>
                  <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </div>
        <!--/col-->
      </div>

      <!--/.container-fluid-->
    </main>

    <aside class="aside-menu">
      <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
          <a
            class="nav-link active"
            data-toggle="tab"
            href="#timeline"
            role="tab"
            ><i class="icon-list"></i
          ></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#messages" role="tab"
            ><i class="icon-speech"></i
          ></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#settings" role="tab"
            ><i class="icon-settings"></i
          ></a>
        </li>
      </ul>
      <!-- Tab panes -->
      <div class="tab-content">
        <div class="tab-pane active" id="timeline" role="tabpanel">
          <div
            class="callout m-a-0 p-y-h text-muted text-xs-center bg-faded text-uppercase"
          >
            <small><b>Today</b> </small>
          </div>
          <hr class="transparent m-x-1 m-y-0" />
          <div class="callout callout-warning m-a-0 p-y-1">
            <div class="avatar pull-xs-right">
              <img
                src="img/avatars/7.jpg"
                class="img-avatar"
                alt="admin@bootstrapmaster.com"
              />
            </div>
            <div>
              Meeting with
              <strong>Lucas</strong>
            </div>
            <small class="text-muted m-r-1"
              ><i class="icon-calendar"></i>&nbsp; 1 - 3pm</small
            >
            <small class="text-muted"
              ><i class="icon-location-pin"></i>&nbsp; Palo Alto, CA</small
            >
          </div>
          <hr class="m-x-1 m-y-0" />
          <div class="callout callout-info m-a-0 p-y-1">
            <div class="avatar pull-xs-right">
              <img
                src="img/avatars/4.jpg"
                class="img-avatar"
                alt="admin@bootstrapmaster.com"
              />
            </div>
            <div>
              Skype with
              <strong>Megan</strong>
            </div>
            <small class="text-muted m-r-1"
              ><i class="icon-calendar"></i>&nbsp; 4 - 5pm</small
            >
            <small class="text-muted"
              ><i class="icon-social-skype"></i>&nbsp; On-line</small
            >
          </div>
          <hr class="transparent m-x-1 m-y-0" />
          <div
            class="callout m-a-0 p-y-h text-muted text-xs-center bg-faded text-uppercase"
          >
            <small><b>Tomorrow</b> </small>
          </div>
          <hr class="transparent m-x-1 m-y-0" />
          <div class="callout callout-danger m-a-0 p-y-1">
            <div>
              New UI Project -
              <strong>deadline</strong>
            </div>
            <small class="text-muted m-r-1"
              ><i class="icon-calendar"></i>&nbsp; 10 - 11pm</small
            >
            <small class="text-muted"
              ><i class="icon-home"></i>&nbsp; creativeLabs HQ</small
            >
            <div class="avatars-stack m-t-h">
              <div class="avatar avatar-xs">
                <img
                  src="img/avatars/2.jpg"
                  class="img-avatar"
                  alt="admin@bootstrapmaster.com"
                />
              </div>
              <div class="avatar avatar-xs">
                <img
                  src="img/avatars/3.jpg"
                  class="img-avatar"
                  alt="admin@bootstrapmaster.com"
                />
              </div>
              <div class="avatar avatar-xs">
                <img
                  src="img/avatars/4.jpg"
                  class="img-avatar"
                  alt="admin@bootstrapmaster.com"
                />
              </div>
              <div class="avatar avatar-xs">
                <img
                  src="img/avatars/5.jpg"
                  class="img-avatar"
                  alt="admin@bootstrapmaster.com"
                />
              </div>
              <div class="avatar avatar-xs">
                <img
                  src="img/avatars/6.jpg"
                  class="img-avatar"
                  alt="admin@bootstrapmaster.com"
                />
              </div>
            </div>
          </div>
          <hr class="m-x-1 m-y-0" />
          <div class="callout callout-success m-a-0 p-y-1">
            <div><strong>#10 Startups.Garden</strong>Meetup</div>
            <small class="text-muted m-r-1"
              ><i class="icon-calendar"></i>&nbsp; 1 - 3pm</small
            >
            <small class="text-muted"
              ><i class="icon-location-pin"></i>&nbsp; Palo Alto, CA</small
            >
          </div>
          <hr class="m-x-1 m-y-0" />
          <div class="callout callout-primary m-a-0 p-y-1">
            <div>
              <strong>Team meeting</strong>
            </div>
            <small class="text-muted m-r-1"
              ><i class="icon-calendar"></i>&nbsp; 4 - 6pm</small
            >
            <small class="text-muted"
              ><i class="icon-home"></i>&nbsp; creativeLabs HQ</small
            >
            <div class="avatars-stack m-t-h">
              <div class="avatar avatar-xs">
                <img
                  src="img/avatars/2.jpg"
                  class="img-avatar"
                  alt="admin@bootstrapmaster.com"
                />
              </div>
              <div class="avatar avatar-xs">
                <img
                  src="img/avatars/3.jpg"
                  class="img-avatar"
                  alt="admin@bootstrapmaster.com"
                />
              </div>
              <div class="avatar avatar-xs">
                <img
                  src="img/avatars/4.jpg"
                  class="img-avatar"
                  alt="admin@bootstrapmaster.com"
                />
              </div>
              <div class="avatar avatar-xs">
                <img
                  src="img/avatars/5.jpg"
                  class="img-avatar"
                  alt="admin@bootstrapmaster.com"
                />
              </div>
              <div class="avatar avatar-xs">
                <img
                  src="img/avatars/6.jpg"
                  class="img-avatar"
                  alt="admin@bootstrapmaster.com"
                />
              </div>
              <div class="avatar avatar-xs">
                <img
                  src="img/avatars/7.jpg"
                  class="img-avatar"
                  alt="admin@bootstrapmaster.com"
                />
              </div>
              <div class="avatar avatar-xs">
                <img
                  src="img/avatars/8.jpg"
                  class="img-avatar"
                  alt="admin@bootstrapmaster.com"
                />
              </div>
            </div>
          </div>
          <hr class="m-x-1 m-y-0" />
        </div>
        <div class="tab-pane p-a-1" id="messages" role="tabpanel">
          <div class="message">
            <div class="p-y-1 p-b-3 m-r-1 pull-left">
              <div class="avatar">
                <img
                  src="img/avatars/7.jpg"
                  class="img-avatar"
                  alt="admin@bootstrapmaster.com"
                />
                <span class="avatar-status tag-success"></span>
              </div>
            </div>
            <div>
              <small class="text-muted">Lukasz Holeczek</small>
              <small class="text-muted pull-left m-t-q">1:52 PM</small>
            </div>
            <div class="text-truncate font-weight-bold">
              Lorem ipsum dolor sit amet
            </div>
            <small class="text-muted"
              >Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
              eiusmod tempor incididunt...</small
            >
          </div>
          <hr />
          <div class="message">
            <div class="p-y-1 p-b-3 m-r-1 pull-left">
              <div class="avatar">
                <img
                  src="img/avatars/7.jpg"
                  class="img-avatar"
                  alt="admin@bootstrapmaster.com"
                />
                <span class="avatar-status tag-success"></span>
              </div>
            </div>
            <div>
              <small class="text-muted">Lukasz Holeczek</small>
              <small class="text-muted pull-left m-t-q">1:52 PM</small>
            </div>
            <div class="text-truncate font-weight-bold">
              Lorem ipsum dolor sit amet
            </div>
            <small class="text-muted"
              >Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
              eiusmod tempor incididunt...</small
            >
          </div>
          <hr />
          <div class="message">
            <div class="p-y-1 p-b-3 m-r-1 pull-left">
              <div class="avatar">
                <img
                  src="img/avatars/7.jpg"
                  class="img-avatar"
                  alt="admin@bootstrapmaster.com"
                />
                <span class="avatar-status tag-success"></span>
              </div>
            </div>
            <div>
              <small class="text-muted">Lukasz Holeczek</small>
              <small class="text-muted pull-right m-t-q">1:52 PM</small>
            </div>
            <div class="text-truncate font-weight-bold">
              Lorem ipsum dolor sit amet
            </div>
            <small class="text-muted"
              >Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
              eiusmod tempor incididunt...</small
            >
          </div>
          <hr />
          <div class="message">
            <div class="p-y-1 p-b-3 m-r-1 pull-left">
              <div class="avatar">
                <img
                  src="img/avatars/7.jpg"
                  class="img-avatar"
                  alt="admin@bootstrapmaster.com"
                />
                <span class="avatar-status tag-success"></span>
              </div>
            </div>
            <div>
              <small class="text-muted">Lukasz Holeczek</small>
              <small class="text-muted pull-right m-t-q">1:52 PM</small>
            </div>
            <div class="text-truncate font-weight-bold">
              Lorem ipsum dolor sit amet
            </div>
            <small class="text-muted"
              >Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
              eiusmod tempor incididunt...</small
            >
          </div>
          <hr />
          <div class="message">
            <div class="p-y-1 p-b-3 m-r-1 pull-left">
              <div class="avatar">
                <img
                  src="img/avatars/7.jpg"
                  class="img-avatar"
                  alt="admin@bootstrapmaster.com"
                />
                <span class="avatar-status tag-success"></span>
              </div>
            </div>
            <div>
              <small class="text-muted">Lukasz Holeczek</small>
              <small class="text-muted pull-right m-t-q">1:52 PM</small>
            </div>
            <div class="text-truncate font-weight-bold">
              Lorem ipsum dolor sit amet
            </div>
            <small class="text-muted"
              >Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
              eiusmod tempor incididunt...</small
            >
          </div>
        </div>
        <div class="tab-pane p-a-1" id="settings" role="tabpanel">
          <h6>Settings</h6>
          <div class="aside-options">
            <div class="clearfix m-t-2">
              <small><b>Option 1</b> </small>
              <label
                class="switch switch-text switch-pill switch-success switch-sm pull-right"
              >
                <input type="checkbox" class="switch-input" checked />
                <span class="switch-label" data-on="On" data-off="Off"></span>
                <span class="switch-handle"></span>
              </label>
            </div>
            <div>
              <small class="text-muted"
                >Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                do eiusmod tempor incididunt ut labore et dolore magna
                aliqua.</small
              >
            </div>
          </div>
          <div class="aside-options">
            <div class="clearfix m-t-1">
              <small><b>Option 2</b> </small>
              <label
                class="switch switch-text switch-pill switch-success switch-sm pull-right"
              >
                <input type="checkbox" class="switch-input" />
                <span class="switch-label" data-on="On" data-off="Off"></span>
                <span class="switch-handle"></span>
              </label>
            </div>
            <div>
              <small class="text-muted"
                >Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                do eiusmod tempor incididunt ut labore et dolore magna
                aliqua.</small
              >
            </div>
          </div>
          <div class="aside-options">
            <div class="clearfix m-t-1">
              <small><b>Option 3</b> </small>
              <label
                class="switch switch-text switch-pill switch-success switch-sm pull-right"
              >
                <input type="checkbox" class="switch-input" />
                <span class="switch-label" data-on="On" data-off="Off"></span>
                <span class="switch-handle"></span>
              </label>
            </div>
          </div>
          <div class="aside-options">
            <div class="clearfix m-t-1">
              <small><b>Option 4</b> </small>
              <label
                class="switch switch-text switch-pill switch-success switch-sm pull-right"
              >
                <input type="checkbox" class="switch-input" checked />
                <span class="switch-label" data-on="On" data-off="Off"></span>
                <span class="switch-handle"></span>
              </label>
            </div>
          </div>
          <hr />
          <h6>System Utilization</h6>
          <div class="text-uppercase m-b-q m-t-2">
            <small><b>CPU Usage</b> </small>
          </div>
          <progress
            class="progress progress-xs progress-info m-a-0"
            value="25"
            max="100"
          >
            25%
          </progress>
          <small class="text-muted">348 Processes. 1/4 Cores.</small>
          <div class="text-uppercase m-b-q m-t-h">
            <small><b>Memory Usage</b> </small>
          </div>
          <progress
            class="progress progress-xs progress-warning m-a-0"
            value="70"
            max="100"
          >
            70%
          </progress>
          <small class="text-muted">11444GB/16384MB</small>
          <div class="text-uppercase m-b-q m-t-h">
            <small><b>SSD 1 Usage</b> </small>
          </div>
          <progress
            class="progress progress-xs progress-danger m-a-0"
            value="95"
            max="100"
          >
            95%
          </progress>
          <small class="text-muted">243GB/256GB</small>
          <div class="text-uppercase m-b-q m-t-h">
            <small><b>SSD 2 Usage</b> </small>
          </div>
          <progress
            class="progress progress-xs progress-success m-a-0"
            value="10"
            max="100"
          >
            10%
          </progress>
          <small class="text-muted">25GB/256GB</small>
        </div>
      </div>
    </aside>
    <!-- زر التعديل -->
    <div id="deletemodal" class="modals">
      <div class="modal-contents">
        <span class="closes">&times;</span>
        <h2>هل أنت متأكد من الحذف؟</h2>
        <p>لا يمكن التراجع عن هذه العملية.</p>
        <div class="modal-actions">
          <button id="confirmDelete" class="btn btn-danger">تأكيد</button>
          <button id="cancelDelete" class="btn btn-secondary">إلغاء</button>
        </div>
      </div>
    </div>
    <!-- المودال -->
    <div id="editModal" class="modal">
      <div class="modal-content">
        <div class="card-header">
          تفاصيل المنتج
          <div class="card-actions">
            <span class="close">&times;</span>
          </div>
        </div>
        <form class="form-horizontal" id="subCategory" method="post" action="" enctype="multipart/form-data">
              <div class="form-group">
                <label for="SubCategoryName" class="control-label col-lg-2">اسم الفئه الفرعيه</label>
                <div class="col-lg-7">
                  <input class="form-control" id="subcategory_name" name="subcategory_name" type="text">
                </div>
              </div>
              <div class="form-group">
                <label for="CategoryName" class="control-label col-lg-2">من الفئه الرئسيه</label>
                <div class="col-lg-7">
                  <select name="category_id" id="category_id" class="form-control">
                    <option>Select a Category</option>

                    <option value="">SDFDFFD</option>
                    
                  </select>
                </div>
              </div>
  
  
    <div class="form-group">
        <label for="SubCategoryStatus" class="control-label col-lg-2">
               حاله الفئه الفرعيه
                </label>
        <div class="col-lg-7">
            <select name="subcategory_status" class="form-control m-bot15">
                    <option>نشط</option>
                    <option>غير نشط</option>
                  </select>
        </div>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary">حفظ</button>
      <button type="button" class="btn btn-danger">الغاء</button>
    </div>
    </form>
      </div>
    </div>

    <footer class="footer">
      <span class="text-left">
        <a href="http://coreui.io">CoreUI</a> &copy; 2016 creativeLabs.
      </span>
      <span class="pull-right">
        Powered by <a href="http://coreui.io">CoreUI</a>
      </span>
    </footer>
    <script>
function updateOrderStatus(form) {
    // تغيير لون الخلفية حسب الحالة المختارة
    const selectElement = form.querySelector('select[name=\"status\"]');
    const newStatus = selectElement.value;
    selectElement.style.backgroundColor = getStatusColor(newStatus);
    
    return true; // المتابعة مع إرسال النموذج
}

function getStatusColor(status) {
    switch(status) {
        case 'قيد المراجعة': return '#ffc107';
        case 'مقبول': return '#28a745';
        case 'مرفوض': return '#dc3545';
        case 'مكتمل': return '#007bff';
        default: return '#6c757d';
    }
}

// إخفاء الرسالة بعد 3 ثواني
setTimeout(() => {
    const message = document.getElementById('message');
    if (message) {
        message.style.display = 'none';
    }
}, 3000);
</script>
    <script>
function updateOrderStatus(selectElement) {
    const orderId = selectElement.getAttribute('data-order-id');
    const newStatus = selectElement.value;
    
    // تغيير لون الخلفية حسب الحالة
    selectElement.style.backgroundColor = getStatusColor(newStatus);
    
    // إرسال طلب AJAX لتحديث الحالة
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_order_status.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            const messageDiv = document.getElementById('updateMessage');
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    messageDiv.textContent = 'تم تحديث حالة الطلب بنجاح';
                    messageDiv.className = 'message success';
                } else {
                    messageDiv.textContent = 'حدث خطأ: ' + response.message;
                    messageDiv.className = 'message error';
                }
            } else {
                messageDiv.textContent = 'حدث خطأ في الاتصال';
                messageDiv.className = 'message error';
            }
            messageDiv.style.display = 'block';
            
            // إخفاء الرسالة بعد 3 ثواني
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 3000);
        }
    };
    
    xhr.send('order_id=' + encodeURIComponent(orderId) + '&status=' + encodeURIComponent(newStatus));
}

function getStatusColor(status) {
    switch(status) {
        case 'قيد المراجعة': return '#ffc107';
        case 'مقبول': return '#28a745';
        case 'مرفوض': return '#dc3545';
        case 'مكتمل': return '#007bff';
        default: return '#6c757d';
    }
}
</script>
    <script>
      // جافا سكريبت لفتح وغلق المودال
      var modal = document.getElementById("editModal");
      var btn = document.getElementById("editButton");
      var span = document.getElementsByClassName("close")[0];

      btn.onclick = function () {
        modal.style.display = "block";
      };

      span.onclick = function () {
        modal.style.display = "none";
      };

      window.onclick = function (event) {
        if (event.target == modal) {
          modal.style.display = "none";
        }
      };
    </script>

    <script>
      // جافا سكريبت لفتح وغلق المودال
      var deleteModal = document.getElementById("deletemodal");
      var deleteBtn = document.getElementById("deleteButton");
      var closeModal = document.getElementsByClassName("closes")[0];
      var cancelDelete = document.getElementById("cancelDelete");
      var confirmDelete = document.getElementById("confirmDelete");

      deleteBtn.onclick = function () {
        deleteModal.style.display = "block";
      };

      cancelDelete.onclick = function () {
        deleteModal.style.display = "none";
      };

      closeModal.onclick = function () {
        deleteModal.style.display = "none";
      };

      window.onclick = function (event) {
        if (event.target == deleteModal) {
          deleteModal.style.display = "none";
        }
      };

      // يمكنك هنا إضافة أكشن للحذف عند الضغط على زر تأكيد الحذف
      confirmDelete.onclick = function () {
        // ضع هنا الكود الذي تود تنفيذه عند تأكيد الحذف
        alert("تم الحذف بنجاح!");
        deleteModal.style.display = "none";
      };
    </script>
    <!-- Bootstrap and necessary plugins -->
    <script src="../js/libs/jquery.min.js"></script>
    <script src="../js/libs/tether.min.js"></script>
    <script src="../js/libs/bootstrap.min.js"></script>
    <script src="../js/libs/pace.min.js"></script>

    <!-- Plugins and scripts required by all views -->
    <script src="../js/libs/Chart.min.js"></script>

    <!-- CoreUI main scripts -->

    <script src="../js/app.js"></script>

    <!-- Plugins and scripts required by this views -->
    <!-- Custom scripts required by this view -->
    <script src="../js/views/main.js"></script>
    <!-- Grunt watch plugin -->
    <script src="..///localhost:35729/livereload.js"></script>
  </body>
</html>
