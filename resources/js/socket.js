import axios from "axios"
import Echo from "laravel-echo"
window.Pusher = require('pusher-js')

export default {
    channel:null,
    auth:function (socketId) {
        console.log(this.channel)
        return axios.post('/api/broadcasting/auth', {
            socket_id: socketId,
            channel_name: this.channel.name
        },{
            headers: {
                Authorization: 'Bearer '+localStorage.token,
            }
        })
            .then(response => {
                callback(false, response.data);
            })
            .catch(error => {
                callback(true, error);
            });
    },
    connect:function () {
        return new Echo({
            broadcaster: 'pusher',
            key: '4326370c5eb04b2329d3',
            wsHost: window.location.hostname,
            wsPort: 6001,
            forceTLS: false,
            authorizer: (channel) => {
                return {
                    authorize: function (socketId,callback) {
                        return axios.post('/api/broadcasting/auth', {
                            socket_id: socketId,
                            channel_name: channel.name
                        },{
                            headers: {
                                Authorization: 'Bearer '+localStorage.token,
                            }
                        })
                            .then(response => {
                                callback(false, response.data);
                            })
                            .catch(error => {
                                callback(true, error);
                            });
                    }
                }
            },
            disableStats: false
        })
    }
}
