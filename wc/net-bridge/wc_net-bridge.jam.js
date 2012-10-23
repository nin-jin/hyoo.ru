$jam.Component
(   'wc_net-bridge'
,   function( nodeRoot ){
        nodeRoot= $jam.Node( nodeRoot )
        nodeRoot.listen
        (   '$jam.eventEdit'
        ,   function( ){
                var text= $jam.html2text( nodeRoot.html() )
                nodeRoot.state( 'modified', text !== textLast )
            }
        )
        
        nodeRoot.listen
        (   '$jam.eventEdit'
        ,   $jam.Throttler
            (   5000
            ,   save
            )
        )
        
        nodeRoot.listen
        (   '$jam.eventCommit'
        ,   save
        )
        
        var textLast= $jam.html2text( nodeRoot.html() )
        function save( ){
            var text= $jam.html2text( nodeRoot.html() )
            if( text === textLast ) return
            
            var xhr= new XMLHttpRequest
            xhr.open( 'POST' , nodeRoot.attr( 'wc_net-bridge_resource' ) )
            xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' )
            xhr.send( nodeRoot.attr( 'wc_net-bridge_field' ) + '=' + encodeURIComponent( text ) )
            textLast= text
            nodeRoot.state( 'modified', false )
        }
        
        return new function( ){
        }
    }
)
