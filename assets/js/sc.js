"use strict";
// دالة آمنة لإضافة الأحداث
// function safeAddEvent(element, event, callback) {
//     if (element && typeof element.addEventListener === 'function') {
//         element.addEventListener(event, callback);
//     }
// }

let cart_ic = document.querySelector("#butoon-seand-mor");
let cart_icc = document.querySelector(".card-icon");
let cart = document.querySelector(".cart-c");
let cart_clos = document.querySelector(".clos-ca");
let cart_clos_lov = document.querySelector(".clos-lov");
let cart_lov = document.querySelector(".cart-c1");
let cart_lov_butt = document.querySelector("#lov-icon");
let cart_lov_butt_clsd = document.querySelector(".lov-icon");

// عند الضغط على cart_ic أو cart_icc
cart_ic.onclick = () => {
    cart.classList.toggle("active");
};
cart_icc.onclick = () => {
    cart.classList.toggle("active");
};

// عند الضغط على cart_lov_butt
// cart_lov_butt.onclick = () => {
//     cart_lov.classList.toggle("active");
// };
// عند الضغط على cart_lov_butt
// cart_lov_butt_clsd.onclick = () => {
//     cart_lov.classList.toggle("active");
// };

// عند الضغط على cart_clos لإغلاق السلة
cart_clos.onclick = () => {
    cart.classList.remove("active");
};
// عند الضغط على cart_clos لإغلاق السلة
cart_clos_lov.onclick = () => {
    cart_lov.classList.remove("active");
};

// التأكد من تحميل المستند بالكامل قبل تشغيل الوظيفة
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", redin);
} else {
    redin();
}

// // انتظر حتى تحميل الصفحة بالكامل
// document.addEventListener('DOMContentLoaded', function() {
//     // الحصول على العناصر بشكل آمن
//     const cart_ic = document.getElementById('cart_ic');
//     const cart_opne = document.getElementById('cart_opne');
//     const cart_icc = document.getElementById('cart_icc');
//     const cart_lov_butt = document.getElementById('cart_lov_butt');
//     const cart_lov_butt_clsd = document.getElementById('cart_lov_butt_clsd');
//     const cart_clos = document.getElementById('cart_clos');
//     const cart_clos_lov = document.getElementById('cart_clos_lov');
//     const cart = document.getElementById('cart');
//     const cart_lov = document.getElementById('cart_lov');

//     // إضافة الأحداث للعناصر الموجودة فقط
//     safeAddEvent(cart_ic, 'click', () => {
//         if (cart) cart.classList.toggle("active");
//     });

//     safeAddEvent(cart_opne, 'click', () => {
//         if (cart) cart.classList.toggle("active");
//     });

//     safeAddEvent(cart_icc, 'click', () => {
//         if (cart) cart.classList.toggle("active");
//     });

//     safeAddEvent(cart_lov_butt, 'click', () => {
//         if (cart_lov) cart_lov.classList.toggle("active");
//     });

//     safeAddEvent(cart_lov_butt_clsd, 'click', () => {
//         if (cart_lov) cart_lov.classList.toggle("active");
//     });

//     safeAddEvent(cart_clos, 'click', () => {
//         if (cart) cart.classList.remove("active");
//     });

//     safeAddEvent(cart_clos_lov, 'click', () => {
//         if (cart_lov) cart_lov.classList.remove("active");
//     });
// });
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

// // الكود الرئيسي لتشغيل التحريك
// $(document).ready(function() {
//     var PercentageID = $("#percent1"),
//         barID = $("#bar1"),
//         preloader = $(".pre-loader"),
//         time = 2000; // 4 ثواني

//     // التحقق من وجود جميع العناصر
//     if (PercentageID.length && barID.length && preloader.length) {
//         var start = 0,
//             end = 100,
//             duration = time;

//         // بدء التحريك مع تأثير easeOut
//         animateValue(PercentageID, start, end, duration, 'easeOut');
//     } else {
//         console.warn("عناصر التحميل غير موجودة");

//         // إذا لم توجد العناصر، إخفاء pre-loader فوراً
//         preloader.hide();
//         $(".overlay").hide();
//     }
// });
// عند الضغط على الزر
document.querySelector(".banner-subtitle").addEventListener("click", function() {
    document.getElementById("customModal").style.display = "flex";
});

