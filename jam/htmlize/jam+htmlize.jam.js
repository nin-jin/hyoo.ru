$jam.htmlize=
function( ns ){
    if( !$jam.doc().getElementsByTagNameNS ) return
    var nodeList= $jam.doc().getElementsByTagNameNS( ns, '*' )
    var docEl= $jam.doc().documentElement
    
    var tracking= function( ){
        sleep()
        var node
        while( node= nodeList[0] ){
            var parent= node.parentNode
            var newNode= $jam.doc().createElement( node.nodeName )
            var attrList= node.attributes
            for( var i= 0; i < attrList.length; ++i ){
                var attr= attrList[ i ]
                newNode.setAttribute( attr.nodeName, attr.nodeValue ) 
            }
            var child; while( child= node.firstChild ) newNode.appendChild( child )
            parent.insertBefore( newNode, node )
            if( node.parentNode === parent ) parent.removeChild( node )
        }
        rise()
    }
    
    $jam.domReady.then( tracking )
    tracking()
    
    function rise( ){
        docEl.addEventListener( 'DOMNodeInserted', tracking, false )
    }
    function sleep( ){
        docEl.removeEventListener( 'DOMNodeInserted', tracking, false )
    }
}
