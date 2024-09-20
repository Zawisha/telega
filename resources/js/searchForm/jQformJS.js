$(document).ready(function() {
    // Добавляем обработчик события blur
    $('.group-input').on('blur', function() {
        let groupName = $(this).val();
        let lineId = $(this).data('line-id');
        let groupId = $(this).data('group-id');

        $.ajax({
            url: "/editGroupName", // URL для обработки запроса
            method: 'POST', // Метод запроса
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                // _token: "{{ csrf_token() }}", // CSRF токен
                group_name: groupName,
                line_id: lineId,
                group_id: groupId
            },
            success: function(response) {
                if (response.status === 'success') {
                    console.log('Имя группы успешно обновлено');
                } else {
                    alert('Ошибка: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log('Ошибка:', error);
            }
        });
    });

    // Добавляем обработчик событий на изменение состояния чекбоксов
    $('.filter-checkbox').change(function() {
        let filterId = $(this).val(); // ID фильтра
        let lineId = $(this).data('line-id'); // ID линии
        let isChecked = $(this).is(':checked'); // Проверяем состояние чекбокса

        // Выполняем AJAX-запрос
        $.ajax({
            url: '/changeCheckboxStatus', // Замените на ваш маршрут
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                // _token: '{{ csrf_token() }}', // CSRF токен
                filter_id: filterId,
                line_id: lineId,
                status: isChecked // true если чекбокс отмечен, иначе false
            },
            success: function(response) {
                if (response.status === 'success') {
                    console.log('Фильтр успешно обновлен');
                } else {
                    console.error('Ошибка при обновлении фильтра: ' + response.message);
                }
            },
            error: function(xhr) {
                console.error('Ошибка при выполнении AJAX-запроса');
            }
        });
    });

    $('.select-client').on('change', function() {
        let clientId = $(this).val(); // Получаем ID выбранного клиента
        let lineId = $('#line_id').val(); // Получаем значение скрытого поля с ID

        // Выполняем AJAX-запрос
        $.ajax({
            url: '/updateSearchClient', // Укажите правильный маршрут
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                client_id: clientId,
                lineId: lineId
            },
            success: function(response) {
                if (response.status === 'success') {
                    console.log('Клиент успешно обновлен');
                } else {
                    console.error('Ошибка при обновлении клиента: ' + response.message);
                }
            },
            error: function(xhr) {
                console.error('Ошибка при выполнении AJAX-запроса');
            }
        });
    });


});
