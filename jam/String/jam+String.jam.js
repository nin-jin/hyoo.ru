$jam.define
(   '$jam.String'
,   $jam.Class( function( klass, proto ){
    
        proto.constructor=
        function( data ){
            this.$= String( $jam.raw( data ) || '' )
            return this
        }
        
        proto.incIndent=
        $jam.Poly
        (   function( ){
                this.$= this.$.replace( /^/mg, '    ' )
                return this
            }
        )

        proto.decIndent=
        $jam.Poly
        (   function( ){
                this.$= this.$.replace( /^    |^\t/mg, '' )
                return this
            }
        )

        proto.minimizeIndent=
        $jam.Poly
        (   function( ){
                this.normilizeSpaces()
                var minIndent= 1/0
                this.$.replace( /^( *)[^ \r\n]/mg, function( str, indent ){
                    if( indent.length < minIndent ) minIndent= indent.length
                })
                if( minIndent === 1/0 ) return this
                this.$= this.$.replace( RegExp( '^[ ]{0,' + minIndent + '}', 'mg' ), '' )
                return this
            }
        )

        proto.normilizeSpaces=
        $jam.Poly
        (   function( ){
                this.$= this.$.replace( /\t/g, '    ' ).replace( /\u00A0/, ' ' )
                return this
            }
        )
        
        proto.trim=
        $jam.Poly
        (   function( ){
                return this.trim( /\s/ )
            }
        ,   function( what ){
                this.$= this.$.replace( RegExp( '^(' + what.source + ')+' ), '' )
                this.$= this.$.replace( RegExp( '(' + what.source + ')+$' ), '' )
                return this
            }
        )
        
        proto.process=
        $jam.Poly
        (   null
        ,   function( proc ){
                this.$= proc( this.$ )
                return this
            }
        )
        
        proto.replace=
        $jam.Poly
        (   null
        ,   function( from ){
                return this.replace( from, '' )
            }
        ,   function( from, to ){
                this.$= this.$.replace( from, to )
                return this
            }
        )
        
        proto.mult=
        $jam.Poly
        (   null
        ,   function( count ){
                this.$= Array( count + 1 ).join( this.$ )
                return this
            }
        )
        
        proto.length=
        $jam.Poly
        (   function( ){
                return this.$.length
            }
        )
        
        proto.toString=
        $jam.Poly
        (   function( ){
                return this.$
            }
        )

    })
)
