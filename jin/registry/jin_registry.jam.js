this.$jin_registry= $jin_mixin( function( $jin_registry, registry ){
    var storage= {}
    
    var make= $jin_registry.$jin_class_make
    
    $jin_registry.$jin_class_make= function( name ){
        var key= '_' + name
        var obj= storage[ key ]
        if( obj ) return obj
        
        return storage[ key ]= make.apply( this, arguments )
    }
    
})
