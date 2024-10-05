<template>
    <div class="container main_buttons">
        <div>Получить токен</div>
        <div>https://vkhost.github.io</div>
        <div>Описание</div>
        <textarea type="text" rows="3" class="auto_input_height textar_width" ref="auto_input"  v-model="account.account_info" @blur="updateTokenVK()" />
        <div>Токен</div>
        <textarea type="text" rows="3" class="auto_input_height textar_width" ref="auto_input"  v-model="account.token" @blur="updateTokenVK()" />
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
            account:this.transfer.account,
        }
    },
    methods: {
        updateTokenVK()
        {
            axios
                .post('/updateTokenVK',{
                    account:this.account,
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
