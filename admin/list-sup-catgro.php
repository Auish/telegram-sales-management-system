
<!--
 * CoreUI - Open Source Bootstrap Admin Template
 * @version v1.0.0-alpha.2
 * @link http://coreui.io
 * Copyright (c) 2016 creativeLabs Łukasz Holeczek
 * @license MIT
 -->
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
      @import url("https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap");
body{
  
  font-family: "Cairo", sans-serif;
}
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
    </style>
    <header class="navbar">
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
              <i class="fa fa-align-justify"></i> عرض الادمن
            </div>
            <div class="card-block">
              <table class="table table-bordered table-striped table-condensed">
                <thead>
                  <tr>
                    <th>الرقم</th>
                    <th>اسم المستخدم</th>
                    <th>البريد الاكتروني</th>
                    <th>العنوان</th>
                    <th>تاريخ الميلاد</th>
                    <th>الصلاحيه</th>
                    <th>الصوره</th>
                    <th>الجنس</th>
                    <th>اوقات العمل</th>
                    <th>الحاله</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td>عياش صادق</td>
                    <td>ausihsatk@gmail.com</td>
                    <td>اليمن-اب-المشنه</td>
                    <td>1/2/2001</td>
                    <td>ادمن</td>
                    <td>
                      <img
                        src="img/avatars/6.jpg"
                        class="img-avatar"
                        alt="admin@bootstrapmaster.com"
                        width="35px"
                        height="35px"
                      />
                    </td>
                    <td>انثئ</td>
                    <td>
                      من 8:00ص<br />
                      الئ 12:00ص
                    </td>
                    <td>
                      <button
                        type="button"
                        class="tag tag-warning"
                        style="margin-bottom: 4px"
                      >
                        <span>نشط</span>
                      </button>
                    </td>
                    <td>
                      <a href="#">
                        <button
                          type="button"
                          class="tag tag-success icon-note"
                          style="margin-bottom: 4px"
                          id="editButton"
                        >
                          <span>تعديل</span>
                        </button>
                      </a>
                      <button
                        id="deleteButton"
                        type="button"
                        class="tag tag-danger icon-trash"
                        style="margin-bottom: 4px"
                      >
                        <span>حذف</span>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
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
        <form action="" method="post" enctype="multipart/form-data">
          <div class="card-block">
            <!-- الفئة والفئة الفرعية -->

            <!-- اسم المنتج ووصف المنتج -->
            <div class="form-group row">
              <label for="product_name" class="col-lg-2 col-sm-2 control-label"
                >اسم المنتج</label
              >
              <div class="col-lg-4">
                <input
                  type="text"
                  name="product_name"
                  class="form-control"
                  id="product_name"
                  required
                />
              </div>
              <label for="product_status" class="control-label col-lg-2"
                >حالة المنتج</label
              >
              <div class="col-lg-4">
                <select
                  name="product_status"
                  id="product_status"
                  class="form-control"
                  required
                >
                  <option>Out of Stock</option>
                  <option>In Stock</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="product_status" class="control-label col-lg-2">
                الفئه</label
              >
              <div class="col-lg-4">
                <select
                  name="product_status"
                  id="product_status"
                  class="form-control"
                  required=""
                >
                  <option>بالساعه</option>
                  <option>باليوم</option>
                </select>
              </div>
              <label for="rent_price" class="col-lg-2 col-sm-2 control-label">
                الفئه الفرعيه
              </label>
              <div class="col-lg-4">
                <select
                  name="product_status"
                  id="product_status"
                  class="form-control"
                  required=""
                >
                  <option>بالساعه</option>
                  <option>باليوم</option>
                </select>
              </div>
            </div>
            <!-- الكمية وسعر المنتج -->

            <!-- حاله المنتج -->

            <!-- Rent Price و Purchase Price -->
            <div class="form-group row">
              <label for="product_status" class="control-label col-lg-2">
                العدد</label
              >
              <div class="col-lg-4">
                <input
                  type="number"
                  step="any"
                  name="rent_price"
                  class="form-control"
                  id="rent_price"
                />
              </div>
              <label for="rent_price" class="col-lg-2 col-sm-2 control-label">
                السعر
              </label>
              <div class="col-lg-4">
                <input
                  type="number"
                  step="any"
                  name="rent_price"
                  class="form-control"
                  id="rent_price"
                />
              </div>
            </div>

            <!-- Rent Duration و Additional Fees -->
            <div class="form-group row">
              <label
                for="return_policy"
                class="col-lg-2 col-sm-2 control-label"
              >
                وصف المنتج .
              </label>
              <div class="col-lg-10">
                <textarea
                  name="return_policy"
                  class="form-control"
                  id="return_policy"
                ></textarea>
              </div>
            </div>
            <!-- Return Policy -->
             
           

            <!-- صور المنتج -->
            <div class="form-group">
              <label class="control-label col-md-2"> اضافه صوره </label>
              <div class="controls col-md-9">
                <div
                  class="fileupload fileupload-new"
                  data-provides="fileupload"
                >
                  <span class="btn btn-default btn-file">
                    <input
                      type="file"
                      name="product_master_image"
                      class="default"
                      onchange="moveImageToDiv(this, 'div1');"
                      required
                    />
                  </span>
                  <span
                    class="fileupload-preview"
                    style="margin-left: 5px"
                  ></span>
                  <a
                    href="#"
                    class="close fileupload-exists"
                    data-dismiss="fileupload"
                    style="float: none; margin-left: 5px"
                  ></a>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">
                صوره الخدمه(الرئسية)
              </label>
              <div class="col-md-9">
                <div
                  class="fileupload fileupload-new"
                  data-provides="fileupload"
                >
                  <div
                    class="fileupload-new thumbnail"
                    style="width: 160px; height: 160px"
                  >
                    <img
                      style="height: 100%; width: 100%"
                      src="./istockphoto-1324356458-612x612.jpg"
                      alt=""
                      id="div1"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!--=*= PRODUCT ADDITIONAL IMAGE =*=-->
            <div class="d-flex d-inline">
              <div class="form-group">
                <label class="control-label col-md-2"> اضافه صور فرعيه </label>
                <div class="controls col-md-3">
                  <div
                    class="fileupload fileupload-new"
                    data-provides="fileupload"
                  >
                    <span class="btn btn-default btn-file">
                      <input
                        type="file"
                        name="products_image_one"
                        class="default"
                        onchange="moveImageToDiv(this, 'div2');"
                      />
                    </span>
                    <span
                      class="fileupload-preview"
                      style="margin-left: 5px"
                    ></span>
                    <a
                      href="#"
                      class="close fileupload-exists"
                      data-dismiss="fileupload"
                      style="float: none; margin-left: 5px"
                    ></a>
                  </div>
                </div>
                <div class="controls col-md-3">
                  <div
                    class="fileupload fileupload-new"
                    data-provides="fileupload"
                  >
                    <span class="btn btn-default btn-file">
                      <input
                        type="file"
                        name="products_image_two"
                        class="default"
                        onchange="moveImageToDiv(this, 'div3');"
                      />
                    </span>
                    <span
                      class="fileupload-preview"
                      style="margin-left: 5px"
                    ></span>
                    <a
                      href="#"
                      class="close fileupload-exists"
                      data-dismiss="fileupload"
                      style="float: none; margin-left: 5px"
                    ></a>
                  </div>
                </div>
                <div class="controls col-md-3">
                  <div
                    class="fileupload fileupload-new"
                    data-provides="fileupload"
                  >
                    <span class="btn btn-default btn-file">
                      <input
                        type="file"
                        name="products_image_three"
                        class="default"
                        onchange="moveImageToDiv(this, 'div4');"
                      />
                    </span>
                    <span
                      class="fileupload-preview"
                      style="margin-left: 5px"
                    ></span>
                    <a
                      href="#"
                      class="close fileupload-exists"
                      data-dismiss="fileupload"
                      style="float: none; margin-left: 5px"
                    ></a>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2">
                  صور الخدمه(الفرعيه)
                </label>
                <div class="col-md-3">
                  <div
                    class="fileupload fileupload-new"
                    data-provides="fileupload"
                  >
                    <div
                      class="fileupload-new thumbnail"
                      style="width: 160px; height: 160px"
                    >
                      <img
                        style="height: 100%; width: 100%"
                        src="./istockphoto-1324356458-612x612.jpg"
                        alt=""
                        id="div2"
                      />
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div
                    class="fileupload fileupload-new"
                    data-provides="fileupload"
                  >
                    <div
                      class="fileupload-new thumbnail"
                      style="width: 160px; height: 160px"
                    >
                      <img
                        style="height: 100%; width: 100%"
                        src="./istockphoto-1324356458-612x612.jpg"
                        alt=""
                        id="div3"
                      />
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div
                    class="fileupload fileupload-new"
                    data-provides="fileupload"
                  >
                    <div
                      class="fileupload-new thumbnail"
                      style="width: 160px; height: 160px"
                    >
                      <img
                        style="height: 100%; width: 100%"
                        src="./istockphoto-1324356458-612x612.jpg"
                        alt=""
                        id="div4"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex d-inline mast">
              <div class="form-group mast">
                <label class="control-label col-md-2"> اضافه صور فرعيه </label>
                <div class="controls col-md-3">
                  <div
                    class="fileupload fileupload-new"
                    data-provides="fileupload"
                  >
                    <span class="btn btn-default btn-file">
                      <input
                        type="file"
                        name="products_image_one"
                        class="default"
                        onchange="moveImageToDiv(this, 'div5');"
                      />
                    </span>
                    <span
                      class="fileupload-preview"
                      style="margin-left: 5px"
                    ></span>
                    <a
                      href="#"
                      class="close fileupload-exists"
                      data-dismiss="fileupload"
                      style="float: none; margin-left: 5px"
                    ></a>
                  </div>
                </div>
                <div class="controls col-md-3">
                  <div
                    class="fileupload fileupload-new"
                    data-provides="fileupload"
                  >
                    <span class="btn btn-default btn-file">
                      <input
                        type="file"
                        name="products_image_two"
                        class="default"
                        onchange="moveImageToDiv(this, 'div6');"
                      />
                    </span>
                    <span
                      class="fileupload-preview"
                      style="margin-left: 5px"
                    ></span>
                    <a
                      href="#"
                      class="close fileupload-exists"
                      data-dismiss="fileupload"
                      style="float: none; margin-left: 5px"
                    ></a>
                  </div>
                </div>
                <div class="controls col-md-3">
                  <div
                    class="fileupload fileupload-new"
                    data-provides="fileupload"
                  >
                    <span class="btn btn-default btn-file">
                      <input
                        type="file"
                        name="products_image_three"
                        class="default"
                        onchange="moveImageToDiv(this, 'div7');"
                      />
                    </span>
                    <span
                      class="fileupload-preview"
                      style="margin-left: 5px"
                    ></span>
                    <a
                      href="#"
                      class="close fileupload-exists"
                      data-dismiss="fileupload"
                      style="float: none; margin-left: 5px"
                    ></a>
                  </div>
                </div>
              </div>
              <div class="form-group mast">
                <label class="control-label col-md-2">
                  صور الخدمه(الفرعيه)
                </label>
                <div class="col-md-3">
                  <div
                    class="fileupload fileupload-new"
                    data-provides="fileupload"
                  >
                    <div
                      class="fileupload-new thumbnail"
                      style="width: 160px; height: 160px"
                    >
                      <img
                        style="height: 100%; width: 100%"
                        src="./istockphoto-1324356458-612x612.jpg"
                        alt=""
                        id="div5"
                      />
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div
                    class="fileupload fileupload-new"
                    data-provides="fileupload"
                  >
                    <div
                      class="fileupload-new thumbnail"
                      style="width: 160px; height: 160px"
                    >
                      <img
                        style="height: 100%; width: 100%"
                        src="./istockphoto-1324356458-612x612.jpg"
                        alt=""
                        id="div6"
                      />
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div
                    class="fileupload fileupload-new"
                    data-provides="fileupload"
                  >
                    <div
                      class="fileupload-new thumbnail"
                      style="width: 160px; height: 160px"
                    >
                      <img
                        style="height: 100%; width: 100%"
                        src="./istockphoto-1324356458-612x612.jpg"
                        alt=""
                        id="div7"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <button
              type="button"
              class="btn btn-outline-success"
              onclick="addClassOpen()"
            >
              <i class="fa fa-magic"></i>&nbsp; اضافه المزيد
            </button>
            <br />

            <div class="d-flex d-inline">
              <div class="form-group">
                <label class="control-label col-md-2"> اضافه فيديو </label>
                <div class="controls col-md-3">
                  <div
                    class="fileupload fileupload-new"
                    data-provides="fileupload"
                  >
                    <span class="btn btn-default btn-file">
                      <input
                        type="file"
                        name="products_image_one"
                        class="default"
                        onchange="moveImageToDiv(this, 'div8');"
                      />
                    </span>
                    <span
                      class="fileupload-preview"
                      style="margin-left: 5px"
                    ></span>
                    <a
                      href="#"
                      class="close fileupload-exists"
                      data-dismiss="fileupload"
                      style="float: none; margin-left: 5px"
                    ></a>
                  </div>
                </div>
                <label class="control-label col-md-2"> اضافه صوره مصغره </label>
                <div class="controls col-md-3">
                  <div
                    class="fileupload fileupload-new"
                    data-provides="fileupload"
                  >
                    <span class="btn btn-default btn-file">
                      <input
                        type="file"
                        name="products_image_one"
                        class="default"
                        onchange="moveImageToDiv(this, 'div9');"
                      />
                    </span>
                    <span
                      class="fileupload-preview"
                      style="margin-left: 5px"
                    ></span>
                    <a
                      href="#"
                      class="close fileupload-exists"
                      data-dismiss="fileupload"
                      style="float: none; margin-left: 5px"
                    ></a>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2">
                  الفيديو (الرئيسي)
                </label>
                <div class="col-md-3">
                  <div
                    class="fileupload fileupload-new"
                    data-provides="fileupload"
                  >
                    <div
                      class="fileupload-new thumbnail"
                      style="width: 160px; height: 160px"
                    >
                      <video
                        style="height: 100%; width: 100%"
                        controls
                        id="div8"
                        poster=""
                      >
                        <picture src="" type="video/mp4" id=" " />
                        المتصفح الخاص بك لا يدعم عرض الفيديو.
                      </video>
                    </div>
                  </div>
                </div>

                <label class="control-label col-md-2">
                  الفيديو (الرئيسي)
                </label>
                <div class="col-md-3">
                  <div
                    class="fileupload fileupload-new"
                    data-provides="fileupload"
                  >
                    <div
                      class="fileupload-new thumbnail"
                      style="width: 160px; height: 160px"
                    >
                      <img
                        style="height: 100%; width: 100%"
                        src="./istockphoto-1324356458-612x612.jpg"
                        alt=""
                        id="div9"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- أزرار الحفظ والإلغاء -->
            <div class="form-actions">
              <button type="button" class="btn btn-outline-success">
                <i class="icon-doc"></i>&nbsp; حفظ
              </button>
              <button type="button" class="btn btn-outline-danger">
                <i class="icon-close"></i>&nbsp; الغاء
              </button>
              <button
                type="button"
                class="btn btn-outline-primary"
                style="display: none;"
                id="AddButoon"
              >
                <i class="icon-plus"></i>
                <font _mstmutation="1" _msttexthash="1440504" _msthash="125">
                  اضافه ادوات الخدمة</font
                >
              </button>
              <button
              style="display: none;"
                type="button"
                class="btn btn-outline-warning"
                id="AddButoon-emplo"
              >
                <i class="icon-plus"></i>&nbsp; اضافه موظفين للخدمة
              </button>
            </div>
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
