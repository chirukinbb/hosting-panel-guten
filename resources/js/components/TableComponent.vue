<template>
  <div class="table-screen position-absolute top-0 start-0 end-0 bg-dark">
    <h3 class="text-center text-white pb-2  mt-2">{{ table.title }}</h3>
    <div class="d-flex justify-content-center mt-4">
      <div class="poker-table position-relative">
        <cards-component
            v-if="table.round.cards.length"
            :count="5"
            :cards="table.round.cards"
            :cards-in-combo="cardsInCombo"
            :step="table.round.step"
            :combo="combo"
            class="position-absolute top-0 start-0 end-0 bottom-0"
        ></cards-component>
        <button-component
            class="position-absolute"
            style="top:-20px;left: 180px"
            :bank="table.round.bank"
        ></button-component>
        <player-component
            v-for="(player,i) in table.players"
            class="position-absolute"
            :player="player"
            :style="playerPosition(i)"
            :index="i"
            :length="table.players.length"
            :key="player.id"
            :cards-in-hand="table.cardsInHand"
            :step="table.round.step"
            @end-time="timeEnd"
        ></player-component>
      </div>
    </div>
    <buttons-component
        :actions="player.actions"
        v-if="isActionButtons"
        :amount-in-hand="player.amount.hand"
        class="position-absolute end-0 start-0 bottom-100"
        style="z-index: 999;"
    ></buttons-component>

    <showdown-component v-if="player.round.showdown.turn"/>
    <finish-component
        :place="player.place"
        :rating="player.rating"
        style="z-index: 99"
        class="position-absolute top-0 bottom-0 start-0 end-0"
        v-if="player.place !== null"
    />
  </div>
</template>

<script>
import CardsComponent from "./Table/CardsComponent"
import ButtonsComponent from "./Table/ButtonsComponent"
import PlayerComponent from "./Table/PlayerComponent"
import ButtonComponent from "./Table/ButtonComponent"
import ShowdownComponent from "@/components/Table/ShowdownComponent";
import FinishComponent from "@/components/Table/FinishComponent";
import Echo from "laravel-echo";

export default {
  name: "TableComponent",
  props: {
    table: Object,
    channel: String
  },
  created: function () {
    document.querySelector('title').innerHTML = this.table.title

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
  computed: {
    player:function () {
      let p = {}

      this.table.players.map((player) => {
        if (player.actions) {
          p = player
        }
      })

      return p
    },
    cardsInCombo:function () {
      let cards = []

      if (this.table.round.step === 7){
        this.table.players.map((player) => {
          if (player.round.showdown.turn) {
            cards = player.round.showdown.cards
          }
        })
      }

      return cards
    },
    isActionButtons:function () {
      let active = false

      for(let key in this.player.actions) {
        if (this.player.actions[key])
          active = true
      }

      return active
    },
    combo:function () {
      if (this.table.round.step < 3)
        return false

      let combo = null

      if (this.table.round.step === 6){
        this.table.players.map(player => {
          console.log(player)
        })
      }else {
        combo = this.player.round.combo
      }

      return combo;
    }
  },
  methods: {
    timeEnd:function (index) {
      this.table.players[index].timer = 0

      if (this.table.players[index].actions) {
        for (let key in this.table.players[index].actions) {
          this.table.players[index].actions[key] = false
        }
      }

      this.table.players[index].round.showdown.turn = false
    },
    playerPosition: function (index) {
      let a = 360 / (this.table.players.length + 1),
          b = 180 - (a * (index + 1)),
          bRad = b * 3.14 / 180,
          l = Math.sin(bRad) * 250,
          h = (Math.cos(bRad) * 250) / 2,
          delta = (54 * (Math.cos(bRad) - 1)) / 2

      return {
        top: ((h + 125) + delta - 10) + 'px',
        left: (l + 180) + 'px'
      }
    }
  },
  components: {
    FinishComponent,
    CardsComponent,
    ButtonsComponent,
    PlayerComponent,
    ButtonComponent,
    ShowdownComponent
  }
}
</script>

<style scoped>
.table-screen {
  z-index: 999;
  height: 100vh;
}

.poker-table {
  /* F = 216.5 */
  height: 250px;
  width: 500px;
  border: 2px solid black;
  background-color: #0c4128;
  border-radius: 50%;
  box-shadow: 0 15px 100px 10px rgba(255, 255, 255, .5);
}
</style>
