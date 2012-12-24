this.$doc_testo= function( uri ){
    
    var socket= $lib_io.connect( uri )
    
    socket.on( 'agent:run', function( param, done ){
        document.location= param.uri
    } )
    
    socket.on( 'connect', function( param, done ){
        socket.emit( 'agent:ready', { id: navigator.userAgent } )
    } )
    
    socket.on( 'disconnect', function( param, done ){
        // stop tests
    } )
    
    setTimeout( function( ){
        socket.emit( 'agent:done', { state: true } )
    }, 2000 )
}

$doc_testo( 'http://localhost:8080' )