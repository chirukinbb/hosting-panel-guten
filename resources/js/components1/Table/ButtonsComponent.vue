<template>
    <div class="action-buttons d-flex justify-content-between position-absolute top-0">
        <div class="btn-group-left">
            <button class="btn btn-outline-light" v-on:click="fold" v-if="canFold">
                Fold
            </button>
            <button class="btn btn-outline-light" v-if="canCheck" v-on:click="check">
                Check
            </button>
            <button class="btn btn-outline-light" v-if="canCall" v-on:click="call">
                Call
            </button>
        </div>
        <div class="btn-group-right">
            <button class="btn btn-outline-light" v-if="canRaise" v-on:click="raise">
                Raise
            </button>
            <button class="btn btn-outline-light" v-if="canAllIn" v-on:click="allIn">
                All-In
            </button>
          <div class="d-flex text-white">
            <div class="raise-on">
              <input type="number" class="form-control bg-dark text-white" min="1" v-model="amount">
            </div>
            <span class="m-2">
              <small>&#9587;</small> BB
            </span>
          </div>
        </div>
    </div>
</template>

<script>
import axios from "axios"

export default {
    name: "ButtonsComponent",
    props:{
        actions:Array,
        currentTurn:Boolean
    },
  data:function () {
    return { amount:1 }
  },
    computed:{
        canFold:function () {
          console.log(this.actions)
            return this.actions[0].is_active
        },
        canCall:function () {
          return true//this.actions[4].is_active
        },
        canCheck:function () {
          return this.actions[1].is_active
        },
        canRaise:function () {
          return this.actions[2].is_active
        },
        canAllIn:function () {
          return this.actions[3].is_active
        }
    },
  methods:{
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
        action_id:1,
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
  width: 70px;
}
small{
  font-size: .55em;
}

</style>
