with( $jam$ )
with( $wc$ )
$Component
(   'wc:hlight'
,   function( nodeRoot ){
        return new function( ){
            nodeRoot= $Node( nodeRoot )

            var hlight= $lang( nodeRoot.state( 'lang' ) )
            var source= $String( nodeRoot.text() ).minimizeIndent().trim( /[\r\n]/ ).$

            nodeRoot
            .html( hlight( source ) )
            
        }
    }
)
