<style scoped>
    .expand-row{
        margin-bottom: 16px;
    }
</style>
<template>
    <div>
        <span v-for="(item, index) in row.wallet" :key="index">
            {{item.balance}}{{item.type_name}},
        </span>
    </div>
</template>
<script>
    export default {
        props: {
            row:Object,
        }
    };
</script>