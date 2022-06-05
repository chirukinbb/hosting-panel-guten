<template>
    <div v-if="error" class="alert alert-danger" role="alert">
        action not available: {{error}}
    </div>
    <list-component
        v-if="isList"
        v-on:load-screen="loadScreen"
    ></list-component>
    <loader-component
        v-if="isLoad"
        v-on:leave-turn="leaveTurn"
        v-on:load-table="loadTable"
        v-on:set-count="setCount"
        :count="count"
        :channel="this.channel"
    ></loader-component>
    <table-component
        v-if="isTable"
        v-on:set-table="setTable"
        :table="table"
        :channel="channel"
    ></table-component>
    <button class="btn btn-outline-primary" :disabled="disable" v-on:click="nextJob">Next</button>
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
            table:{},
            disable:false
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
                console.log(response.data)
                switch (response.data.screen) {
                    case 'error':
                        this.error =  response.data.message
                        break
                    case 'list':
                        this.isList = true
                        break
                    case 'loader':
                        this.isList = false
                        this.isLoad = true
                        this.count = response.data.count
                        this.channel = response.data.channel
                        break
                    case 'table':
                        this.channel = response.data.channel
                        this.table = response.data.table
                        this.isLoad = false
                        this.isTable = true
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
        },
        loadTable:function (channel, table) {
            this.table = table
            this.channel = channel
            this.isTable = true
        },
        setCount:function (count) {
            this.count = count
        },
        setTable:function (table) {
            this.table = table
        },
        nextJob:function () {
            this.disable = false

            axios.get('/api/next').
            then(()=>{this.disable=false})
        }
    },
    created:function () {
        axios.post('/api/turn/state',{}, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    Authorization: 'Bearer ' + document.querySelector('meta[name="api-token"]').getAttribute('content')
                }
            }).then(response => {
            console.log(response.data)
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
