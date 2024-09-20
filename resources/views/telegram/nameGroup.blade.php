@extends('layouts.main')

@section('content')
    <div class="container main_marg">
        <div class="row">
            <div class="col-12 d-flex justify-content-left">
                <div class="centered-content">
                    <div>Добавление пользователей в базу для рассылки по телеграм</div>
                  <database-user-telegram :transfer='{{ json_encode($transfer)}}'></database-user-telegram>
                </div>
            </div>
        </div>
    </div>
@endsection
