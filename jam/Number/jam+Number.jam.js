$jam.define
(   '$jam.Number'
,   $jam.Class( function( klass, proto ){
    
        proto.constructor=
        function( numb ){
            this.$= Number( numb )
            return this
        }
        
        proto.valueOf=
        function( ){
            return this.$
        }

    })
)
