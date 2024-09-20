<template>
    <div class="container telegram_marg">
        <div>
            <main-inputs :phone="phone" @update-phone="updatePhone"></main-inputs>
            <authorization-telegram :phone="phone" @update-phone="updatePhone"></authorization-telegram>
            <join-group :phone="phone" @update-phone="updatePhone"></join-group>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            phone:'',
        }
    },
    methods: {
        updatePhone(newPhone) {
            this.phone = newPhone;
        }
    }
}
</script>

<style scoped>

</style>
