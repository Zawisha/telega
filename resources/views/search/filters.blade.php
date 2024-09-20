@extends('layouts.main')

@section('content')
<div>
    <filters :transfer='{{ json_encode($transfer)}}'></filters>
</div>
@endsection
