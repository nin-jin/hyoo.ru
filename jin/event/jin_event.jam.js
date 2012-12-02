this.$jin_event= $jin_mixin( function( $jin_event, event ){
    $jin_wrapper.scheme.apply( this, arguments )
    
    $jin_event.type= null
    
    $jin_event.listen= function( node, handler ){
        node.addEventListener
        (   $jin_event.type
        ,   handler
        ,   false
        )
        return $jin_listener( $jin_event, node, handler )
    }
    
    $jin_event.forget= function( node, handler ){
        node.removeEventListener
        (   $jin_event.type
        ,   handler
        ,   false
        )
    }
    
    var init= event.init
    event.init= function( event, value ){
        if( !value ){
            value= document.createEvent( 'Event' )
            value.initEvent( $jin_event.type, true, true )
        }
        init( event, value )
    }
    
    event.scream=
    function( event, node ){
        node.dispatchEvent( event.$ )
        return event
    }
    
    event.toString=
    function( event ){
        return 'Event:' + $jin_event.type
    }
    
})
