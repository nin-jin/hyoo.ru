this.$jin_factory= function( scheme ){
    
    var factory= function( ){
        if( this instanceof factory ) return
        return factory.make.apply( factory, arguments )
    }
    
    factory.make= function( ){
        return new this
    }
    
    scheme( factory )
    
    return factory
}
