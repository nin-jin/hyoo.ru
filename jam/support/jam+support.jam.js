$jam.define
(   '$jam.support'
,   new function(){
        var Support= function( state ){
            var sup= $jam.Value( state )
            sup.select= function( map ){
                return $jam.switch( this(), map )
            }
            return sup
        }
    
        var node= $jam.doc().createElement( 'div' )
        
        this.msie= Support( /*@cc_on!@*/ false )
        this.xmlModel= Support( ( $jam.glob().DOMParser && $jam.glob().XSLTProcessor ) ? 'w3c' : 'ms' )
    }
)
