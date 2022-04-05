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
import Echo from "laravel-echo"
window.Pusher = require('pusher-js')

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
  created(event, callback) {
    this.socket = new Echo({
      broadcaster: 'pusher',
      key: '4326370c5eb04b2329d3',
      wsHost: window.location.hostname,
      wsPort: 6001,
      forceTLS: false,
      authorizer: (channel, options) => {
        return {
          authorize: (socketId, callback) => {
            axios.post('/api/broadcasting/auth', {
              socket_id: socketId,
              channel_name: channel.name
            },{
              headers: {
                Authorization: 'Bearer '+document.querySelector('meta[name="api-token"]').getAttribute('content'),
              }
            })
                .then(response => {
                  callback(false, response.data);
                })
                .catch(error => {
                  callback(true, error);
                });
          }
        };
      },
      disableStats: false
    }).private(this.channel).listen('.turn', e => {
        console.log(e)
        switch (e.screen) {
            case 'loader':
                this.$emit('set-count', e.count)
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
