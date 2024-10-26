@extends('layouts.main')

@section('content')
    @empty($post)
        @if(request('clientName'))
            <p>У клиента {{ request('clientName') }} больше нет постов</p>
            <div><a href="{{ url('/notReadyFilterCommon') }}">Перейти на список клиентов</a></div>
        @else
            <p>У всех клиентов больше нет не обработанных постов</p>
        @endif
    @else
<div>
    <div>Осталось:{{ $countClients }}</div>
    <div>Клиент:{{ $post->client_name }}</div>
    <div class="ready_res_class">Ссылка:<a href={{ $post->link }} target="_blank">{{ $post->link }}</a></div>
    <div class="ready_res_class">Сообщение:{{ $post->message }}</div>


    <div class="d-flex justify-content-between">
        <form action="{{ route('addReadyClient') }}" method="POST" class="addStrokaMarg">
            @csrf
            <input type="hidden" name="id" value="{{ $post->id }}">
            <input type="hidden" name="choose" value="true">
            @if(request('clientName'))
                <input type="hidden" name="clientName" value="{{ request('clientName') }}">
            @endif
            <button type="submit" class="btn btn-success button_choose ready_res_class">Добавить</button>
        </form>

        <form action="{{ route('addReadyClient') }}" method="POST" class="addStrokaMarg ms-3">
            @csrf
            @if(request('clientName'))
                <input type="hidden" name="clientName" value="{{ request('clientName') }}">
            @endif
            <input type="hidden" name="id" value="{{ $post->id }}">
            <input type="hidden" name="choose" value="false">
            <button type="submit" class="btn btn-danger button_choose ready_res_class">Удалить</button>
        </form>
    </div>
    @endempty


</div>
@endsection
