<template>
    <list-component v-if="isList" v-on:load-screen="loadScreen"></list-component>
    <loader-component v-if="isLoad" v-on:leave-turn="leaveTurn"></loader-component>
    <table-component v-if="isTable"></table-component>
</template>

<script>
import ListComponent from "./ListComponent"
import LoaderComponent from "./LoaderComponent"
import TableComponent from "./TableComponent"

import axios from 'axios'

export default {
    name: "GameComponent",
    components: {
        ListComponent,
        LoaderComponent,
        TableComponent
    },
    data:function () {
        return {
            isList:false,
            isLoad:false,
            isTable:false
        }
    },
    created:function () {
        axios
            .get('/api/turn/state')
            .then(response => {
                switch (response.data.screen) {
                    case 'list':
                        this.isList = true
                        break
                    case 'loader':
                        this.isLoad = true
                        break
                    case 'table':
                        this.isTable = true
                        break
                }
            })
    },
    methods: {
        loadScreen:function () {
            this.isList = false
            this.isLoad = true
        },
        leaveTurn:function () {
            this.isList = true
            this.isLoad = false
        }
    }
}
</script>

<style scoped>

</style>
