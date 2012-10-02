$lang.php=
new function( ){

    var php=
    function( str ){
        return php.root( php.content( str ) )
    }

    php.root= $lang.Wrapper( 'lang_php' )
    php.dollar= $lang.Wrapper( 'lang_php_dollar' )
    php.variable= $lang.Wrapper( 'lang_php_variable' )
    php.string= $lang.Wrapper( 'lang_php_string' )
    php.number= $lang.Wrapper( 'lang_php_number' )
    php.func= $lang.Wrapper( 'lang_php_func' )
    php.keyword= $lang.Wrapper( 'lang_php_keyword' )
    
    php.content=
    $lang.Parser( new function(){
        
        this[ /\b(__halt_compiler|abstract|and|array|as|break|callable|case|catch|class|clone|const|continue|declare|default|die|do|echo|else|elseif|empty|enddeclare|endfor|endforeach|endif|endswitch|endwhile|eval|exit|extends|final|for|foreach|function|global|gotoif|implements|include|include_once|instanceof|insteadof|interface|isset|list|namespace|new|or|print|private|protected|public|require|require_once|return|static|switch|throw|trait|try|unset|use|var|while|xor|__CLASS__|__DIR__|__FILE__|__FUNCTION__|__LINE__|__METHOD__|__NAMESPACE__|__TRAIT__)\b/.source ]=
        $jam.Pipe( $lang.text, php.keyword )
        
        this[ /(\$)(\w+)\b/.source ]=
        function( dollar, variable ){
            dollar= $lang.php.dollar( dollar )
            variable= $lang.php.variable( variable )
            return dollar + variable
        }
        
        this[ /(\w+)(?=\s*\()/.source ]=
        php.func
        
        this[ /('(?:[^\n'\\]*(?:\\\\|\\[^\\]))*[^\n'\\]*')/.source ]=
        this[ /("(?:[^\n"\\]*(?:\\\\|\\[^\\]))*[^\n"\\]*")/.source ]=
        $jam.Pipe( $lang.text, php.string )
        
        this[ /((?:\d*\.)?\d(?:[eE])?)/.source ]=
        $jam.Pipe( $lang.text, php.number )
        
    })
    
    return php
}
