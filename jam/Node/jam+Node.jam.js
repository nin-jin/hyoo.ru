$jam.define
(   '$jam.Node'
,   $jam.Class( function( klass, proto ){
        
        klass.Element=
        function( name ){
            return klass.create( $jam.doc().createElement( name ) )
        }
        
        klass.Text=
        function( str ){
            return klass.create( $jam.doc().createTextNode( str ) )
        }
        
        klass.Comment=
        function( str ){
            return klass.create( $jam.doc().createComment( str ) )
        }
        
        klass.Fragment=
        function( ){
            return klass.create( $jam.doc().createDocumentFragment() )
        }
        
        proto.text=
        $jam.Poly
        (   function( ){
                return $jam.html2text( this.$.innerHTML )
            }
        ,   new function(){
                return function( val ){
                    val= String( val )
                    if( this.text() === val ) return this
                    this.$.textContent= val
                    return this
                }
            }
        )
        
        proto.html=
        $jam.Poly
        (   function( ){
                var val= this.$.innerHTML
                .replace
                (   /<\/?[A-Z]+/g
                ,   function( str ){
                        return str.toLowerCase()
                    }
                )
                return val
            }
        ,   function( val ){
                val= String( val )
                if( this.html() === val ) return this
                this.clear()
                this.$.innerHTML= String( val )
                return this
            }
        )
        
        proto.clear=
        function( ){
            while( true ){
                var child= this.$.firstChild
                if( !child ) break
                this.$.removeChild( child )
            }
            return this
        }
        
        proto.name=
        function( ){
            return this.$.nodeName.toLowerCase()
        }
        
        proto.attr=
        $jam.Poly
        (   null
        ,   function( name ){
                return this.$.getAttribute( name )
            }
        ,   function( name, val ){
                this.$.setAttribute( String( name ), String( val ) )
                this.$.className+= ''
                return this
            }    
        )
        
        proto.state=
        $jam.Poly
        (   function( ){
                return this.param( [] )
            }
        ,   function( key ){
                return $jam.Hiqus({ splitterChunks: ' ' }).merge( this.$.className || '' ).get( key )
            }
        ,   function( key, value ){
                this.$.className= $jam.Hiqus({ splitterChunks: ' ' }).merge( this.$.className ).put( key, value )
                return this
            }
        )
        
        proto.width=
        function( ){
            if( 'offsetWidth' in this.$ ) return this.$.offsetWidth
            if( 'getBoundingClientRect' in this.$ ){
                var rect= this.$.getBoundingClientRect()
                return rect.right - rect.left
            }
            return 0
        }
        
        proto.height=
        function( ){
            if( 'offsetHeight' in this.$ ) return this.$.offsetHeight
            if( 'getBoundingClientRect' in this.$ ){
                var rect= this.$.getBoundingClientRect()
                return rect.bottom - rect.top
            }
            return 0
        }
        
        proto.posLeft=
        function( ){
            if( 'offsetLeft' in this.$ ) return this.$.offsetLeft
            var rect= this.$.getBoundingClientRect()
            return rect.left
        }
        
        proto.posTop=
        function( ){
            if( 'offsetTop' in this.$ ) return this.$.offsetTop
            var rect= this.$.getBoundingClientRect()
            return rect.top
        }
        
        proto.editable=
        $jam.Poly
        (   function( ){
                var editable= this.$.contentEditable
                if( editable == 'inherit' ) return this.parent().editable()
                return editable == 'true'
            }
        ,   function( val ){
                this.$.contentEditable= val
                return this
            }
        )
        
        proto.ancList=
        function( name ){
            var filtered= []
            var node= this
            do {
                if( name && node.name().replace( name, '' ) ) continue
                filtered.push( node )
            } while( node= node.parent() )
            
            return $jam.NodeList( filtered )
        }
        
        proto.childList=
        function( name ){
            var list= this.$.childNodes
            var filtered= []
            
            for( var i= this.head(); i; i= i.next() ){
                if( name && i.name().replace( name, '' ) ) continue
                filtered.push( i )
            }
            
            return $jam.NodeList( filtered )
        }
        
        proto.descList=
        function( name ){
            var list= this.$.getElementsByTagName( name )
            var filtered= []
            
            for( var i= 0; i < list.length; ++i ){
                filtered.push( list[ i ] )
            }
            
            return $jam.NodeList( filtered )
        }

        proto.parent= 
        $jam.Poly
        (   function( ){
                return $jam.Node( this.$.parentNode )
            }
        ,   function( node ){
                node= $jam.raw( node )
                var parent= this.$.parentNode
                if( node ){
                    if( parent === node ) return this
                    node.appendChild( this.$ )
                } else {
                    if( !parent ) return this
                    parent.removeChild( this.$ )
                }
                return this
            }
        )
        
        proto.ancestor=
        function( name ){
            var current= this
            while( true ){
                if( current.name() === name ) return current
                current= current.parent()
                if( !current ) return current
            }
        }
        
        proto.surround=
        function( node ){
            var node= $jam.raw( node )
            var parent= this.$.parentNode
            var next= this.$.nextSibling
            node.appendChild( this.$ )
            parent.insertBefore( node, next )
            return this
        }
        
        proto.dissolve=
        function( ){
            for( var head; head= this.head(); ){
                this.prev( head )
            }
            //if( this.name() === 'br' ) return this;//this.prev( $jam.Node.Text( '\r\n' ) )
            this.parent( null )
            return this
        }
        
        proto.dissolveTree=
        function( ){
            var endNode= this.follow()
            var curr= this
            while( curr ){
                curr= curr.delve()
                if( !curr ) break;
                if( curr.$ === endNode.$ ) break;
                if( curr.name() === '#text' ) continue;
                var next= curr.delve()
                curr.dissolve()
                curr= next
            }
            return this
        }
        
        proto.head=
        $jam.Poly
        (   function(){
                return $jam.Node( this.$.firstChild )
            }
        ,   function( node ){
                this.$.insertBefore( $jam.raw( node ), this.$.firstChild )
                return this
            }
        )
        
        proto.tail=
        $jam.Poly
        (   function(){
                return $jam.Node( this.$.lastChild )
            }
        ,   function( node ){
                this.$.appendChild( $jam.raw( node ) )
                return this
            }
        )
        
        proto.next=
        $jam.Poly
        (   function(){
                return $jam.Node( this.$.nextSibling )
            }
        ,   function( node ){
                var parent= this.$.parentNode
                var next= this.$.nextSibling
                parent.insertBefore( $jam.raw( node ), next ) 
                return this
            }   
        )
        
        proto.delve=
        function( ){
            return this.head() || this.follow()
        }

        proto.follow=
        function( ){
            var node= this
            while( true ){
                var next= node.next()
                if( next ) return next
                node= node.parent()
                if( !node ) return null
            }
        }

        proto.precede=
        function( ){
            var node= this
            while( true ){
                var next= node.prev()
                if( next ) return next
                node= node.parent()
                if( !node ) return null
            }
        }

        proto.prev=
        $jam.Poly
        (   function(){
                return $jam.Node( this.$.previousSibling )
            }
        ,   function( node ){
                node= $jam.raw( node )
                var parent= this.$.parentNode
                parent.insertBefore( node, this.$ ) 
                return this
            }   
        )
        
        proto.inDom=
        $jam.Poly
        (   function( ){
                var doc= node.$.ownerDocument
                var node= this
                while( true ){
                    if( node.$ === doc ) return true
                    node= node.parent()
                    if( !node ) return false
                }
            }
        )
        
        klass.parse=
        new function( ){
            var parent= klass.Element( 'div' )
            return function( html ){
                parent.html( html )
                var child= parent.head()
                if( !child ) return null
                if( !child.next() ) return child
                var fragment= $jam.Node.Fragment()
                while( child= parent.head() ) fragment.tail( child )
                return fragment
            }
        }

        proto.toString=
        new function( ){
            var parent= klass.Element( 'div' )
            return function( ){
                parent.clear().tail( this.cloneTree() )
                return parent.html()
            }
        }
        
        proto.clone=
        function( ){
            return $jam.Node( this.$.cloneNode( false ) )
        }

        proto.cloneTree=
        function( ){
            return $jam.Node( this.$.cloneNode( true ) )
        }
        
        proto.listen=
        function( eventName, handler ){
            return $jam.Observer()
            .eventName( eventName )
            .node( this )
            .handler( handler )
            .listen()
        }

    })
)
