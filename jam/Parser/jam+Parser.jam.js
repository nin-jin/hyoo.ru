$jam.define
(    '$jam.Parser'
,    function( syntaxes ){
        var lexems= []
        var handlers= []
        handlers[ '' ]= syntaxes[ '' ] || $jam.Pipe()

        for( var regexp in syntaxes ){
            if( !syntaxes.hasOwnProperty( regexp ) ) continue
            if( !regexp ) continue
            lexems.push( RegExp( regexp ) )
            handlers.push( syntaxes[ regexp ] )
        }
        var lexer= $jam.Lexer( lexems )
        
        return function( str ){
            var res= []
            for( var i= lexer( str ); i.next().found; ){
                var val= handlers[ i.name ].apply( this, i.chunks )
                if( val !== void 0 ) res.push( val )
            }
            return res
        }
    }
)
