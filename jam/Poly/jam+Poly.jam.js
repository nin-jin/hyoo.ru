$jam.define
(   '$jam.Poly'
,   function(){
        var map= arguments
        return function(){
            return map[ arguments.length ].apply( this, arguments )
        }
    }
)
