
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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
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
    <header class="navbar">
        <div class="container-fluid">
            <button class="navbar-toggler mobile-toggler hidden-lg-up" type="button">
          &#9776;
        </button>
            <a class="navbar-brand" href="#"></a>
            <ul class="nav navbar-nav hidden-md-down">
                <li class="nav-item">
                    <a class="nav-link navbar-toggler layout-toggler" href="#">&#9776;</a
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
                    <a class="nav-link aside-toggle" href="#"><i class="icon-bell"></i
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
                    <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="img/avatars/6.jpg" class="img-avatar" alt="admin@bootstrapmaster.com" />
                        <span class="hidden-md-down">مدیر</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header text-xs-center">
                            <strong>تنظیمات</strong>
                        </div>
                        <a class="dropdown-item" href="#"><i class="fa fa-user"></i> پروفایل</a
              >
              <a class="dropdown-item" href="#"
                ><i class="fa fa-wrench"></i> تنظیمات</a
              >
              <!--<a class="dropdown-item" href="#"><i class="fa fa-usd"></i> Payments<span class="tag tag-default">42</span></a>-->
                        <div class="divider"></div>
                        <a class="dropdown-item" href="#"><i class="fa fa-lock"></i> خروج</a
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
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <a class="btn btn-secondary" href="#"><i class="icon-speech"></i
            ></a>
                    <a class="btn btn-secondary" href="./"><i class="icon-graph"></i> &nbsp;Dashboard</a
            >
            <a class="btn btn-secondary" href="#"
              ><i class="icon-settings"></i> &nbsp;Settings</a
            >
          </div>
        </li>
      </ol>
      <div class="animated fadeIn">
        <div class="row">
          <div class=" ">
            <div class="card">
              <div class="card-header">
                <i class="fa fa-align-justify"></i>بيانات الخصم
              </div>
              <form action="" method="post" enctype="multipart/form-data">
                <div class="card-block">
               
                  

                    <div class="form-group">
                        <label for="CategoryName" class="control-label col-lg-2">
                          الكميه
                        </label>
                        <div class="col-lg-7">
                          <input class="form-control" name="category_name" type="text">
                        </div>
                      </div>
               
                  <div class="form-group">
                    <label for="ProductPrice" class="col-lg-2 col-sm-2 control-label">
                      نسبه الخصم    
                    </label>
                    <div class="col-lg-7">
                      <input type="number" step="any" name="product_price" class="form-control" id="product_price" required="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="ProductStatus" class="control-label col-lg-2">
                      حاله الخصم
                    </label>
                    <div class="col-lg-7">
                      <select name="product_status" class="form-control" required="">
                        <option>Out of Stock</option>
                        <option>In Stock</option>
                      </select>
                    </div>
                  </div>
                  <!--/row-->

              


 

                    <!--=*= PRODUCT ADDITIONAL IMAGE =*=-->
                </div>
                </form>

                </div>
                </div>

                <!--/col-->

           
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-align-justify"></i>الخدمات
                            </div>
                            <div class="card-block">
                                <!-- Search and filter section -->
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="tableSearch" placeholder="Search...">
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" id="roleFilter">
                                <option value="">Filter by Role</option>
                                <option value="Member">Member</option>
                                <option value="Staff">Staff</option>
                                <option value="Admin">Admin</option>
                            </select>
                                    </div>
                                </div>
                                <table class="table table-bordered table-striped table-condensed">
                                  <thead>
                                    <tr>
                                      <th><input type="checkbox" id="selectAll"></th>
                                      <th>الرقم</th>
                                      <th>اسم تلخدمة</th>
                                      <th>الوحده الزمنيه</th>
                                      <th>سعر الوحده</th>
                                      <th>نطاق العمل</th>
                                      <th>الصوره</th>
                                      <th>حالة الخدمة</th>
                                      <th></th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td><input type="checkbox" class="rowCheckbox"></td>
                                      <td>1</td>
                                      <td>ثوب الزفاف تراثي قديم</td>
                                      <td>باليوم</td>
                                      <td>15000</td>
                                      <td>داخل المدينة</td>
                                      <td>
                                        <img
                                          src="../img/avatars/6.jpg"
                                          class="img-avatar"
                                          alt="admin@bootstrapmaster.com"
                                          width="35px"
                                          height="35px"
                                        />
                                      </td>
                                      <td>متوفر</td>
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
                                        <button
                                          type="button"
                                          class="tag tag-success icon-note"
                                          style="margin-bottom: 4px"
                                          id="editButton"
                                        >
                                          <span>تعديل</span>
                                        </button>
                                        <button
                                          type="button"
                                          class="tag tag-danger icon-trash"
                                          style="margin-bottom: 4px"
                                          id="deleteButton"
                                        >
                                          <span>حذف</span>
                                        </button>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                                <div id="selectedRowData" class="mt-3"></div>
                                <nav>
                                    <ul class="pagination">
                                        <li class="page-item"><a class="page-link" href="#">Prev</a>
            </li>
            <li class="page-item active">
                <a class="page-link" href="#">1</a>
            </li>
            <li class="page-item"><a class="page-link" href="#">2</a>
            </li>
            <li class="page-item"><a class="page-link" href="#">3</a>
            </li>
            <li class="page-item"><a class="page-link" href="#">4</a>
            </li>
            <li class="page-item"><a class="page-link" href="#">Next</a>
            </li>
            </ul>
        </nav>
        </div>
    </div>
    </div>
    </div>
    <div class=" ">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i>بيانات الخدمه
            </div>
            <div class="card-block">
                <form class="form-horizontal" role="form" method="post" action="" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="product_name" class="control-label col-lg-2">اسم الخمه</label>
                        <div class="col-lg-10">
                            <input type="text" name="product_name" class="form-control modles" id="product_name" readonly="">
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="product_price" class="control-label col-lg-2">السعر</label>
                        <div class="col-lg-10">
                            <input type="number" step="any" name="product_price" class="form-control pric_modle" id="product_price" readonly="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="product_status" class="control-label col-lg-2">حالة المنتج</label>
                        <div class="col-lg-10">
                            <input type="text" name="product_name" class="form-control stat_modle" id="product_name" readonly="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">الصوره الرئسية</label>
                        <div class="col-md-9">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-new thumbnail" style="width: 160px; height: 160px">
                                    <img style="height: 70%; width: 70%" src="istockphoto-1324356458-612x612.jpg" alt="" id="div11">
                                </div>
                            </div>
                        </div>
                    </div>


                </form>


                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">حفظ</button>
                    <button type="button" class="btn btn-danger">الغاء</button>
                </div>

            </div>
        </div>
    </div>

    <!--/col-->
    </div>
    <!--/row-->

    <!--/row-->

    <!--/row-->
    </div>

    <!--/.container-fluid-->
    </main>

    <aside class="aside-menu">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#timeline" role="tab"><i class="icon-list"></i
          ></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#messages" role="tab"><i class="icon-speech"></i
          ></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#settings" role="tab"><i class="icon-settings"></i
          ></a>

            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="timeline" role="tabpanel">
                <div class="callout m-a-0 p-y-h text-muted text-xs-center bg-faded text-uppercase">
                    <small><b>Today</b> </small>
                </div>
                <hr class="transparent m-x-1 m-y-0" />
                <div class="callout callout-warning m-a-0 p-y-1">
                    <div class="avatar pull-xs-right">
                        <img src="img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com" />
                    </div>
                    <div>
                        Meeting with
                        <strong>Lucas</strong>
                    </div>
                    <small class="text-muted m-r-1"><i class="icon-calendar"></i>&nbsp; 1 - 3pm</small
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
                    <small class="text-muted m-r-1"><i class="icon-calendar"></i>&nbsp; 10 - 11pm</small
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
                <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
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
            <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
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
        <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
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
        <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
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
        <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
              eiusmod tempor incididunt...</small
            >
          </div>
        </div>
        <div class="tab-pane p-a-1" id="settings" role="tabpanel">
          <h6>Settings</h6>
          <div class="aside-options">
            <div class="clearfix m-t-2">
              <small><b>Option 1</b> </small>
        <label class="switch switch-text switch-pill switch-success switch-sm pull-right">
                <input type="checkbox" class="switch-input" checked />
                <span class="switch-label" data-on="On" data-off="Off"></span>
                <span class="switch-handle"></span>
              </label>
        </div>
        <div>
            <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                do eiusmod tempor incididunt ut labore et dolore magna
                aliqua.</small
              >
            </div>
          </div>
          <div class="aside-options">
            <div class="clearfix m-t-1">
              <small><b>Option 2</b> </small>
            <label class="switch switch-text switch-pill switch-success switch-sm pull-right">
                <input type="checkbox" class="switch-input" />
                <span class="switch-label" data-on="On" data-off="Off"></span>
                <span class="switch-handle"></span>
              </label>
        </div>
        <div>
            <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                do eiusmod tempor incididunt ut labore et dolore magna
                aliqua.</small
              >
            </div>
          </div>
          <div class="aside-options">
            <div class="clearfix m-t-1">
              <small><b>Option 3</b> </small>
            <label class="switch switch-text switch-pill switch-success switch-sm pull-right">
                <input type="checkbox" class="switch-input" />
                <span class="switch-label" data-on="On" data-off="Off"></span>
                <span class="switch-handle"></span>
              </label>
        </div>
        </div>
        <div class="aside-options">
            <div class="clearfix m-t-1">
                <small><b>Option 4</b> </small>
                <label class="switch switch-text switch-pill switch-success switch-sm pull-right">
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
        <progress class="progress progress-xs progress-info m-a-0" value="25" max="100">
            25%
          </progress>
        <small class="text-muted">348 Processes. 1/4 Cores.</small>
        <div class="text-uppercase m-b-q m-t-h">
            <small><b>Memory Usage</b> </small>
        </div>
        <progress class="progress progress-xs progress-warning m-a-0" value="70" max="100">
            70%
          </progress>
        <small class="text-muted">11444GB/16384MB</small>
        <div class="text-uppercase m-b-q m-t-h">
            <small><b>SSD 1 Usage</b> </small>
        </div>
        <progress class="progress progress-xs progress-danger m-a-0" value="95" max="100">
            95%
          </progress>
        <small class="text-muted">243GB/256GB</small>
        <div class="text-uppercase m-b-q m-t-h">
            <small><b>SSD 2 Usage</b> </small>
        </div>
        <progress class="progress progress-xs progress-success m-a-0" value="10" max="100">
            10%
          </progress>
        <small class="text-muted">25GB/256GB</small>
        </div>
        </div>
    </aside>

    <footer class="footer">
        <span class="text-left">
        <a href="http://coreui.io">CoreUI</a> &copy; 2016 creativeLabs.
      </span>
        <span class="pull-right">
        Powered by <a href="http://coreui.io">CoreUI</a>
      </span>
    </footer>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    var imgElement = document.getElementById("div1");
                    imgElement.src = e.target.result;

                    var divId = input.getAttribute("set-to"); // تصحيح الخاصية set-to
                    var divElement = document.getElementById(divId);
                    divElement.innerHTML = ""; // تفريغ محتوى العنصر
                    divElement.appendChild(imgElement);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script>
        function addClassOpen() {
            const formGroups = document.querySelectorAll(".form-group.mast ");
            formGroups.forEach((formGroup) => {
                formGroup.classList.toggle("open"); // يقوم بإضافة الكلاس إذا لم يكن موجودًا، ويزيله إذا كان موجودًا
            });
        }
    </script>

    <script>
        // إضافة مستمع للأحداث عند تحديد خانة الاختيار
        document.querySelectorAll(".rowCheckbox").forEach(function(checkbox) {
            checkbox.addEventListener("change", function() {
                // تحقق مما إذا كان قد تم تحديد خانة الاختيار
                if (checkbox.checked) {
                    // الوصول إلى الصف المحدد
                    const row = checkbox.closest("tr");
                    if (!row) return; // تحقق من وجود الصف

                    // استخراج البيانات من الصف
                    const cells = row.querySelectorAll("td");
                    const productName = cells[2] ? cells[2].innerText : ''; // اسم الخدمة
                    const productPrice = cells[4] ? cells[4].innerText : ''; // سعر الإيجار
                    const productStatus = cells[7] ? cells[7].innerText : ''; // حالة المنتج
                    const productImage = cells[6] ? cells[6].querySelector("img") : null;

                    // ملء الحقول في النموذج
                    document.querySelector(".modles").value = productName; // تعبئة اسم الخدمة
                    document.querySelector(".pric_modle").value = productPrice; // تعبئة سعر الإيجار
                    document.querySelector(".stat_modle").value = productStatus; // تعبئة حالة المنتج

                    // تحديث صورة المنتج في <img> إذا كانت الصورة موجودة
                    if (productImage) {
                        document.getElementById("div11").src = productImage.src; // تحديث الصورة في <img>
                    }

                    // تحديث حقل input[type="hidden"]
                    // document.querySelector(".defaultdiv11").value = productImage ? productImage.src : ''; // حفظ المسار إذا كان موجودًا
                }
            });
        });
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