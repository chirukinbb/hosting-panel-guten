<template>
  <div class="table-field">
      <ul>
          <li v-for="player in players">
              <img src="{{player.avatar}}" alt="">
          </li>
      </ul>
  </div>
</template>

<script>
export default {
    name: "TableComponent",
    data:function () {
        return {
            players:[
                {
                    avatar:null
                }
            ]
        }
    }
}
</script>

<style scoped>

</style>
