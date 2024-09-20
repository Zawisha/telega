@extends('layouts.main')

@section('content')
    <div class="container main_marg">
        <div class="row">
            <div class="col-12 d-flex justify-content-left">
                <div class="centered-content">
                    <div>Инвайт в телеграм</div>
                <invite-telegram :transfer='{{ json_encode($transfer)}}'></invite-telegram>
                </div>
            </div>
        </div>
    </div>
@endsection
