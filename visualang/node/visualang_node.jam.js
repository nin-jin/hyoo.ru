$jam.Component
(   'div'
,   function( nodeRoot ){
        nodeRoot= $jam.Node( nodeRoot )
        if( !nodeRoot.attr( 'visualang_node' ) ) return null
        nodeRoot.editable( true ).attr( 'draggable', 'true' )
        nodeRoot.listen( 'dragstart', function( event ){
            event.$.dataTransfer.setData( 'text/html', String( nodeRoot ) )
            event.$.stopPropagation()
        })
        nodeRoot.listen( 'dragover', function( event ){
            event.defaultBehavior( false )
        })
        nodeRoot.listen( 'drop', function( event ){
            event.defaultBehavior( false )
            $jam.Node.parse( event.$.dataTransfer.getData( 'text/html' ) ).parent( nodeRoot )
            event.$.stopPropagation()
        })
        return null
    }
)
