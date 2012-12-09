this.$jin_onElemAdd= $jin_event( function( $jin_onElemAdd, event ){
    
    $jin_onElemAdd.type= '$jin_onElemAdd'
    
    $jin_onElemAdd.listen=
    function( node, handler ){
        return $jin_nodeListener
        (   node
        ,   'DOMNodeInserted'
        ,   $jin_onElemAdd.wrapHandler( handler )
        )
    }
    
    $jin_onElemAdd.wrapHandler= function( handler ){
        return function( event ){
            event= $jin_onElemAdd( event )
            
            var target= event.target()
            if( target.nodeType !== 1 ) return
            
            handler( event )
            
            if( /*@cc_on!@*/ false )
                return
            
            var elems= target.getElementsByTagName( '*' )
            for( var i= 0; i < elems.length; ++i ){
                event.target( elems[ i ] )
                handler( event )
            }
        }
    }
    
} )
