<template>
    <div class="container telegram_marg">
        <h1>Добавить нового клиента</h1>
        <div class="telegram_lit_marg">Название</div>
        <textarea class="form-control" rows="1"  name="text" v-model="name" placeholder="Название"></textarea>
        <div class="telegram_lit_marg">Описание клиента ( может быть пустым )</div>
        <textarea class="form-control" rows="1"  name="text" v-model="desc" placeholder="Описание клиента"></textarea>
        <div>
            <button type="button" class="btn btn-warning btn-block telegram_lit_marg" v-on:click="addClient()">Добавить клиента</button>
        </div>
        <div class="filters_button">
            <button type="button" class="btn btn-primary otst_but" @click="goToLink('filters')">Фильтры</button>
        </div>
    </div>
</template>

<script>

export default {

    data() {
        return {
            name:'',
            desc:'',
        }
    },
    watch: {

    },
    methods: {
        addClient()
        {
            axios
                .post('/addClient',{
                    name:this.name,
                    desc:this.desc,
                })
                .then(response => {
                    // Успешный ответ
                    alert(response.data.message);
                    this.name='';
                    this.desc='';
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
