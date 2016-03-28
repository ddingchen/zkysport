<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,user-scalable=no" name="viewport">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/huodong.css?11') }}"/>
    <script type="text/javascript" src="{{ asset('js/jquery-1.7.2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/huodong.js') }}"></script>
    <title>活动详情</title>
</head>
<body>
<img src="/uploads/activities/originals/{{$activity->banner}}"/>
<div class="shang">
    <p class="biaoti">－{{ $activity->title }}－</p>
</div>
<div class="neirong">
    {{ $activity->desc }}
</div>
<div class="xia">
    <p class="bmrs">已有20人报名</p>
    @if($paid)
    <a href="#">
        <div class="baoming anniu disable ">已报名</div>
    </a>
    @else
    <a href="/activity/{{ $activity->id }}/join">
        <div class="baoming anniu">立即报名</div>
    </a>
    @endif
</div>
</body>
</html>
