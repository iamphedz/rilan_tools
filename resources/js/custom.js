$(document).ready(function() {
    $(document).on("click", ".admin-top-nav a", function(e) {
        e.preventDefault();
        $(document)
            .find(".admin-top-nav a")
            .removeClass("active");
        $(this).addClass("active");
    });

    $(document).on("click", ".toggle-search-options", function() {
        var optbutton = $(this);
        $(document)
            .find(".search-options")
            .slideToggle(100, function() {
                if ($(this).is(":hidden")) {
                    optbutton
                        .find("i.fa")
                        .removeClass("fa-caret-up")
                        .addClass("fa-caret-down");
                } else {
                    optbutton
                        .find("i.fa")
                        .removeClass("fa-caret-down")
                        .addClass("fa-caret-up");
                }
            });
    });

    $(document).on("click", ".checkout-page .proceed", function() {
        $(document)
            .find(".checkout-page .guidelines")
            .hide();
        $(document)
            .find(".checkout-page .delivery-form")
            .show();
    });

    $(document).on("click", ".checkout-page .back", function() {
        window.location.href = window.location.origin + "/shopping_cart";
    });

    $(document).on("click", ".img-thumbnail", function() {
        var src = $(this).attr("src");
        $(document)
            .find(".img-preview-modal img")
            .attr("src", src);
        toggleModal();
    });

    $(document).on("click", ".gallery img", function() {
        var gallery = $(document).find(".gallery img");
        var src = $(this).attr("src");
        gallery.removeClass("active");
        $(this).addClass("active");
        $(document)
            .find(".view-product .img-thumbnail")
            .attr("src", src);
    });

    $(document).on("click", ".dropdown", function() {
        $(document)
            .find(".top-nav")
            .toggleClass("hidden");
    });

    $(".cart-icon").hover(
        function() {
            $(document)
                .find(".cart-icon-msg")
                .fadeIn(100);
        },
        function() {
            $(document)
                .find(".cart-icon-msg")
                .fadeOut(100);
        }
    );
});
