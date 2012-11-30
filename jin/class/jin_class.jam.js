this.$jin_class= function( scheme ){
    
    var factory= function( ){
        if( this instanceof factory ) return this
        return factory.$jin_class_make.apply( factory, arguments )
    }
    var proto= factory.prototype
    
    factory.$jin_class_scheme= scheme
    
    factory.$jin_class_make= function( ){
        var obj= new factory
        obj.$jin_class_init.apply( obj, arguments )
        return obj
    }
    
    proto.$jin_class_init= function( obj ){ }
    
    scheme( factory, proto )
    
    for( var key in proto )
        proto[ key ]= $jin_method( proto[ key ] )
    
    return factory
}
