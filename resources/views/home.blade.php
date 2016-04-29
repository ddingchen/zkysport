<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,user-scalable=no" name="viewport">
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="js/jquery.flexslider-min.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
    <title>中铠城市运动公园</title>
    <script type="text/javascript">
        (function(window, location) {
            history.replaceState(null, document.title, location.pathname+"#!/stealingyourhistory");
            history.pushState(null, document.title, location.pathname);

            window.addEventListener("popstate", function() {
              if(location.hash === "#!/stealingyourhistory") {
                history.replaceState(null, document.title, location.pathname);
                setTimeout(function(){
                  location.replace("/");
                },0);
              }
            }, false);
        }(window, location));
    </script>
</head>
<body>
<div class="index_1 index_1_height width_full">
    <div class="index_1_zuo index_1_height">
        <div class="index_1_touxiang">
            <img class="width_full" src="{{$avatar}}">
        </div>
        <div class="index_1_zuo_mingzi">
            <a class="mingzi">{{$nickname}}</a><br>
            <!--<a class="julebu">无锡中铠俱乐部</a><br>-->
        </div>
    </div>
    <div class="index_1_you index_1_height">
        <a href="/history/account/recharge"><div class=""><o>余额</o><b>{{floatval($amount)}}</b></div></a>
        <hr>
        <a href="/user/card"><div class=""><o>虚拟卡</o><b>{{$cardCount}}</b></div></a>
        <hr>
        <div class=""><o>积分</o><b>0</b></div>
    </div>
</div>
<div class="index_2 width_full index_2_height">
    <a href="/sport">
        <div><img src="img/yuding.png"><br>场馆预定</div>
    </a>
    <hr class="index_2_height">
    <a href="activity">
        <div><img src="img/shangcheng.png"><br>比赛活动</div>
    </a>
    <hr class="index_2_height">
    <a href="vip">
        <div><img src="img/vip.png"><br>购买会员卡</div>
    </a>
    <hr style="width: 100%;height: 1px;">
    <a href="user/card">
        <div><img src="img/xunikaicon.png"><br>我的会员卡</div>
    </a>
    <hr class="index_2_height">
    <a href="/history/book/finish">
        <div><img src="img/dingdanicon.png"><br>我的订单</div>
    </a>
    <hr class="index_2_height">
    <a>
        <div><img src="img/kefuicon.png"><br>客服咨询</div>
    </a>
    <div id="lunbo" class="flexslider">
        <ul class="slides">
            <li class="li">
                    <img src="img/001.jpg">
            </li>

            <li class="li">
                    <img src="img/001.jpg">
            </li>

            <li class="li">
                    <img src="img/001.jpg">
            </li>
        </ul>
    </div>
</div>
</body>
</html>
