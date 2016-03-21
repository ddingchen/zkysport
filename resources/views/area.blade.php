<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/changdixuanze.css') }}"/>
    <title>Title</title>
    <script type="text/javascript" src="{{ asset('js/jquery-1.7.2.min.js') }}"></script>
    <script type="text/javascript">
        var leixing=1;//要PHP在这里输出一下,输出var leixing=$_GET('x')
    </script>
    <script type="text/javascript" src="{{ asset('js/changdixuanze.js') }}"></script>
</head>
<body onload="hhhhhhhhh()">
<form method="get" action="/sport">
<!--羽毛球场地-->
<div  hidden class="changdi_x ymq">
    <div class="kong" data-id="1">1</div>
    <div class="ding" data-id="2">2</div>
    <div class="kong" data-id="3">3</div>
    <div class="kong gekai" data-id="10">10</div>
    <div class="kong" data-id="11">11</div>
    <div class="kong" data-id="4">4</div>
    <div class="kong" data-id="5">5</div>
    <div class="kong" data-id="6">6</div>
    <div class="kong gekai" data-id="12">12</div>
    <div class="kong" data-id="13">13</div>
    <div class="kong" data-id="7">7</div>
    <div class="ding" data-id="8">8</div>
    <div class="kong" data-id="9">9</div>
    <div class="bai gekai">0</div>
    <div class="kong" data-id="14">14</div>
</div>
<div hidden class="xian">
    <hr class="hr_zuo hr">
    <img src="{{ asset('img/entrance.png') }}">
    <hr class="hr_you hr">
</div>
<!--乒乓球场地-->
<div hidden class="changdi_x ppq">
    <div class="kong ppq_shang" data-id="1"><p>1</p></div>
    <div class="kong ppq_shang" data-id="2"><p>2</p></div>
    <div class="kong ppq_shang" data-id="3"><p>3</p></div>
    <div class="kong ppq_shang" data-id="4"><p>4</p></div>
    <div class="kong ppq_xia" data-id="5"><p>5</p></div>
    <div class="kong ppq_xia" data-id="6"><p>6</p></div>
    <div class="kong ppq_xia" data-id="7"><p>7</p></div>
    <div class="kong ppq_xia" data-id="8"><p>8</p></div>
    <div class="kong ppq_xia" data-id="9"><p>9</p></div>
    <div class="kong ppq_xia" data-id="10"><p>10</p></div>
</div>
<input name="area_id_list" type="hidden" id="inp" type="text"/>
<input class="anniu" style="left:6.5%" type="submit" value="确定" />
</form>
</body>
</html>
