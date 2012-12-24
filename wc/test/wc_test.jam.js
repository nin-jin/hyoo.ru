void function( ){
    var nodeSummary= $jam.Lazy( function( ){
        return $jam.Value( $jam.Node.parse( '<a wc_test_summary="true" />' ).parent( $jam.body() ) )
    } )
    
    var refreshSummary= $jam.Throttler( 50, function( ){
        var nodes= $jam.Node( document ).descList( 'script' )
        for( var i= 0; i < nodes.length(); ++i ){
            var node= nodes.get( i )
            switch( node.attr( 'wc_test_passed' ) ){
                case 'true':
                    break
                case 'false':
                    nodeSummary().attr( 'wc_test_passed', 'false' )
                    while( node && !node.attr( 'id' ) ) node= node.parent()
                    if( node ) nodeSummary().attr( 'href', node.attr( 'id' ) )
                case 'wait':
                    return
            }
        }
        nodeSummary().attr( 'wc_test_passed', 'true' )
        nodeSummary().attr( 'href', '' )
    } )
    
    $jam.Component
    (   'script'
    ,   function( nodeRoot ){
            nodeRoot= $jam.Node( nodeRoot )
            if( nodeRoot.attr( 'type' ) !== 'wc_test' ) return null
            
            return new function( ){
                
                var source= $jam.String( nodeRoot.html() ).minimizeIndent().trim( /[\n\r]/ ).$
                
                nodeRoot.clear()
                var nodeSource0= $jam.Node.Element( 'wc_test_source' ).parent( nodeRoot )
                var nodeSource= $jam.Node.parse( '<wc_editor wc_editor_hlight="js" />' ).text( source ).parent( nodeSource0 )
                var nodeLogs= $jam.Node.Element( 'wc_test_logs' ).parent( nodeRoot )
                var nodeControls= $jam.Node.Element( 'wc_hontrol' ).parent( nodeRoot )
                var nodeClone= $jam.Node.parse( '<wc_hontrol_clone title="ctrl+shift+enter" />' ).text( 'clone' ).parent( nodeControls )
                var nodeDelete= $jam.Node.parse( '<wc_hontrol_delete/>' ).text( 'delete' ).parent( nodeControls )
                
                var checkDone= function( ){
                    refreshSummary()
                    if( passed() === 'wait' ) return
                    passed( false )
                    throw new Error( 'Test already done' )
                }
                
                var stop
                
                var passed=
                $jam.Poly
                (   function( ){
                        return nodeRoot.attr( 'wc_test_passed' )
                    }
                ,   function( val ){
                        nodeRoot.attr( 'wc_test_passed', val )
                    }
                )
                
                var printError=
                function( val ){
                    var node= $jam.Node.Element( 'wc_test_error' )
                    node.text( val )
                    nodeLogs.tail( node )
                }
                
                var dumpValue=
                function( val ){
                    if( typeof val === 'function' ){
                        if( !val.hasOwnProperty( 'toString' ) ){
                            return 'Function: [object Function]'
                        }
                    }
                    return $jam.classOf( val ) + ': ' + val
                }
                
                var printResults=
                function( list ){
                    var node= $jam.Node.Element( 'wc_test_results' )
                    for( var j= 0; j < list.length; ++j ){
                        var val= $jam.Node.Element( 'wc_test_results_value' )
                        val.text( dumpValue( list[ j ] ) )
                        node.tail( val )
                    }
                    nodeLogs.tail( node )
                }
                
                var run=
                function( ){
                    nodeLogs.clear()
                    passed( 'wait' )
                    $jin_test( nodeSource.text(), update )
                }
                
                var update=
                function( test ){
                    passed( test.passed )
                    for( var i= 0; i < test.results.length; ++i ){
                        printResults( test.results[ i ] )
                    }
                    for( var i= 0; i < test.errors.length; ++i ){
                        printError( test.errors[ i ] )
                    }
                    refreshSummary()
                }
                
                var clone=
                function( ){
                    run()
                    var node=
                    $jam.Node.Element( 'script' ).attr( 'type', 'wc_test' )
                    .text( nodeSource.text() )
                    nodeRoot.prev( node )
                }
                
                var del=
                function( ){
                    nodeRoot.parent( null )
                }
                
                run()
                
                var onCommit=
                nodeRoot.listen( '$jam.eventCommit', run )
                
                var onClone=
                nodeRoot.listen( '$jam.eventClone', clone )
                
                var onClone=
                nodeRoot.listen( '$jam.eventDelete', del )
                
                var onCloneClick=
                nodeClone.listen( 'click', function( event ){
                    $jam.Event().type( '$jam.eventClone' ).scream( event.target() )
                })
                
                var onDeleteClick=
                nodeDelete.listen( 'click', function( event ){
                    $jam.Event().type( '$jam.eventDelete' ).scream( event.target() )
                })
                
                this.destroy=
                function( ){
                    onCommit.sleep()
                    onClone.sleep()
                    onCloneClick.sleep()
                    onDeleteClick.sleep()
                    if( stop ) stop()
                    refreshSummary()
                }
                
            }
        }
    )
}()