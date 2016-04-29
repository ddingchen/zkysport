<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="x-rim-auto-match" content="none">
    <link rel="stylesheet" type="text/css" href="{{asset("css/jilu.css?1")}}"/>
    <script type="text/javascript" src="{{asset("js/jquery-1.7.2.min.js")}}"></script>
    <script type="">
        //X和Y的值,需要PHP输出,X代表目录类型(黄色选项),Y代表下面信息类型(灰色选项)这两个值在PHP部分应该也很重要
        //PS:X和Y必须数字,不能是字符
        var X = 1 ;//X=$_GET('X')
        var Y = 1 ;//Y=$_GET('Y')
    </script>
    <script type="text/javascript" src="{{asset("js/jilu.js")}}"></script>
    <title>Title</title>
</head>
<body>
<div class="mulu">
    <div class="xiangmu">
        <a href="#?X=1&Y=1">
            <div href="#?X=1&Y=1">场馆预订</div>
        </a>
        <a href="#?X=2&Y=1">
            <div class="xiangmu_xuan">余额</div>
        </a>
        <a href="#?X=3&Y=1">
            <div>活动</div>
        </a>
    </div>
    <div class="leixing">
        <a id="y1" href="#?X=1&Y=1">
            <div class="xuan">历史预订</div>
        </a>
        <a id="y2" href="#?X=1&Y=2">
            <div class="hui">未完成预订</div>
        </a>
    </div>
