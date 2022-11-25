// Management of the hero's image height according to the screen height
$(".header").css({ height: ($(window).height() + "px") });

$(window).on("resize", function() {
  $(".header").css({ height: $(window).height() + "px" });
});