$jam.Component
(   'wc_hlight'
,   function( nodeRoot ){
        return new function( ){
            nodeRoot= $jam.Node( nodeRoot )

            var hlight= $lang( nodeRoot.attr( 'wc_hlight_lang' ) )
            var source= $jam.String( nodeRoot.text() ).minimizeIndent().trim( /[\r\n]/ ).$

            nodeRoot
            .html( hlight( source ) )
            
        }
    }
)
