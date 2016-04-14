<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>销售产品列表</title>
    <link rel="stylesheet" href="{{asset('css/weui.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/weui.example.min.css')}}"/>
</head>
<body ontouchstart>
    <div class="container js_container">
        <div class="page slideIn cell">
            <div class="hd">
                <h1 class="page_title">历史记录</h1>
            </div>
            <div class="bd">
                <div class="weui_cells">
                    @foreach($records as $record)
                    <div class="weui_cell">
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>¥ {{$record['amount']}}</p>
                        </div>
                        <div class="weui_cell_ft">{{$record['name']}} {{$record['tel']}}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>
</html>
