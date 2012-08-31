$lang.js=
new function(){

    var js=
    function( str ){
        return js.root( js.content( str ) )
    }

    js.root= $lang.Wrapper( 'lang:js' )
    js.remark= $lang.Wrapper( 'lang:js_remark' )
    js.string= $lang.Wrapper( 'lang:js_string' )
    js.internal= $lang.Wrapper( 'lang:js_internal' )
    js.external= $lang.Wrapper( 'lang:js_external' )
    js.keyword= $lang.Wrapper( 'lang:js_keyword' )
    js.number= $lang.Wrapper( 'lang:js_number' )
    js.regexp= $lang.Wrapper( 'lang:js_regexp' )
    js.bracket= $lang.Wrapper( 'lang:js_bracket' )
    js.operator= $lang.Wrapper( 'lang:js_operator' )
         
    js.content=
    $lang.Parser( new function(){
    
        this[ /(\/\*[\s\S]*?\*\/)/.source ]=
        $jam.Pipe( $lang.text, js.remark )
        this[ /(\/\/[^\n]*)/.source ]=
        $jam.Pipe( $lang.text, js.remark )
        
        this[ /('(?:[^\n'\\]*(?:\\\\|\\[^\\]))*[^\n'\\]*')/.source ]=
        $jam.Pipe( $lang.text, js.string )
        this[ /("(?:[^\n"\\]*(?:\\\\|\\[^\\]))*[^\n"\\]*")/.source ]=
        $jam.Pipe( $lang.text, js.string )
        
        this[ /(\/(?:[^\n\/\\]*(?:\\\\|\\[^\\]))*[^\n\/\\]*\/[mig]*)/.source ]=
        $jam.Pipe( $lang.pcre, js.regexp )
        
        this[ /\b(_[\w$]*)\b/.source ]=
        $jam.Pipe( $lang.text, js.internal )
        
        this[ /(\$[\w$]*)(?![\w$])/.source ]=
        $jam.Pipe( $lang.text, js.external )

        this[ /\b(this|function|new|var|if|else|switch|case|default|for|in|while|do|with|boolean|continue|break|throw|true|false|void|try|catch|null|typeof|instanceof|return|delete|window|document|let|each|yield)\b/.source ]=
        $jam.Pipe( $lang.text, js.keyword )
        
        this[ /((?:\d*\.)?\d(?:[eE])?)/.source ]=
        $jam.Pipe( $lang.text, js.number )
        
        this[ /([(){}\[\]])/.source ]=
        $jam.Pipe( $lang.text, js.bracket )
        
        this[ /(\+{1,2}|-{1,2}|\*|\/|&{1,2}|\|{1,2}|={1,2}|%|\^|!)/.source ]=
        $jam.Pipe( $lang.text, js.operator )
        
    })
    
    return js
}
