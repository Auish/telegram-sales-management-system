/*****
 * CONFIGURATION
 */
//Main navigation
$.navigation = $("nav > ul.nav");

$.panelIconOpened = "icon-arrow-up";
$.panelIconClosed = "icon-arrow-down";

//Default colours
$.brandPrimary = "#20a8d8";
$.brandSuccess = "#4dbd74";
$.brandInfo = "#63c2de";
$.brandWarning = "#f8cb00";
$.brandDanger = "#f86c6b";

$.grayDark = "#2a2c36";
$.gray = "#55595c";
$.grayLight = "#818a91";
$.grayLighter = "#d1d4d7";
$.grayLightest = "#f8f9fa";

("use strict");

/****
 * MAIN NAVIGATION
 */

$(document).ready(function ($) {
  // Add class .active to current link
  $.navigation.find("a").each(function () {
    var cUrl = String(window.location);

    if (cUrl.substr(cUrl.length - 1) == "#") {
      cUrl = cUrl.slice(0, -1);
    }

    if ($($(this))[0].href == cUrl) {
      $(this).addClass("active");

      $(this)
        .parents("ul")
        .add(this)
        .each(function () {
          $(this).parent().addClass("open");
        });
    }
  });
  $.navigation.on("click", "a", function (e) {
    // منع الحدث الافتراضي إذا كان تحميل الـ AJAX قيد التشغيل
    if ($.ajaxLoad) {
      e.preventDefault();
      return; // الخروج من الدالة لتجنب المزيد من المعالجة
    }

    // إذا كان العنصر يحتوي على الفئة 'nav-dropdown-toggle'
    if ($(this).hasClass("nav-dropdown-toggle")) {
      // إغلاق جميع العناصر المفتوحة
      $.navigation.find(".open").not($(this).parent()).removeClass("open");

      // فتح أو غلق العنصر الحالي
      $(this).parent().toggleClass("open");

      // منع السلوك الافتراضي للرابط (إذا كان يفتح قائمة منسدلة)
      e.preventDefault();
    }
  });

  function resizeBroadcast() {
    var timesRun = 0;
    var interval = setInterval(function () {
      timesRun += 1;
      if (timesRun === 5) {
        clearInterval(interval);
      }
      window.dispatchEvent(new Event("resize"));
    }, 62.5);
  }

  /* ---------- Main Menu Open/Close, Min/Full ---------- */
  $(".navbar-toggler").click(function () {
    var bodyClass = localStorage.getItem("body-class");

    if (
      $(this).hasClass("layout-toggler") &&
      $("body").hasClass("sidebar-off-canvas")
    ) {
      $("body")
        .toggleClass("sidebar-opened")
        .parent()
        .toggleClass("sidebar-opened");
      //resize charts
      resizeBroadcast();
    } else if (
      $(this).hasClass("layout-toggler") &&
      ($("body").hasClass("sidebar-nav") || bodyClass == "sidebar-nav")
    ) {
      $("body").toggleClass("sidebar-nav");
      localStorage.setItem("body-class", "sidebar-nav");
      if (bodyClass == "sidebar-nav") {
        localStorage.clear();
      }
      //resize charts
      resizeBroadcast();
    } else {
      $("body").toggleClass("mobile-open");
    }
  });

  $(".aside-toggle").click(function () {
    $("body").toggleClass("aside-menu-open");

    //resize charts
    resizeBroadcast();
  });

  $(".sidebar-close").click(function () {
    $("body")
      .toggleClass("sidebar-opened")
      .parent()
      .toggleClass("sidebar-opened");
  });

  /* ---------- Disable moving to top ---------- */
  $('a[href="#"][data-top!=true]').click(function (e) {
    e.preventDefault();
  });
});

/****
 * CARDS ACTIONS
 */

$(document).on("click", ".card-actions a", function (e) {
  e.preventDefault();

  if ($(this).hasClass("btn-close")) {
    $(this).parent().parent().parent().fadeOut();
  } else if ($(this).hasClass("btn-minimize")) {
    var $target = $(this).parent().parent().next(".card-block");
    if (!$(this).hasClass("collapsed")) {
      $("i", $(this))
        .removeClass($.panelIconOpened)
        .addClass($.panelIconClosed);
    } else {
      $("i", $(this))
        .removeClass($.panelIconClosed)
        .addClass($.panelIconOpened);
    }
  } else if ($(this).hasClass("btn-setting")) {
    $("#myModal").modal("show");
  }
});

function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

function moveImageToDiv(input, targetDivId) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      var imgElement = document.getElementById(targetDivId);
      imgElement.src = e.target.result;

      var divElement = document.getElementById(targetDivId);
      divElement.innerHTML = ""; // Clear the div content
      divElement.appendChild(imgElement);
    };
    reader.readAsDataURL(input.files[0]);
  }
}

function addClassOpen() {
  const formGroups = document.querySelectorAll(".form-group.mast");
  formGroups.forEach((formGroup) => {
    formGroup.classList.toggle("open"); // يقوم بإضافة الكلاس إذا لم يكن موجودًا، ويزيله إذا كان موجودًا
  });
}
var selectedFile = null;
var imgCounter = 0; // عداد لتتبع أي عنصر صورة يتم تحديثه

// مستمع لأحداث التغيير عند اختيار ملف
document
  .getElementById("fileInput")
  .addEventListener("change", function (event) {
    if (event.target.files && event.target.files[0]) {
      selectedFile = event.target.files[0];

      // بمجرد اختيار الملف، يمكن تحديث الحقل المخفي بالقيمة
      var reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById("hiddenInput").value = e.target.result; // تخزين المسار في الحقل المخفي
      };
      reader.readAsDataURL(selectedFile);
    }
  });

// دالة لعرض الصورة في العناصر المحددة حسب targetClass
// function displayImage(targetClass) {
//   if (selectedFile) {
//     var reader = new FileReader();
//     reader.onload = function (e) {
//       var imgElements = document.getElementsByClassName(targetClass);
//       if (imgCounter < imgElements.length) {
//         var imgElement = imgElements[imgCounter]; // اختيار العنصر المحدد حسب العداد

//         imgElement.src = e.target.result; // عرض الصورة في العنصر
//         imgCounter++; // تحديث العداد ليشير إلى العنصر التالي
//         selectedFile = null; // إعادة تعيين الملف المحدد بعد العرض
//       } else {
//         console.warn("No more elements to add the image to.");
//       }
//     };
//     reader.readAsDataURL(selectedFile); // قراءة الملف كـ Data URL
//   } else {
//     console.error("No file has been selected.");
//   }
// }

// استدعاء الدالة لاختبارها
// تأكد من استدعاء هذه الدالة بعد تحديد الملف لعرض الصورة
displayImage("image-target");

function init(url) {
  /* ---------- Tooltip ---------- */
  $('[rel="tooltip"],[data-rel="tooltip"]').tooltip({
    placement: "bottom",
    delay: { show: 400, hide: 200 },
  });

  /* ---------- Popover ---------- */
  $('[rel="popover"],[data-rel="popover"],[data-toggle="popover"]').popover();
}
