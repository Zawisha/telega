<template>
    <div class="container main_buttons">
        <button type="button" class="btn btn-primary otst_but" @click="goToLink('telegramNavi')" v-if="isHomePage">Инвайт в телеге</button>
        <button type="button" class="btn btn-primary otst_but otst_mob_top" @click="goToLink('checkClient')"v-if="!isHomePage">Кому уже писал</button>
        <button type="button" class="btn btn-primary otst_but otst_mob_top" @click="goToLink('searchNavi')">Поиск клиентов</button>
        <button type="button" class="btn btn-primary otst_but otst_mob_top" @click="goToLink('vk')" v-if="isHomePage">ВК</button>
    </div>
</template>

<script>
export default {
    computed: {
        isHomePage() {
            return window.location == 'http://yourlocaldomain.local/'; // Проверка, что текущий URL локалка
        }
    },
    methods: {
    }
}
</script>

<style scoped>

</style>