// عند الضغط على X
document.querySelector(".close-modal").addEventListener("click", function() {
    document.getElementById("customModal").style.display = "none";
});

// عند الضغط خارج الصندوق
document.getElementById("customModal").addEventListener("click", function(e) {
    if (e.target === this) {
        this.style.display = "none";
    }
});


// زر الفتح
document.getElementById("banner-btn-seand")
    .addEventListener("click", function() {
        document.getElementById("requestModal").style.display = "flex";
    });

// زر الإغلاق
document.querySelector("#requestModal .close-modal")
    .addEventListener("click", function() {
        document.getElementById("requestModal").style.display = "none";
    });

// إغلاق عند الضغط خارج الصندوق
document.getElementById("requestModal")
    .addEventListener("click", function(e) {
        if (e.target === this) this.style.display = "none";
    });

// زر "يلغي"
document.querySelector(".btn-cancel")
    .addEventListener("click", function() {
        document.getElementById("requestModal").style.display = "none";
    });


let openBtn = document.getElementById("butoon-seand");
let modal = document.getElementById("walletModal");
let closeBtn = document.getElementById("closeModal");

openBtn.onclick = () => {
    modal.style.display = "flex";
};

closeBtn.onclick = () => {
    modal.style.display = "none";
};

// إغلاق عند الضغط خارج المودل
window.onclick = (e) => {
    if (e.target === modal) modal.style.display = "none";
};


// let openBtn2 = document.getElementById("butoon-seand-mor");
// let modal2 = document.getElementById("recordModal");
// let closeBtn2 = document.getElementById("closeModal2");

// openBtn2.onclick = () => {
//     modal2.style.display = "flex";
// };

// closeBtn2.onclick = () => {
//     modal2.style.display = "none";
// };

// window.onclick = (e) => {
//     if (e.target === modal2) modal2.style.display = "none";
// };

let openlogo = document.getElementById("open-buttton-logut");
let logut = document.getElementById("logout");

openlogo.onclick = () => {
    logut.style.display = "flex";
};


window.onclick = (e) => {
    if (e.target === logut) logut.style.display = "none";
};


function redin() {
    // Remove
    var removeing_pro = document.querySelectorAll(".cart-remove");

    for (let i = 0; i < removeing_pro.length; i++) {
        var butt_elmaent = removeing_pro[i];
        butt_elmaent.addEventListener("click", removeitem);
    }
    var quaent = document.getElementsByClassName("catr-quantiy");

    for (let i = 0; i < quaent.length; i++) {
        var input = quaent[i];
        input.addEventListener("change", quenteintchang);
    }
    //    add catd
    var addcart = document.getElementsByClassName("add-card");

    for (let i = 0; i < addcart.length; i++) {
        var boutton = addcart[i];
        boutton.addEventListener("click", addcardclicked);
    }
    var addlov = document.getElementsByClassName("add-lov");

    for (let i = 0; i < addlov.length; i++) {
        var boutton1 = addlov[i];
        boutton1.addEventListener("click", addlovclicked);
    }

    // document
    //     .getElementsByClassName("bramer-boutt-shop")[0]
    //     .addEventListener("click", purchaseClicked);
    lodd();
    lodd_lov();
}

function purchaseClicked() {
    alert("Thank you for your purchase");
    var cartItems = document.getElementsByClassName("cart-content")[0];
    while (cartItems.hasChildNodes()) {
        cartItems.removeChild(cartItems.firstChild);
    }
    uptedtElement();
    updetquentent();
    updetquententlov();
}
// Remove Cart Item
function removeitem(event) {
    var button_click = event.target;
    button_click.parentElement.remove();
    uptedtElement();
    updetquententlov();
    sevalllocuesort();
    saveAllToLocalStorage_lov();
}
// تهيئة Swiper للصور الصغيرة
// تحقق من وجود العنصر ".smali-image" قبل تهيئة Swiper للصور الصغيرة
if (document.querySelector(".smali-image")) {
    const swiper2 = new Swiper(".smali-image", {
        loop: true,
        spaceBetween: 10,
        slidesPerView: 3,
        freeMode: true,
        watchSlidesProgress: true,
    });
}
// تأكد من أن العنصر الذي يحتوي على الفئة ".save" موجود
const selectImage = document.querySelector(".save");
const inputFile = document.querySelector("#file");

