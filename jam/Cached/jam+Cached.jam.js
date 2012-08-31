$jam.define
(    '$jam.Cached'
,    function( func ){
        var cache= $jam.Hash()
        return function( key ){
            if( cache.has( key ) ) return cache.get( key )
            var value= func.apply( this, arguments )
            cache.put( key, value )
            return value 
        }
    }
)
