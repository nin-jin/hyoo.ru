$jam.currentScript= function( ){
    var script= document.currentScript
    
    if( !script ){
        var scriptList= document.getElementsByTagName( 'script' )
        script= scriptList[ scriptList.length - 1 ]
    }
    
    for( var parent; parent= script.parentScript; script= parent );
    
    return script
}