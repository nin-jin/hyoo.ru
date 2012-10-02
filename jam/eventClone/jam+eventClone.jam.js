$jam.define
(   '$jam.eventClone'
,   new function(){
        var handler=
        function( event ){
            if( !event.keyMeta() ) return
            if( !event.keyShift() ) return
            if( event.keyAlt() ) return
            if( event.keyCode() != 13 ) return
            $jam.Event().type( '$jam.eventClone' ).scream( event.target() )
        }
        
        $jam.Node( document.documentElement )
        .listen( 'keyup', handler )
    }
)
