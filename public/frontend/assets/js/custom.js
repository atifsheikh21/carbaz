"use strict";

const formWrapper = document.querySelector(".formbold-form-wrapper");
const formActionButton = document.querySelector(".formbold-action-btn");

function chatboxToogleHandler() {
  formWrapper.classList.toggle("active");
  formActionButton.classList.toggle("active");
}

//AOS Animation
$(window).on("scroll", function () {
  AOS.init();
});

$(function () {
  // mobile nav
  const openBtn = document.querySelector("#nav-opn-btn");
  const closeBtn = document.querySelector("#nav-cls-btn");
  const offcanvasContainer = document.querySelector("#offcanvas-nav");

  function openNav() {
    document.body.style.overflowY = "hidden";
    offcanvasContainer.classList.add("open");
  }

  function closeNav() {
    document.body.style.overflowY = "";
    offcanvasContainer.classList.remove("open");
  }

  openBtn.addEventListener("click", openNav);
  closeBtn.addEventListener("click", closeNav);

  // back to top
  $(" .back-to-top ").on("click", function () {
    $("html,body").animate({
      scrollTop: 0,
    });
  });

  $(window).on("scroll", function () {
    var scrolling = $(this).scrollTop();

    if (scrolling > 150) {
      $(".menu-bg").addClass("nav-bg");
    } else {
      $(".menu-bg").removeClass("nav-bg");
    }

    var scrolling = $(this).scrollTop();
    if (scrolling > 200) {
      $(".back-to-top ").fadeIn(500);
    } else {
      $(".back-to-top ").fadeOut(500);
    }
  });

  $(window).on("load", function () {
    if ($(".shafull-container").length > 0) {
      var $grid = $(".shafull-container");
      $grid.shuffle({
        itemSelector: ".shaf-item",
        sizer: ".shaf-sizer",
      });
      /* reshuffle when user clicks a filter item */
      $(".shaf-filter li").on("click", function () {
        // set active class
        $(".shaf-filter li").removeClass("active");
        $(this).addClass("active");
        // get group name from clicked item
        var groupName = $(this).attr("data-group");
        // reshuffle grid
        $grid.shuffle("shuffle", groupName);
      });
    }
  });

  // next-prev-btn

  $(".next-prev-btn li a").on("click", function () {
    // set active class
    $(".next-prev-btn li a").removeClass("active");
    $(this).addClass("active");
  });

  // dashbord-active btn

  $(".dashboard-btn li ").on("click", function () {
    // set active class
    $(".dashboard-btn li ").removeClass("active");
    $(this).addClass("active");
  });

  // feature-slick

  $(".banner-slick").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: true,
    speed: 2500,
    arrows: false,
    dots: true,
    fade: true,
  });

  // feature-slick

  $(".feature-slick").slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    prevArrow: ".feature-slick-prev",
    nextArrow: ".feature-slick-next",
    autoplay: true,
    speed: 2500,
    rtl: $("html").attr("dir") === "rtl",

    responsive: [
      {
        breakpoint: 992,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        },
      },

      {
        breakpoint: 768,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        },
      },

      {
        breakpoint: 576,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
    ],
  });

  // feature-slick
  $(".feature-slick-two").slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    prevArrow: ".feature-slick-prev",
    nextArrow: ".feature-slick-next",
    autoplay: true,
    speed: 2500,

    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 992,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        },
      },

      {
        breakpoint: 768,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
    ],
  });

  // testimonial-slick
  $(".testimonial-slick").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    prevArrow: ".testimonial-slick-prve",
    nextArrow: ".testimonial-slick-next",
    autoplay: true,
    rtl: $("html").attr("dir") === "rtl",
    speed: 2500,

    responsive: [
      {
        breakpoint: 992,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },

      {
        breakpoint: 768,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
    ],
  });

  // $('.trending-slick
  $(".trending-slick").slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    prevArrow: ".trending-slick-prve",
    nextArrow: ".trending-slick-next",
    autoplay: true,
    speed: 2500,

    responsive: [
      {
        breakpoint: 992,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        },
      },

      {
        breakpoint: 768,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
    ],
  });

  // $('.dealer-two-slick
  $(".dealer-two-slick").slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    prevArrow: ".trending-slick-prve",
    nextArrow: ".trending-slick-next",
    autoplay: true,
    speed: 2500,

    responsive: [
      {
        breakpoint: 992,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        },
      },

      {
        breakpoint: 768,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
    ],
  });

  // inventory-details-slick

  $(".inventory-details-slick-for").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
    autoplay: true,
    speed: 1000,
    asNavFor: ".inventory-details-slick-nav",
  });

  $(".inventory-details-slick-nav").slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    asNavFor: ".inventory-details-slick-for",
    dots: true,
    focusOnSelect: true,
    arrows: false,
    dots: false,
    centerMode: true,
    centerPadding: "0px",
    speed: 2000,
    responsive: [
      {
        breakpoint: 992,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        },
      },

      {
        breakpoint: 768,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        },
      },
    ],
  });

  // $('.dealer-two-slick
  $(".payment-method-slick").slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay: true,
    speed: 2500,
    arrows: false,

    responsive: [
      {
        breakpoint: 992,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        },
      },

      {
        breakpoint: 768,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
    ],
  });

  $(".shop-details-slick").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
    asNavFor: ".shop-details-slick-nav",
    autoplay: true,
    speed: 1000,
  });

  $(".shop-details-slick-nav").slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    asNavFor: ".shop-details-slick",
    dots: false,
    arrows: false,
    centerMode: true,
    centerPadding: "0",
    focusOnSelect: true,
    vertical: true,
    speed: 1000,
    responsive: [
      {
        breakpoint: 992,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        },
      },

      {
        breakpoint: 768,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
    ],
  });
});

new VenoBox({
  selector: ".my-video-links",
});
