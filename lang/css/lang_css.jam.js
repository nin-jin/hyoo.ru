$lang.css=
new function(){
    
    var css=
    function( str ){
        return css.root( css.stylesheet( str ) )
    }
    
    css.html2text= $jam.html2text

    css.root= $lang.Wrapper( 'lang_css' )
    css.remark= $lang.Wrapper( 'lang_css_remark' )
    css.string= $lang.Wrapper( 'lang_css_string' )
    css.bracket= $lang.Wrapper( 'lang_css_bracket' )
    css.selector= $lang.Wrapper( 'lang_css_selector' )
    css.tag= $lang.Wrapper( 'lang_css_tag' )
    css.id= $lang.Wrapper( 'lang_css_id' )
    css.klass= $lang.Wrapper( 'lang_css_class' )
    css.pseudo= $lang.Wrapper( 'lang_css_pseudo' )
    css.property= $lang.Wrapper( 'lang_css_property' )
    css.value= $lang.Wrapper( 'lang_css_value' )
    
    css.stylesheet=
    $lang.Parser( new function( ){
        
        this[ /(\/\*[\s\S]*?\*\/)/.source ]=
        $jam.Pipe( $lang.text, css.remark )
        
        this[ /(\*|(?:\\[\s\S]|[\w-])+)/.source ]=
        $jam.Pipe( $lang.text, css.tag )
        
        this[ /(#(?:\\[\s\S]|[\w-])+)/.source ]=
        $jam.Pipe( $lang.text, css.id )
        
        this[ /(\.(?:\\[\s\S]|[\w-])+)/.source ]=
        $jam.Pipe( $lang.text, css.klass )
        
        this[ /(::?(?:\\[\s\S]|[\w-])+)/.source ]=
        $jam.Pipe( $lang.text, css.pseudo )
        
        this[ /\{([\s\S]+?)\}/.source ]=
        new function( ){
            var openBracket= css.bracket( '{' )
            var closeBracket= css.bracket( '}' )
            return function( style ){
                style= css.style( style )
                return openBracket + style + closeBracket
            }
        }             
    })
    
    css.style=
    $lang.Parser( new function( ){
            
        this[ /(\/\*[\s\S]*?\*\/)/.source ]=
        $jam.Pipe( $lang.text, css.remark )
        
        this[ /([\w-]+\s*:)/.source  ]=
        $jam.Pipe( $lang.text, css.property )
        
        this[ /([^:]+?(?:;|$))/.source ]=
        $jam.Pipe( $lang.text, css.value )
        
    })
    
    return css
}
