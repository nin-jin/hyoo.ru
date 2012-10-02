$jam.http= $jam.Class( function( klass, proto ){
    
    proto.constructor= function( uri ){
        this.$= { uri: uri }
        return this
    }
    
    proto.request= function( method, data ){
        var channel= new XMLHttpRequest
        channel.open( method, this.$.uri, false )
        if( data && !( data instanceof String ) ){
            var chunks= []
            for( var key in data ){
                if( !data.hasOwnProperty( key ) )
                    continue
                chunks.push( encodeURIComponent( key ) + '=' + encodeURIComponent( data[ key ] ) )
            }
            data= chunks.join( '&' )
            channel.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' )
        }
        channel.send( data )
        return channel.responseText
    }
    
    proto.get= function( ){
        return this.request( 'get' )
    }
    
    proto.post= function( data ){
        return this.request( 'post', data )
    }
    
    proto.put= function( data ){
        return this.request( 'put', data )
    }
    
})
