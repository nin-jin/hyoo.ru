with( $jam$ )
$Component( 'wc:preview', function( nodeRoot ){
    nodeRoot=
    $Node( nodeRoot )
    
    var nodeLink=
    nodeRoot.childList( 'a' ).get( 0 )
    
    var nodeFrame=
    nodeRoot.childList( 'iframe' ).get( 0 )
    
    if( !nodeFrame ) nodeFrame= $Node.Element( 'iframe' ).parent( nodeRoot )
    
    nodeFrame.attr( 'src', nodeLink.attr( 'href' ) )
    
    var opened=
    $Poly
    (   function(){
            return nodeRoot.state( 'opened' ) != 'false'
        }
    ,   function( val ){
            nodeRoot.state( 'opened', val )
            return opened
        }
    )
    
    nodeLink.listen( 'click', function( event ){
        if( event.button() !== 0 ) return
        opened( !opened() )
        event.defaultBehavior( false )
    })
    
})
