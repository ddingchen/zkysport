$(function () {    gaodu();    $(".zf_fs").on("touchstart", function (){        var zf=$(this).attr("data-id");        $(".right").hide();        $("#"+zf).show();        $("#zhifu_fangshi").val(zf);    });});//页面各高度自适应function gaodu() {    var width = $(window).width();    var bili = 1 / 750 * width;//页面比例    var ziti = 10 * bili;//字体自适应参数    $("html").css("font-size", ziti + "px");}