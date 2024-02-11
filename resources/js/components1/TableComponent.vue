<template>
  <div class="table-screen position-absolute top-0 start-0 end-0 bg-dark">
      <h3 class="text-center text-white">{{computedTitle}}</h3>
      <div class="d-flex justify-content-center mt-5">
          <div class="poker-table position-relative">
              <cards-component
                  v-if="table.round"
                  :count="5"
                  :cards="table.round.cards"
                  class="position-absolute top-0 start-0 end-0 bottom-0"
              ></cards-component>
              <button-component
                  class="position-absolute"
                  style="top:-20px;left: 180px"
                  :actions="actions"
              ></button-component>
              <player-component
                  v-for="(player,i) in table.players"
                  class="position-absolute"
                  :player="player"
                  :style="playerPosition(i)"
                  :index="i"
                  :length="table.players.length"
              ></player-component>
          </div>
      </div>
      <buttons-component
          :actions="actions"
          class="position-absolute end-0 start-0 bottom-100"
      ></buttons-component>
    </div>
</template>

<script>
import CardsComponent from "./Table/CardsComponent"
import ButtonsComponent from "./Table/ButtonsComponent"
import PlayerComponent from "./Table/PlayerComponent"
import ButtonComponent from "./Table/ButtonComponent"
import Echo from "laravel-echo";

export default {
    name: "TableComponent",
    props:{
      table:Object,
      channel:String
    },
    data:function () {
        return {
            actions:[],
            combo:''
        }
    },
    created:function () {
        document.querySelector('title').innerHTML = this.computedTitle

        this.table.players.forEach((player) => {
            if (player.actions){
                this.actions = player.actions
                this.combo = player.round.combo
            }
        })
        //console.log(this.actions)

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
      }).private(this.channel).listen('.table', e => {
          console.log(e)
        switch (e.screen) {
          case 'table':
            this.table = e.table
            break
        }
      })
    },
    computed:{
        canCall:function () {
            return !(this.player.actions.canCall && this.player.myTurn)
        },
        canCheck:function () {
            return !(this.player.actions.canCheck && this.player.myTurn)
        },
        canBet:function () {
            return !(this.player.actions.canBet && this.player.myTurn)
        },
        canRaise:function () {
            return !(this.player.actions.canRaise && this.player.myTurn)
        },
        canAllIn:function () {
            return !(this.player.actions.canAllIn && this.player.myTurn)
        },
        computedTitle:function () {
            return this.combo ?
                this.combo :
                this.table.title
        }
    },
    methods:{
        cardsCountInHand:function () {
            return this.player.hand.cards.length
        },
        playerPosition:function (index) {
            let a = 360 / (this.table.players.length + 1),
                b = 180 - (a * (index + 1)),
                bRad = b * 3.14 / 180,
                l = Math.sin(bRad) * 250,
                h = (Math.cos(bRad) * 250) / 2,
                delta = (54 * (Math.cos(bRad) - 1)) / 2
            console.log(a)
            return {
                top: ((h + 125) + delta - 10) + 'px',
                left: (l + 180) + 'px'
            }
        }
    },
    components:{
        CardsComponent,
        ButtonsComponent,
        PlayerComponent,
        ButtonComponent
    }
}
</script>

<style scoped>
.table-screen{
    z-index: 999;
    height: 100vh;
}
.poker-table{
    /* F = 216.5 */
    height: 250px;
    width: 500px;
    border: 2px solid black;
    background-color: #0c4128;
    border-radius: 50%;
    box-shadow: 0 15px 100px 10px rgba(255,255,255,.5);
}
</style>
