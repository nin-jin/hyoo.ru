<?php

class so_article
{
    use so_resource;
    
    var $uri_value;
    var $uri_depends= array( 'uri', 'name', 'author' );
    function uri_make( ){
        return so_query::make(array(
            'article' => $this->name,
            'by' => $this->author,
        ))->uri;
    }
    function uri_store( $data ){
        $query= so_uri::make( $data )->query;
        $this->name= $query[ 'article' ];
        $this->author= $query[ 'by' ];
    }
    
    var $name_value;
    var $name_depends= array( 'uri', 'name' );
    function name_make( ){
        return '...';
    }
    function name_store( $data ){
        if( !(string)$data )
            return null;
        return (string) $data;
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
    
    var $version_value;
    function version_make( ){
        return $this->storage->version;
    }
    
    var $gist_value;
    function gist_make( ){
        if( $this->version )
            return so_gist::make( $this->model[ '@so_article_gist' ] );
        
        return so_gist::makeInstance()->id( $this->uri )->author( $this->author )->primary();
    }
    
    var $model_value;
    var $model_depends= array();
    function model_make( ){
        if( $this->version )
            return so_dom::make( $this->storage->content );
        
        return so_dom::make( array(
            'so_article' => array(
                '@so_uri' => (string) $this->uri,
                '@so_article_name' => (string) $this->name,
                '@so_article_author' => (string) $this->author,
                '@so_article_gist' => (string) $this->gist,
            ),
        ) );
    }
    function model_store( $data ){
        $this->storage->content= (string) $data->doc;
        unset( $this->version );
        return $data;
    }
    
    function get( $data= null ){
        $output= ( $this->version || $this->gist->version ) ? so_output::ok() : so_output::missed();
        
        $output->content= array(
            '@so_page_uri' => (string) $this->uri,
            '@so_page_title' => (string) $this->name,
            $this->model,
            $this->gist->teaser,
        );
        
        return $output;
    }
    
    function delete( $data ){
        $this->gist->content= $data[ 'content' ] ?: "    /Article deleted/.\n";
        return so_output::ok( 'Article deleted' );
    }

    function move( $data ){
        $name= strtr( $data[ 'name' ], array( "\n" => '', "\r" => '' ) );
        $target= so_article::makeInstance()->name( $name )->author( $this->author )->primary();
        
        if( $target != $this ):
            $target->gist->put(array( 'content' => $this->gist->content ));
            $this->delete(array( 'content' => "    /Article moved to [new location\\{$target}]/.\n" ));
        endif;
        
        return so_output::ok()->content(array( 'so_relocation' => (string) $target ));
    }

}
