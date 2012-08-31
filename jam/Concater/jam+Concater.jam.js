$jam.define
(   '$jam.Concater'
,   function( delim ){
        delim= delim || ''
        return function( list ){
            return list.join( delim )
        }
    }
)
