$jam.define
(   '$jam.domReady.then'
,   function( proc ){
        var checker= function( ){
            if( $jam.domReady() ) proc()
            else $jam.schedule( 5, checker )
        }
        checker()
    }
)