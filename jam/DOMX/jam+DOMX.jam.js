with( $jam$ )
$define
(   '$DOMX'
,   $Class( function( klass, proto ){
    
        proto.constructor=
        function( dom ){
            if( dom.toDOMDocument ) dom= dom.toDOMDocument()
            this.$= dom
            return this
        }
        
        proto.toDOMDocument=
        function( ){
            return this.$
        }
        
        proto.toString=
        $support.xmlModel.select(
        {   'w3c': function( ){
                var serializer= new XMLSerializer
                var text= serializer.serializeToString( this.$ )
                return text
            }
        ,   'ms': function( ){
                return $String( this.$.xml ).trim().$
            }
        })
        
        proto.transform=
        $support.xmlModel.select(
        {   'w3c': function( stylesheet ){
                var proc= new XSLTProcessor
                proc.importStylesheet( $raw( stylesheet ) )
                var doc= proc.transformToDocument( this.$ )
                return $DOMX( doc )
            }
        ,   'ms': function( stylesheet ){
                var text= this.$.transformNode( $raw( stylesheet ) )
                return $DOMX.parse( text )
            }
        })
        
        klass.parse=
        $support.xmlModel.select(
        {   'w3c': function( str ){
            var parser= new DOMParser
                var doc= parser.parseFromString( str, 'text/xml' )
                return $DOMX( doc )
            }
        ,   'ms': function( str ){
                var doc= new ActiveXObject( 'MSXML2.DOMDocument' )
                doc.async= false
                doc.loadXML( str )
                return $DOMX( doc )
            }
        })

    })
)
