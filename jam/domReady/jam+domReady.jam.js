$jam.define
(   '$jam.domReady'
,   function( ){
        var state= document.readyState
        if( state === 'loaded' ) return true
        if( state === 'complete' ) return true
        return false
    }
)
