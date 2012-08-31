$jam.define
(   '$jam.htmlEscape'
,   function( str ){
        return String( str )
        .replace( /&/g, '&amp;' )
        .replace( /</g, '&lt;' )
        .replace( />/g, '&gt;' )
        .replace( /"/g, '&quot;' )
        .replace( /'/g, '&apos;' )
    }
)
