<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,user-scalable=no" name="viewport">
    <link rel="stylesheet" type="text/css" href="css/huodong.css"/>
    <title>比赛活动</title>
</head>
<body>
<div class="huodong">
    @foreach($activities as $activity)
    <a href="activity/{{$activity->id}}">
        <div>
            <img @if($activity->expired) class="gray" @endif src="/uploads/activities/wap/{{$activity->banner}}"/>
        </div>
    </a>
    @endforeach
</div>
</body>
</html>
