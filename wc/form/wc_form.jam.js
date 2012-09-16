$jam.Component
(   'form'
,   function( nodeRoot ){
        nodeRoot= $jam.Node( nodeRoot )
        if( !nodeRoot.attr( 'wc_form' ) ) return null
        
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
            var nodes= nodeRoot.$.elements
            var data= []
            for( var i= 0; i < nodes.length; ++i ){
                var node= nodes[ i ]
                data.push( encodeURIComponent( node.name ) + '=' + encodeURIComponent( node.value ) )
            }
            
            var request= new XMLHttpRequest
            request.open( nodeRoot.attr( 'method' ) || 'get', nodeRoot.$.action, false )
            request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' )
            request.send( data.join( '&' ) )
            
            var location= request.responseXML.evaluate( '//so_relocation', request.responseXML, null, XPathResult.STRING_TYPE, null ).stringValue
            if( location ) document.location= location;
        }
        
        return new function(){
            this.destroy= function( ){
                onSubmit.sleep()
            }
        }
    }
)