if (selectImage && inputFile) {
    // إضافة مستمع الأحداث فقط إذا كان العنصر موجوداً
    selectImage.addEventListener("click", function() {
        inputFile.click();
    });
} else {
    console.error("لم يتم العثور على عنصر '.save' أو '#file' في الصفحة.");
}
// تعيين تاريخ النهاية
const countdownDate = new Date("Dec 31, 2024 23:59:59").getTime();

// تحديث العداد كل ثانية
const countdownFunction = setInterval(function() {
    // الحصول على الوقت الحالي
    const now = new Date().getTime();

    // حساب الفرق بين الآن وتاريخ النهاية
    const distance = countdownDate - now;

    // حساب الوقت بالأيام، الساعات، الدقائق، والثواني
    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor(
        (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
    );
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    // التحقق من وجود العناصر قبل عرض النتائج
    const daysElement = document.getElementById("days");
    const hoursElement = document.getElementById("hours");
    const minutesElement = document.getElementById("minutes");
    const secondsElement = document.getElementById("seconds");

    if (daysElement && hoursElement && minutesElement && secondsElement) {
        // عرض النتائج في العناصر المحددة إذا كانت موجودة
        daysElement.innerHTML = days;
        hoursElement.innerHTML = hours;
        minutesElement.innerHTML = minutes;
        secondsElement.innerHTML = seconds;
    } else {
        console.warn("عناصر العد التنازلي غير موجودة في الصفحة.");
    }

    // إذا انتهى العد التنازلي
    if (distance < 0) {
        clearInterval(countdownFunction);
        if (daysElement && hoursElement && minutesElement && secondsElement) {
            daysElement.innerHTML = "0";
            hoursElement.innerHTML = "0";
            minutesElement.innerHTML = "0";
            secondsElement.innerHTML = "0";
        }
    }
}, 1000);
var time = 2000; // تعيين قيمة للمتغير time (المدة بالمللي ثانية)

// var PercentageID = $("#percent1"),
//     barID = $("#bar1"),
//     preloader = $(".pre-loader");

// if (PercentageID.length && barID.length && preloader.length) {
//     // التأكد من وجود العناصر قبل البدء
//     var start = 0,
//         end = 100,
//         duration = time; // استخدام المتغير time كمدة

//     animateValue(PercentageID, start, end, duration);
// } else {
//     console.warn("واحد أو أكثر من العناصر المستهدفة غير موجودة في الصفحة.");
// }

function animateValue(id, start, end, duration) {
    var range = end - start,
        current = start,
        increment = end > start ? 1 : -1,
        stepTime = Math.abs(Math.floor(duration / range)),
        obj = $(id);

    var timer = setInterval(function() {
        current += increment;
        $(obj).text(current + "%");

        if ($("#bar1").length) {
            // التأكد من وجود العنصر #bar1 قبل تغيير عرضه
            $("#bar1").css("width", current + "%");
        }

        if (current == end) {
            clearInterval(timer);

            if ($(".pre-loader").length) {
                // التأكد من وجود العنصر ".pre-loader" قبل إضافة الكلاس
                $(".pre-loader").addClass("close");
            }
        }
    }, stepTime);
}

function moveImageToDiv(input, targetDivId) {
    // التحقق من وجود ملفات واختيار ملف
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            // التحقق من أن العنصر المستهدف موجود وأنه عنصر img
            var imgElement = document.getElementById(targetDivId);
            if (imgElement && imgElement.tagName === "IMG") {
                imgElement.src = e.target.result; // تعيين مصدر الصورة
            } else {
                console.error("العنصر المستهدف إما غير موجود أو ليس عنصر IMG.");
            }
        };

        // قراءة الملف كـ DataURL
        reader.readAsDataURL(input.files[0]);
    } else {
        console.error("لم يتم اختيار ملف.");
    }
}
// تحقق من وجود العنصر ".big-image" قبل تهيئة Swiper للصور الكبيرة
if (document.querySelector(".big-image")) {
    const swiper = new Swiper(".big-image", {
        loop: true,
        autoHeight: true, // لضبط ارتفاع الـ Swiper بناءً على محتوى الشرائح
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        thumbs: {
            // تحقق من وجود swiper2 قبل ربطه بالـ Swiper الكبير
            swiper: typeof swiper2 !== "undefined" ? swiper2 : null,
        },
    });
}

