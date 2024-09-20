<template>
    <div class="container telegram_marg">
        <h1>1) Добавление телефона</h1>
        <div>Введите данные пользователя телеграм</div>
        <div class="telegram_lit_marg">Телефон(можно просто цифры без знака +)</div>
        <textarea class="form-control" rows="1"  name="text" v-model="new_user_telephone" placeholder="Телефон" @input="emitPhoneUpdate"></textarea>
        <div class="telegram_lit_marg"><a href="https://my.telegram.org/auth?to=apps" target="_blank" >api_id</a></div>
        <textarea class="form-control" rows="1"  name="text" v-model="api_id" placeholder="api_id"></textarea>
        <div class="telegram_lit_marg"><a href="https://my.telegram.org/auth?to=apps" target="_blank" >api_hash</a></div>
        <textarea class="form-control" rows="1"  name="text" v-model="api_hash" placeholder="api_hash"></textarea>
        <div>
            <button type="button" class="btn btn-warning btn-block telegram_lit_marg" v-on:click="saveNewTelUser()">Сохранить телефон</button>
        </div>
    </div>
</template>

<script>

export default {
    props: ['phone'],
    data() {
        return {
            new_user_telephone:this.phone,
            api_id:'',
            api_hash:'',
        }
    },
    watch: {
        phone(newPhone) {
            this.new_user_telephone = newPhone;
        }
    },
    methods: {
        saveNewTelUser()
        {
            //проверяем если первый не знак + то добавляем его
            this.new_user_telephone=this.addSymbIfNeeded(this.new_user_telephone,'+');
            this.emitPhoneUpdate();
            axios
                .post('/saveNewTelegramUser',{
                    new_user_telephone:this.new_user_telephone,
                    api_id:this.api_id,
                    api_hash:this.api_hash,
                })
                .then(response => {
                    // Успешный ответ
                    alert(response.data.message);
                    this.api_id='';
                    this.api_hash='';
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
        //метод для управления родительским значением теелфона
        emitPhoneUpdate() {
            this.$emit('update-phone', this.new_user_telephone);
        }
    }
}
</script>

<style scoped>

</style>
