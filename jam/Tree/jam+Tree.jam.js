$jam.define
(   '$jam.Tree'
,   $jam.Class( function( klass, proto ){
        
        proto.constructor=
        function( data ){
            this.$= data || []
            return this
        }
        
        klass.Parser=
        function( syntax ){
            if( !syntax ) syntax= {}
            var lineSep= syntax.lineSep || ';'
            var valSep= syntax.valSep || '='
            var oneIndent= syntax.oneIndent || '+'
            var keySep= syntax.keySep || '_'
            var lineParser= $jam.RegExp.build( '^((?:', oneIndent, ')*)(.*?)(?:', valSep, '(.*))?$' ).$

            var parser=
            function( str ){
                var lineList= str.split( lineSep )
                var data= []
                var stack= [ data ]
                
                for( var i= 0; i < lineList.length; ++i ){
                    var line= lineParser.exec( lineList[ i ] )
                    var indentCount= line[1].length / oneIndent.length
                    stack= stack.slice( stack.length - indentCount - 1 )
                    var path= line[2]
                    var val= line[3]
                    var keyList= path.split( keySep )
                    var keyEnd= keyList.pop()
                    var cur= stack[0]
                    if( keyEnd ){
                        keyList.push( keyEnd )
                    } else {
                        stack.unshift( val= [] )
                    }
                    while( keyList.length ){
                        var key= keyList.pop()
                        val= [{ name: key, content: val }]
                    }
                    cur.push( val[0] )
                }
                
                return $jam.Tree( data )
            }
            
            return parser
        }
        
    })
)