$jam.Component
(   'div'
,   function( nodeRoot ){
        nodeRoot= $jam.Node( nodeRoot )
        if( !nodeRoot.attr( 'visualang_place' ) ) return null
        nodeRoot.editable( true ).attr( 'droppable', 'true' )
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