</div>
<div class="neirong">
    <p hidden class="meiyou">您当前没有任何记录</p>
    <!--场馆预订-->
    <div class="yuding biaoqian">
        <div class="yd1">
            <a>预订号:11111111111111s1111x111</a>
            <a class="right">下单时间:1111-11-11 00:00:00</a>
        </div>
        <div class="yd_nr">
            <p>姓名:&nbsp;&nbsp;小清新</p>
            <p>电话:&nbsp;&nbsp;11111111111</p>
            <p>场地:&nbsp;&nbsp;1号/2号羽毛球场1111111111111</p>
            <p>时间:&nbsp;&nbsp;2015年00月00日 00:00-00:00 </p>
            <p>状态:&nbsp;&nbsp;
                <a hidden class="yiguanbi">已关闭</a><!--4个A标签,按需要只需要显示一个-->
                <a hidden class="yiwancheng">已完成</a>
                <a class="yiyuding">已预订</a>
                <a hidden class="daizhifu">待支付</a>
            </p>
        </div>
    </div>
    <!--场馆预订结束-->
    <!--场馆预订再来一次-->
    <div class="yuding biaoqian">
        <div class="yd1">
            <a>预订号:11111111111111s1111x111</a>
            <a class="right">下单时间:1111-11-11 00:00:00</a>
        </div>
        <div class="yd_nr">
            <p>姓名:&nbsp;&nbsp;小清新</p>
            <p>电话:&nbsp;&nbsp;11111111111</p>
            <p>场地:&nbsp;&nbsp;1号/2号羽毛球场1111111111111</p>
            <p>时间:&nbsp;&nbsp;2015年00月00日 00:00-00:00 </p>
            <p>状态:&nbsp;&nbsp;
                <a hidden class="yiguanbi">已关闭</a><!--4个A标签,按需要只需要显示一个-->
                <a hidden class="yiwancheng">已完成</a>
                <a class="yiyuding">已预订</a>
                <a hidden class="daizhifu">待支付</a>
            </p>
        </div>
    </div>
    <!--场馆预订再来一次结束-->
    <!--活动-->
    <a href="http://site.baidu.com/cool.html">
    <div class="yue biaoqian">
        <div class="yd1">
            <a>订单号:11111111111111s1111x111</a>
            <a class="right">报名时间:1111-11-11 00:00:00</a>
        </div>
        <div class="yd_nr">
            <p>活动名称:&nbsp;&nbsp;中铠空间站120小时测试性居住活动</p>
            <p>报名信息:&nbsp;&nbsp;姓名:吴飞宇/年龄:28岁/手机号:13921105502</p>
            <p>报名收费:&nbsp;&nbsp;88888888元</p>
            <p>状态:&nbsp;&nbsp;
                <a class="yiguanbi">已关闭</a><!--4个A标签,按需要只需要显示一个-->
                <a hidden class="yiwancheng">已完成</a>
                <a hidden class="yiyuding">已预订</a>
                <a hidden class="daizhifu">待支付</a>
            </p>
        </div>
    </div>
    </a>
    <!--活动结束-->
    <!--活动再来一次-->
    <a href="http://site.baidu.com/cool.html">
        <div class="yue biaoqian">
            <div class="yd1">
                <a>订单号:11111111111111s1111x111</a>
                <a class="right">报名时间:1111-11-11 00:00:00</a>
            </div>
            <div class="yd_nr">
                <p>活动名称:&nbsp;&nbsp;中铠空间站120小时测试性居住活动</p>
                <p>报名信息:&nbsp;&nbsp;姓名:吴飞宇/年龄:28岁/手机号:13921105502</p>
                <p>报名收费:&nbsp;&nbsp;88888888元</p>
                <p>状态:&nbsp;&nbsp;
                    <a class="yiguanbi">已关闭</a><!--4个A标签,按需要只需要显示一个-->
                    <a hidden class="yiwancheng">已完成</a>
                    <a hidden class="yiyuding">已预订</a>
                    <a hidden class="daizhifu">待支付</a>
                </p>
            </div>
        </div>
    </a>
    <!--活动再来一次结束-->
    <!--余额-->
        <div class="huodong biaoqian2">
            <div class="yue">
                <p>
                    <x class="heizi">预定-篮球-钻石会员卡</x>
                    <x class="right huizi" name="zzz">1111-11-11</x>
                </p>
                <p>
                    <x class="huizi">余额:999999.00</x>
                    <x class="right hongzi">-100.00</x>
                </p>
            </div>
        </div>
    <!--余额结束-->
    <!--余额再来一次-->
    <div class="huodong biaoqian2">
        <div class="yue">
            <p>
                <x class="heizi">预定-篮球-钻石会员卡</x>
                <x class="right huizi" name="zzz">1111-11-11</x>
            </p>
            <p>
                <x class="huizi">余额:999999.00</x>
                <x class="right hongzi">-100.00</x>
            </p>
        </div>
    </div>
    <!--余额再来一次结束-->
    <!--余额再来一次-->
    <div class="huodong biaoqian2">
        <div class="yue">
            <p>
                <x class="heizi">预定-篮球-钻石会员卡</x>
                <x class="right huizi" name="zzz">1111-11-11</x>
            </p>
            <p>
                <x class="huizi">余额:999999.00</x>
                <x class="right hongzi">-100.00</x>
            </p>
        </div>
    </div>
    <!--余额再来一次结束-->
    <!--余额再来一次-->
    <div class="huodong biaoqian2">
        <div class="yue">
            <p>
                <x class="heizi">预定-篮球-钻石会员卡</x>
                <x class="right huizi" name="zzz">1111-11-11</x>
            </p>
            <p>
                <x class="huizi">余额:999999.00</x>
                <x class="right hongzi">-100.00</x>
            </p>
        </div>
    </div>
    <!--余额再来一次结束-->
    <!--余额再来一次-->
    <div class="huodong biaoqian2">
        <div class="yue">
            <p>
                <x class="heizi">预定-篮球-钻石会员卡</x>
                <x class="right huizi" name="zzz">1111-11-11</x>
            </p>
            <p>
                <x class="huizi">余额:999999.00</x>
                <x class="right hongzi">-100.00</x>
            </p>
        </div>
    </div>
    <!--余额再来一次结束-->
    <!--余额再来一次-->
    <div class="huodong biaoqian2">
        <div class="yue">
            <p>
                <x class="heizi">预定-篮球-钻石会员卡</x>
                <x class="right huizi" name="zzz">1111-11-11</x>
            </p>
            <p>
                <x class="huizi">余额:999999.00</x>
                <x class="right hongzi">-100.00</x>
            </p>
        </div>
    </div>
    <!--余额再来一次结束-->
    <!--余额再来一次-->
    <div class="huodong biaoqian2">
        <div class="yue">
            <p>
                <x class="heizi">预定-篮球-钻石会员卡</x>
                <x class="right huizi" name="zzz">1111-11-11</x>
            </p>
            <p>
                <x class="huizi">余额:999999.00</x>
                <x class="right hongzi">-100.00</x>
            </p>
        </div>
    </div>
    <!--余额再来一次结束-->
    <!--余额再来一次-->
    <div class="huodong biaoqian2">
        <div class="yue">
            <p>
                <x class="heizi">预定-篮球-钻石会员卡</x>
                <x class="right huizi" name="zzz">1111-11-11</x>
            </p>
            <p>
                <x class="huizi">余额:999999.00</x>
                <x class="right hongzi">-100.00</x>
            </p>
        </div>
    </div>
    <!--余额再来一次结束-->
</div>
</body>
</html>