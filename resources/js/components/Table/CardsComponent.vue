<template>
  <div class="table d-flex flex-column justify-content-center">
    <div class="pt-5">
      <div class="d-flex justify-content-center">
        <div class="card-slot" v-for="i in count" :key="i">
          <div class="crd border d-flex flex-column justify-content-center" v-if="cards.length >= i"
               :class="opacityClass(cards[i - 1])">
            <div :class="suits[cards[i-1].suit].class" class="text-center">
              {{ cards[i - 1].nominal }}<span v-html="suits[cards[i-1].suit].code"></span>
            </div>
          </div>
        </div>
      </div>
      <p class="text-center text-white">{{combo}}</p>
    </div>
  </div>
</template>

<script>
export default {
  name: "CardsComponent",
  props: {
    cards: Object,
    count: Number,
    cardsInCombo: Array,
    step: Number,
    combo:String
  },
  data: function () {
    return {
      suits: [
        {code: '&#9824;', class: 'text-dark'},
        {code: '&#9827;', class: 'text-dark'},
        {code: '&#9829;', class: 'text-danger'},
        {code: '&#9830;', class: 'text-danger'}
      ]
    }
  },
  methods: {
    position: function (x, y) {
      x = -1 * ((x - 1) * 49.3602 + 29.4444)
      y = -1 * (y * 69.1869 + 17.4717)

      return {
        backgroundPositionX: x + 'px',
        backgroundPositionY: y + 'px'
      }
    },
    opacityClass: function (card) {
      if (this.step !== 6)
        return true

      let result = false

      for (let i in this.cardsInCombo) {
        if (this.cardsInCombo[i].nominal === card.nominal && this.cardsInCombo[i].suit === card.suit)
          result = true
      }

      return result ? true : 'opacity'
    }
  }
}
</script>

<style scoped>
.crd {
  height: 65px;
  width: 46px;
  display: block;
  margin: 5px;
  border-radius: 2px;
  background-color: white;
  font-size: 25px;
}

.opacity {
  opacity: .4;
}
</style>
