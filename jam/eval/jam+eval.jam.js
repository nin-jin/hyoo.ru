$jam.define
(   '$jam.eval'
,   $jam.Thread(function( source ){
        return window.eval( source )
    })
)
