<?php

class mixer_article_list_author
{
    use so_gist_list;
    
    var $uri_value;
    var $uri_depends= array( 'uri', 'name', 'author' );
    function uri_make( ){
        return so_query::make(array(
            'article;list',
            'author' => (string) $this->author->name,
        ))->uri;
    }
    function uri_store( $data ){
        $query= so_uri::make( $data )->query;
        $author= mixer_author::makeInstance()->name( $query[ 'author' ] )->primary();
        $this->author= $author;
    }
    
    var $author_value;
    var $author_depends= array( 'uri', 'author' );
    function author_make( ){
        return mixer_author::make();
    }
    function author_store( $data ){
        return mixer_author::make( $data );
    }
    
    function get_resource( $data= null ){
        $articleList= array();
        
        foreach( $this->map as $article )
            $articleList[]= $article->teaser;
        
        return so_output::ok()->content( array(
            '@so_page_uri' => (string) $this->uri,
            'mixer_article_list' => array(
                '@so_uri' => (string) $this->uri,
                '@mixer_article_author' => (string) $this->author,
                $articleList,
            ),
            $this->author->teaser,
        ) );
    }
    
}
