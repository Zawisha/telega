@extends('layouts.main')

@section('content')
<div>
    <search-telegram :transfer='{{ json_encode($transfer)}}'></search-telegram>
</div>
@endsection
