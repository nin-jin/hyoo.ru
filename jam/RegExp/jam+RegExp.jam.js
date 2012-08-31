$jam.define
(   '$jam.RegExp'
,   $jam.Class( function( klass, proto ){
    
        proto.constructor=
        function( regexp ){
            this.$= new RegExp( regexp )
            return this
        }
        
        klass.escape=
        new function( ){
            var encodeChar= function( symb ){
                return '\\' + symb
            }
            var specChars = '^({[\\.?+*]})$'
            var specRE= RegExp( '[' + specChars.replace( /./g, encodeChar ) + ']', 'g' )
            return function( str ){
                return String( str ).replace( specRE, encodeChar )
            }
        }
        
        klass.build=
        function( ){
            var str= ''
            for( var i= 0; i < arguments.length; ++i ){
                var chunk= arguments[ i ]
                if( i % 2 ) chunk= $jam.RegExp.escape( chunk )
                str+= chunk
            }
            return $jam.RegExp( str )
        }

        proto.source=
        function(){
            return this.$.source
        }

        proto.count=
        new function( ){
            var offset= /^$/.exec( '' ).length
            return function( ){
                return RegExp( '^$|' + this.$.source ).exec( '' ).length - offset
            }
        }

    })
)
