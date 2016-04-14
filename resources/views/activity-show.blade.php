<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,user-scalable=no" name="viewport">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/huodong.css?1112453') }}"/>
    <script type="text/javascript" src="{{ asset('js/jquery-1.7.2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/huodong.js') }}"></script>
    <title>活动详情</title>
</head>
<body>
<img src="/uploads/activities/wap/{{$activity->banner}}"/>
<div class="shang">
    <p class="biaoti">{{ $activity->title }}</p>
</div>
<div class="neirong">
    <!-- {!! str_replace(PHP_EOL, '<br/>', $activity->desc) !!} -->
    {!! $activity->desc !!}
</div>
<div class="xia">
    <p class="bmrs">已有{{$numOfJoiners}}人报名</p>
    @if($paid)
    <a href="#">
        <div class="baoming anniu disable ">已报名</div>
    </a>
    @elseif(!$published)
    <a href="#">
        <div class="baoming anniu disable ">即将开启</div>
    </a>
    @elseif($expired)
    <a href="#">
        <div class="baoming anniu disable ">已截止报名</div>
    </a>
    @else
    <a href="/activity/{{ $activity->id }}/join">
        <div class="baoming anniu">立即报名</div>
    </a>
    @endif
</div>
</body>
</html>
