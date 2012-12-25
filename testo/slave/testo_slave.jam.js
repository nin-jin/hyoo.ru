var $testo_slave= new function( ){
    var socket= $lib_io.connect( ':1351' )
    
    socket.on( 'connect', function( param ){
        socket.emit( 'agent:ready', { id: navigator.userAgent } )
    } )
    
    socket.on( 'agent:run', function( param ){
        document.location= param.uri
    } )
    
    this.done= function( passed ){
        socket.emit( 'agent:done', { id: navigator.userAgent, state: passed } )
    }

}
