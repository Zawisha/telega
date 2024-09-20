<template>
    <div class="container telegram_marg">
<h2>Список фильтров</h2>
        <div>Для добавления вручную вносишь в таблицу search_filters</div>
        <ul>
            <li v-for="(filter, index) in filters" :key="index">
                <a :href="'/filter/' + filter.id">{{ index + 1 }}: {{ filter.filter_name }}</a>
            </li>
        </ul>
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
