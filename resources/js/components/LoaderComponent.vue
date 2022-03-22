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
  props:{ count:Number }
}
</script>

<style scoped>

</style>
