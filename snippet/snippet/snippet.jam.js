with( $jam )
$Component
(   'snippet:root'
,   function( nodeRoot ){
        nodeRoot= $jam.Node( nodeRoot )
        var nodeContent= nodeRoot.descList( 'snippet:content' ).get( 0 )
        var nodeLink= nodeRoot.descList( 'snippet:link' ).get( 0 )
        
        var load=
        function( ){
            nodeContent.clear()
            var hash= $doc().location.hash
            if( !hash ) hash= '#h1=Snippet!;wc:js-test=_test.ok()'
            var chunks= hash.substring( 1 ).split( ';' )
            for( var i= 0; i < chunks.length; ++i ){
                var pair= chunks[i].split( '=' )
                if( pair.length < 2 ) continue
                var source= decodeURIComponent( pair[1] ).replace( /\t/, '    ' )
                var content= $jam.Node.parse( '<wc:hlight class=" editable=true " />' ).text( source )
                $jam.Node.Element( pair[0] ).tail( content ).parent( nodeContent )
            }
        }
        
        var save=
        $Throttler
        (   50
        ,   function( ){
                var chunks= []
                var childList= nodeContent.childList()
                for( var i= 0; i < childList.length(); ++i ){
                    var child= childList.get( i )
                    if( child.name() === 'wc:js-test' ){
                        var source= child.childList( 'wc:js-test_source' ).get(0).text()
                    } else {
                        var source= child.text()
                    }
                    source= $String( source ).trim( /[\r\n]/ ).replace( /    /, '\t' ).$
                    chunks.push( child.name() + '=' + encodeURIComponent( source ) )
                }
                $doc().location= '#' + chunks.join( ';' )
                nodeLink.clear()
                googl($doc().location.href,function( href ){
                    if( href ){
                        $jam.Node.Element( 'a' )
                        .text( href )
                        .attr( 'href', href )
                        .parent( nodeLink )
                    } else {
                        var form= $jam.Node.parse( '<form method="post" target="_blank" action="http://goo.gl/action/shorten">' ).parent( nodeLink )
                        var url= $jam.Node.parse( '<input type="hidden" name="url" />' ).attr( 'value', $doc().location.href ).parent( form )
                        var submit= $jam.Node.parse( '<wc:button><button type="submit">get short link' ).parent( form ) 
                    }
                })
            }
        )
        
        load()
        
        var onURIChanged=
        $jam.Node( $doc() ).listen( '$jam.eventURIChanged', load )
        
        var onCommit=
        nodeRoot.listen( '$jam.eventCommit', save )
        
        var onDelete=
        nodeRoot.listen( '$jam.eventDelete', save )
        
        var onEdit=
        nodeRoot.listen( '$jam.eventEdit', function( ){
            nodeLink.clear()
        })
        
        return new function( ){
            this.destroy=
            function( ){
                onURIChanged.sleep()
                onCommit.sleep()
                onDelete.sleep()
                onEdit.sleep()
            }
        }
    }
)
