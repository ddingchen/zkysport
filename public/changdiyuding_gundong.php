<!DOCTYPE html><html><head>    <meta charset="UTF-8">    <meta name="viewport"          content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>    <meta name="apple-mobile-web-app-capable" content="yes">    <meta name="apple-mobile-web-app-status-bar-style" content="black">    <link rel="stylesheet" type="text/css" href="css/weui.css?1"/>    <link rel="stylesheet" type="text/css" href="css/changguanyuding.css?1"/>    <style>        #gundong3{            width: 100%;        }        body{            background-color: rgba(0,0,0,0);        }        html{            font-size: 34%;            background-color: rgba(0,0,0,0);        }        #gundong3 li{            font-size: 3.6rem;        }    </style>    <title>Title</title>    <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>    <script type="text/javascript" src="js/iscroll.js"></script>    <script type="text/javascript">        var gundong_3;        var z;        $(function () {            gaodu();            gundong_3 = new iScroll("gundong3", {                snap: "li",                momentum: true,                hScrollbar: false,                vScrollbar: false,                hScroll: false, //是否水平滚动                bounce: false, //是否超过实际位置反弹                wheelAction: 'scroll', //鼠标滚动行为（还可以是zoom）                 onTouchEnd: function () {                     z = gundong_3.currPageY+3;                     z = $("#gundong3 ul li:nth-child(" + z + ")").html();                     if(!z){                         z='22:00';                     }                     $("#gg3").val(z);                 }, //在滚动完成后的回调            });        });        function gaodu() {            var width = $(window).width();            var bili = 1 / 750 * width;//页面比例            var ziti = 31.5 * bili;//字体自适应参数            $("html").css("font-size", ziti + "px");        }    </script></head><div id="gundong3" class="gundong"><ul>    <li></li>    <li></li>    <?php    $x = $_GET['i'];    for ($i = $x; $i < 13; $i++) {        $o = $i + 10;        echo '<li>' . $o . ':00</li>';    }    ?>    <li></li>    <li></li></ul><input hidden name="gg3" id="gg3" value="<?php echo ($x+10).':00';?>"></div>