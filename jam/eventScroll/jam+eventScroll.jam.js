with( $jam$ )
$define
(   '$eventScroll'
,   new function(){
        var handler=
        function( event ){
            $Event()
            .type( '$jam.$eventScroll' )
            .wheel( event.wheel() )
            .scream( event.target() )
        }
        
        var docEl= $Node( $doc().documentElement )
        docEl.listen( 'mousewheel', handler )
        docEl.listen( 'DOMMouseScroll', handler )
    }
)
