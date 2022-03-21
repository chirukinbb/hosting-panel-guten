<template>
    <div class="load">
        Waiting opponents({{count}})...
        <button v-on:click="$emit('leave-turn')">
            Leave turn
        </button>
    </div>
</template>

<script>
export default {
    name: "LoaderComponent",
    data:function () {
        return {count:0}
    }
}
</script>

<style scoped>

</style>
