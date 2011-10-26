with( $jam$ )
$define
(   '$eventDelete'
,   new function( ){
        var handler=
        function( event ){
            if( !event.keyShift() ) return
            if( event.keyMeta() ) return
            if( event.keyAlt() ) return
            if( event.keyCode() != 46 ) return
            if( !$glob().confirm( 'Are you sure to delee this?' ) ) return
            $Event().type( '$jam$.$eventDelete' ).scream( event.target() )
        }
        
        $Node( $doc().documentElement )
        .listen( 'keyup', handler )
    }
)
