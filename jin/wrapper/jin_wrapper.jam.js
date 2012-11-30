this.$jin_wrapper= $jin_mixin( function( $jin_wrapper, wrapper ){
    var make= $jin_wrapper.$jin_class_make
    
    $jin_wrapper.$jin_class_make= function( obj ){
        if( obj instanceof $jin_wrapper ) return obj
        
        return make.apply( this, arguments )
    }
    
    wrapper.$= null
    
    wrapper.$jin_class_init= function( wrapper, value ){
        wrapper.$= value
    }
    
})
