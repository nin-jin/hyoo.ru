$jam.define
(   '$jam.eventEdit'
,   new function(){
        
        var scream=
        $jam.Throttler
        (   50
        ,   function( target ){
                $jam.Event().type( '$jam.eventEdit' ).scream( target )
            }
        )

        var handler=
        function( event ){
            if( event.keyCode() >= 16 && event.keyCode() <= 18 ) return
            if( event.keyCode() >= 33 && event.keyCode() <= 40 ) return
            scream( event.target() )
        }

        var node=
        $jam.Node( $jam.doc().documentElement )
        
        node.listen( 'keyup', handler )
        node.listen( 'cut', handler )
        node.listen( 'paste', handler )

    }
)
