$(document).ready(function () {
  function removeAllSidebarToggleClass() {
    $("#sidebar-toggle-hide").removeClass("d-md-inline");
    $("#sidebar-toggle-hide").removeClass("d-none");
    $("#sidebar-toggle-show").removeClass("d-inline");
    $("#sidebar-toggle-show").removeClass("d-md-none");
  }

  $("#sidebar-toggle-hide").click(function () {
    // Stop all ongoing animations and close sidebar
    $(".sidebar-group-link")
      .children(".sidebar-dropdown")
      .stop(true, true)
      .slideUp();
    $(".sidebar-group-link").removeClass("sidebar-group-link-active");
    $("#sidebar").fadeOut(300);
    $("#main-body").animate({ width: "100%" }, 300);
    setTimeout(function () {
      removeAllSidebarToggleClass();
      $("#sidebar-toggle-hide").addClass("d-none");
      $("#sidebar-toggle-show").removeClass("d-none");
    }, 300);
  });

  $("#sidebar-toggle-show").click(function () {
    $("#sidebar").fadeIn(300);
    setTimeout(function () {
      removeAllSidebarToggleClass();
      $("#sidebar-toggle-hide").removeClass("d-none");
      $("#sidebar-toggle-show").addClass("d-none");
    }, 300);
  });

  $("#menu-toggle").click(function () {
    $("#body-header").toggle(300);
  });

  $("#search-toggle").click(function () {
    $("#search-toggle").removeClass("d-md-inline");
    $("#search-area").addClass("d-md-inline");
    $("#search-input").animate({ width: "12rem" }, 300);
  });

  $("#search-area-hide").click(function () {
    $("#search-input").animate({ width: "0" }, 300);
    setTimeout(function () {
      $("#search-toggle").addClass("d-md-inline");
      $("#search-area").removeClass("d-md-inline");
    }, 300);
  });

  $("#header-notification-toggle").click(function () {
    $("#header-notification").fadeToggle();
  });

  $("#header-comment-toggle").click(function () {
    $("#header-comment").fadeToggle();
  });

  $("#header-profile-toggle").click(function () {
    $("#header-profile").fadeToggle();
  });

  $(".sidebar-group-link").click(function () {
    // Stop ongoing animations before toggling
    $(this).children(".sidebar-dropdown").stop(true, true).slideToggle();
    $(this).toggleClass("sidebar-group-link-active");
    $(this)
      .children(".sidebar-dropdown-toggle")
      .children(".angle")
      .toggleClass("fa-angle-down fa-angle-left");

    // Close other open dropdowns
    $(".sidebar-group-link").not(this).removeClass("sidebar-group-link-active");
    $(".sidebar-group-link")
      .not(this)
      .children(".sidebar-dropdown-toggle")
      .children(".angle")
      .removeClass("fa-angle-down")
      .addClass("fa-angle-left");
    $(".sidebar-group-link")
      .not(this)
      .children(".sidebar-dropdown")
      .stop(true, true)
      .slideUp();
  });

  // Prevent closing of sidebar dropdowns when clicking on a child link
  $(".sidebar-dropdown a").click(function (event) {
    event.stopPropagation();
  });

  // Close header comment section when clicking outside
  $(document).click(function (event) {
    if (
      !$(event.target).closest("#header-comment-toggle, #header-comment").length
    ) {
      $("#header-comment").fadeOut();
    }
  });

  $("#full-screen").click(function () {
    toggleFullScreen();
  });

  function toggleFullScreen() {
    if (
      (document.fullScreenElement && document.fullScreenElement !== null) ||
      (!document.mozFullScreen && !document.webkitIsFullScreen)
    ) {
      if (document.documentElement.requestFullscreen) {
        document.documentElement.requestFullscreen();
      } else if (document.documentElement.mozRequestFullscreen) {
        document.documentElement.mozRequestFullscreen();
      } else if (document.documentElement.webkitRequestFullscreen) {
        document.documentElement.webkitRequestFullscreen(
          Element.ALLOW_KEYBOARD_INPUT
        );
      }
      $("#screen-compress").removeClass("d-none");
      $("#screen-expand").addClass("d-none");
    } else {
      if (document.cancelFullScreen) {
        document.cancelFullScreen();
      } else if (document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
      } else if (document.webkitCancelFullScreen) {
        document.webkitCancelFullScreen();
      }
      $("#screen-compress").addClass("d-none");
      $("#screen-expand").removeClass("d-none");
    }
  }

  // Ensure header shows correctly when resizing window
  $(window).resize(function () {
    if ($(window).width() > 768) {
      $("#body-header").show();
    }
  });
});
