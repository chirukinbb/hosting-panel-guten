<template>
    <div class="player d-flex position-relative">
        <div class="d-flex player-label">
            <div class="col-auto avatar-container">
                <img :src="player.avatar" class="img-fluid rounded-circle avatar m-0" alt="">
            </div>
            <div class="col">
                <strong class="name border-bottom">{{ player.name }}</strong>
                <p v-if="player.timer" class="m-0 text-center">{{ player.timer }}</p>
                <p v-else class="m-0 text-center">
                    <span title="Bid">{{ player.amount.bid }}</span>/<span
                    title="Chips in Hand">{{ player.amount.hand }}</span>
                </p>
            </div>
        </div>
        <div class="cards position-absolute bottom-0 d-flex justify-content-center" v-if="player.round">
            <div class="card-slot" v-for="(card,i) in player.round.cards" :key="i">
                <span class="crd" :style="position(i,card.nominal,card.suit)"></span>
            </div>
        </div>
        <div class="chips d-flex position-absolute" :style="chipsPosition()" v-if="player.round">
            <div class="dealer" v-if="player.round.isDealer">D</div>
            <div class="big-blind" v-if="player.round.isBB">BB</div>
            <div class="small-blind" v-if="player.round.isLB">SB</div>
        </div>
    </div>
</template>

<script>
export default {
    name: "PlayerComponent",
    props: {
        player: Object,
        index: Number,
        length: Number
    },
    data: function () {
        return {time: 0}
    },
    computed: {
        timer: function () {
            return !!this.player.timer
        }
    },
    methods: {
        position: function (i, x, y) {
            x = -1 * ((x - 1) * 49.3602 + 29.4444)
            y = -1 * (y * 69.1869 + 17.4717)

            return {
                backgroundPositionX: x + 'px',
                backgroundPositionY: y + 'px',
                marginLeft: -i * (100 - (100 / this.player.round.cards.length)) + '%'
            }
        },
        chipsPosition: function () {
            let a = 360 / (this.length + 1),
                b = 180 - (a * (this.index + 1)),
                bRad = b * 3.14 / 180,
                sin = Math.sin(bRad),
                cos = Math.cos(bRad),
                top = (cos < 0) ? 60 * Math.abs(1 - cos) : -45 * Math.abs(cos),
                left = (sin > 0) ? -45 * Math.pow(sin, 5) : (100 * Math.abs(sin) + 45 * Math.pow(1 + sin, 5))
            console.log(left, sin)
            return {
                top: top + '%',
                left: left + '%'
            }
        }
    },
    // created: function () {
    //     setInterval(() => {
    //         if (this.player.timer) {
    //             this.player.timer--
    //             console.log(this.player.timer)
    //         }
    //     }, 1000)
    // }
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
    width: 85px;
    display: block;
}

.player-label {
    z-index: 10;
    border-radius: 27px;
    background-color: silver;
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
}
</style>
