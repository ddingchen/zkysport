function kuandu(){
    var height=$(window).height();
    var width=$(window).width();
    var shang1=345/750*width;
    var shang2 = $('.shang').height();
    var xia=$(".xia").height();
    var h=height-shang1-shang2-xia-25;
    var bili=1/750*width;
    var ziti=10*bili;
    //var w=width*0.74;
    $(".neirong").css("height",h);
    //$(".neineirong").css("width",w);
    $("html").css("font-size",ziti+"px");
}

$(function(){
    kuandu();
});