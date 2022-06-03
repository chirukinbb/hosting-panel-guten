export default {
    data:{
        screen:'table',
        channel:'blabls',
        table:{
            title:'Holdem, 9 Players...',
            blind:10,
            cardsInHand:2,
            players:[
                {
                    id:6,
                    name:'John Silva',
                    avatar:'/img/JohnDoe.webp',
                    amount:{
                        hand:99,
                        bid:1
                    },
                    actions:{
                        canFold:false,
                        canCheck:false,
                        canRaise:false,
                        canAllIn:false,
                        canCall:false
                    },
                    action_result:null,
                    timer:0,
                    round:{
                        isActive:true,
                        cards:[],
                        chips:{
                            isDealer:true,
                            isBB:false,
                            isLB:false
                        },
                        combo: null,
                        showdown:{
                            combo: null,
                            cards:[],
                            turn:false
                        }
                    },
                    place:null,
                    rating:null
                },
                {
                    id:4,
                    name:'John Silva 2',
                    avatar:'/img/JohnDoe.webp',
                    amount:{
                        hand:98,
                        bid:2
                    },
                    actions:false,
                    action_result:null,
                    timer:0,
                    round:{
                        isActive:true,
                        cards:[],
                        chips:{
                            isDealer:false,
                            isBB:true,
                            isLB:false
                        },
                        combo: null,
                        showdown:{
                            combo: null,
                            cards:[],
                            turn:false
                        }
                    },
                    place:null,
                    rating:null
                },
                {
                    id:1,
                    name:'John Silva 3',
                    avatar:'/img/JohnDoe.webp',
                    amount:{
                        hand:97,
                        bid:3
                    },
                    actions:false,
                    action_result:null,
                    timer:0,
                    round:{
                        isActive:true,
                        cards:[],
                        chips:{
                            isDealer:false,
                            isBB:false,
                            isLB:true
                        },
                        combo: null,
                        showdown:{
                            combo: null,
                            cards:[],
                            turn:false
                        }
                    },
                    place:null,
                    rating:null
                },
                {
                    id:16,
                    name:'John Silva 4',
                    avatar:'/img/JohnDoe.webp',
                    amount:{
                        hand:96,
                        bid:4
                    },
                    actions:false,
                    action_result:null,
                    timer:0,
                    round:{
                        isActive:true,
                        cards:[],
                        chips:{
                            isDealer:false,
                            isBB:false,
                            isLB:false
                        },
                        combo: null,
                        showdown:{
                            combo: null,
                            cards:[],
                            turn:false
                        }
                    },
                    place:null,
                    rating:null
                }
            ],
            round: {
                number: 7,
                step: 1,
                ante: 0,
                cards: [],
                bank: [0]
            }
        }
    }
}