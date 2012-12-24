this.$jin_component=
$jin_mixin( function( $jin_component, component ){
    
    $jin_component.id= null
    
    var init= component.init
    component.init= function( component ){
        init.apply( this, arguments )
        
    }
    
} )

$jin_component.map= {}

//$jin_onElemAdd.listen( document, function( event ){
//    var node= event.target()
//    if( $jin_component.map[ node.localName ] );
//})