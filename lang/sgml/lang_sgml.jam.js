$lang.sgml=
new function(){

    var sgml=
    function( str ){
        return sgml.root( sgml.content( str ) )
    }

    sgml.root= $lang.Wrapper( 'lang_sgml' )
    sgml.tag= $lang.Wrapper( 'lang_sgml_tag' )
    sgml.tagBracket= $lang.Wrapper( 'lang_sgml_tag-bracket' )
    sgml.tagName= $lang.Wrapper( 'lang_sgml_tag-name' )
    sgml.attrName= $lang.Wrapper( 'lang_sgml_attr-name' )
    sgml.attrValue= $lang.Wrapper( 'lang_sgml_attr-value' )
    sgml.comment= $lang.Wrapper( 'lang_sgml_comment' )
    sgml.decl= $lang.Wrapper( 'lang_sgml_decl' )
    
    sgml.tag=
    $jam.Pipe
    (   $lang.Parser( new function(){
        
            this[ /^(<\/?)([a-zA-Z][\w:-]*)/.source ]=
            function( bracket, tagName ){
                return sgml.tagBracket( $lang.text( bracket ) ) + sgml.tagName( tagName )
            } 
            
            this[ /(\s)([sS][tT][yY][lL][eE])(\s*=\s*)(")([\s\S]*?)(")/.source ]=
            this[ /(\s)([sS][tT][yY][lL][eE])(\s*=\s*)(')([\s\S]*?)(')/.source ]=
            function( prefix, name, sep, open, value, close ){
                name= sgml.attrName( name )
                value= sgml.attrValue( open + $lang.css.style( value ) + close )
                return prefix + name + sep + value
            }

            this[ /(\s)([oO][nN]\w+)(\s*=\s*)(")([\s\S]*?)(")/.source ]=
            this[ /(\s)([oO][nN]\w+)(\s*=\s*)(')([\s\S]*?)(')/.source ]=
            function( prefix, name, sep, open, value, close ){
                name= sgml.attrName( name )
                value= sgml.attrValue( open + $lang.js( value ) + close )
                return prefix + name + sep + value
            }

            this[ /(\s)([a-zA-Z][\w:-]+)(\s*=\s*)("[\s\S]*?")/.source ]=
            this[ /(\s)([a-zA-Z][\w:-]+)(\s*=\s*)('[\s\S]*?')/.source ]=
            function( prefix, name, sep, value ){
                name= sgml.attrName( name )
                value= sgml.attrValue( value )
                return prefix + name + sep + value
            }
        
        })
    ,   $lang.Wrapper( 'lang_sgml_tag' )
    )

    sgml.content=
    $lang.Parser( new function(){
    
        this[ /(<!--[\s\S]*?-->)/.source ]=
        $jam.Pipe( $lang.text, sgml.comment )
        
        this[ /(<![\s\S]*?>)/.source ]=
        $jam.Pipe( $lang.text, sgml.decl )
        
        this[ /(<[sS][tT][yY][lL][eE][^>]*>)([\s\S]+?)(<\/[sS][tT][yY][lL][eE]>)/.source ]=
        function( prefix, content, postfix ){
            prefix= $lang.sgml.tag( prefix )
            postfix= $lang.sgml.tag( postfix )
            content= $lang.css( content )
            return prefix + content + postfix
        }
        
        this[ /(<[sS][cC][rR][iI][pP][tT][^>]*>)([\s\S]+?)(<\/[sS][cC][rR][iI][pP][tT]>)/.source ]=
        function( prefix, content, postfix ){
            prefix= $lang.sgml.tag( prefix )
            postfix= $lang.sgml.tag( postfix )
            content= $lang.js( content )
            return prefix + content + postfix
        }
        
        this[ /(<[^>]+>)/.source ]=
        sgml.tag
        
    })
    
    return sgml
}