//  fuinction is qkaintchaing
function quenteintchang(event) {
    var inupot = event.target;
    if (isNaN(inupot.value) || inupot.value <= 0) {
        inupot.value = 1;
    }
    uptedtElement();
    updetquentent();
    updetquententlov();
    sevalllocuesort();
    saveAllToLocalStorage_lov();
}
var flg = 0;
//card add
function addprodectcard_chik(tithe, price, prodectimg, quaent) {
    // تأكد من أن cartTableBody يشير إلى tbody داخل الجدول
    var cartTableBody = document.querySelector("#cart-table tbody");

    var cardBoxcontent = `
  <tr class="contant">
    <td class="flexitem">
      <div class="thumbnail object-cover">
        <a href="#">
          <img src="${prodectimg}" alt="" class="cart-img"/>
        </a>
      </div>
      <div class="content">
        <strong><a href="#">${tithe}</a></strong>
        <p>من: عكاوه</p>
      </div>
    </td>
    <td>${price}</td>
    <td>
      <div class="qty-control flexitem">
        <button class="minus"></button>
        <input type="number" value="${quaent}" min="1" class="catr-quantiy"/>
        <button class="plus"></button>
      </div>
    </td>
    <td>${price * quaent}</td>
    <td>
      <a href="#" class="item-remove"><i class="ri-close-line"></i></a>
    </td>
  </tr>
`;

    if (cartTableBody) {
        cartTableBody.innerHTML += cardBoxcontent;

        // إضافة الأحداث للعناصر الجديدة إذا لزم الأمر
        cartTableBody
            .querySelector(".item-remove")
            .addEventListener("click", removeitem);
        // cartTableBody
        //   .querySelector(".catr-quantiy1") // تم تصحيح selector هنا
        //   .addEventListener("change", quenteintchang);

        // تحديث العناصر المختلفة
        uptedtElement();
        updetquentent();
        updetquententlov();
        sevalllocuesort();
    } else {
        console.error("Element with ID 'cart-table' not found.");
    }
}

function addprodectcard(tithe, price, prodectimg) {
    var cardshoping = document.createElement("div");
    cardshoping.classList.add("cart-cox");
    var cartitem = document.getElementsByClassName("cart-content")[0];
    let div_show = document.querySelector(".notification-card");
    let div_show_fols = document.querySelector(".notification-cardfols");
    var cartItemName = cartitem.getElementsByClassName("cart-prodect-titil"); // تصحيح التسمية هنا
    for (let i = 0; i < cartItemName.length; i++) {
        if (cartItemName[i].innerText === tithe) {
            // تأكد من استخدام 'title' بدلاً من 'tithe'
            div_show_fols.classList.toggle("show");
            // إخفاء العنصر بعد 7 ثوانٍ
            setTimeout(() => {
                div_show_fols.classList.remove("show");
            }, 5000); // 7000 ميلي ثانية = 7 ثوانٍ
            return; // تصحيح التسمية هنا
        }
    }
    var cardBoxcontent = `  <img src="${prodectimg}" alt="" class="cart-img">
          <div class="data-bpx">
              <div class="cart-prodect-titil">${tithe}</div>
              <div class="cart-pric">${price}</div>
              <input type="number" name="" id="" value="1" class="catr-quantiy">
          </div>
          <i class="ri-delete-bin-7-line cart-remove"></i>`;
    cardshoping.innerHTML = cardBoxcontent;
    cartitem.append(cardshoping);

    cardshoping
        .getElementsByClassName("cart-remove")[0]
        .addEventListener("click", removeitem);
    cardshoping
        .getElementsByClassName("catr-quantiy")[0]
        .addEventListener("change", quenteintchang);
    uptedtElement();
    updetquentent();
    updetquententlov();
    sevalllocuesort();
    flg++;
}

