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
        
        var node=
        $jam.Node( document.documentElement )
        
        node.listen( 'keyup', function( event ){
            if( event.keyCode() >= 16 && event.keyCode() <= 18 ) return
            if( event.keyCode() >= 33 && event.keyCode() <= 40 ) return
            scream( event.target() )
        } )
        
        var handler= function( event ){
            scream( event.target() )
        }
        
        node.listen( 'cut', handler )
        node.listen( 'paste', handler )
        node.listen( 'drop', handler )

    }
)
