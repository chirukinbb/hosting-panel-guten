import axios from "axios"

export default {
    stand:function (className) {
        return axios.post('/api/turn/stand',{
            tableClass:className
        },{
            headers:{
                'X-CSRF-TOKEN': localStorage.csrf,
                Authorization: 'Bearer '+localStorage.token,
            }
        })
    },
    leave:function () {
        return axios.post('/api/turn/leave',{},{
            headers:{
                'X-CSRF-TOKEN': localStorage.csrf,
                Authorization: 'Bearer '+localStorage.token,
            }
        })
    },
    state:function () {
        return axios.post('/api/turn/state',{}, {
            headers: {
                'X-CSRF-TOKEN': localStorage.csrf,
                Authorization: 'Bearer ' + localStorage.token,
            }
        })
    }
}
