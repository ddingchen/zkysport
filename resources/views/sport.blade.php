<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <link rel="stylesheet" type="text/css" href="css/changguanyuding.css?1"/>
    <title>Title</title>
    <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="js/iscroll.js"></script>
    <script type="text/javascript">
    </script>
    <script type="text/javascript" src="js/changguanyuding.js"></script>
</head>
<body onload="load()">
<form method="post" action="sport">
{{ csrf_field() }}
<div id="kongbai" style="position:absolute;width:100%;height:1000px;z-index: 9990;background-color: rgba(0,0,0,0);"></div>
<div id="xiangmu" style="z-index: 9999;">
    <div class="cgyd2 div_h " style="background-color: rgba(0,0,0,0);color: rgba(0,0,0,0); ">
        a
    </div>
    <div class="cgyd2 div_h cgyd2_xm" data-id="1">
        <img src="img/Badminton.png">
        羽毛球
    </div>
    <hr>
    <div class="cgyd2 div_h cgyd2_xm" data-id="2">
        <img src="img/Table%20tennis.png">
        乒乓球
    </div>
    <hr>
    <div class="cgyd2 div_h cgyd2_xm" data-id="3">
        <img src="img/basketball.png">
        篮球
    </div>
    <hr>
    <!--<div class="cgyd2 div_h cgyd2_xm" data-id="4">
        <img src="img/football.png">
        足球
    </div>-->
    <div class="cgyd2 " style="background-color: rgba(0,0,0,0.5);height: 1000px">

    </div>

</div>
<div class="cgyd1 div_h cgyd1_1" id="xm">
    选择运动项目
</div>
<div class="cgyd1">
    <div class="cgyd2 div_h">
        <img class="img" src="img/user.png"/>
        <input name="name" id="input1" class="input" type="text" value="{{ old('name') }}" placeholder="预约人姓名">
    </div>
    <hr>
    <div class="cgyd2 div_h">
        <img class="img" src="img/mobile.png"/>
        <input name="tel" id="input2" class="input" type="text" value="{{ old('tel') }}" placeholder="预约电话" value="">
    </div>
</div>
<div class="cgyd1" style="margin-bottom: 1px;">
    <div id="renshu" class="cgyd2 div_h cgyd2_3">
        <img class="img" src="img/Number%20of%20people.png"/>
        <a>
            预约人数
        </a>
        <div class="num num_zuo" onclick="num(-1)"><img src="img/Group%202.png"></div>
        <input name="num" class="num num_zhong" id="number" type="text" value="2">
        <div class="num num_you" onclick="num(1)"><img src="img/Group.png"></div>
    </div>
    <hr>
    <div class="cgyd2 div_h">
        <img class="img" src="img/time%20icon.png"/>
        <a>
            预约时间
        </a>
        <div id="day" class="time" type="text">今天</div>
        <div id="time1" class="time" type="text">00:00</div>
        <a style="width: 3%;height: 2px;background-color: #D1D1D1;color: #D1D1D1;line-height: 0;margin: 6% 0.5% 0 -1.8%;"></a>
        <div id="time2" class="time" type="text">00:00</div>
    </div>
</div>
<div class="cgyd1 div_h" onclick="xzcd()"><!--<=====================选择场地按钮,整个DIV都是-->
    <img class="img" src="img/information.png"/>
    选择场地
    <div class="cgyd1_4_you cgyd1_4_you_2">
        <img src="img/Shape.png">
    </div>
    <p id="changdi_haoma" class="cgyd1_4_you">{{ $selectedAreaNames }}</p>
</div>
<input name="sport_id" type="hidden" id="leixing" value="{{ old('sport_id') }}"><!--//运动场地类型参数,羽毛球1,乒乓球2,篮球3,足球4-->
<input name="date" type="hidden" id="xxx" value="{{ old('date') }}"><!--//时间 天-->
<input name="from" type="hidden"  id="yyy" value="{{ old('from') }}"><!--//开始时间-->
<input name="to" type="hidden" id="zzz" value="{{ old('to') }}"><!--//结束时间-->
<input name="areas" type="hidden" id="changdi" value="{{ $selectedAreas }}"><!--//场地号-->
<input class="anniu" style="left:0" type="submit" value="提交预约" />
<div class="gunlun">
    <div style="position: absolute;bottom: 0;height: 250px;width: 100%;background-color: #ffffff;">
        <div class="gunlun_anniu">
            <div class="gunlun_queren">确认</div>
            <div class="gunlun_quxiao">取消</div>
        </div>
        <div id="gundong1" class="gundong">
            <ul>
                <li></li>
                <li></li>
                <li>今天</li>
                <li>明天</li>
                <li>后天</li>
                <li></li>
                <li></li>
            </ul>
        </div>
        <div id="gundong2" class="gundong">
            <ul>
                <li></li>
                <li></li>
                <li>9:00</li>
                <li>10:00</li>
                <li>11:00</li>
                <li>12:00</li>
                <li>13:00</li>
                <li>14:00</li>
                <li>15:00</li>
                <li>16:00</li>
                <li>17:00</li>
                <li>18:00</li>
                <li>19:00</li>
                <li>20:00</li>
                <li>21:00</li>
                <li></li>
                <li></li>
            </ul>
        </div>
        <div id="gundong3" class="gundong">
            <ul>
                <li></li>
                <li></li>
                <li>10:00</li>
                <li>11:00</li>
                <li>12:00</li>
                <li>13:00</li>
                <li>14:00</li>
                <li>15:00</li>
                <li>16:00</li>
                <li>17:00</li>
                <li>18:00</li>
                <li>19:00</li>
                <li>20:00</li>
                <li>21:00</li>
                <li>22:00</li>
                <li></li>
                <li></li>
            </ul>
        </div>
        <hr style="position: absolute;bottom: 150px;width: 100%;height: 1px;background-color: #FFE240">
        <hr style="position: absolute;bottom: 100px;width: 100%;height: 1px;background-color: #FFE240">
    </div>
</div>
<div hidden id="changdi3" class="changdi">
    @foreach($areaSelects[3] as $i=>$option)
    <div data-id="{{ $option['code'] }}" style="bottom: {{ (count($areaSelects[3])-$i)*50 }}px" >{{ $option['title'] }}</div>
    <hr style="position: absolute;bottom: 50px;width: 100%;height: 1px;">
    @endforeach
    <div style="bottom: 0" >取消</div>
</div>
<div hidden id="changdi4" class="changdi">
    <div style="bottom: 100px" >5人场(5v5)</div>
    <hr style="position: absolute;bottom: 100px;width: 100%;height: 1px;">
    <div style="bottom: 50px" >7人场(7v7)</div>
    <hr style="position: absolute;bottom: 50px;width: 100%;height: 1px;">
    <div style="bottom: 0" >取消</div>
</div>
</form>
</body>
</html>