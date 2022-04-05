<template>
    <div v-if="error" class="alert alert-danger" role="alert">
        action not available: {{error}}
    </div>
    <list-component v-if="isList" v-on:load-screen="loadScreen"></list-component>
    <loader-component v-if="isLoad" v-on:leave-turn="leaveTurn" :count="count" :channel="this.channel"></loader-component>
    <table-component v-if="isTable" :table="table"></table-component>
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
            isTable:false,
            error:false,
            channel:null,
            count:0,
            table:{}
        }
    },
    methods: {
        loadScreen:function (className) {
            axios.post('/api/turn/stand',{
                tableClass:className
            },{
                headers:{
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    Authorization: 'Bearer '+document.querySelector('meta[name="api-token"]').getAttribute('content'),
                }
            }).then(response => {

                if (response.data.screen === 'error') {
                    this.error = response.data.message
                }

                if (response.data.screen === 'loader') {
                    this.isList = false
                    this.isLoad = true
                    this.count = response.data.count
                    this.channel = response.data.channel
                }
            })
        },
        leaveTurn:function () {
            axios.post('/api/turn/leave',{},{
                headers:{
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    Authorization: 'Bearer '+document.querySelector('meta[name="api-token"]').getAttribute('content'),
                }
            }).then(response => {
                this.isList = true
                this.isLoad = false
            })
        }
    },
    created:function () {
        axios.post('/api/turn/state',{}, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    Authorization: 'Bearer ' + document.querySelector('meta[name="api-token"]').getAttribute('content')
                }
            }).then(response => {
            console.log(response.data.table)
                switch (response.data.screen) {
                    case 'list':
                        this.isList = true
                        break
                    case 'loader':
                        this.isLoad = true
                        this.count = response.data.count
                        this.channel = response.data.channel
                        break
                    case 'table':
                        this.isTable = true
                        this.channel = response.data.channel
                        this.table = response.data.table
                        break
                }
            })
    }
}
</script>

<style scoped>

</style>
