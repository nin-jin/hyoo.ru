$jam.define
(   '$jam.eval'
,   $jam.Thread(function( source ){
        return $jam.glob().eval( source )
    })
)
