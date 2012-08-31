$jam.define
(   '$jam.eventURIChanged'
,   new function(){
        
        var lastURI= $jam.doc().location.href
        
        var refresh=
        function( ){
            var newURI= $jam.doc().location.href
            if( lastURI === newURI ) return
            lastURI= newURI
            $jam.Event().type( '$jam.eventURIChanged' ).scream( $jam.doc() )
        }
        
        $jam.glob().setInterval( refresh, 20)
    }
)
