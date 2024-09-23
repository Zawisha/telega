@extends('layouts.main')

@section('content')
<div>
    <div>Отправка на хостинг</div>
 <send-hosting :transfer='{{ json_encode($transfer)}}'></send-hosting>
</div>
@endsection
