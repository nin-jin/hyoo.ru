with( $jam$ )
with( $wc$ )
$Component
(   'wc:js-test'
,   function( nodeRoot ){
        return new function( ){
            nodeRoot= $Node( nodeRoot )
            
            var exec= $Thread( function( ){
                var source= nodeSource.text()
                var proc= new Function( '_test', source )
                proc( _test )
                return true
            })
            
            var source= $String( nodeRoot.text() ).minimizeIndent().trim( /[\n\r]/ ).$
        
            nodeRoot.clear()
            var nodeSource0= $Node.Element( 'wc:js-test_source' ).parent( nodeRoot )
            var nodeSource= $Node.parse( '<wc:editor class=" hlight=js " />' ).text( source ).parent( nodeSource0 )
            var nodeControls= $Node.Element( 'wc:hontrol' ).parent( nodeRoot )
            var nodeClone= $Node.parse( '<wc:hontrol_clone title="ctrl+shift+enter">clone' ).parent( nodeControls )
            var nodeDelete= $Node.parse( '<wc:hontrol_delete>delete' ).parent( nodeControls )

            var _test= {}
            
            var checkDone= function( ){
                if( passed() !== 'wait' ) throw new Error( 'Test already done' )
            }
            
            _test.ok=
            $Poly
            (   function( ){
                    checkDone()
                    if( passed() === 'wait' ) passed( true )
                }
            ,   function( val ){
                    checkDone()
                    passed( Boolean( val ) )
                    printValue( val )
                    if( !val ) throw new Error( 'Result is empty' )
                }
            ,   function( a, b ){
                    checkDone()
                    passed( a === b )
                    printValue( a )
                    if( a !== b ){
                        printValue( b )
                        throw new Error( 'Results is not equal' )
                    }
                }
            )

            _test.not=
            $Poly
            (   function( ){
                    checkDone()
                    passed( false )
                    throw new Error( 'Test fails' )
                }
            ,   function( val ){
                    checkDone()
                    printValue( val )
                    passed( !val )
                    if( val ) throw new Error( 'Result is not empty' )
                }
            ,   function( a, b ){
                    checkDone()
                    printValue( a )
                    printValue( b )
                    passed( a !== b )
                    if( a == b ) throw new Error( 'Results is equal' )
                }
            )
            
            var stop
            
            var noMoreWait= function( ){
                if( passed() !== 'wait' ) return
                passed( false )
                print( 'Timeout!' )
                stop= null
                throw new Error( 'Timeout!' )
            }
            
            _test.deadline=
            $Poly
            (   null
            ,   function( ms ){
                    if( stop ) throw new Error( 'Deadline redeclaration' )
                    stop= $schedule( ms, noMoreWait )
                }
            )
        
            var passed=
            $Poly
            (   function( ){
                    return nodeRoot.state( 'passed' )
                }
            ,   function( val ){
                    nodeRoot.state( 'passed', val )
                }
            )
            
            var print=
            function( val ){
                var node= $Node.Element( 'wc:js-test_result' )
                node.text( val )
                nodeRoot.tail( node )
            }
            
            var printValue=
            function( val ){
                if( typeof val === 'function' ){
                    if( !val.hasOwnProperty( 'toString' ) ){
                        print( 'Function: [object Function]' )
                        return
                    }
                }
                print( $classOf( val ) + ': ' + val )
            }
            
            var run=
            function( ){
                var results= nodeRoot.childList( 'wc:js-test_result' )
                for( var i= 0; i < results.length(); ++i ){
                    results.get(i).parent( null )
                }
                passed( 'wait' )
                stop= null
                if( !exec() ) passed( false )
                if(( !stop )&&( passed() === 'wait' )) passed( false )
            }
            
            var clone=
            function( ){
                run()
                var node=
                $Node.Element( 'wc:js-test' )
                .text( nodeSource.text() )
                nodeRoot.prev( node )
            }
            
            var del=
            function( ){
                nodeRoot.parent( null )
            }
            
            run()

            var onCommit=
            nodeRoot.listen( '$jam$.$eventCommit', run )
            
            var onClone=
            nodeRoot.listen( '$jam$.$eventClone', clone )
            
            var onClone=
            nodeRoot.listen( '$jam$.$eventDelete', del )
            
            var onCloneClick=
            nodeClone.listen( 'click', function( event ){
                $Event().type( '$jam$.$eventClone' ).scream( event.target() )
            })
            
            var onDeleteClick=
            nodeDelete.listen( 'click', function( event ){
                $Event().type( '$jam$.$eventDelete' ).scream( event.target() )
            })
            
            this.destroy=
            function( ){
                onCommit.sleep()
                onClone.sleep()
                onCloneClick.sleep()
                onDeleteClick.sleep()
                if( stop ) stop()
                _test.ok= _test.not= $Value()
            }
            
        }
    }
)