function addprodectlov(tithe, price, prodectimg) {
    var cardshoping = document.createElement("div");
    cardshoping.classList.add("cart-cox1");
    let lov_vaard = document.querySelector(".notification-lov");
    var cartitem = document.getElementsByClassName("cart-content2")[0];
    var cartitemnam = cartitem.getElementsByClassName("cart-prodect-titil");

    // التحقق من وجود المنتج بالفعل
    for (let i = 0; i < cartitemnam.length; i++) {
        if (cartitemnam[i].innerText === tithe) {
            lov_vaard.classList.toggle("show");
            setTimeout(() => {
                lov_vaard.classList.remove("show");
            }, 5000); // 7000 ميلي ثانية = 7 ثوانٍ

            return;
        }
    }

    // محتوى العنصر المضاف
    var cardBoxcontent = `
      <img src="${prodectimg}" alt="" class="cart-img">
      <div class="data-bpx">
          <div class="cart-prodect-titil">${tithe}</div>
          <div class="cart-pric">${price}</div>
      </div>
      <i class="ri-delete-bin-7-line cart-remove"></i>`;

    cardshoping.innerHTML = cardBoxcontent;
    cartitem.append(cardshoping);

    // إضافة مستمعي الأحداث للزر الخاص بالإزالة
    cardshoping
        .getElementsByClassName("cart-remove")[0]
        .addEventListener("click", removeitem);

    // uptedtElement();
    // updetquentent();
    updetquententlov();
    saveAllToLocalStorage_lov();
}

function addcardclicked(event) {
    let div_show = document.querySelector(".notification-card");
    var button = event.target;
    var shopingProduct = button.closest(".showcase"); // تعديل البحث ليكون أدق
    var title = shopingProduct.querySelector(".showcase-title").innerText;
    var price = shopingProduct.querySelector(".price").innerText;
    var productImg = shopingProduct.querySelector(".product-img").src;

    addprodectcard(title, price, productImg);

    div_show.classList.toggle("show");
    setTimeout(() => {
        div_show.classList.remove("show");
    }, 5000);
    uptedtElement();
    updetquentent();
    updetquententlov();
}

function addlovclicked(event) {
    let clik_lov = document.querySelector(".notification-toast");
    var boutton = event.target;
    var shopingprodacts = boutton.closest(".showcase");
    var tithe =
        shopingprodacts.getElementsByClassName("showcase-title")[0].innerText;
    var price = shopingprodacts.getElementsByClassName("price")[0].innerText;
    var prodectimg = shopingprodacts.getElementsByClassName("product-img")[0].src;

    addprodectlov(tithe, price, prodectimg);
    clik_lov.classList.toggle("show");
    setTimeout(() => {
        clik_lov.classList.remove("show");
    }, 5000); // 7000 ميلي ثانية = 7 ثوانٍ
    uptedtElement();
    updetquentent();
    updetquententlov();
}

// هذا الكود يقوم بحساب المجموع الإجمالي لقيم عناصر سلة التسوق بناءً على الأسعار والكميات المحددة.
function uptedtElement() {
    var cartContent = document.getElementsByClassName("cart-content")[0];
    var cartBoxes = document.getElementsByClassName("cart-cox");
    var title = 0;
    for (var i = 0; i < cartBoxes.length; i++) {
        var cartbox = cartBoxes[i];
        var priceElment = cartbox.getElementsByClassName("cart-pric")[0];
        var quntentElment = cartbox.getElementsByClassName("catr-quantiy")[0];
        var price = parseFloat(priceElment.innerText.replace("$", ""));
        var quent = quntentElment.value;
        title = title + price * quent;
    }
    //  title = Math.random(title * 100) / 100;
    // document.getElementsByClassName("cart-total")[0].innerText = " " + title;

    localStorage.setItem("cartoti", title);
    // var cartitem = JSON.stringify(title);
    // document.cookie =
    //   "cartitemtet=" +
    //   cartitem +
    //   "; expires=Thu, 31 Dec 2026 23:59:59 UTC; path=/";
    updetquentent();
}
// هذا الكود يقوم بحساب إجمالي الكميات في سلة التسوق وتحديث أيقونة السلة في واجهة المستخدم لعرض العدد الإجمالي للعناصر
function updetquentent() {
    var carsBoxx = document.getElementsByClassName("cart-cox");
    var quentent = 0;
    for (var i = 0; i < carsBoxx.length; i++) {
        var cardbox = carsBoxx[i];
        var quentenelment = cardbox.getElementsByClassName("catr-quantiy")[0];
        quentent += parseInt(quentenelment.value);
    }
    var cardicon = document.querySelector("#card-icon");
    cardicon.setAttribute("data-quantity", quentent);
    var cardiconclas = document.querySelector(".card-icon");
    cardiconclas.setAttribute("data-quantity", quentent);
}

