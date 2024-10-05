<template>
    <div>
<div>
    Стоп ссылки для фильтра linkInPost_vk
</div>
        <div class="stop_slova_header">Добавить часть ссылки или полную ссылку</div>
        <div>Например test.com или https://vk.com/techfeya или @link или 423532532 ( номер паблика часто встречается )</div>
        <input class="form-control"  name="text" v-model="newWord" placeholder="ссылка или часть ссылки" @blur="slovoAdd">

        <div class="stop_slova_header">Список слов</div>
        <div class="stop_slova">
            <span v-for="(slovo, index) in slova" :key="slovo.id">
      {{ slovo.slovo }}<span v-if="index < slova.length - 1">, </span>
            </span>
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
            slova:this.transfer.slova,
            newWord:''
        }
    },
    watch: {

    },
    methods: {
        slovoAdd()
        {
            if(this.newWord!=='')
            {
            axios
                .post('/slovoAddVK',{
                    slovo:this.newWord,
                })
                .then(response => {
                    // Успешный ответ
                    this.newWord=''
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
            }
        }
    }
}
</script>

<style scoped>

</style>
