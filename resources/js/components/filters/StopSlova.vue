<template>
    <div>
<div>
    Стоп слова
</div>
        <div class="stop_slova_header">Добавить слово</div>
        <input class="form-control"  name="text" v-model="newWord" placeholder="Телефон" @blur="slovoAdd">

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
                .post('/slovoAdd',{
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
