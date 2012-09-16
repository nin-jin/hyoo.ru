$jam.define
(   '$jam.Component'
,   function( tagName, factory ){
        if(!( this instanceof $jam.Component )) return new $jam.Component( tagName, factory )
        var fieldName= 'componnet|' + tagName + '|' + (new Date).getTime()
    
        var nodes= $jam.doc().getElementsByTagName( tagName )
    
        var elements= []
    
        var checkName=
        ( tagName === '*' )
        ?   $jam.Value( true )
        :   function checkName_right( el ){
                return( el.nodeName.toLowerCase() == tagName )
            }
        
        function isAttached( el ){
            return typeof el[ fieldName ] === 'object'
        }
        
        function attach( el ){
    
            el[ fieldName ]= null
            var widget= factory( el )
            el[ fieldName ]= widget || null
            if( widget ) elements.push( el )
        }
        
        function attachIfLoaded( el ){
            var cur= el
            do {
                if( !cur.nextSibling ) continue
                attach( el )
                break
            } while( cur= cur.parentNode )
        }
        
        function dropElement( el ){
            for( var i= 0; i < elements.length; ++i ){
                if( elements[ i ] !== el ) continue
                elements.splice( i, 1 )
                return
            }
        }
        
        function detach( nodeList ){
            for( var i= 0, len= nodeList.length; i < len; ++i ){
                var node= nodeList[ i ]
                var widget= node[ fieldName ]
                if( widget.destroy ) widget.destroy()
                node[ fieldName ]= void 0
                dropElement( node )
            }
        }
        
        function check4attach( nodeList ){
            var filtered= []
            filtering:
            for( var i= 0, len= nodeList.length; i < len; ++i ){
                var node= nodeList[ i ]
                if( isAttached( node ) ) continue
                if( !checkName( node ) ) continue
                filtered.push( node )
            }
            for( var i= 0, len= filtered.length; i < len; ++i ){
                attachIfLoaded( filtered[ i ] )
            }
        }
    
        function check4detach( nodeList ){
            var filtered= []
            filtering:
            for( var i= 0, len= nodeList.length; i < len; ++i ){
                var node= nodeList[ i ]
    
                if( !node[ fieldName ] ) continue
    
                var current= node
                var doc= current.ownerDocument
                while( current= current.parentNode ){
                    if( current === doc ) continue filtering
                }
    
                filtered.push( node )
            }
            detach( filtered )
        }
    
        function tracking( ){
            check4attach( nodes )
            check4detach( elements )
        }
    
        var interval=
        $jam.glob().setInterval( tracking, 50 )
    
        $jam.domReady.then(function whenReady(){
            $jam.glob().clearInterval( interval )
            attachIfLoaded= attach
            tracking()
        })
    
        var docEl= $jam.doc().documentElement
        docEl.addEventListener( 'DOMNodeInserted', function whenNodeInserted( ev ){
            var node= ev.target
            //$jam.schedule( 0, function( ){
                check4attach([ node ])
                if( !$jam.support.msie() && node.getElementsByTagName ) check4attach( node.getElementsByTagName( tagName ) )
            //})
        }, false )
        docEl.addEventListener( 'DOMNodeRemoved', function whenNodeRemoved( ev ){
            var node= ev.target
            //$jam.schedule( 0, function( ){
                check4detach([ node ])
                if( !$jam.support.msie() && node.getElementsByTagName ) check4detach( node.getElementsByTagName( tagName ) )
            //})
        }, false )
        
        this.tagName= $jam.Value( tagName )
        this.factory= $jam.Value( factory )
        this.elements=
        function elements( ){
            return elements.slice( 0 )
        }
        
        tracking()
    }
)
