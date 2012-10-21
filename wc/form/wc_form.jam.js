$jam.Component
(   'form'
,   new function( ){
        var currentScript= $jam.currentScript()
        return function( nodeRoot ){
            nodeRoot= $jam.Node( nodeRoot )
            if( !nodeRoot.attr( 'wc_form' ) ) return null
            
            var nodeResult= nodeRoot.descList( 'wc_form_result' ).head()
            
            var onCommit= nodeRoot.listen
            (   $jam.eventCommit
            ,   send
            )
            
            var onSubmit= nodeRoot.listen
            (   'submit'
            ,   send
            )
            
            var onClick= nodeRoot.listen
            (   'click'
            ,   function( event ){
                    if( event.target().type !== 'submit' )
                        return
                    send( event )
                }
            )
            
            function send( event ){
                event.defaultBehavior( false )
                console.log(event.$)
                var method= nodeRoot.attr( 'method' ) || 'get'
                
                if( nodeResult ){
                    nodeResult.text( '' )
                } else if( method == 'get' ){
                    nodeRoot.$.submit()
                    return 
                }
                
                var nodes= nodeRoot.$.elements
                var data= {}
                if( event.target().name && event.target().value )
                    data[ event.target().name ]= event.target().value
                
                for( var i= 0; i < nodes.length; ++i ){
                    var node= nodes[ i ]
                    data[ node.name ]= node.value
                }
                
                var response= $jam.http( nodeRoot.$.action ).request( method, data )
                var location= response.$.evaluate( '//so_relocation', response.$, null, XPathResult.STRING_TYPE, null ).stringValue
                if( location ) document.location= location
                
                var templates= $jam.domx.parse( $jam.http( currentScript.src.replace( /[^\/]*$/, 'release.xsl' ) ).get() )
                response= response.select(' / * / * ').transform( templates )
                if( nodeResult ) nodeResult.html( response )
            }
            
            return new function(){
                this.destroy= function( ){
                    onSubmit.sleep()
                }
            }
        }
    }
)