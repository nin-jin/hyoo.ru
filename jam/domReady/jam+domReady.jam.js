$jam.define
(   '$jam.domReady'
,   function( ){
        var state= $jam.doc().readyState
        if( state === 'loaded' ) return true
        if( state === 'complete' ) return true
        return false
    }
)
