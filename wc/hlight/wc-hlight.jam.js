$jam.Component
(   'wc:hlight'
,   function( nodeRoot ){
        return new function( ){
            nodeRoot= $jam.Node( nodeRoot )

            var hlight= $lang( nodeRoot.state( 'lang' ) )
            var source= $jam.String( nodeRoot.text() ).minimizeIndent().trim( /[\r\n]/ ).$

            nodeRoot
            .html( hlight( source ) )
            
        }
    }
)
