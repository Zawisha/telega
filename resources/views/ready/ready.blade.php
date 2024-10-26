@extends('layouts.main')

@section('content')
    @empty($post)
        <p>Всё обработано</p>
    @else
<div>
    <div>Осталось:{{ $countClients }}</div>
    <div>Клиент:{{ $post->client_name }}</div>
    <div class="ready_res_class">Ссылка:<a href={{ $post->link }}>{{ $post->link }}</a></div>
    <div class="d-flex justify-content-between">
        <form action="{{ route('getClient') }}" method="POST" class="addStrokaMarg">
            @csrf
            <input type="hidden" name="id" value="{{ $post->id }}">
            <input type="hidden" name="choose" value="getIt">
            <input type="hidden" name="archive" value={{ $archive }}>
            @if(request('clientName'))
                <input type="hidden" name="clientName" value="{{ request('clientName') }}">
            @endif
            <button type="submit" class="btn btn-success button_choose ready_res_class">Забрал</button>
        </form>
        <form action="{{ route('getClient') }}" method="POST" class="addStrokaMarg">
            @csrf
            <input type="hidden" name="id" value="{{ $post->id }}">
            <input type="hidden" name="choose" value="archive">
            @if(request('clientName'))
                <input type="hidden" name="clientName" value="{{ request('clientName') }}">
            @endif
            @if($archive==null)
                <button type="submit" class="btn btn-secondary button_choose ready_res_class">В архив</button>
            @endif
        </form>
        <form action="{{ route('skipArchive') }}" method="POST" class="addStrokaMarg">
            @csrf
            <input type="hidden" name="id" value="{{ $post->id }}">
            @if(request('clientName'))
                <input type="hidden" name="clientName" value="{{ request('clientName') }}">
            @endif
            @if($archive!==null)
                <button type="submit" class="btn btn-secondary button_choose ready_res_class">Пропустить</button>
            @endif
        </form>

    </div>
    <div class="ready_res_class">Сообщение:{{ $post->message }}</div>
    @endempty
</div>
@endsection
