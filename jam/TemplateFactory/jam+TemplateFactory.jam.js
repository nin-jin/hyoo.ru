$jam.define
(   '$jam.TemplateFactory'
,   new function( ){

        var factory= function( arg ){
            if( !arg ) arg= {}
            
            var open= arg.tokens && arg.tokens[0] || '{'
            var close= arg.tokens && arg.tokens[1] || '}'
            
            var openEncoded= $jam.RegExp.escape( open )
            var closeEncoded= $jam.RegExp.escape( close )
            
            var Selector= arg.Selector || arg.encoder && factory.Selector( arg.encoder ) || factory.Selector()
    
            var parse= $jam.Parser( new function(){
                this[ openEncoded + openEncoded ]=
                $jam.Value( open )
                
                this[ closeEncoded +closeEncoded ]=
                $jam.Value( close )
                
                this[ '(' + openEncoded + '([^' + openEncoded + closeEncoded + ']*)' + closeEncoded + ')' ]=
                Selector
            })
    
            return $jam.Class( function( klass, proto ){
                
                proto.constructor=
                function( str ){
                    this.struct= parse( str )
                    this.fill( {} )
                    return this
                }
                
                proto.clone=
                function( ){
                    return klass( this.struct.slice( 0 ) )
                }
                
                proto.fill=
                function( data ){
                    for( var i= 0; i < this.struct.length; ++i ){
                        if( typeof this.struct[ i ] !== 'function' ) continue
                        this.struct[ i ]= this.struct[ i ]( data )
                    }
                    return this
                }
    
                proto.toString=
                function( ){
                    return this.struct.join( '' )
                }
    
            })
        }
        
        factory.Selector=
        $jam.Poly
        (   $jam.Lazy( function( ){
                return $jam.Value( factory.Selector( $jam.Pipe() ) )
            })
        ,   function( proc ){
                return function( str, key ){
                    var selector= function( data ){
                        if( key in data ){
                            return proc( data[ key ] )
                        } else {
                            return selector
                        }
                    }
                    selector.toString= $jam.Value( str )
                    return selector
                }
            }
        )
        
        return factory

    }
)
