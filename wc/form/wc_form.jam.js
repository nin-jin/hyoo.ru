$jam.Component
(   'form'
,   function( nodeRoot ){
        nodeRoot= $jam.Node( nodeRoot )
        if( !nodeRoot.attr( 'wc_form' ) ) return null
        
        var nodeResult= nodeRoot.descList( 'wc_form_result' ).head()
        
        var onSubmit= nodeRoot.listen
        (   'keydown'
        ,   function( event ){
                if( !event.keyMeta() ) return
                if( event.keyShift() ) return
                if( event.keyAlt() ) return
                if( !event.keyCode().enter && !event.keyCode().s ) return
                event.defaultBehavior( false )
                send()
            }
        )
        
        function send( ){
            var method= nodeRoot.attr( 'method' ) || 'get'
            
            if( nodeResult ){
                nodeResult.text( '' )
            } else if( method == 'get' ){
                nodeRoot.$.submit()
                return 
            }
            
            var nodes= nodeRoot.$.elements
            var data= {}
            for( var i= 0; i < nodes.length; ++i ){
                var node= nodes[ i ]
                data[ node.name ]= node.value
            }
            
            var response= $jam.domx.parse( $jam.http( nodeRoot.$.action ).request( method, data ) )
            var location= response.$.evaluate( '//so_relocation', response.$, null, XPathResult.STRING_TYPE, null ).stringValue
            if( location ) document.location= location
            
            var templates= $jam.domx.parse( $jam.http( 'appGist/-mix/compiled.xsl' ).get() )
            response= response.select(' // so_console_result | // so_error ').transform( templates )
            if( nodeResult ) nodeResult.html( response )
        }
        
        return new function(){
            this.destroy= function( ){
                onSubmit.sleep()
            }
        }
    }
)