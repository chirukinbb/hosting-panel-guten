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
import socket from "../socket";

export default {
    name: "LoaderComponent",
  props:{
      count:Number,
      channel:String
  },
  data:function () {
    return {
      isSearch:true,
      socket:null
    }
  },
  methods:{
      leaving:function () {
        this.isSearch  = false

        this.$emit('leave-turn')
      }
  },
  created() {
    this.socket = socket.connect().private(this.channel).listen('.turn', e => {
        console.log(e)
        switch (e.screen) {
            case 'loader':
                e.count ? this.$emit('set-count', e.count) : this.$emit('load-table',e.newChannel,e.table)
                break
            case 'table':
                this.$emit('load-table',e.newChannel, e.table)
                break
        }
    })
  }
}
</script>

<style scoped>
.spinner-border{
  top: 1px;
  left: calc(50% - 1rem);
}
</style>
