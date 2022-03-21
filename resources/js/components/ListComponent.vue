<template>
    <ul>
        <li v-for="(table,i) in tables" :key="i">
            <button v-on:click="toTurn(table.className)">
                {{table.title}}
            </button>
        </li>
    </ul>
</template>

<script>
export default {
    name: "ListComponent",
    data:function () {
        return {
            tables: [
                {title: 'Holdem, 2 players', className: 'HoldemTwoPokerTable'},
                {title: 'Omaha, 2 players', className: 'OmahaTwoPokerTable'},
            ]
        }
    },
    methods:{
        toTurn:function () {
            this.$emit('load-screen')
        }
    }
}
</script>

<style scoped>

</style>
