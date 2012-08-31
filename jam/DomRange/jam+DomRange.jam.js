$jam.define
(   '$jam.DomRange'
,   $jam.Class( function( klass, proto ){
    
        proto.constructor=
        $jam.Poly
        (   function( ){
                var sel= $jam.selection()
                if( sel.rangeCount ) this.$= sel.getRangeAt( 0 ).cloneRange()
                else this.$= $jam.doc().createRange()
                return this
            }
        ,   function( range ){
                if( !range ) throw new Error( 'Wrong TextRange object' )
                this.$= klass.raw( range )
                return this
            }
        )
        
        proto.select=
        function( ){
            var sel= $jam.selection()
            sel.removeAllRanges()
            sel.addRange( this.$ )
            return this
        }
        
        proto.collapse2end=
        function( ){
            this.$.collapse( false )
            return this
        }
        
        proto.collapse2start=
        function( ){
            this.$.collapse( true )
            return this
        }
        
        proto.dropContents=
        function( ){
            this.$.deleteContents()
            return this
        }
        
        proto.text=
        $jam.Poly
        (   function( ){
                return $jam.html2text( this.html() )
            }
        ,   function( text ){
                this.html( $jam.htmlEscape( text ) )
                return this
            }
        )
        
        proto.html=
        $jam.Poly
        (   function( ){
                return $jam.Node( this.$.cloneContents() ).toString()
            }
        ,   function( html ){
                var node= html ? $jam.Node.parse( html ).$ : $jam.Node.Text( '' ).$
                this.replace( node )
                return this
            }
        )
        
        proto.replace=
        function( node ){
            node= $jam.raw( node )
            this.dropContents()
            this.$.insertNode( node )
            this.$.selectNode( node )
            return this
        }
        
        proto.ancestorNode=
        function( ){
            return this.$.commonAncestorContainer
        }
        
        proto.compare=
        function( how, range ){
            range= $jam.DomRange( range ).$
            how= Range[ how.replace( '2', '_to_' ).toUpperCase() ]
            return range.compareBoundaryPoints( how, this.$ )
        }
        
        proto.hasRange=
        function( range ){
            range= $jam.DomRange( range )
            var isAfterStart= ( this.compare( 'start2start', range ) >= 0 )
            var isBeforeEnd= ( this.compare( 'end2end', range ) <= 0 )
            return isAfterStart && isBeforeEnd
        }
        
        proto.equalize=
        function( how, range ){
            how= how.split( 2 )
            var method= { start: 'setStart', end: 'setEnd' }[ how[ 0 ] ]
            range= $jam.DomRange( range ).$
            this.$[ method ]( range[ how[1] + 'Container' ], range[ how[1] + 'Offset' ] )
            return this
        }
        
        proto.move=
        function( offset ){
            this.collapse2start()
            if( offset === 0 ) return this
            var current= $jam.Node( this.$.startContainer )
            if( this.$.startOffset ){
                var temp= current.$.childNodes[ this.$.startOffset - 1 ]
                if( temp ){
                    current= $jam.Node( temp ).follow()
                } else {
                    offset+= this.$.startOffset
                }
            }
            while( current ){
                if( current.name() === '#text' ){
                    var range= $jam.DomRange().aimNode( current )
                    var length= current.$.nodeValue.length
                    
                    if( !offset ){
                        this.equalize( 'start2start', range )
                        return this
                    } else if( offset > length ){
                        offset-= length
                    } else {
                        this.$.setStart( current.$, offset )
                        return this
                    }
                }
                if( current.name() === 'br' ){
                    if( offset > 1 ){
                        offset-= 1
                    } else {
                        var range= $jam.DomRange().aimNode( current )
                        this.equalize( 'start2end', range )
                        return this
                    }
                }
                current= current.delve()
            }
            return this
        }
        
        proto.clone=
        function( ){
            return $jam.DomRange( this.$.cloneRange() )
        }
        
        proto.aimNodeContent=
        function( node ){
            this.$.selectNodeContents( $jam.raw( node ) )
            return this
        }
        
        proto.aimNode=
        function( node ){
            this.$.selectNode( $jam.raw( node ) )
            return this
        }
        
    })
)
