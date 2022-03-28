<template>
    <div class="player row">
        <div class="col-auto avatar-container">
            <img :src="player.avatar" class="img-fluid rounded-circle avatar m-0" alt="">
        </div>
        <div class="col">
            <strong class="name border-bottom">{{player.name}}</strong>
            <p v-if="player.timer.start" class="m-0">{{player.timer.start}}</p>
            <p v-else class="m-0">{{player.amount.bank}}/{{player.amount.hand}}</p>
        </div>
    </div>
</template>

<script>
export default {
    name: "PlayerComponent",
    data:function () {
        return  {
            player: {
                name: 'John Doe',
                avatar: '/img/JohnDoe.webp',
                amount: {
                    hand: 502,
                    bank: 50
                },
                action: {
                    message: 'Raise to 100',
                    hand: 452,
                    bank: 100
                },
                timer:{
                    start:20
                }
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
    width: 250px;
    background-color: silver;
}
</style>
