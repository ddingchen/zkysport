<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <link rel="stylesheet" type="text/css" href="{{asset("css/weui.css?1")}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/changdixuanze.css?11') }}"/>
    <title>选择场地</title>
    <script type="text/javascript" src="{{ asset('js/jquery-1.7.2.min.js') }}"></script>
    <script type="text/javascript">
        var leixing='{{$sport}}';//要PHP在这里输出一下,输出var leixing=$_GET('x')
    </script>
    <script type="text/javascript" src="{{ asset('js/changdixuanze.js?2') }}"></script>
</head>
<body onload="hhhhhhhhh()">
<form method="post" action="/sport/{{$sport}}/area">
{{ csrf_field() }}
<!--羽毛球场地-->
<div  hidden class="changdi_x ymq">
    @foreach($sportAreas['badminton'] as $index=>$area)
        @if($index==4||$index==9)
        <div class="@if($area['booked']) ding @elseif($area['selected']) xuan @else kong @endif gekai" data-id="{{$index}}">{{$area['title']}}</div>
        @elseif($index==14)
        <div class="bai gekai">0</div>
        <div class="@if($area['booked']) ding @elseif($area['selected']) xuan @else kong @endif" data-id="{{$index}}">{{$area['title']}}</div>
        @else
        <div class="@if($area['booked']) ding @elseif($area['selected']) xuan @else kong @endif" data-id="{{$index}}">{{$area['title']}}</div>
        @endif
    @endforeach
</div>
<div hidden class="xian">
    <hr class="hr_zuo hr">
    <img src="{{ asset('img/entrance.png') }}">
    <hr class="hr_you hr">
</div>
<!--乒乓球场地-->
<div hidden class="changdi_x ppq">
    @foreach($sportAreas['pingpong'] as $index=>$area)
        @if($index<=4)
        <div class="@if($area['booked']) ding @elseif($area['selected']) xuan @else kong @endif ppq_shang" data-id="{{$index}}"><p>{{$area['title']}}</p></div>
        @else
        <div class="@if($area['booked']) ding @elseif($area['selected']) xuan @else kong @endif ppq_xia" data-id="{{$index}}"><p>{{$area['title']}}</p></div>
        @endif
    @endforeach
</div>
<input name="sport" type="hidden" value="{{$sport}}" />
<input name="areas" type="hidden" id="inp" value="{{$oldInputAreas}}" />
<input class="anniu" style="left:6.5%;" type="submit" value="确定" />
</form>
<div hidden id="loading">
    <div class="weui_toast">
        <div class="weui_loading">
            <div class="weui_loading_leaf weui_loading_leaf_0"></div>
            <div class="weui_loading_leaf weui_loading_leaf_1"></div>
            <div class="weui_loading_leaf weui_loading_leaf_2"></div>
            <div class="weui_loading_leaf weui_loading_leaf_3"></div>
            <div class="weui_loading_leaf weui_loading_leaf_4"></div>
            <div class="weui_loading_leaf weui_loading_leaf_5"></div>
            <div class="weui_loading_leaf weui_loading_leaf_6"></div>
            <div class="weui_loading_leaf weui_loading_leaf_7"></div>
            <div class="weui_loading_leaf weui_loading_leaf_8"></div>
            <div class="weui_loading_leaf weui_loading_leaf_9"></div>
            <div class="weui_loading_leaf weui_loading_leaf_10"></div>
            <div class="weui_loading_leaf weui_loading_leaf_11"></div>
        </div>
        <p style="margin-top: 60%;margin-bottom: 0" class="weui_toast_content">请稍候</p>
    </div>
</div>
</body>
</html>