function updetquententlov() {
    var carsBoxx = document.getElementsByClassName("cart-cox1");
    var quentent = 0;
    for (var i = 0; i < carsBoxx.length; i++) {
        var cardbox = carsBoxx[i];
        // هنا يمكن إضافة منطق لحساب الكمية إذا كان مطلوبًا
        quentent += 1; // على سبيل المثال: إضافة 1 لكل عنصر في القائمة
    }
    var cardicon = document.querySelector("#lov-icon");
    cardicon.setAttribute("data-quantity", quentent);
    var card_lov_clas = document.querySelector(".lov-icon");
    card_lov_clas.setAttribute("data-quantity", quentent);
}

function addProductToShowcase(title, price, productImg) {
    var showcaseContainer = document.createElement("div");
    showcaseContainer.classList.add("showcase");

    var cartItemShow = document.getElementsByClassName("show-cards")[0];
    if (!cartItemShow) {
        console.warn(
            "The element with class 'show-cards' is not present on the page."
        );
        return; // إذا كان العنصر غير موجود، لا تقم بمتابعة التنفيذ
    }

    var showcaseBoxContent = `
    <a href="#" class="showcase-img-box">
    <img src="${productImg}" alt="${title}" class="showcase-img" width="70" />
</a>

<div class="showcase-content">
    <a href="#">
        <h4 class="showcase-title">
            ${title}
        </h4>
    </a>

    <a href="#" class="showcase-category">ملابس الزفاف</a>

    <div class="price-box">
        <p class="price">${price}</p>
        <p class="category-item-amount">لساعه</p>
    </div>
    
     
</div>`;

    // تعيين المحتوى الجديد إلى showcaseContainer
    showcaseContainer.innerHTML = showcaseBoxContent;

    // إضافة العنصر الجديد إلى showcase-container
    cartItemShow.append(showcaseContainer);

    // تحديث وإضافة الأحداث إذا لزم الأمر
    uptedtElement();
    updetquentent();
    // updetquententlov();
    sevalllocuesort();
}

function addProductToShowlov(title, price, productImg) {
    var showcaseContainer = document.createElement("div");
    showcaseContainer.classList.add("showcase");

    var cartItemShow = document.getElementsByClassName("show-lovs")[0];

    if (!cartItemShow) {
        console.warn(
            "The element with class 'show-cards' is not present on the page."
        );
        return; // إذا كان العنصر غير موجود، لا تقم بمتابعة التنفيذ
    }
    var showcaseBoxContent = `
    <a href="#" class="showcase-img-box">
        <img src="${productImg}" alt="${title}" class="showcase-img" width="70" />
    </a>
    <div class="showcase-content">
        <a href="#">
            <h4 class="showcase-title">${title}</h4>
        </a>
        <a href="#" class="showcase-category">Mens Fashion</a>
        <div class="price-box">
            <p class="price">${price}</p>
            <del> </del>
        </div>
    </div>`;

    // تعيين المحتوى الجديد إلى showcaseContainer
    showcaseContainer.innerHTML = showcaseBoxContent;

    // إضافة العنصر الجديد إلى showcase-container
    cartItemShow.append(showcaseContainer);

    // تحديث وإضافة الأحداث إذا لزم الأمر
    updetquentent();
    updetquententlov();
    saveAllToLocalStorage_lov();
}

const submat = document.querySelectorAll(".has-chid, .icon-small");
submat.forEach((menu) => menu.addEventListener("click", toggle));

function toggle(e) {
    e.preventDefault();
    submat.forEach((item) => {
        if (item !== this && item.closest(".has-chid")) {
            item.closest(".has-chid").classList.remove("expand");
        }
    });
    this.closest(".has-chid").classList.toggle("expand");
}

