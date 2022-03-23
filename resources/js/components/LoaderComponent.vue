<template>
    <div class="load col-8 bg-light m-1 py-2 px-4">
      <p class="text-center">Waiting opponents({{count}})...</p>
      <button v-on:click="leaving" class="btn  btn-info float-end position-relative" :disabled="!isSearch">
        <span v-if="isSearch">Leave turn</span>
        <span v-else>Leaving...</span>
        <span class="spinner-border text-white position-absolute" v-if="!isSearch"></span>
      </button>
      <div class="clearfix"></div>
    </div>
</template>

<script>
export default {
    name: "LoaderComponent",
  props:{ count:Number },
  data:function () {
    return { isSearch:true }
  },
  methods:{
      leaving:function () {
        this.isSearch  = false

        this.$emit('leave-turn')
      }
  }
}
</script>

<style scoped>
.spinner-border{
  top: 1px;
  left: calc(50% - 1rem);
}
</style>
