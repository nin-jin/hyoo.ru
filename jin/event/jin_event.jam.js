this.$jin_event= $jin_mixin( function( $jin_event, event ){
    $jin_wrapper.scheme.apply( this, arguments )
    
    $jin_event.type= null
    
    $jin_event.listen= function( node, handler ){
        return $jin_nodeListener
        (   node
        ,   $jin_event.type
        ,   $jin_event.wrapHandler( handler )
        )
    }
    
    $jin_event.wrapHandler= function( handler ){
        return function( event ){
            return handler( $jin_event( event ) )
        }
    }
    
    $jin_event.toString=
    function( ){
        return $jin_event.type
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
    
    event.target=
    function( event, value ){
        if( value == null )
            return event.$.$jin_event_target || event.$.target
        
        event.$.$jin_event_target= value
        return event
    }
    
    event.toString=
    function( event ){
        return $jin_event + '()'
    }
    
})
