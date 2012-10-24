<?php

class mixer_article_list
{
    use so_resource;
    
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
    
    var $database_value;
    function database_make( ){
        $storage= so_storage::make( $this->uri );
        return so_teaser_database::make( $storage->dir[ 'mixer_article_list.sqlite' ] );
    }
    
    var $model_value;
    var $model_depends= array();
    function model_make( ){
        return so_dom::make( array(
            'mixer_article_list' => array(
            ),
        ) );
    }
    
    function get_resource( $data= null ){
        $output= so_output::ok();
        
        $output->content= array(
            '@so_page_uri' => (string) $this->uri,
            $this->model,
        );
        
        return $output;
    }
    
}
