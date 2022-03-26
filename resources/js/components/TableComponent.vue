<template>
  <div class="table-screen position-absolute top-0 start-0 end-0 bottom-0 bg-white">
      <h3>{{computedTitle}}</h3>
<!--      <div class="visual">-->
<!--          <ul class="position-relative">-->
<!--              <li v-for="(player,i) in table.players" :key="i">-->
<!--                  <div class="place">-->
<!--                      <h6>{{player.name}}</h6>-->
<!--                      <span class="amount">{{player.hand.amount}}</span>-->
<!--                      <img src="{{player.avatar}}" alt="">-->
<!--                      <div class="cards" v-if="player.hand.cards">-->
<!--                          <div class="card" v-for="(card,i) in player.hand.cards" :key="i">-->
<!--                              <span class="card-{{card.suit}}-{{card.suit}}"></span>-->
<!--                          </div>-->
<!--                      </div>-->
<!--                  </div>-->
<!--              </li>-->
<!--          </ul>-->
<!--          <div class="table-field"></div>-->
<!--      </div>-->
      <cards-component
          :count="5"
          :cards="table.round.cards"
          title="Cards on the Table"
      ></cards-component>
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
        }
    },
    components:{
        CardsComponent,
        ButtonsComponent
    }
}
</script>

<style scoped>
.table-screen{
    z-index: 999;
}
</style>
