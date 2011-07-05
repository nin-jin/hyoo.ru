;new function( modules ){
    var packPath= '/jam/-mix/index.js'
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
    "../../jam/jam/jam.jam?1309515733",
    "../../jam/define/jam+define.jam?1309525662",
    "../../jam/Class/jam+Class.jam?1309515732",
    "../../jam/Obj/jam+Obj.jam?1309515732",
    "../../jam/Hash/jam+Hash.jam?1309515732",
    "../../jam/Cached/jam+Cached.jam?1309515732",
    "../../jam/Value/jam+Value.jam?1309515733",
    "../../jam/support/jam+support.jam?1309515733",
    "../../jam/glob/jam+glob.jam?1309515733",
    "../../jam/doc/jam+doc.jam?1309517115",
    "../../jam/schedule/jam+schedule.js?1309515733",
    "../../jam/domReady/jam+domReady.jam?1309797122",
    "../../jam/Component/jam+Component.jam?1309845511",
    "../../jam/Poly/jam+Poly.js?1309515732",
    "../../jam/classOf/jam+classOf.jam?1309515733",
    "../../jam/Event/jam+Event.jam?1309515732",
    "../../jam/Hiqus/jam+Hiqus.jam?1309515732",
    "../../jam/Lazy/jam+Lazy.jam?1309515732",
    "../../jam/String/jam+String.jam?1309846685",
    "../../jam/RegExp/jam+RegExp.jam?1309515732",
    "../../jam/Lexer/jam+Lexer.jam?1309515732",
    "../../jam/Pipe/jam+Pipe.jam?1309515732",
    "../../jam/Parser/jam+Parser.jam?1309515732",
    "../../jam/TemplateFactory/jam+TemplateFactory.jam?1309515733",
    "../../jam/html/jam+html.jam?1309515733",
    "../../jam/Node/jam+Node.jam?1309848190",
    "../../jam/Number/jam+Number.jam?1309515732",
    "../../jam/Thread/jam+Thread.jam?1309789791",
    "../../jam/Throttler/jam+Throttler.js?1309515733",
    "../../jam/log/jam+log.jam?1309515733",
    "../../jam/commit/jam+commit.jam?1309515733",
    "../../jam/createNameSpace/wc+createNameSpace.jam?1309796421",
    "../../jam/dom/jam+dom.jam?1309515733",
    "../../jam/eval/jam+eval.jam?1309777202",
    "../../jam/htmlize/jam+htmlize.jam?1309842631",
null ])
