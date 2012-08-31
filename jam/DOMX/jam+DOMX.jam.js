$jam.define
(   '$jam.DOMX'
,   $jam.Class( function( klass, proto ){
    
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
        $jam.support.xmlModel.select(
        {   'w3c': function( ){
                var serializer= new XMLSerializer
                var text= serializer.serializeToString( this.$ )
                return text
            }
        ,   'ms': function( ){
                return $jam.String( this.$.xml ).trim().$
            }
        })
        
        proto.transform=
        $jam.support.xmlModel.select(
        {   'w3c': function( stylesheet ){
                var proc= new XSLTProcessor
                proc.importStylesheet( $jam.raw( stylesheet ) )
                var doc= proc.transformToDocument( this.$ )
                return $jam.DOMX( doc )
            }
        ,   'ms': function( stylesheet ){
                var text= this.$.transformNode( $jam.raw( stylesheet ) )
                return $jam.DOMX.parse( text )
            }
        })
        
        klass.parse=
        $jam.support.xmlModel.select(
        {   'w3c': function( str ){
            var parser= new DOMParser
                var doc= parser.parseFromString( str, 'text/xml' )
                return $jam.DOMX( doc )
            }
        ,   'ms': function( str ){
                var doc= new ActiveXObject( 'MSXML2.DOMDocument' )
                doc.async= false
                doc.loadXML( str )
                return $jam.DOMX( doc )
            }
        })

    })
)
