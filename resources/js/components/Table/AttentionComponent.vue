<template>
  <div class="d-flex flex-column justify-content-center">
    <div class="d-flex justify-content-center">
      <div class="border p-3 bg-light" style="border-radius: 5px;box-shadow: 1px 1px 5px black">
        <h2  class="text-center">{{title}}</h2>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "AttentionComponent",
  computed:{
    title:function () {
      return 'Start new round #1'
    }
  }
}
</script>

<style scoped>

</style>