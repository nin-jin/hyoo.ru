$jam.define
(   '$jam.Lexer'
,   function( lexems ){
        if( !lexems ) throw new Error( 'lexems is required' )
    
        var nameList= []
        var regexpList= []
        var sizeList= []
    
        for( var name in lexems ){
            var regexp= $jam.RegExp( lexems[ name ] )
            nameList.push( name )
            regexpList.push( regexp.source() )
            sizeList.push( regexp.count() )
        }
        
        var regexp= RegExp( '([\\s\\S]*?)(?:((' + regexpList.join( ')|(' ) + '))|($\n?))', 'gm' )
        var count= $jam.RegExp(regexp).count()
        
        return $jam.Class( function( klass, proto ){
            
            proto.constructor=
            function( str ){
                this.string= String( str )
                this.position= 0
                return this
            }
            
            proto.next=
            function(){
                regexp.lastIndex= this.position
                var found= regexp.exec( this.string )
                var prefix= found[1]
                if( prefix ){
                    this.position+= prefix.length
                    this.name= ''
                    this.found= prefix
                    this.chunks= [ prefix ]
                    return this
                } else if( found[ 2 ] ){
                    this.position+= found[ 2 ].length
                    var offset= 4
                    for( var i= 0; i < sizeList.length; ++i ){
                        var size= sizeList[ i ]
                        if( found[ offset - 1 ] ){
                            this.name= nameList[ i ]
                            this.found= found[2]
                            this.chunks= found.slice( offset, offset + size )
                            return this
                        }
                        offset+= size + 1
                    }
                    throw new Error( 'something wrong' )
                } else if( regexp.lastIndex >= this.string.length ){
                    delete this.name
                    delete this.found
                    delete this.chunks
                    return this
                } else {
                //console.log(found,regexp,this.string,count)
                    this.position+= found[count] ? found[count].length : 0
                    this.name= ''
                    this.found= found[count]
                    this.chunks= [ found[count] ]
                    return this
                }
            }
            
        })
    }
)
