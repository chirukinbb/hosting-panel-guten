<template>
    <div class="col-8">
        <div v-for="(table,i) in tables" :key="i" class="bg-light m-1 py-2 px-4">
            <h3>{{table.title}}</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi deserunt dolor enim eum, ipsa ipsam laboriosam nesciunt nisi quae quidem. Delectus deserunt eaque expedita ipsam nam numquam quod ratione ut.</p>
            <button v-on:click="loaderScreen(table.className,i)" class="float-end btn btn-primary position-relative" :disabled="table.inSearch">
                <span>{{table.button}}</span>
                <span class="spinner-border text-white position-absolute" v-if="table.inSearch"></span>
            </button>
            <div class="clearfix"></div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ListComponent",
    data:function () {
        return {
            button:'Game',
            tables: [
                {title: 'Holdem, 2 players', className: 'HoldemTwoPokerTable'},
                {title: 'Omaha, 2 players', className: 'OmahaTwoPokerTable'},
            ]
        }
    },
    created:function () {
        let newTable = {},
            newTables = []

        this.tables.map(function (el) {
            newTable = {}
            Object.assign(newTable,el,{
                inSearch:false,
                button:'Find Game'
            })
            newTables.push(newTable)
        })

        this.tables = newTables
    },
    methods:{
        loaderScreen:function (className,i) {
            console.log(this.tables[i])
            this.tables[i].inSearch  = true
            this.tables[i].button  = 'Loading...'

            this.$emit('load-screen',className)
        }
    }
}
</script>

<style scoped>
.spinner-border{
    top: 1px;
    left: calc(50% - 1rem);
}
</style>
