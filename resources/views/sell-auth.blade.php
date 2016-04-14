<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>销售人员身份确认</title>
    <link rel="stylesheet" href="{{asset('css/weui.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/weui.example.min.css')}}"/>
</head>
<body ontouchstart>
    <div class="container js_container">
        <div class="page slideIn cell">
            <div class="hd">
                <h1 class="page_title">身份确认</h1>
            </div>
            <div class="bd">
                <form method="post">
                    {{csrf_field()}}
                    <div class="weui_cells weui_cells_form">
                        <div class="weui_cell">
                            <div class="weui_cell_hd"><label class="weui_label">姓名</label></div>
                            <div class="weui_cell_bd weui_cell_primary">
                                <input name="name" class="weui_input" type="text" value="{{old('name')}}" placeholder="请输入姓名">
                            </div>
                        </div>
                    </div>
                    <div class="weui_cells_tips">该姓名将用来确认您的销售成果</div>
                    <div class="weui_btn_area">
                        <input class="weui_btn weui_btn_primary" type="submit" value="确定"/>
                    </div>
                </form>
            </div>
            @if(count($errors)>0)
            <div class="weui_dialog_alert" id="alert">
                <div class="weui_mask"></div>
                <div class="weui_dialog">
                    <div class="weui_dialog_hd"><strong class="weui_dialog_title">输入信息验证失败</strong></div>
                    <div class="weui_dialog_bd">{{$errors->first()}}</div>
                    <div class="weui_dialog_ft">
                        <a href="javascript:;" class="weui_btn_dialog primary" onclick="$('#alert').hide()">确定</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    <script type="text/javascript" src="{{asset('js/zepto.min.js')}}"></script>
</body>
</html>
