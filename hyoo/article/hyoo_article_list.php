<?php

class hyoo_article_list
{
    use so_gist_list;
    
    var $uri_value;
    var $uri_depends= array( 'uri', 'name', 'author' );
    function uri_make( ){
        return so_query::make(array(
            'article;list',
        ))->uri;
    }
    function uri_store( $data ){
    }
    
    function get_resource( $data= null ){
        $articleList= array();
        $authorHash= array();
        
        foreach( $this->map as $article ):
            $articleList[]= $article->teaser;
            $author= $article->author;
            $authorHash[ (string) $author ]= $author->teaser;
        endforeach;
        
        return so_output::ok()->content( array(
            '@so_page_uri' => (string) $this->uri,
            'hyoo_article_list' => array(
                '@so_uri' => (string) $this->uri,
                $articleList,
            ),
            array_values( $authorHash ),
        ) );
    }
    
}
