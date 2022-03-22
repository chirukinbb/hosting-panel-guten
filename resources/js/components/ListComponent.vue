<template>
    <ul>
        <li v-for="(table,i) in tables" :key="i">
            <button v-on:click="$emit('load-screen',table.className)">
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
    }
}
</script>

<style scoped>

</style>
