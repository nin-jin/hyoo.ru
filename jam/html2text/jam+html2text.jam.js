$jam.define
(   '$jam.html2text'
,   function( html ){
        return $jam.htmlDecode
        (   String( html )
            .replace( /<div><br[^>]*>/gi, '\n' )
            .replace( /<br[^>]*>/gi, '\n' )
            .replace( /<div>/gi, '\n' )
            .replace( /<[^<>]+>/g, '' )
        )
    }
)
