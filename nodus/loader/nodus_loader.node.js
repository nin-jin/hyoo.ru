module.exports= Proxy.createFunction
(   new function( ){
    
        this.get=
        function( obj, name ){
            var chunks= name.split( '_' )
            var pack= chunks[ 0 ]
            var mod= chunks[ 1 ]
            if( !mod ) return void 0
            return require( __dirname.replace( /[^\/\\]*[\/\\][^\/\\]*$/, '' ) + pack + '/' + mod + '/' + name + '.node.js' )[ name ]
        }
        
        this.getOwnPropertyNames=
        function( proxy ){
            return []
        }
    
    }
,   function( name ){
        return require.apply( this, arguments )
    }
)
