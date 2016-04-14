<!DOCTYPE html><html lang="en"><head>    <meta charset="UTF-8">    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">    <meta name="csrf-token" content="{{ csrf_token() }}">    <title>销售产品列表</title>    <link rel="stylesheet" href="{{asset('css/weui.min.css')}}"/>    <link rel="stylesheet" href="{{asset('css/weui.example.min.css')}}"/>    <style type="text/css">    .qr{        width: 70%;        margin-left: 15%;    }    </style></head><body ontouchstart>    <div class="container js_container">        <div class="page slideIn cell">            <div class="hd">                <h1 class="page_title">扫码报名</h1>            </div>            <div class="bd">                <div class="weui_cells_title">套餐选择：</div>                <div class="weui_cells weui_cells_radio">                    @foreach($productions as $i=>$production)                    <label class="weui_cell weui_check_label" for="p{{$i}}">                        <div class="weui_cell_bd weui_cell_primary">                            <p>{{$production->title}}</p>                        </div>                        <div class="weui_cell_ft">                            <input type="radio" class="weui_check" name="production" data-id="{{$production->id}}" id="p{{$i}}">                            <span class="weui_icon_checked"></span>                        </div>                    </label>                    @endforeach                </div>                <div class="weui_cells_title">二维码</div>                <div class="weui_cells">                    <div class="weui_cell">                        <img class="qr" src="">                    </div>                </div>                <div class="weui_cells_title">销售统计</div>                <div class="weui_cells weui_cells_access">                    <a class="weui_cell" href="/sell/history">                        <div class="weui_cell_bd weui_cell_primary">                            <p>已售份数</p>                        </div>                        <div id="count" class="weui_cell_ft"></div>                    </a>                </div>            </div>        </div>    </div>    <div hidden id="loading">        <div class="weui_toast">            <div class="weui_loading">                <div class="weui_loading_leaf weui_loading_leaf_0"></div>                <div class="weui_loading_leaf weui_loading_leaf_1"></div>                <div class="weui_loading_leaf weui_loading_leaf_2"></div>                <div class="weui_loading_leaf weui_loading_leaf_3"></div>                <div class="weui_loading_leaf weui_loading_leaf_4"></div>                <div class="weui_loading_leaf weui_loading_leaf_5"></div>                <div class="weui_loading_leaf weui_loading_leaf_6"></div>                <div class="weui_loading_leaf weui_loading_leaf_7"></div>                <div class="weui_loading_leaf weui_loading_leaf_8"></div>                <div class="weui_loading_leaf weui_loading_leaf_9"></div>                <div class="weui_loading_leaf weui_loading_leaf_10"></div>                <div class="weui_loading_leaf weui_loading_leaf_11"></div>            </div>            <p style="margin-top: 60%;margin-bottom: 0" class="weui_toast_content">请稍候</p>        </div>    </div>    <script src="//cdn.bootcss.com/jquery/2.2.1/jquery.min.js"></script>    <script type="text/javascript">        $.ajaxSetup({            headers: {                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')            }        });        function loadQR(productionId){            $('#loading').show();            $.post('/sell/qr',{                'production_id':productionId            },function(url){                $('img').attr('src',url);                $('#loading').hide();            });        }        $('input[name="production"]').change(function(){            var productionId = $(this).data('id');            loadQR(productionId);        });        $defaultCheck = $('input[name="production"]:eq(0)');        $defaultCheck.attr('checked','');        loadQR($defaultCheck.data('id'));        // function refreshSoldInfo(){        //     $.get('sell/sold',function(count){        //         $('#count>span').text(count);        //     });        // }        // self.setInterval("refreshSoldInfo()",5000);        if(typeof(EventSource)!=="undefined"){            var source=new EventSource("/sell/sold");            source.onmessage=function(event){                $('#count').text(event.data+'份');            };        }else{            alert('当前浏览器暂不支持已销售套餐份数的实时显示，如需了解相关信息，点击已销售套餐进入详细页查看。');        }    </script></body></html>