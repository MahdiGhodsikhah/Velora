$(document).ready(function () {
    // اسلایدر داخلی هر کارت
    $(".product-slider").slick({
        infinite: true,
        dots: true,
        speed: 400,
        autoplay: true,
        autoplaySpeed: 2800,
        rtl: true,
        arrows: false,           //arrow
        slidesToShow: 1,
        slidesToScroll: 1,
        fade: true,
        cssEase: 'cubic-bezier(0.4, 0, 0.2, 1)',
        swipe: true
    });
    // اسلایدر اصلی محصولات
    $(".main-product-slider").slick({
        infinite: true,
        rtl: true,
        dots: false,
        arrows: true,
        prevArrow: '<button type="button" class="slick-prev custom-arrow"><span>❮</span></button>',
        nextArrow: '<button type="button" class="slick-next custom-arrow"><span>❯</span></button>',
        speed: 500,
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        draggable: true,
        adaptiveHeight: false,
        responsive: [
            { breakpoint: 992, settings: { slidesToShow: 2 } },
            { breakpoint: 576, settings: { slidesToShow: 1 } }
        ]
    });
    // انیمیشن قلب
    $(".wishlist").on("click", function () {
        $(this).toggleClass("active");
    });
});