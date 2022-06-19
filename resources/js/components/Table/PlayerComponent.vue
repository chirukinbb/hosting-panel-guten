<template>
  <div class="player d-flex position-relative" :style="disabled">
    <div class="d-flex player-label">
      <div class="col-auto avatar-container position-relative">
        <img :src="player.avatar" class="img-fluid rounded-circle avatar m-0" alt="">
        <timer-component
            :time="timer"
            @end-time="$emit('end-time', index)"
            class="position-absolute top-0  start-0"
            v-if="timer"
        />
        <div class="chips d-flex position-absolute bottom-0 end-0" v-if="player.round">
          <div class="dealer" v-if="player.round.chips.isDealer">D</div>
          <div class="big-blind" v-if="player.round.chips.isBB">BB</div>
          <div class="small-blind" v-if="player.round.chips.isLB">SB</div>
        </div>
      </div>
      <div class="col col-85">
        <strong class="name border-bottom">{{ player.name }}</strong>
        <p class="m-0 text-center" v-if="result">{{result}}</p>
        <p class="m-0 text-center" v-else>
          <span title="Bid">{{ player.amount.bid }}</span>/<span
            title="Chips in Hand">{{ player.amount.hand }}</span>
        </p>
      </div>
    </div>
    <div class="cards position-absolute bottom-0 d-flex justify-content-center" v-bind:class="{active:isActive,opacity:isNotYourShowdown}"
         v-if="player.round">

      <div v-if="showCloseCard" class="d-flex">
        <div class="card-slot" v-for="i in cardsInHand" :key="i">
          <div class="crd back" :style="style(i - 1)"></div>
        </div>
      </div>

      <div v-else class="card-slot" v-for="(card,i) in player.round.cards" :key="i" :style="{zIndex:zIndex(card)}">
        <div class="crd border d-flex flex-column justify-content-center" :style="style(i,card)">
          <div :class="suits[card.suit].class">
            {{ card.nominal }}<span v-html="suits[card.suit].code"></span>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import TimerComponent from "./TimerComponent";
export default {
  name: "PlayerComponent",
  components: {TimerComponent},
  props: {
    player: Object,
    index: Number,
    length: Number,
    showdownTime: Boolean,
    step:Number,
    cardsInHand:Number
  },
  data: function () {
    return {
      time: 0,
      suits: [
        {code: '&#9824;', class: 'text-dark'},
        {code: '&#9827;', class: 'text-dark'},
        {code: '&#9829;', class: 'text-danger'},
        {code: '&#9830;', class: 'text-danger'}
      ],
      results:[
          'Fold',
          'Check',
          'Raise',
          'All-In',
          'Call'
      ]
    }
  },
  computed: {
    disabled:function() {
      return this.player.place === null ? true : {opacity:0.5}
    },
    result:function () {
      return this.player.action_result !== null ? this.results[this.player.action_result] : false
    },
    timer: function () {
      return this.player.timer
    },
    isActive: function () {
      return this.player.round.isActive
    },
    showCloseCard:function () {
      return this.player.round.isActive && this.player.round.cards.length === 0 && this.step > 0
    },
    isNotYourShowdown:function () {
      if (this.step !== 6)
          return false

      return this.showdownTime && !this.player.round.showdown.turn
    }
  },
  methods: {
    style: function (i, card) {
      if (this.player.round.showdown === undefined || !this.showdownTime)
        return {marginLeft: -i * (100 - (100 / this.cardsInHand)) + '%'}

      if (!this.player.round.showdown.turn) {
        return {marginLeft: -i * (100 - (100 / this.cardsInHand)) + '%'}
      } else {
        let result = false

        for (let i in this.player.round.showdown.cards) {
          if (this.player.round.showdown.cards[i].nominal === card.nominal && this.player.round.showdown.cards[i].suit === card.suit)
            result = true
        }

        return result ? {
          marginLeft: -i * (100 - (100 / this.cardsInHand)) + '%',
          zIndex: 4
        } : {
          marginLeft: -i * (100 - (100 / this.cardsInHand)) + '%',
          opacity: 0.4
        }
      }
    },
    isOpen: function (card) {
      return card.nominal !== undefined
    },
    zIndex:function (card) {
      if (this.player.round.showdown === undefined || this.showdownTime && !this.player.round.showdown.turn || !this.player.round.showdown)
        return 0

      let result = false

      for (let i in this.player.round.showdown.cards) {
        if (this.player.round.showdown.cards[i].nominal === card.nominal && this.player.round.showdown.cards[i].suit === card.suit)
          result = true
      }

      return result ? 4 : 0
    },
  }
}
</script>

<style scoped>
.avatar {
  height: 50px;
  width: 50px;
  display: block;
}

.avatar-container {
  padding: 2px;
}

.player {
  border-radius: 27px;
  border: 1px solid black;
  width: 140px;
}

.name {
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
  display: block;
}

.opacity {
  opacity: .4;
}

.player-label {
  z-index: 10;
  border-radius: 27px;
  background-color: silver;
}

.crd {
  height: 65px;
  width: 46px;
  display: block;
  margin: 5px;
  border-radius: 2px;
  background-color: white;
  font-size: 25px;
  padding-bottom: 30px;
}

.back {
  background-image: url("/img/back.PNG");
}

.cards {
  z-index: 9;
  left: 15%;
}

.card-slot {
  margin-bottom: 15px;
}

.chips > div {
  height: 25px;
  width: 25px;
  border-radius: 50%;
  border: 1px solid black;
  background-color: antiquewhite;
  text-align: center;
  margin: 1px;
}

.col-85 {
  width: 85px;
}

.name, p {
  width: 70px;
}

.cards:not(.active) {
  opacity: .4;
}

.cards:not(.active):hover {
  opacity: 1;
}
</style>
