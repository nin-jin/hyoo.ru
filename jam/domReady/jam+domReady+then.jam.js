$jam.define
(   '$jam.domReady.then'
,   function( proc ){
        var checker= function( ){
            if( $jam.domReady() ) proc()
            else $jam.schedule( 10, checker )
        }
        checker()
    }
)