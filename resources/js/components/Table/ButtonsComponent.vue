<template>
    <div class="action-buttons">
        <div class="btn-group position-absolute start-0">
            <button class="btn btn-outline-light" v-if="canFold">
                Fold
            </button>
            <button class="btn btn-outline-light" v-if="canCheck">
                Check
            </button>
            <button class="btn btn-outline-light" v-if="canCall">
                Call
            </button>
        </div>
        <div class="btn-group position-absolute end-0">
            <button class="btn btn-outline-light" v-if="canRaise">
                Raise
            </button>
            <button class="btn btn-outline-light" v-if="canCheck">
                All-In
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "ButtonsComponent",
    props:{
        actions:Object,
        currentTurn:Boolean
    },
    computed:{
        canFold:function () {
            return true
        },
        canCall:function () {
            return !(this.actions.canCall && this.currentTurn)
        },
        canCheck:function () {
            console.log(this.actions)
            return !(this.actions.canCheck && this.currentTurn)
        },
        canRaise:function () {
            return !(this.actions.canRaise && this.currentTurn)
        },
        canAllIn:function () {
            return !(this.actions.canAllIn && this.currentTurn)
        }
    }
}
</script>

<style scoped>

</style>
