this.$jin_mixin= function( schemeMinin ){
    var mixin= $jin_class( schemeMinin )
    
    mixin.$jin_class_make= function( scheme ){
        return $jin_class( function( Class, proto ){
            schemeMinin( Class, proto )
            scheme( Class, proto )
        })
    }
    
    return mixin
}


