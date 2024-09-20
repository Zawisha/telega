@extends('layouts.main')

@section('content')
    @empty($post)
        <p>Данных нет. Пост не найден.</p>
    @else
<div>
    <div>Клиент:{{ $post->client_name }}</div>
    <div class="ready_res_class">Ссылка:<a href={{ $post->link }}>{{ $post->link }}</a></div>
    <div class="ready_res_class">Сообщение:{{ $post->message }}</div>


    <div class="d-flex justify-content-between">
        <form action="{{ route('addReadyClient') }}" method="POST" class="addStrokaMarg">
            @csrf
            <input type="hidden" name="id" value="{{ $post->id }}">
            <input type="hidden" name="choose" value="true">
            <button type="submit" class="btn btn-success button_choose ready_res_class">Добавить</button>
        </form>

        <form action="{{ route('addReadyClient') }}" method="POST" class="addStrokaMarg ms-3">
            @csrf
            <input type="hidden" name="id" value="{{ $post->id }}">
            <input type="hidden" name="choose" value="false">
            <button type="submit" class="btn btn-danger button_choose ready_res_class">Удалить</button>
        </form>
    </div>
    @endempty


</div>
@endsection
