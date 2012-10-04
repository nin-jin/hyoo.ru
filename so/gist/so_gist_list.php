<?php

class so_gist
{
    use so_meta;
    use so_resource;
    
    var $uri_value;
    var $uri_depends= array( 'uri', 'author' );
    function uri_make( ){
        return so_query::make(array(
            'gist',
            'list',
            'by' => $this->author,
        ))->uri;
    }
    function uri_store( $data ){
        $query= so_uri::make( $data )->query;
        $this->author= $query[ 'by' ];
    }
    
    var $author_value;
    var $author_depends= array( 'uri', 'author' );
    function author_make( ){
        return so_user::make()->id;
    }
    function author_store( $data ){
        return (string) $data ?: $this->author_make();
    }
    
    var $storage_value;
    function storage_make( ){
        return so_storage::make( $this->uri );
    }
    
    var $model_value;
    var $model_depends= array();
    function model_make( ){
        $storage= $this->storage;
        
        $gistList= array();
        if( $storage->version ):
            $uriList= array_reverse( explode( "\n", $storage->content ) );
            foreach( $uriList as $uri )
                $gistList[]= so_gist::makeInstance()->uri( $uri )->teaser;
        endif;
        
        return so_dom::make( array(
            'so_gist_list' => array(
                '@so_uri' => (string) $this->uri,
                '@so_gist_author' => (string) $this->author,
            ),
        ) );
    }
    
    function get( $data= null ){
        $output= $this->storage->version ? so_output::ok() : so_output::missed();
        
        $output->content= array(
            '@so_page_uri' => (string) $this->uri,
            '@so_page_title' => (string) $this->author . " - записи",
            $this->model,
        );
        
        return $output;
    }
    
    function post( $data= null ){
        $gist= so_gist::make( $data[ 'gist' ] );
        
        if( !count( $this->model->select( " // so_gist [ @so_uri = '{$gist}' ]" ) ) )
            $this->storage->append( $gist . "\n" );
        
        return so_output::ok( "Gist [{$gist}] was added" );
    }
    
}
