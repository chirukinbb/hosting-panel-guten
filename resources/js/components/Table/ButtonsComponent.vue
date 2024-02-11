<template>
  <div class="position-absolute top-0">
    <div class="d-flex justify-content-between action-buttons">
        <div class="btn-group-left btn-group-lg btn-group d-inline">
            <button class="btn btn-secondary me-1 rounded-0" v-on:click="fold" v-if="actions.canFold">
                Fold
            </button>
            <button class="btn btn-secondary" v-if="actions.canCheck" v-on:click="check">
                Check
            </button>
            <button class="btn btn-secondary" v-if="actions.canCall" v-on:click="call">
                Call
            </button>
        </div>
        <div class="btn-group-right btn-group-lg btn-group d-inline ms-auto">
            <button class="btn btn-secondary" v-if="actions.canRaise" v-on:click="raise">
                Raise
            </button>
            <button class="btn btn-secondary ms-1 rounded-0" v-if="actions.canAllIn" v-on:click="allIn">
                All-In
            </button>
        </div>
    </div>
    <div class="mt-1 d-flex text-white justify-content-end" style="height: 38px; overflow: hidden">
      <button class="btn btn-secondary rounded-0 rounded-start" @click="minus" :disabled="minusButtonDisabled">-</button>
      <div class="raise-on mx-1">
        <input type="number"
               class="form-control bg-dark text-white border"
               inputmode="numeric"
               min="1"
               :max="amountInHand"
               @keyup="checkAmount"
               v-model="amount"
        >
      </div>
      <button class="btn btn-secondary rounded-0 rounded-end" @click="add" :disabled="addButtonDisabled">+</button>
      <span class="m-2 mx-1">
              <small>&#9587;</small> BB
            </span>
    </div>
  </div>
</template>

<script>
import axios from "axios"

export default {
    name: "ButtonsComponent",
    props:{
        actions:Array,
        currentTurn:Boolean,
      amountInHand:Number
    },
  data:function () {
    return {
      amount:1
    }
  },
  computed:{
    addButtonDisabled:function () {
      return this.amountInHand < (this.amount + 1)
    },
    minusButtonDisabled:function () {
      return this.amountInHand > 1  &&  this.amount <= 1
    }
  },
  methods:{
      checkAmount:function () {
        if (this.amount > this.amountInHand)
          this.amount = this.amountInHand
      },
      add:function () {
        this.amount ++
      },
      minus:function () {
        this.amount --
      },
      fold:function () {
        this.request({ action_id:0 })
      },
    call:function () {
      this.request({ action_id:4 })
    },
    check:function () {
      this.request({ action_id:1 })
    },
    raise:function () {
      this.request({
        action_id:2,
        amount: this.amount
      })
    },
    allIn:function () {
      this.request({ action_id:3 })
    },
      request:function (data) {
        axios.post('/api/table/turn-action', data)
      }
  }
}
</script>

<style scoped>
input{
  width: 48px;
}
small{
  font-size: .55em;
}

input[type=number]{
  -moz-appearance: textfield;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

.btn:focus{
  box-shadow: none!important;
}
</style>
