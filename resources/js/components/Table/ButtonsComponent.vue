<template>
    <div class="btn-group">
        <button v-for="(button,i) in buttons" :key="i" class="btn btn-outline-primary" :disabled="button.callback">
            {{button.title}}
        </button>
        <div class="form-check m-2">
            <input type="checkbox" class="form-check-input" id="afk" v-model="isAFK">
            <label class="form-check-label" for="afk">AFK</label>
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
    data:function () {
        return {
            buttons:[
                {title:'Call',callback:'canCall'},
                {title:'Fold',callback:'canFold'},
                {title:'Check',callback:'canCheck'},
                {title:'Bet',callback:'canBet'},
                {title:'Raise',callback:'canRaise'},
                {title:'All-In',callback:'canAllIn'},
            ],
            isAFK:false
        }
    },
    computed:{
        canCall:function () {
            return !(this.actions.canCall && this.currentTurn)
        },
        canCheck:function () {
            return !(this.actions.canCheck && this.currentTurn)
        },
        canBet:function () {
            return !(this.actions.canBet && this.currentTurn)
        },
        canRaise:function () {
            return !(this.actions.canRaise && this.currentTurn)
        },
        canAllIn:function () {
            return !(this.actions.canAllIn && this.currentTurn)
        },
        computedTitle:function () {
            return this.table.title.replace('{blind}', this.table.blind)
                .replace('{ante}', this.table.round.ante)
        }
    }
}
</script>

<style scoped>

</style>
