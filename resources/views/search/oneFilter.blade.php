@extends('layouts.main')

@section('content')
<div>
    @if($id == 1)
    <vse-avtory-chata :transfer='{{ json_encode($transfer)}}'></vse-avtory-chata>
    @endif
    @if($id == 2)
    <stop-slova :transfer='{{ json_encode($transfer)}}'></stop-slova>
    @endif
    @if($id == 3)
    <link-in-post :transfer='{{ json_encode($transfer)}}'></link-in-post>
    @endif
    @if($id == 4)
    <stop-links-v-k :transfer='{{ json_encode($transfer)}}'></stop-links-v-k>
    @endif
</div>
@endsection
