this.$jin_onKeyPress= $jin_event( function( $jin_onKeyPress, event ){
    
    $jin_onKeyPress.type= '$jin_onKeyPress'
    $jin_onKeyPress.bubbles= true
    
    $jin_onChange.listen= function( node, handler ){
        return $jin_nodeListener
        (   node
        ,   'keypress'
        ,   $jin_onChange.wrapHandler( handler )
        )
    }
    
} )
