this.$jin_listener=
$jin_class( function( $jin_listener, listener ){
    
    listener.event= null
    listener.router= null
    listener.handler= null
    
    listener.init=
    function( listener, event, router, handler ){
        listener.event= event
        listener.router= router
        listener.handler= handler
        
        return listener
    }
    
    var destroy= listener.destroy
    listener.destroy=
    function( listener ){
        listener.event.forget( listener.router, listener.handler )
        destroy.apply( this, arguments )
    }
    
})