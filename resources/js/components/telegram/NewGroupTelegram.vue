<template>
    <div class="container telegram_marg">
        <div>Добавить группу(формат:@group)</div>
        <textarea class="form-control" rows="1"  name="text" v-model="groupName" placeholder="адрес группы"></textarea>
        <div>
            <button type="button" class="btn btn-warning btn-block telegram_lit_marg" v-on:click="addGroup()">Добавить группу</button>
        </div>
        <div>Список групп</div>
        <table class="table">
            <thead>
            <tr>
                <th scope="col" class="do_col_group">id</th>
                <th scope="col" class="do_col_group">Группа</th>
                <th scope="col" class="check_do">Удалить строку</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(group, index) in groupsList" :key="index">
                <td>
                    {{ group.id }}
                </td>
                <td>
                    {{ group.group_name }}
                </td>
                <td v-on:click="deleteGroup(group.id, index)" class="deleteCursor">Удалить</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>

export default {
    props: {
        transfer: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            groupsList:this.transfer.groups,
            groupName:''
        }
    },

    methods: {
        addGroup()
        {
            this.groupName=this.addSymbIfNeeded(this.groupName,'@');

            axios
                .post('/addGroup',{
                    group_name:this.groupName,
                })
                .then(response => {
                    // Успешный ответ
                    this.groupsList.push(response.data.group);
                    alert(response.data.message);
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        // Обработка ошибок валидации
                        alert(this.errorsToString(error.response.data.errors));
                    } else {
                        // Обработка других ошибок
                        alert(error);
                    }
                });
        },
        deleteGroup(id, index)
        {
            // Отображаем диалоговое окно с подтверждением
            const userConfirmed = confirm("Вы уверены, что хотите удалить эту строку?");

            if (userConfirmed) {
                axios
                    .post('/deleteGroup',{
                        id:id,
                    })
                    .then(response => {
                        // Успешный ответ
                        this.groupsList=this.groupsList.filter(item => item.id !== id)
                        alert(response.data.message);
                    })
                    .catch(error => {
                        if (error.response && error.response.status === 422) {
                            // Обработка ошибок валидации
                            alert(this.errorsToString(error.response.data.errors));
                        } else {
                            // Обработка других ошибок
                            alert(error);
                        }
                    });
            }
        }
    }
}
</script>
