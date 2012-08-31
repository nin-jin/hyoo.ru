$jam.define
(   '$jam.eventDelete'
,   new function( ){
        var handler=
        function( event ){
            if( !event.keyShift() ) return
            if( event.keyMeta() ) return
            if( event.keyAlt() ) return
            if( event.keyCode() != 46 ) return
            if( !$jam.glob().confirm( 'Are you sure to delee this?' ) ) return
            $jam.Event().type( '$jam.eventDelete' ).scream( event.target() )
        }
        
        $jam.Node( $jam.doc().documentElement )
        .listen( 'keyup', handler )
    }
)
