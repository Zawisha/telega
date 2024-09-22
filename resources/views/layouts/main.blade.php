<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Test</title>
    @vite('resources/js/app.js')
    @vite('resources/js/searchForm/jQformJS.js')
    @vite('resources/js/searchForm/formJS.js')
</head>
<body class="back_img">

<div id="app" class="bg">
    <div class="container">
    <a href="{{ url('/') }}">
       Главная
    </a>
    @yield('content')
    </div>
</div>
</body>

</html>
