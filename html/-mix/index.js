;new function( modules ){
    var packPath= '/html/-mix/index.js'
    var scripts= document.getElementsByTagName('script')
    for( var i= scripts.length - 1; i >= 0; --i ){
        var script= scripts[ i ]
        var src= script.src
        src= src.replace( /^(?=[^:]+\/)/, document.location.href.replace( /\/[^\/]*$/, '/' ) )
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
        var parent= script.parentNode
        var next= function(){
            var module= modules.shift()
            if( !module ) return
            var loader= document.createElement( 'script' )
            loader.src= dir + module
            loader.onload= next
            parent.insertBefore( loader, script )
        }
        next()
    }
}([
    "../../jam/jam/jam.jam?1305067177",
    "../../jam/Class/jam+Class.jam?1309231687",
    "../../jam/Obj/jam+Obj.jam?1309182591",
    "../../jam/define/jam+define.jam?1308927760",
    "../../jam/Value/jam+Value.jam?1309103756",
    "../../jam/glob/jam+glob.jam?1305067177",
    "../../jam/createNameSpace/wc+createNameSpace.jam?1308209032",
    "../../html/html/html.jam?1308040898",
    "../../jam/support/jam+support.jam?1309327224",
    "../../jam/doc/jam+doc.jam?1305067177",
    "../../jam/schedule/jam+schedule.js?1308910976",
    "../../jam/domReady/jam+domReady.jam?1309006120",
    "../../jam/Poly/jam+Poly.js?1309012542",
    "../../jam/classOf/jam+classOf.jam?1305067176",
    "../../jam/String/jam+String.jam?1309231590",
    "../../jam/Hiqus/jam+Hiqus.jam?1309249411",
    "../../jam/Event/jam+Event.jam?1309279397",
    "../../jam/Node/jam+Node.jam?1309327392",
    "../../jam/Component/jam+Component.jam?1309329114",
    "../../html/a/html-a.jam?1309253258",
null ])
