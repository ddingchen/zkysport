$(function () {
    lunbo();
    //$("li").css("width","100%");
    gaodu();
});
//轮播广告(默认3个数量增加的话控制器显示会略微偏移)
function lunbo(){
    $('#lunbo').flexslider({
        slideToStart: 0, //Integer:  (0 = first slide)初始化第一次显示图片位置
        animation: '.slides li img',
        slideshowSpeed: 5000,
        slideToStart: 1, //Integer:  (0 = first slide)初始化第一次显示图片位置
        directionNav: false,//是否显示左右控制按钮
        controlNav: true,//是否显示控制菜单
        slideshow: true, //载入页面时，是否自动播放
        manualControlEvent: "click", //String:自定义导航控制触发事件:默认是click,可以设定hover
    });
}

//自适应高度
function gaodu(){
    //var height=$(window).height();
    var width=$(window).width();
    var bili=1/750*width;//页面比例
    var ziti=10*bili;//字体自适应参数
    var h1=240*bili;//上半部各div高度
    var h2=250*bili;//下半部各div高度
    var h_x=width*0.052;
    var h3=(h1-2)/3-h_x;//上半部小div高度
    // 轮播控制器各种比例
    var w_dian=20*bili;//圆点直径
    var l_dian=12*bili;//圆点间距
    var t_dian=-40*bili;//圆点高度
    var k_dian=321*bili;//圆点横向位置
    $("html").css("font-size",ziti+"px");
    $(".index_1_height").css("height",h1);
    $(".index_2_height").css("height",h2);
    $(".index_1_you div").css("height",h3)
    $(".flex-control-paging  li a").css("width",w_dian);
    $(".flex-control-paging  li a").css("height",w_dian);
    $(".flex-control-paging  li a").css("margin-left",l_dian);
    $(".flex-control-paging  li a").css("top",t_dian);
    $("#lunbo .flex-control-nav").css("margin-left",k_dian);
}