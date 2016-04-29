<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/VIP.css') }}"/>
    <title>我的会员卡</title>
    <script type="text/javascript">
        (function(window, location) {
            history.replaceState(null, document.title, location.pathname+"#!/stealingyourhistory");
            history.pushState(null, document.title, location.pathname);

            window.addEventListener("popstate", function() {
              if(location.hash === "#!/stealingyourhistory") {
                history.replaceState(null, document.title, location.pathname);
                setTimeout(function(){
                  location.replace("/");
                },0);
              }
            }, false);
        }(window, location));
    </script>
    <script type="text/javascript" src="{{ asset('js/jquery-1.7.2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/user-card.js') }}"></script>
</head>
<body style="background-color: #fff">
@foreach($cards as $card)
<a  class="card @if(!$card->isActive()) touming @endif" data-cardno="{{$card->no}}">
<img class="img yinying" src="{{ asset('img/VIP/'.$card->vip->image) }}"/>
</a>
@endforeach
<a href="/vip/create">
<img class="img huikuang" src="{{ asset('img/VIP/create.jpg') }}"/>
</a>
</body>
</html>
