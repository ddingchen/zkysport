//页面各高度自适应var tongyi_quanju=1;$(function () {    gaodu();    panduan();    $('#tongyi').on('touchstart',function(){        if(tongyi_quanju==1){            $('.fang').addClass('an');            tongyi_quanju='';        }else {            $('.fang').removeClass('an');            tongyi_quanju=1;        }    });    $('.anniu').on('touchstart',function(){        if(!tongyi_quanju){            alert('请您先阅读并同意我们的章程!');            return false;        }    });});function gaodu() {    var width = $(window).width();    var bili = 1 / 750 * width;//页面比例    var ziti = 10 * bili;//字体自适应参数    $("html").css("font-size", ziti + "px");}function panduan(){    var leixing=$('#dengji').val();    switch (leixing){        case '1':            break;        case '2':            $(".lan").addClass('hong');            $(".hong").removeClass('lan');            $(".zhe").html('8');            $(".zuqiu").html('270');            $(".div p o").html('家庭卡会员特权');            $('.shoujia .zi').html('1500');            // $('img.tou').attr("src",asset+'img/vip/goumai_jiating.png');            break;        case '3':            $(".lan").addClass('hei');            $(".hei").removeClass('lan');            $(".zhe").html('7');            $(".zuqiu").html('240');            $(".div p o").html('钻石卡会员特权');            $('.shoujia .zi').html('5000');            // $('img.tou').attr("src",asset+'img/vip/goumai_zuanshi.png');            break;    }}