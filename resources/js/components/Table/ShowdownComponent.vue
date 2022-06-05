<template>
  <div class="action-buttons d-flex justify-content-between position-absolute top-0 start-0 end-0">
    <div class="btn-group-left btn-group-lg">
      <button class="btn btn-secondary rounded-0 rounded-end" v-on:click="fold">
        Fold
      </button>
    </div>
    <div class="btn-group-right btn-group-lg">
      <button class="btn btn-secondary rounded-0 rounded-end" v-on:click="show">
        Show Cards
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: "ShowdownComponent",
  methods:{
    show:function () {

    },
    fold:function () {

    }
  }
}
</script>

<style scoped>

</style>