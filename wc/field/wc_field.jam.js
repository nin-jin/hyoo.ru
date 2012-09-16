$jam.Component
(   'wc_field'
,   function( nodeRoot ){
        nodeRoot= $jam.Node( nodeRoot )
        
        var nodeInput=
        $jam.Node.Element( 'input' )
        .attr( 'type', 'hidden' )
        .attr( 'name', nodeRoot.attr( 'wc_field_name' ) )
        .parent( nodeRoot )
        
        nodeRoot.listen
        (   '$jam.eventEdit'
        ,   function( ){
                var text= $jam.html2text( nodeRoot.html() )
                nodeInput.$.value= text
            }
        )
        
        var onEdit=
        nodeRoot.listen
        (   '$jam.eventEdit'
        ,   sync
        )
        
        function sync( ){
            var text= $jam.html2text( nodeRoot.html() )
            nodeInput.$.value= text
        }
        
        sync()
        
        return new function( ){
            this.destroy= function(){
                onEdit.sleep()
                nodeInput.parent( null )
            }
        }
    }
)
