@extends('layouts.main')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <input type="hidden" name="line_id" id="line_id" value="{{ $id }}">
    <div>Клиент</div>
    <div>Внимание:Фильтры накладываются друг на друга</div>
    <div>Внимание:Для новой группы новый инпут!</div>
    <div>Внимание:На каждой линии своя технология! Не смешивать тг, вк, инст</div>
    <div>Если хочешь копнуть глубже, просто в таблице one_client_settings_groups_telegram_lines находишь нужную группу, указываешь номер поста с которого хочешь копать</div>
    <select name="client_id" class="form-select select_suct select-client">
        <option value="" {{ !isset($oneLine[0]->myClient) ? 'selected' : '' }}>-- Выберите клиента --</option>
        @foreach ($clients as $oneClient)
            <option value="{{ $oneClient->id }}"
                {{ isset($oneLine[0]->myClient) && $oneLine[0]->myClient->id == $oneClient->id ? 'selected' : '' }}>
                {{ $oneClient->name }}
            </option>
        @endforeach
    </select>

    <!-- Форма с кнопкой для отправки запроса -->
    <form action="{{ route('addOneClientStroka') }}" method="POST" class="addStrokaMarg">
        @csrf
        <input type="hidden" name="id" value="{{ $id }}">
        <button type="submit" class="btn btn-primary mt-3">Добавить строку(Обновляет страницу)</button>
    </form>
    @foreach($lines as $key => $line)
        <div class="line-container mb-4 p-3">
        <div class="d-flex align-items-center">
            <p class="mb-0">Линия: {{ $key + 1 }}</p>

            {{-- Форма для добавления группы --}}
            <form action="{{ route('addGroupEditLine') }}" method="POST" class="ms-3">
                @csrf
                <input type="hidden" name="line_id" value="{{ $line->id }}">
                <button class="btn btn-success">+группу</button>
            </form>

            <select class="select_source select_source" name="category" id="source_{{ $line->id }}">
                <option value="">ВЫБЕРИ ТИП ИСТОЧНИКА</option>
                <!-- Перебираем данные, переданные из контроллера -->
                @foreach ($sourceName as $category)
                    <option value="{{ $category->id }}"
                      {{ $line->source_id == $category->id ? 'selected' : '' }}
                    >{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        @if($line->settingsGroups)
            <p>Группы:</p>
            <div class="d-flex flex-wrap align-items-center mb-3">
                @foreach($line->settingsGroups as $key1 => $group)
                    <div class="d-flex align-items-center me-3 mb-2">
                        <input
                            id="group_{{ $key1 }}"
                            type="text"
                            name="group_{{ $key1 }}"
                            value="{{ $group->group_name }}"
                            data-line-id="{{ $line->id }}"
                            data-group-id="{{ $group->id }}"
                            class="group-input form-control me-2"
                            style="width: 200px;"> <!-- Указываем фиксированную ширину -->

                        <form action="{{ route('deleteGroupEditLine') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="line_id" value="{{ $group->id }}">
                            <button class="btn btn-danger">-</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif

        <p>Фильтры настроек:</p>
        <div class="d-flex flex-wrap align-items-center mb-3">
            @foreach($filters as $filter)
                @php
                    // Флаг для отслеживания, отмечен ли чекбокс
                    $isChecked = false;
                @endphp
                {{-- Перебор локальных фильтров для текущей линии --}}
                @foreach($line->settingsFilter as $localFilter)
                    @if($filter->id == $localFilter->filter_id)
                        @php
                            $isChecked = true; // Установить флаг, если фильтр совпал
                        @endphp
                    @endif
                @endforeach
                <div class="form-check me-3 mb-2">
                    <input
                        class="form-check-input filter-checkbox"
                        type="checkbox"
                        name="filters[]"
                        value="{{ $filter->id }}"
                        id="filter_{{ $filter->id }}"
                        data-line-id="{{ $line->id }}"
                        {{ $isChecked ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="filter_{{ $filter->id }}">
                        {{ $filter->filter_name }}
                    </label>
                </div>
            @endforeach
        </div>
{{--            форма удаления линии--}}
            <form action="{{ route('deleteEditLine') }}" method="POST" class="ms-3">
                @csrf
                <input type="hidden" name="line_id" value="{{ $line->id }}">
                <button class="btn btn-danger">Удалить всю линию</button>
            </form>
        </div>
    @endforeach

@endsection
