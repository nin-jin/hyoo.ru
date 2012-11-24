$jam.Component
(   'wc_editor'
,   function( nodeRoot ){
        return new function( ){
            nodeRoot= $jam.Node( nodeRoot )

            var source= $jam.htmlEscape( nodeRoot.text() ).replace( /\r?\n/g, '<br />' )
            
            var hint= nodeRoot.attr( 'wc_editor_hint' ) || ''
            
            nodeRoot.clear()
            var nodeSource= $jam.Node.Element( 'div' ).attr( 'wc_editor_content', hint )
            .html( source )
            .parent( nodeRoot )
            
            var sourceLast= ''
            var update= function( addon, replace ){
                //var source= $jam.String( nodeSource.text() ).replace( /\n?\r?$/, '\n' ).$
                addon= addon || ''
                var hlighter= $lang ( nodeRoot.attr( 'wc_editor_hlight' ) )
                var source= replace || $jam.html2text( nodeSource.html() )
                
                if( !addon && source === sourceLast ) return
                sourceLast= source
                
                var nodeRange= $jam.DomRange().aimNodeContent( nodeSource )
                var startPoint= $jam.DomRange().collapse2start()
                //console.log(nodeRange.html())
                var endPoint= $jam.DomRange().collapse2end()
                var hasStart= nodeRange.hasRange( startPoint )
                var hasEnd= nodeRange.hasRange( endPoint )
                if( hasStart ){
                    var metRange= $jam.DomRange()
                    .equalize( 'end2start', startPoint )
                    .equalize( 'start2start', nodeRange )
                    var offsetStart= metRange.text().length
                }
                if( hasEnd ){
                    var metRange= $jam.DomRange()
                    .equalize( 'end2start', endPoint )
                    .equalize( 'start2start', nodeRange )
                    var offsetEnd= metRange.text().length
                    //console.log(metRange.html(),metRange.text(), offsetEnd)
                }
                
                //console.log(offsetStart,offsetEnd)
                var offsetCut= offsetEnd || source.length
                source= source.substring( 0, offsetCut ) + addon + source.substring( offsetCut )
                if( offsetStart >= offsetCut ) offsetStart= offsetStart + addon.length
                if( offsetEnd >= offsetCut ) offsetEnd= offsetEnd + addon.length
                
                source=
                $jam.String( source )
                .process( hlighter )
                .replace( /  /g, '\u00A0 ' )
                .replace( /  /g, ' \u00A0' )
                //.replace( /[^\n<>](?:<[^<>]+>)*$/, '$&\n' )
                .replace( /$/, '\n' )
                .replace( /\n/g, '<br/>' )
                .$
                
                nodeSource.html( source )
                
                var selRange= $jam.DomRange()
                if( hasStart ){
                    var startRange= nodeRange.clone().move( offsetStart )
                    selRange.equalize( 'start2start', startRange )
                }
                if( hasEnd ){
                    selRange.equalize( 'end2start', nodeRange.clone().move( offsetEnd ) )
                }
                if( hasEnd || hasEnd ){
                    selRange.select()
                }
                
                //nodeSource.dissolveTree()
                //console.log(source.charCodeAt( source.length -1 ))
                //if( source.charAt( source.length -1 ) !== '\n' ) nodeSource.tail( $jam.Node.Text( '\n' ) )
                //if( !source ) $jam.DomRange().aimNode( nodeSource.head() ).collapse2end().select()
                //if( nodeSource.tail() && nodeSource.tail().name() !== 'br' ) nodeSource.tail( $jam.Node.Element( 'br' ) )
            }
            
            var onEdit=
            nodeRoot.listen( '$jam.eventEdit', $jam.Throttler( 100, function(){ update() } ) )
            
            var onEnter=
            nodeRoot.listen( 'keypress', function( event ){
                event= $jam.Event( event )
                if( !event.keyCode().enter ) return
                if( event.keyAccel() ) return
                event.defaultBehavior( false )
                $jam.DomRange().html( '<br/>' ).collapse2end().select()
            })
            
            var onAltSymbol=
            nodeRoot.listen( 'keydown', function( event ){
                event= $jam.Event( event )
                //console.log( event.keyCode() )
                if( !event.keyAlt() ) return
                
                if( event.keyShift() ){
                    var symbolSet= new function( ){
                        this[ '0' ]= '∅' // пустое множество
                        this[ '5' ]= '‰' // промилле
                        this[ '8' ]= '∞' // бесконечность
                        this[ 'a' ]= '∀' // всеобщность
                        this[ 'e' ]= '∃' // существование
                        this[ 's' ]= '∫' // интегралл
                        this[ 'v' ]= '√' // корень
                        this[ 'x' ]= '×' // умножение
                        this[ 'plus' ]= '±' // плюс-минус
                        this[ 'comma' ]= '≤' // не больше
                        this[ 'minus' ]= '−' // минус
                        this[ 'period' ]= '≥' // не меньше
                        this[ 'openBracket' ]= '{'
                        this[ 'closeBracket' ]= '}'
                    }
                } else {
                    var symbolSet= new function( ){
                        this[ '0' ]= '°' // градус
                        this[ '3' ]= '#'
                        this[ '4' ]= '$'
                        this[ 'c' ]= '©' // копирайт
                        this[ 'o' ]= '•' // маркер списка
                        this[ 's' ]= '§' // параграф
                        this[ 'v' ]= '✓' // выполнено
                        this[ 'x' ]= '✗' // провалено
                        this[ 'plus' ]= '≠' // не равно
                        this[ 'comma' ]= '«' // открывающая кавычка
                        this[ 'minus' ]= '–' // среднее тире
                        this[ 'period' ]= '»' // закрывающая кавычка
                        this[ 'tilde' ]= '\u0301' // ударение
                        this[ 'openBracket' ]= '['
                        this[ 'backSlash' ]= '|'
                        this[ 'closeBracket' ]= ']'
                    }
                }
                
                var symbol= symbolSet[ $jam.keyCode( event.keyCode() ) ]
                if( !symbol ) return
                
                event.defaultBehavior( false )
                $jam.DomRange().text( symbol ).collapse2end().select()
            })
            
            //var onBackspace=
            //nodeRoot.listen( 'keydown', function( event ){
            //    event= $jam.Event( event )
            //    if( event.keyCode() != 8 ) return
            //    if( event.keyAccel() ) return
            //    event.defaultBehavior( false )
            //    var fullRange= $jam.DomRange().aimNodeContent( nodeSource )
            //    var newOffset= fullRange.clone().equalize( 'end2start', $jam.DomRange() ).text().length - 1
            //    if( newOffset < 0 ) newOffset= 0
            //    var range= fullRange.clone().move( newOffset ).equalize( 'end2end', $jam.DomRange() )
            //    range.dropContents()
            //})
            
            var onTab=
            nodeRoot.listen( 'keydown', function( event ){
                event= $jam.Event( event )
                if( !event.keyCode().tab ) return
                if( event.keyAccel() ) return
                event.defaultBehavior( false )
                $jam.DomRange().text( '    ' ).collapse2end().select()
            })
            
            var onLeave=
            nodeSource.listen( 'blur', function( event ){
                //$jam.Event().type( '$jam.eventCommit' ).scream( nodeRoot )
            })
            
            var onPaste=
            nodeSource.listen( 'paste', function( event ){
                $jam.schedule( 0, function( ){
                    var hlighter= $lang ( nodeRoot.attr( 'wc_editor_hlight' ) )
                    var source= hlighter.html2text( nodeSource.html() )
                    update( '', source )
                })
            })
            
            var onActivate=
            nodeRoot.listen( 'mousedown', function( event ){
                event= $jam.Event( event )
                if( event.keyAccel() ) return
                if( $jam.Node( event.target() ).ancList( 'a' ).length() ) return
                nodeRoot.attr( 'wc_editor_active', true )
                nodeSource.editable( true )
            })
            
            var onDeactivate=
            nodeRoot.listen( 'keydown', function( event ){
                event= $jam.Event( event )
                if( !event.keyCode().escape ) return
                nodeSource.editable( false )
                nodeRoot.attr( 'wc_editor_active', false )
                event.defaultBehavior( false )
            })
            
            var onDragEnter=
            nodeRoot.listen( 'dragenter', function( event ){
                event.defaultBehavior( false )
            })
            
            var onDragOver=
            nodeRoot.listen( 'dragover', function( event ){
                event.defaultBehavior( false )
            })
            
            var onDragLeave=
            nodeRoot.listen( 'dragleave', function( event ){
                event.defaultBehavior( false )
            })
            
            var onDrop=
            nodeRoot.listen( 'drop', function( event ){
                event.defaultBehavior( false )
                function upload( file ){
                    var form = new FormData()
                    form.append( 'file', file )
                    var resource= '?image=' + Math.random()
                    var result= $jam.http( resource ).post( form )
                    var src= $jam.domx.parse( result ).select(' // * [ @so_uri = "' + resource + '" ] / @hyoo_image_maximal ').$.value
                    var link= $jam.domx.parse( result ).select(' // * [ @so_uri = "' + resource + '" ] / @hyoo_image_original ').$.value
                    update( '\n./' + src + '\\./' + link + '\n' )
                }
                var files= event.$.dataTransfer.files
                for( var i= 0; i < files.length; ++i ){
                    upload( files[ i ] )
                }
            })
            
            this.destroy= function( ){
                onEdit.sleep()
                onLeave.sleep()
            }
            
            update()
            nodeRoot.attr( 'wc_editor_inited', true )
            
            if( nodeRoot.attr( 'wc_editor_active' ) == 'true' )
                nodeSource.editable( true )
        }
    }
)
