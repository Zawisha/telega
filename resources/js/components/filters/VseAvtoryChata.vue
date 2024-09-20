<template>
    <div>
Фильтр. Все авторы чата
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
            filters:this.transfer.filters,
        }
    },
    watch: {

    },
    methods: {

    }
}
</script>

<style scoped>

</style>
