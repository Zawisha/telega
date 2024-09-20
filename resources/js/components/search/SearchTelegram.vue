<template>
    <div class="container telegram_marg">
        <h2>Поиск новых клиентов в телеграм</h2>
        <div>Если где то подвисает, то проверяй название чата(канала)</div>
        <div>
            <button type="button" class="btn btn-warning telegram_lit_marg" v-on:click="addStrokaSearchClientTelegram()">Добавить строку(обнови страницу)</button>
            <button type="button" class="btn btn-success telegram_lit_marg" v-on:click="startSearchClients()">Старт</button>
<!--            <button type="button" class="btn btn-warning telegram_lit_marg" v-on:click="test()">Test</button>-->
        </div>

        <table class="table">
            <thead>
            <tr>
                <th scope="col" class="do_col_group">Клиент</th>
                <th scope="col">Настройки строки</th>
                <th scope="col">Старт</th>
                <th scope="col" class="check_do">Удалить строку</th>
                <th scope="col" class="check_do">Сделано</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(line, index) in linesInvite" >
                <td :class="{ 'active_class_table': index === currentLine }">
                    {{ line.my_client && line.my_client.name ? line.my_client.name : 'Пусто' }}
                </td>
                <td :class="{ 'active_class_table': index === currentLine }">
                    <div class="deleteCursor" @click="goToLink('editLineSearchSettings/'+line.id)">Настройки</div>
                </td>
                <td :class="{ 'active_class_table': index === currentLine }">
                    <input type="checkbox"
                           v-model="line.vkl"
                           @blur="updateCheckboxClientList(line.id,line.vkl)"
                    >
                </td>
                <td v-on:click="deleteClientLine(line.id)" class="deleteCursor" :class="{ 'active_class_table': index === currentLine }">Удалить</td>
                <td  class="deleteCursor" :class="{ 'active_class_table': index === currentLine }">{{ line.sdelano }}</td>

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
            linesInvite:this.transfer.lines,
            currentLine:0
        }
    },
    watch: {

    },
    methods: {
        test()
        {
          console.log(this.linesInvite)
          console.log(this.currentLine)
        },
        deleteClientLine(id)
        {
            // Отображаем диалоговое окно с подтверждением
            const userConfirmed = confirm("Вы уверены, что хотите удалить эту строку?");

            if (userConfirmed) {
                axios
                    .post('/deleteClientLine',{
                        id:id,
                    })
                    .then(response => {
                        // Успешный ответ
                        this.linesInvite=this.linesInvite.filter(item => item.id !== id)
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
        },
        startSearchClients()
        {
            if(this.linesInvite.length>this.currentLine)
            {
                if(this.linesInvite[this.currentLine]['vkl'])
                {
                    this.searchClients(this.linesInvite[this.currentLine]['id'],this.linesInvite[this.currentLine]['my_client_id']);
                }
                else
                {
                    this.currentLine++
                    this.startSearchClients();
                }
            }
            else
            {
                alert('Работа окончена')
            }
        },
        //основной метод поиска клиентов
        searchClients(line_id,my_client_id)
        {
            axios
                .post('/searchClients',{
                    line_id: line_id,
                    my_client_id: my_client_id,
                })
                .then(response => {
                    this.linesInvite[this.currentLine]['sdelano']=response.data.posts.length
                    this.currentLine++
                    this.startSearchClients();
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        // Обработка ошибок валидации
                        //alert(this.errorsToString(error.response.data.errors));
                        console.log('Поле клиент пусто')
                        this.currentLine++
                        this.startSearchClients();
                    } else {
                        // Обработка других ошибок
                        alert(error.response.data.message);
                    }
                });
        },
        updateCheckboxClientList(id,data)
        {
            axios
                .post('/updateCheckboxClientList',{
                    id: id,
                    data: data,
                })
                .then(response => {
                    // Успешный ответ
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        // Обработка ошибок валидации
                        alert(this.errorsToString(error.response.data.errors));
                    } else {
                        // Обработка других ошибок
                        alert(error.response.data.message);
                    }
                });
        },
        addStrokaSearchClientTelegram()
        {
            axios
                .post('/addStrokaSearchClientTelegram',{
                })
                .then(response => {
                    // Успешный ответ
                    if(response.data.message)
                    {
                        alert(response.data.message);
                    }
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        // Обработка ошибок валидации
                        alert(this.errorsToString(error.response.data.errors));
                    } else {
                        // Обработка других ошибок
                        alert(error.response.data.message);
                    }
                });
        },
    }
}
</script>

<style scoped>

</style>
