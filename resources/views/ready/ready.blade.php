@extends('layouts.main')

@section('content')
    @empty($post)
        <p>Всё обработано</p>
    @else
<div>
    <div>Клиент:{{ $post->client_name }}</div>
    <div class="ready_res_class">Ссылка:<a href={{ $post->link }}>{{ $post->link }}</a></div>
    <div class="d-flex justify-content-between">
        <form action="{{ route('getClient') }}" method="POST" class="addStrokaMarg">
            @csrf
            <input type="hidden" name="id" value="{{ $post->id }}">
            <input type="hidden" name="choose" value="true">
            <button type="submit" class="btn btn-success button_choose ready_res_class">Забрал</button>
        </form>
    </div>
    <div class="ready_res_class">Сообщение:{{ $post->message }}</div>
    @endempty
</div>
@endsection
