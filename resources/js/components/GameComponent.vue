<template>
    <list-component v-if="isList" v-on:load-screen="loadScreen"></list-component>
    <loader-component v-if="isLoad" v-on:leave-turn="leaveTurn" :count="count"></loader-component>
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
            isTable:false,
            channel:null,
            count:0
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
                if (response.data.screen === 'loader') {
                    this.isList = false
                    this.isLoad = true
                    this.count = response.data.count
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
                if (response.data.screen === 'list') {
                    Echo.disconnect()
                    this.isList = true
                    this.isLoad = false
                }
            })
        },
        reconnect:function (channel,listen) {
            Echo.disconnect()

            Echo
                .channel(channel)
                .listen(listen,function (data) {
                    console.log(data)
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
                switch (response.data.screen) {
                    case 'list':
                        this.isList = true
                        break
                    case 'loader':
                        this.isLoad = true
                        this.count = response.data.count
                        this.reconnect(response.data.channel,response.data.listen)
                        break
                    case 'table':
                        this.isTable = true
                        this.reconnect(response.data.channel,response.data.listen)
                        break
                }
            })
    }
}
</script>

<style scoped>

</style>
