<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>hay matakarar</title>
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/font-awesome.css')}}">
    <link rel="stylesheet" href="{{asset('css/entypo-font.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/hover.css')}}">
    <link rel="stylesheet" href="{{asset('css/responsive.css')}}">
</head>
<body>
<header>
    <h1>
        <a class="logo d-block" href="#">hay matakarar</a>
    </h1>
</header>

@yield('content')

<script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
<script src="{{asset('js/popper.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<script src="{{asset('js/script.js')}}"></script>
</body>
</html>