// const btn = document.querySelector("button");
// const post = document.querySelector(".post");
// const widget = document.querySelector(".star-widget");
// const editBtn = document.querySelector(".edit");
// btn.onclick = () => {
//   widget.style.display = "none";
//   post.style.display = "block";
//   editBtn.onclick = () => {
//     widget.style.display = "block";
//     post.style.display = "none";
//   };
//   return false;
// };

// accordion variables
const accordionBtn = document.querySelectorAll("[data-accordion-btn]");
const accordion = document.querySelectorAll("[data-accordion]");

for (let i = 0; i < accordionBtn.length; i++) {
    accordionBtn[i].addEventListener("click", function() {
        const clickedBtn = this.nextElementSibling.classList.contains("active");

        for (let i = 0; i < accordion.length; i++) {
            if (clickedBtn) break;

            if (accordion[i].classList.contains("active")) {
                accordion[i].classList.remove("active");
                accordionBtn[i].classList.remove("active");
            }
        }

        this.nextElementSibling.classList.toggle("active");
        this.classList.toggle("active");
    });
}

// mobile menu variables
const mobileMenuOpenBtn = document.querySelectorAll(
    "[data-mobile-menu-open-btn]"
);
const mobileMenu = document.querySelectorAll("[data-mobile-menu]");
const mobileMenuCloseBtn = document.querySelectorAll(
    "[data-mobile-menu-close-btn]"
);

// const overlay = document.querySelector("[data-overlay]");

for (let i = 0; i < mobileMenuOpenBtn.length; i++) {
    // mobile menu function
    const mobileMenuCloseFunc = function() {
        mobileMenu[i].classList.remove("active");
        overlay.classList.remove("active");
    };

    mobileMenuOpenBtn[i].addEventListener("click", function() {
        mobileMenu[i].classList.add("active");
        overlay.classList.add("active");
    });

    mobileMenuCloseBtn[i].addEventListener("click", mobileMenuCloseFunc);
    overlay.addEventListener("click", mobileMenuCloseFunc);
}

function sevalllocuesort() {
    var cartContent = document.getElementsByClassName("cart-content")[0];
    var cartBoxes = document.getElementsByClassName("cart-cox");
    var cartitm = [];
    for (var i = 0; i < cartBoxes.length; i++) {
        var cardbox = cartBoxes[i];
        var titlement = cardbox.getElementsByClassName("cart-prodect-titil")[0];
        var quentenelment = cardbox.getElementsByClassName("catr-quantiy")[0];
        var priceElment = cardbox.getElementsByClassName("cart-pric")[0];
        var prodectimg = cardbox.getElementsByClassName("cart-img")[0].src;
        var item = {
            tite: titlement.innerText,
            pris: priceElment.innerText,
            qnut: quentenelment.value,
            proimg: prodectimg,
        };
        cartitm.push(item);
    }

    localStorage.setItem("cartitem", JSON.stringify(cartitm));

    // // تحويل مصفوفة "cartitm" إلى سلسلة JSON
    // var cartJson = JSON.stringify(cartitm);

    // // حفظ السلسلة JSON في الكوكيز
    // document.cookie =
    //   "cartitem=" + cartJson + "; expires=Thu, 31 Dec 2026 23:59:59 UTC; path=/";
}

function saveAllToLocalStorage_lov() {
    var cartContent = document.getElementsByClassName("cart-content")[0];
    var cartBoxes = document.getElementsByClassName("cart-cox1");
    var loveItems = [];

    for (var i = 0; i < cartBoxes.length; i++) {
        var cartBox = cartBoxes[i];
        var titleElement = cartBox.getElementsByClassName("cart-prodect-titil")[0];
        var priceElement = cartBox.getElementsByClassName("cart-pric")[0];
        var productImg = cartBox.getElementsByClassName("cart-img")[0].src;

        var loveItem = {
            title: titleElement.innerText,
            price: priceElement.innerText,
            image: productImg,
        };

        loveItems.push(loveItem);
    }

    localStorage.setItem("loveItems", JSON.stringify(loveItems));
}

