<template>
    <div class="container telegram_marg">
    <div>Не отправлено: {{ countResultes }}</div>
    <button type="button" class="btn btn-success telegram_lit_marg" v-on:click="sendToHosting()">Старт</button>
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
            countResultes:this.transfer.countResultes,
        }
    },
    watch: {

    },
    methods: {
        sendToHosting()
        {
            axios
                .post('/sendToHosting',{
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
                        alert(error.response.data.message);
                    }
                });
        },
    }
}
</script>

<style scoped>

</style>
