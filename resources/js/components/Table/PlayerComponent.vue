<template>
    <div class="player d-flex position-relative">
        <div class="d-flex player-label">
            <div class="col-auto avatar-container">
                <img :src="player.avatar" class="img-fluid rounded-circle avatar m-0" alt="">
            </div>
            <div class="col">
                <strong class="name border-bottom">{{player.name}}</strong>
                <p v-if="player.timer.start" class="m-0 text-center">{{player.timer.start}}</p>
                <p v-else class="m-0 text-center">{{player.amount.bank}}/{{player.amount.hand}}</p>
            </div>
        </div>
        <div class="cards position-absolute bottom-0 d-flex justify-content-center" v-if="player.hand">
            <div class="card-slot" v-for="(card,i) in player.hand.cards" :key="i">
                <span class="crd" :style="position(i,card.nominal,card.suit)"></span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "PlayerComponent",
    props:{ player:Object },
    methods:{
        position:function (i,x,y) {
            x = -1 * (x * 164.534 + 98.148)
            y  = -1 * (y * 230.623 + 58.239)

            return {
                backgroundPositionX:x+'px',
                backgroundPositionY:y+'px',
                marginLeft: -i * (100 - (100 / this.player.hand.cards.length)) + '%'
            }
        }
    }
}
</script>

<style scoped>
.avatar{
    height: 50px;
    width: 50px;
    display: block;
}
.avatar-container{
    padding: 2px;
}
.player{
    border-radius: 27px;
    border: 1px solid black;
    width: 140px;
}
.name{
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
    width: 85px;
    display: block;
}
.player-label{
    z-index: 10;
    border-radius: 27px;
    background-color: silver;
}
.cards{
    z-index: 9;
    left: 15%;
}
</style>
