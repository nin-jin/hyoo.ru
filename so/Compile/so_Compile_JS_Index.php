<?php

class so_Compile_JS_Index extends so_NativeTemplate {
    function exec(){
        extract( $this->param );


?>
;(function( modules ){
    var packPath= '<?=$indexPath;?>'
    var scripts= document.getElementsByTagName( 'script' )
    if( !scripts.length ) scripts= document.getElementsByTagNameNS( 'http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul', 'script' )
    
    for( var i= scripts.length - 1; i >= 0; --i ){
        var script= scripts[ i ]
        var src= script.getAttribute( 'src' )
        if( !src ) continue
        src= src.replace( /^(?=[^:]+\/)/, document.location.pathname.replace( /\/[^\/]*$/, '/' ) )
        while( true ) {
            srcNew= src.replace( /\/(?!\.\.)[^\/]+\/\.\.(?=\/)/g, '' )
            if( srcNew === src ) break
            src= srcNew
        }
        if( src.indexOf( packPath ) >= 0 ) break
        if( !i ) throw new Error( 'Can not locate index script path' )
    }
    var dir= src.replace( /[^\/]+$/, '' )
        
    try {
        document.write( '' )
        var canWrite= true
    } catch( e ){}
    
    if( canWrite ){
        var module
        while( module= modules.shift() ){
            document.write( '<script src="' + dir + module + '"><' + '/script>' )
        }
    } else {
        var next= function(){
            var module= modules.shift()
            if( !module ) return
            var loader= document.createElement( 'script' )
            loader.src= dir + module
            loader.onload= next
            script.parentNode.insertBefore( loader, script )
        }
        next()
    }
}).call( this, [
<? foreach( $files as $file ): ?>
    "../../<?= $file->id; ?>?<?= $file->version; ?>",
<? endforeach; ?>
null ])
<?php


    }
}
    
