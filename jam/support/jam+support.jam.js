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
    
        var node= document.createElement( 'div' )
        
        this.msie= Support( /*@cc_on!@*/ false )
        this.xmlModel= Support( ( window.DOMParser && window.XSLTProcessor ) ? 'w3c' : 'ms' )
    }
)
