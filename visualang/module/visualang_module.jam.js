$jam.Component
(   'div'
,   function( nodeRoot ){
        nodeRoot= $jam.Node( nodeRoot )
        if( !nodeRoot.attr( 'visualang_module' ) ) return null
        nodeRoot.editable( false ).attr( 'draggable', 'true' )
        nodeRoot.listen( 'dragstart', function( event ){
            event.$.dataTransfer.setData( 'text/html', String( nodeRoot ) )
            event.$.stopPropagation()
        })
        return null
    }
)
