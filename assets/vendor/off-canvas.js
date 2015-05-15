$(document).ready(function () {

    var container = $(".app"), canvasDirection, w = $(window), delay = 300;

    function closeOffCanvas(delay) {
        setTimeout(function(){
          container.removeClass("off-canvas");
          canvasDirection = "";
        }, delay);
    }

    $(".main-content").append('<div class="site-overlay"></div>');

    $(document).on("click", "[data-toggle=off-canvas]", function (e) {

        e.preventDefault();

        if (w.width() >= 767 ) {
            delay = 0;
        }

        if ($(this).data("move") === canvasDirection) {
            closeOffCanvas(delay);
        }

        if ($(this).data("move") !== undefined && $(this).data("move") === "rtl") {
            container.toggleClass("move-right").removeClass("move-left");
            canvasDirection = "rtl";
        } else {
            container.toggleClass("move-left").removeClass("move-right");
            canvasDirection = "ltr";
        }

        if (container.hasClass("move-right") || container.hasClass("move-left")) {
            container.addClass("off-canvas");
        }

    });


    $(document).on("click", ".main-navigation .dropdown-menu > li > a", function () {

        if (w.width() <= 767 ) {

            if (container.hasClass("move-right") || container.hasClass("move-left")) {
                closeOffCanvas(delay);
            }

            if (container.hasClass("move-right")) {
                container.toggleClass("move-right");
            }

            if (container.hasClass("move-left")) {
                container.toggleClass("move-left");
            }
        }
    });

    $(".main-content").on("click", ".site-overlay", function (e) {

        e.preventDefault();

        if (w.width() >= 767) {
            delay = 0;
        }

        if (container.hasClass("move-right") || container.hasClass("move-left")) {
            closeOffCanvas(delay);
        }

        if (container.hasClass("move-right")) {
            container.toggleClass("move-right");
        }

        if (container.hasClass("move-left")) {
            container.toggleClass("move-left");
        }
    });

});
