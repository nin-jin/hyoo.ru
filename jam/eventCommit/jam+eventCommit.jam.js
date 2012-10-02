$jam.define
(   '$jam.eventCommit'
,   new function(){
        var handler=
        function( event ){
            if( !event.keyMeta() ) return
            if( event.keyShift() ) return
            if( event.keyAlt() ) return
            if( event.keyCode() != 13 && event.keyCode() != 'S'.charCodeAt( 0 ) ) return
            event.defaultBehavior( false )
            $jam.Event().type( '$jam.eventCommit' ).scream( event.target() )
        }
        
        $jam.Node( document.documentElement )
        .listen( 'keydown', handler )
        
        this.toString= $jam.Value( '$jam.eventCommit' )
    }
)
