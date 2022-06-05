<template>
  <div class="position-relative">
    <div class="position-absolute d-flex flex-column justify-content-center h-100 w-100" style="z-index: 999;">
      <p class="text-center text-white m-0 h2">{{seconds}}</p>
    </div>
    <svg width="54px" height="54px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid meet">
      <circle class="sector" r="25" cx="50" cy="50" :style="sector"/>
      <circle class="border" r="49" cx="50" cy="50" />
    </svg>
  </div>
</template>

<script>
export default {
  name: "TimerComponent",
  data:function () {
    return {
      length:0,
      perTime:0,
      seconds:0
    }
  },
  props:{
    time:Number
  },
  created() {
    this.length = 50 * Math.PI
    this.perTime = 1 / this.time
    this.seconds = this.time

    setInterval(() => {
      this.seconds --

      if (this.seconds === 0)
        this.$emit('end-time')
    }, 1000)
  },
  computed:{
    sector:function () {
      return {
        strokeDasharray: this.seconds * this.perTime * this.length + ',' + this.length
      }
    }
  }
}
</script>

<style scoped>
.border{
  fill: none;
  stroke: green;
  stroke-width: 1px;
}
.sector{
  fill: none;
  stroke: green;
  stroke-width: 50px;
}

svg{
  opacity: .5;
  transform: rotate(-90deg) scaleY(-1);
}
</style>