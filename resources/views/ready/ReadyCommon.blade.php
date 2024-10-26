@extends('layouts.main')

@section('content')
    <div>Обработка готовых клиентов</div>
    @if($clientsName->isEmpty())
        <p>Все клиенты обработаны</p>
    @else
<div>Будет выводить по одному клиенту из общего не обработанного списка</div>
<div><a href="{{ url('/readyFilter') }}" class="btn btn-success btn-custom my-2">Общая обработка готовых клиентов</a></div>
<div class="select_list_clients">
    <div>Список обработанных клиентов. Будет выводить только выбранного клиента вместе с архивом</div>
    <select name="client_name" id="client_name">
        @foreach($clientsName as $client)
            <option value="{{ $client }}">{{ $client }}</option>
        @endforeach
    </select>
        <button onclick="redirectToClient('com')" class="btn btn-success btn-custom my-2 client_button_com">Перейти на клиента</button>
        <button onclick="redirectToClient('archive')" class="btn btn-success btn-custom my-2 client_button_com">Перейти на архив клиента</button>

</div>
    @endif
@endsection
<script>
    function redirectToClient(pathTo) {
        if(pathTo==='com')
        {
            const clientName = document.getElementById('client_name').value;
            window.location.href = `/readyFilter/${encodeURIComponent(clientName)}`;
        }
        if(pathTo==='archive')
        {
            const clientName = document.getElementById('client_name').value;
            window.location.href = '/readyFilter/'+clientName+'/archive';
        }

    }
</script>
