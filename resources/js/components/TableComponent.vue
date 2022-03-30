<template>
  <div class="table-screen position-absolute top-0 start-0 end-0 bg-dark">
      <h3>{{computedTitle}}</h3>
      <div class="d-flex justify-content-center mt-5">
          <div class="poker-table position-relative">
              <cards-component
                  :count="5"
                  :cards="table.round.cards"
                  class="position-absolute top-0 start-0 end-0 bottom-0"
              ></cards-component>
              <button-component
                  class="position-absolute"
                  style="top:-20px;left: 180px"
              ></button-component>
              <player-component
                  v-for="(player,i) in table.players"
                  class="position-absolute"
                  :player="player"
                  :style="playerPosition(i)"
              ></player-component>
          </div>
      </div>
      <buttons-component
          :actions="player.actions"
          :turn="currentTurn"
          class="position-absolute end-0 start-0 bottom-100"
      ></buttons-component>
    </div>
</template>

<script>
import CardsComponent from "./Table/CardsComponent"
import ButtonsComponent from "./Table/ButtonsComponent"
import PlayerComponent from "./Table/PlayerComponent"
import ButtonComponent from "./Table/ButtonComponent"

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
                    cards:[
                        { nominal:5,suit:1 },
                        { nominal:0,suit:3 },
                        { nominal:12,suit:0 }
                    ]
                },
                players: [
                    {
                        name: 'John Doe',
                        avatar: '/img/JohnDoe.webp',
                        myTurn:true,
                        isDealer: true,
                        isBB: false,
                        isLB: false,
                        actions:{
                            canCall:true,
                            canCheck:true,
                            canBet:true,
                            canRaise:true,
                            canAllIn:true
                        },
                        hand: {
                            cards: [
                                {nominal: 5, suit: 2},
                                {nominal: 6, suit: 2},
                                {nominal: 5, suit: 2},
                                {nominal: 6, suit: 2},
                            ],
                            combo:'jjjjj',
                            amount: 1000,
                            inGame:true
                        },
                        amount: {
                            hand: 502,
                            bank: 50
                        },
                        action: {
                            message: 'Raise to 100',
                            hand: 452,
                            bank: 100
                        },
                        timer: {
                            start: 0
                        }
                    },
                    {
                        name: 'John Doe',
                        avatar: '/img/JohnDoe.webp',
                        myTurn:true,
                        isDealer: true,
                        isBB: false,
                        isLB: false,
                        amount: {
                            hand: 502,
                            bank: 50
                        },
                        action: {
                            message: 'Raise to 100',
                            hand: 452,
                            bank: 100
                        },
                        timer: {
                            start: 20
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
            if (typeof player.hand === 'object')
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
                h = (Math.cos(bRad) * 250) / 2,
                delta = (54 * (Math.cos(bRad) - 1)) / 2

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
