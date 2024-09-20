<template>
    <div class="container telegram_marg">
        <select v-model="groupId">
            <option v-for="group in groupsList" :value="group.id">{{ group.group_name }}</option>
        </select>
        <div>Введите username телеги  пользователей в колонку</div>
        <div>Количество строк в поле: {{ lineCount }}</div>
        <textarea
            class="form-control textarea_admin"
            rows="10"
            name="text"
            v-model="usersList"
            placeholder="Список пользователей в колонку"
            @blur="lineCountMeth()"
        ></textarea>
        <div class="otst_but_top">
            <button type="button" class="btn btn-dark btn-block procedure_button textarea_admin" v-on:click="addTelegramUsersToGroup()" >Добавить пользователей</button>
        </div>
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
            groupId:'',
            usersList:'',
            lineCount:0,
            firstLines:''
        }
    },

    methods: {
        addTelegramUsersToGroup()
        {
            if(this.groupId!=='')
            {
            this.lineCountMeth();
            if(this.lineCount!==0)
            {
          this.extractLines();
              axios
                .post('/addTelegramUsersToGroup',{
                    users:this.firstLines,
                    group_id:this.groupId,
                })
                .then(response => {
                    // Успешный ответ
                    this.addTelegramUsersToGroup();
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
            }
            else
            {
                alert('Обработка окончена');
            }
            }
            else
            {
                alert('Выбери группу')
            }

        },
        lineCountMeth() {
            // Разделяем текст на строки по символу новой строки и считаем их количество
            this.lineCount=this.usersList.split('\n').length;
            if(this.usersList=='')
            {
                this.lineCount=0;
            }
        },
        extractLines() {
            // Разделяем текст на массив строк
            const lines = this.usersList.split('\n');
            // Извлекаем первые 50 строк
            this.firstLines = lines.slice(0, 50).join('\n');
            // Убираем первые 50 строк из usersList
            this.usersList = lines.slice(50).join('\n');
        }

    }
}
</script>

<style scoped>

</style>
