$jam.Component
(   'wc:demo'
,   function( nodeRoot ){
        return new function( ){
            nodeRoot= $jam.Node( nodeRoot )

            var source= $jam.String( nodeRoot.text() ).minimizeIndent().trim( /[\n\r]/ ).$
            
            nodeRoot.clear()
            
            var nodeResult=
            $jam.Node.Element( 'wc:demo_result' )
            .parent( nodeRoot )
            
            var nodeSource0=
            $jam.Node.Element( 'wc:demo_source' )
            .parent( nodeRoot )
            
            var nodeSource=
            $jam.Node.parse( '<wc:editor wc:editor_hlight="sgml" />' )
            .parent( nodeSource0 )
            .text( source )
            
            var exec= $jam.Thread( function( ){
                var source= $jam.String( nodeSource.text() ).minimizeIndent().trim( /[\n\r]/ )
                //nodeSource.text( source )
                nodeResult.html( source )
                var scripts= nodeResult.descList( 'script' )
                for( var i= 0; i < scripts.length; ++i ){
                    var script= $jam.Node( scripts[i] )
                    $jam.eval( script.text() )
                }
                return true
            })
            
            exec()
        
            var onCommit=
            nodeSource.listen( '$jam.eventCommit', exec )
            
            this.destroy=
            function( ){
                onCommit.sleep()
            }
        }
    }
)
