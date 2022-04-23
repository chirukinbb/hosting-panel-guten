<template>
    <div class="table d-flex flex-column justify-content-center">
        <div class="d-flex justify-content-center">
            <div class="card-slot" v-for="i in count" :key="i">
                <span v-if="cards.length >= i" class="crd" :style="position(cards[i - 1].nominal,cards[i - 1].suit)"></span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CardsComponent",
    props:{
        cards: Object,
        count: Number,
        title: String
    },
    methods:{
        position:function (x,y) {
            x = -1 * ((x - 1) * 49.3602 + 29.4444)
            y  = -1 * (y * 69.1869 + 17.4717)

            return {
                backgroundPositionX:x+'px',
                backgroundPositionY:y+'px'
            }
        }
    }
}
</script>

<style scoped>

</style>
