with( $jam )
$define
(	'$Parser'
,	function( syntaxes ){
		var lexems= {}
		for( var name in syntaxes ){
			var regexp= syntaxes[ name ].regexp
			if( !regexp ) continue
			lexems[ name ]= $RegExp( regexp ).$
		}
		var lexer= $Lexer( lexems )
		
		var handlers= { '': $Pipe() }
		for( var name in syntaxes ) handlers[ name ]= syntaxes[ name ].handler
		
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
