$jam.define
(   '$jam.eventScroll'
,   new function(){
        var handler=
        function( event ){
            $jam.Event()
            .type( '$jam.eventScroll' )
            .wheel( event.wheel() )
            .scream( event.target() )
        }
        
        var docEl= $jam.Node( document.documentElement )
        docEl.listen( 'mousewheel', handler )
        docEl.listen( 'DOMMouseScroll', handler )
    }
)
