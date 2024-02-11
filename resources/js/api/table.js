export default {
    data:{
        screen:String,
        channel:String,
        table:{
            title:String,
            blind:Number,
            cardsInHand:Number,
            players:[
                {
                    id:Number,
                    name:String,
                    avatar:String,
                    amount:{
                        hand:Number,
                        bid:Number
                    },
                    actions:{// for current only
                        canFold:Boolean,
                        canCheck:Boolean,
                        canRaise:Boolean,
                        canAllIn:Boolean,
                        canCall:Boolean
                    },
                    action_result:Number,
                    timer:Number,
                    round:{
                        isActive:Boolean,
                        cards:[// for current only
                            {nominal:Number,suit:Number}
                        ],
                        chips:{
                            isDealer:Boolean,
                            isBB:Boolean,
                            isLB:Boolean
                        },
                        combo: String,// for current only
                        showdown:{
                            cards:[// for current only
                                {suit:Number,nominal:Number}
                            ],
                            turn:Boolean
                        }
                    },
                    place:Number,
                    rating:Number
                },
            ],
            round:{
                number: Number,
                step: Number,
                ante: Number,
                cards:[
                    {suit:Number,nominal:Number}
                ],
                bank:[]
            },
        }
    }
}