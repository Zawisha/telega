@extends('layouts.main')

@section('content')

    <div><a href="{{ url('/adminSearch') }}" class="btn btn-primary btn-custom my-2">Админка поиска клиентов</a></div>
    <div><a href="{{ url('/newClientTelegramSearch') }}" class="btn btn-primary btn-custom my-2">Поиск в телеграм</a></div>
    @if (request()->url() === 'http://yourlocaldomain.local/searchNavi')
    <div><a href="{{ url('/sendHosting') }}" class="btn btn-primary btn-custom my-2">Отправка на хостинг =>hashiro.ru</a></div>
    @endif
    @if (request()->url() === 'http://hashiro.ru/searchNavi')
    <div><a href="{{ url('/notReadyFilter') }}" class="btn btn-primary btn-custom my-2">Обработка не готовых клиентов</a></div>
    <div><a href="{{ url('/readyFilter') }}" class="btn btn-primary btn-custom my-2">Обработка готовых клиентов</a></div>
    @endif
@endsection
