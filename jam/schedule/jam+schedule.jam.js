$jam.define
(   '$jam.schedule'
,   function( timeout, proc ){
        var timerID= $jam.glob().setTimeout( proc, timeout )
        return function( ){
            $jam.glob().clearTimeout( timerID )
        }
    }
)