// function lodd() {
//   var cartItems = localStorage.getItem("cartitem");
//   if (cartItems) {
//     cartItems = JSON.parse(cartItems);
//     for (var i = 0; i < cartItems.length; i++) {
//       var item = cartItems[i];
//       addprodectcard(item.tite, item.pris, item.proimg);
//       addProductToShowcase(item.tite, item.pris, item.proimg);
//       addprodectcard_chik(item.tite, item.pris, item.proimg);

//       var cartBoxes = document.getElementsByClassName("cart-cox");
//       var cartTableBody = document.querySelector("#cart-table tbody");

//       // تحديث الكمية في العناصر
//       if (cartBoxes.length > 0) {
//         var cartBox = cartBoxes[i];
//         var quantityInput = cartBox.getElementsByClassName("catr-quantiy")[0];
//         if (quantityInput) {
//           quantityInput.value = item.qnut;
//         }
//       }

//       if (cartTableBody) {
//         if (cartTableBody.children.length > 0) {
//           var cartRow = cartTableBody.children[i];
//           var quantityInputChk =
//             cartRow.getElementsByClassName("catr-quantiy1")[0];
//           if (quantityInputChk) {
//             quantityInputChk.value = item.qnut;
//           }
//         }
//       }
//     }
//   }

//   var cartTotal = localStorage.getItem("cartoti");
//   if (cartTotal) {
//     document.getElementsByClassName("cart-total")[0].innerText =
//       "$" + cartTotal;
//   }
// }
function lodd() {
    var cartItems = localStorage.getItem("cartitem");
    console.log("Cart Items from localStorage:", cartItems);

    if (cartItems) {
        cartItems = JSON.parse(cartItems);
        console.log("Parsed Cart Items:", cartItems);

        for (var i = 0; i < cartItems.length; i++) {
            var item = cartItems[i];
            addprodectcard(item.tite, item.pris, item.proimg);
            addProductToShowcase(item.tite, item.pris, item.proimg);
            addprodectcard_chik(item.tite, item.pris, item.proimg, item.qnut);

            var cartBoxes = document.getElementsByClassName("cart-cox");
            console.log("Number of Cart Boxes:", cartBoxes.length);

            var cartTableBody = document.querySelector("#cart-table tbody");
            console.log("Cart Table Body:", cartTableBody);

            if (cartBoxes.length > 0 && cartBoxes[i]) {
                var cartBox = cartBoxes[i];
                var quantityInput = cartBox.getElementsByClassName("catr-quantiy")[0];
                console.log("Quantity Input in Cart Box:", quantityInput);
                if (quantityInput) {
                    quantityInput.value = item.qnut;
                }
            }

            if (cartTableBody && cartTableBody.children.length > i) {
                var cartRow = cartTableBody.children[i];
                var quantityInputChk =
                    cartRow.getElementsByClassName("catr-quantiy1")[0];
                console.log("Quantity Input in Cart Table Body Row:", quantityInputChk);
                if (quantityInputChk) {
                    quantityInputChk.value = item.qnut;
                }
            }
        }
    }

    var cartTotal = localStorage.getItem("cartoti");
    console.log("Cart Total from localStorage:", cartTotal);

}

function lodd_lov() {
    var carlovtitem = localStorage.getItem("loveItems");
    if (carlovtitem) {
        carlovtitem = JSON.parse(carlovtitem);
        for (var i = 0; i < carlovtitem.length; i++) {
            var loveItem = carlovtitem[i];
            addprodectlov(loveItem.title, loveItem.price, loveItem.image);
            addProductToShowlov(loveItem.title, loveItem.price, loveItem.image);
        }
    }
}
// الحصول على جميع العناصر التي تحتوي على data-toast
const notificationToasts = document.querySelectorAll("[data-toast]");
const toastCloseBtns = document.querySelectorAll("[data-toast-close]");

// التأكد من وجود عناصر للتنبيهات وأزرار الإغلاق
if (notificationToasts.length && toastCloseBtns.length) {
    // التكرار على كل زر إغلاق
    toastCloseBtns.forEach((closeBtn) => {
        closeBtn.addEventListener("click", function() {
            // إزالة كلاس "show" من جميع عناصر التنبيهات
            notificationToasts.forEach((toast) => {
                toast.classList.remove("show");
            });
        });
    });
}