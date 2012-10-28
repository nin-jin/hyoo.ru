$jam.define
(    '$lang.tags'
,    new function(){
        
        var tags=
        function( str ){
            return tags.root( tags.content( str ) )
        }
        
        tags.html2text= $jam.html2text
        
        tags.root= $lang.Wrapper( 'lang_tags' )
        tags.item= $lang.Wrapper( 'lang_tags_item' )
        
        tags.content=
        $lang.Parser( new function(){
        
            this[ /^(\s*?)([^\n\r]+)(\s*?)$/.source ]=
            function( open, text, close ){
                return open + '<a href="?gist/list/' + $jam.htmlEscape( text ) + '">' + tags.item( text ) + '</a>' + close
            }
            
        })
        
        return tags
    }
) 
