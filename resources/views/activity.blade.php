<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,user-scalable=no" name="viewport">
    <link rel="stylesheet" type="text/css" href="css/huodong.css"/>
    <title>Title</title>
</head>
<body>
<div class="huodong">
    @foreach($activities as $activity)
    <a href="activity/{{$activity->id}}">
        <div>
            <img src="{{$activity->banner}}"/>
        </div>
    </a>
    @endforeach
</div>
</body>
</html>
