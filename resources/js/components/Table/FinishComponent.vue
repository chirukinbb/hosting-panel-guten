<template>
  <div class="d-flex flex-column justify-content-center">
    <div class="d-flex justify-content-center">
      <div class="border p-4 bg-light" style="border-radius: 5px;box-shadow: 1px 1px 5px black">
        <h2  class="text-center">Congratulate!</h2>
        <p>Your tournament position {{place}}({{rating}} to rating)</p>
        <button class="btn btn-secondary w-100 text-center" @click="goToLobby">OK</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "FinishComponent",
  props:{
    place:Number,
    rating:Number
  },
  methods:{
    goToLobby:function () {

    }
  }
}
</script>

<style scoped>

</style>