$lang.pcre=
new function(){

    var pcre=
    function( str ){
        return pcre.root( pcre.content( str ) )
    }

    pcre.html2text= $jam.html2text

    pcre.root= $lang.Wrapper( 'lang_pcre' )
    pcre.backslash= $lang.Wrapper( 'lang_pcre_backslash' )
    pcre.control= $lang.Wrapper( 'lang_pcre_control' )
    pcre.spec= $lang.Wrapper( 'lang_pcre_spec' )
    pcre.text= $lang.Wrapper( 'lang_pcre_text' )
    
    pcre.content=
    $lang.Parser( new function(){
    
        this[ /\\([\s\S])/.source ]=
        new function( ){
            var backslash= pcre.backslash( '\\' )
            return function( symbol ){
                return backslash + pcre.spec( $lang.text( symbol ) )
            }
        }

        this[ /([(){}\[\]$*+?^])/.source ]=
        $jam.Pipe( $lang.text, pcre.control )
        
    })
    
    return pcre
}
