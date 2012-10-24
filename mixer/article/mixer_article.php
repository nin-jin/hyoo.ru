<?php

class mixer_article
{
    use so_resource;
    
    var $uri_value;
    var $uri_depends= array( 'uri', 'name', 'author' );
    function uri_make( ){
        return so_query::make(array(
            'article' => $this->name,
            'author' => (string) $this->author->name,
        ))->uri;
    }
    function uri_store( $data ){
        $query= so_uri::make( $data )->query;
        $this->name= $query[ 'article' ];
        $author= mixer_author::makeInstance()->name( $query[ 'author' ] )->primary();
        $this->author= $author;
    }
    
    var $name_value;
    var $name_depends= array( 'uri', 'name' );
    function name_make( ){
        return so_crypt::generateId();
    }
    function name_store( $data ){
        if( !(string)$data )
            return null;
        return (string) $data;
    }
    
    var $author_value;
    var $author_depends= array( 'uri', 'author' );
    function author_make( ){
        return mixer_author::make();
    }
    function author_store( $data ){
        return mixer_author::make( $data );
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
            return mixer_gist::make( $this->model[ '@mixer_article_gist' ] );
        
        return mixer_gist::makeInstance()->id( $this->uri )->author( $this->author )->primary();
    }
    
    var $model_value;
    var $model_depends= array();
    function model_make( ){
        if( $this->version )
            return so_dom::make( $this->storage->content );
        
        return so_dom::make( array(
            'mixer_article' => array(
                '@so_uri' => (string) $this->uri,
                '@mixer_article_name' => (string) $this->name,
                '@mixer_article_author' => (string) $this->author,
                '@mixer_article_gist' => (string) $this->gist,
            ),
        ) );
    }
    function model_store( $data ){
        $this->storage->content= $data->doc;
        unset( $this->version );
        return $data;
    }
    
    function get_resource( $data= null ){
        $output= ( $this->version || $this->gist->version ) ? so_output::ok() : so_output::missed();
        
        $output->content= array(
            '@so_page_uri' => (string) $this->uri,
            '@so_page_title' => (string) $this->name,
            $this->model,
            $this->gist->teaser,
            $this->author->model,
        );
        
        return $output;
    }
    
    #function delete_resource( $data ){
    #    $this->gist->content= $data[ 'content' ] ?: "    /Article deleted/.\n";
    #    return so_output::ok( 'Article deleted' );
    #}

    function move_resource( $data ){
        $name= strtr( $data[ 'name' ], array( "\n" => '', "\r" => '' ) );
        $force= (boolean) $data[ 'force' ];
        
        $target= mixer_article::makeInstance()->name( $name )->primary();
        
        if( $target !== $this ):
            if( $target->gist->version && !$force ):
                return so_output::conflict( 'Article already exists' );
            endif;
            
            $target->gist->post_resource(array( 'content' => $this->gist->content ));
            if( $this->gist->version && $target->author === $this->author )
                $this->gist->post_resource(array( 'content' => "    /Article moved to [new location\\{$target}]/.\n" ));
        endif;
        
        return so_output::ok()->content(array( 'so_relocation' => (string) $target ));
    }

}
