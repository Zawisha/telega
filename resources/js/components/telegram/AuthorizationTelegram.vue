<template>
    <div class="container telegram_marg">
        <h1>2) Авторизация в телеграм</h1>
<!--        <div>Введите данные пользователя телеграм</div>-->
        <div class="telegram_lit_marg">Телефон(можно просто цифры без знака +)</div>
        <textarea class="form-control" rows="1"  name="text" v-model="new_user_telephone" placeholder="Телефон" @input="emitPhoneUpdate"></textarea>
        <div>
            <button type="button" class="btn btn-warning btn-block telegram_lit_marg" v-on:click="getAuthCodeTelegram()">Запросить код авторизации</button>
        </div>
        <div class="telegram_lit_marg">Код авторизации</div>
        <textarea class="form-control" rows="1"  name="text" v-model="authCode" placeholder="Код авторизации"></textarea>
        <div>
            <button type="button" class="btn btn-warning btn-block telegram_lit_marg" v-on:click="sendCode()">Отправить код авторизации</button>
        </div>
    </div>
</template>

<script>

export default {
    props: ['phone'],
    data() {
        return {
            new_user_telephone:this.phone,
            authCode:'',
        }
    },
    watch: {
        phone(newPhone) {
            this.new_user_telephone = newPhone;
        }
    },
    methods: {
        //метод для управления родительским значением теелфона
        emitPhoneUpdate() {
            this.$emit('update-phone', this.new_user_telephone);
        },
        getAuthCodeTelegram()
        {
            this.new_user_telephone=this.addSymbIfNeeded(this.new_user_telephone,'+');
            axios
                .post('/getAuthCodeTelegram',{
                    phone:this.new_user_telephone,
                })
                .then(response => {
                    // Успешный ответ
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
        sendCode()
        {
            this.new_user_telephone=this.addSymbIfNeeded(this.new_user_telephone,'+');
            axios
                .post('/sendCode',{
                    phone:this.new_user_telephone,
                    authCode:this.authCode,
                })
                .then(response => {
                    // Успешный ответ
                    if(response.data.message)
                    {
                        alert(response.data.message);
                    }
                   else
                    {
                        alert('Неизвестная ошибка, возможно 2fa, проверь ответ');
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
        }
    }
}
</script>

<style scoped>

</style>
