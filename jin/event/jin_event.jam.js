this.$jin_event= $jin_mixin( function( $jin_event, event ){
    $jin_wrapper.scheme.apply( this, arguments )
    
    $jin_event.type= null
    $jin_event.bubbles= false
    $jin_event.cancelable= false
    
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
    event.init= function( event, raw ){
        if( raw ){
            raw= $jin_unwrap( raw )
        } else {
            raw= document.createEvent( 'Event' )
            raw.initEvent( $jin_event.type, $jin_event.bubbles, $jin_event.cancelable )
        }
        init( event, raw )
    }
    
    event.scream=
    function( event, node ){
        node.dispatchEvent( event.$ )
        return event
    }
    
    event.target=
    function( event, target ){
        if( target == null )
            return event.$.$jin_event_target || event.$.target
        
        event.$.$jin_event_target= target
        return event
    }
    
    event.type=
    function( event, type ){
        if( type == null )
            return event.$.type
        
        event.$.initEvent( type, event.bubbles(), event.cancelable() )
        return event
    }
    
    event.bubbles=
    function( event, bubbles ){
        if( bubbles == null )
            return event.$.bubbles
        
        event.$.initEvent( event.type(), bubbles, event.cancelable() )
        return event
    }
    
    event.cancelable=
    function( event, cancelable ){
        if( cancelable == null )
            return event.$.cancelable
        
        event.$.initEvent( event.type(), event.bubbles(), cancelable )
        return event
    }
    
    event.toString=
    function( event ){
        return $jin_event + '()'
    }
    
})
