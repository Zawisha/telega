@extends('layouts.main')

@section('content')
    @if($clientsName->isEmpty())
        <p>Все клиенты обработаны</p>
    @else
<div>Будет выводить по одному клиенту из общего не обработанного списка</div>
<div><a href="{{ url('/notReadyFilter') }}" class="btn btn-primary btn-custom my-2">Общая обработка не готовых клиентов</a></div>
<div class="select_list_clients">
    <div>Список не обработанных клиентов. Будет выводить только выбранного клиента</div>
    <select name="client_name" id="client_name">
        @foreach($clientsName as $client)
            <option value="{{ $client }}">{{ $client }}</option>
        @endforeach
    </select>
        <button onclick="redirectToClient()" class="btn btn-primary btn-custom my-2 client_button_com">Перейти на клиента</button>
</div>
    @endif
@endsection
<script>
    function redirectToClient() {
        const clientName = document.getElementById('client_name').value;
        window.location.href = `/notReadyFilter/${encodeURIComponent(clientName)}`;
    }
</script>
