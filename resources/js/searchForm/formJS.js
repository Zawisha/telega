document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.group-input').forEach(input => {
        input.addEventListener('blur', function() {
            const editGroupNameRoute = "{{ route('editGroupName') }}";

                // Получаем значения
            let groupName = this.value;
            let lineId = this.getAttribute('data-line-id');
            let groupId = this.getAttribute('data-group-id');

            // Выполняем AJAX-запрос
            fetch(editGroupNameRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    group_name: groupName,
                    line_id: lineId,
                    group_id: groupId
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Имя группы обновлено');
                    } else {
                        alert('Ошибка: ' + data.message);
                    }
                })
                .catch(error => console.error('Ошибка:', error));
        });
    });
});
