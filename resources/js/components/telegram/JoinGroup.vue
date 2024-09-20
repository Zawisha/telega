<template>
    <div class="container telegram_marg">
        <h1>3) Вступить в группу (проверка на бан)</h1>
        <div class="telegram_lit_marg">Телефон(можно просто цифры без знака +)</div>
        <textarea class="form-control" rows="1"  name="text" v-model="new_user_telephone" placeholder="Телефон" @input="emitPhoneUpdate"></textarea>
        <div class="telegram_lit_marg">Адрес группы(формат: @avto_moskva_ok1 )</div>
        <textarea class="form-control" rows="1"  name="text" v-model="groupAdress" placeholder="адрес группы"></textarea>
        <div>
            <button type="button" class="btn btn-warning btn-block telegram_lit_marg" v-on:click="joinToGroup()">Вступить в группу</button>
        </div>

    </div>
</template>

<script>

export default {
    props: ['phone'],
    data() {
        return {
            new_user_telephone:this.phone,
            groupAdress:'@avto_moskva_ok1',
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
        joinToGroup()
        {
            this.new_user_telephone=this.addSymbIfNeeded(this.new_user_telephone,'+');
            axios
                .post('/joinToGroup',{
                    phone:this.new_user_telephone,
                    groupAdress:this.groupAdress,
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
