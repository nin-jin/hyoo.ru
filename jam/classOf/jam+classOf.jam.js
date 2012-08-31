$jam.define
(   '$jam.classOf'
,   new function( ){
        var toString = {}.toString
        return function( val ){
            if( val === void 0 ) return 'Undefined'
            if( val === null ) return 'Null'
            if( val === $jam.glob() ) return 'Global'
            return toString.call( val ).replace( /^\[object |\]$/g, '' )
        }
    }
)
