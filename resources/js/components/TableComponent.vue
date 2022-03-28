<template>
  <div class="table-screen position-absolute top-0 start-0 end-0 bg-white">
      <h3>{{computedTitle}}</h3>
      <div class="d-flex justify-content-center">
          <div class="poker-table position-relative">
              <cards-component
                  :count="5"
                  :cards="table.round.cards"
                  title="Cards on the Table"
              ></cards-component>
              <player-component
                  v-for="(player,i) in table.players"
                  class="position-absolute"
                  :style="playerPosition(i)"
              ></player-component>
          </div>
      </div>
      <div class="row m-0">
          <div class="col-6">
              <cards-component
                  :count="cardsCountInHand()"
                  :cards="player.hand.cards"
                  title="Cards in the Hand"
              ></cards-component>
          </div>
          <div class="col-6">
              <p class="combo">
                  {{player.hand.combo}}
              </p>
              <buttons-component
                  :actons="player.actions"
                  :turn="currentTurn"
              ></buttons-component>
          </div>
      </div>
    </div>
</template>

<script>
import CardsComponent from "./Table/CardsComponent"
import ButtonsComponent from "./Table/ButtonsComponent"
import PlayerComponent from "./Table/PlayerComponent"

export default {
    name: "TableComponent",
    data:function () {
        return {
            table: {
                title:'Holdem, 2 Players, Blind {blind}, Ante {ante}',
                blind:10,
                cardsInHand:2,
                round:{
                    number: 0,
                    bank:[0],
                    ante:0,
                    cards:[]
                },
                players: [
                    {
                        avatar: null,
                        name: 'Louis',
                        myTurn:true,
                        isDealer: true,
                        isBB: false,
                        isLB: false,
                        actions:{
                            canCall:false,
                            canCheck:true,
                            canBet:true,
                            canRaise:true,
                            canAllIn:true
                        },
                        hand: {
                            cards: [
                                {nominal: 5, suit: 2},
                                {nominal: 6, suit: 2},
                            ],
                            combo:'jjjjj',
                            amount: 1000,
                            inGame:true
                        }
                    },
                    {
                        avatar: null,
                        name: 'Louis',
                        myTurn:true,
                        isDealer: true,
                        isBB: false,
                        isLB: false,
                        actions:{
                            canCall:false,
                            canCheck:true,
                            canBet:true,
                            canRaise:true,
                            canAllIn:true
                        },
                        hand: {
                            cards: [
                                {nominal: 5, suit: 2},
                                {nominal: 6, suit: 2},
                            ],
                            combo:'jjjjj',
                            amount: 1000,
                            inGame:true
                        }
                    },
                    {
                        avatar: null,
                        name: 'John',
                        isDealer: true,
                        isBB: false,
                        isLB: false,
                        hand: {
                            amount: 1000,
                            inGame:true
                        }
                    },
                ]
            },
            player:{},
            currentTurn: false
        }
    },
    created:function () {
        document.querySelector('title').innerHTML = this.computedTitle

        this.table.players.forEach((player) => {
            if (typeof player.hand.cards === 'object')
                this.player = player
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
            return this.table.title.replace('{blind}', this.table.blind)
                .replace('{ante}', this.table.round.ante)
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
                h = (Math.cos(bRad) * 250) / 2
            console.log(a,l,h,b,bRad)
            return {
                top: (h + 125) + 'px',
                left: (l + 125) + 'px'
            }
        }
    },
    components:{
        CardsComponent,
        ButtonsComponent,
        PlayerComponent
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
}
</style>
