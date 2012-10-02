$jam.define
(   '$jam.eventURIChanged'
,   new function(){
        
        var lastURI= document.location.href
        
        var refresh=
        function( ){
            var newURI= document.location.href
            if( lastURI === newURI ) return
            lastURI= newURI
            $jam.Event().type( '$jam.eventURIChanged' ).scream( document )
        }
        
        window.setInterval( refresh, 20)
    }
)
