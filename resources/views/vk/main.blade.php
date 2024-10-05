@extends('layouts.main')

@section('content')
<vkmain :transfer='{{ json_encode($transfer)}}'></vkmain>
@endsection
