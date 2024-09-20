@extends('layouts.main')

@section('content')
    <div class="container main_marg">
        <div class="row">
            <div class="col-12 d-flex justify-content-left">
                <div class="centered-content">
                    <div>Добавление и редактирование групп</div>
                 <new-group-telegram :transfer='{{ json_encode($transfer)}}'></new-group-telegram>
                </div>
            </div>
        </div>
    </div>
@endsection
