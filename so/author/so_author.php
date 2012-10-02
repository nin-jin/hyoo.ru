<?php

class so_author
{
    use so_meta2;
    use so_resource;
    
    var $uri_value;
    var $uri_depends= array( 'uri', 'name' );
    function uri_make( ){
        return so_query::make(array(
            'author' => $this->name,
        ))->uri;
    }
    function uri_store( $data ){
        $query= so_uri::make( $data )->query;
        $this->name= $query[ 'author' ];
    }
    
    var $name_value;
    var $name_depends= array( 'uri', 'name' );
    function name_make( ){
        return so_user::make()->id;
    }
    function name_store( $data ){
        if( !$data ) return null;
        return (string) $data;
    }
    
    var $storage_value;
    function storage_make( ){
        return so_storage::make( $this->uri );
    }
    
    var $gistAbout_value;
    function gistAbout_make(){
        return so_gist::makeInstance()->name( '' )->author( $this->name )->primary();
    }
    
    var $model_value;
    var $model_depends= array();
    function model_make( ){
        $storage= $this->storage;
        
        if( $storage->version )
            return so_dom::make( $storage->content );
        
        return so_dom::make( array(
            'so_author' => array(
                '@so_uri' => (string) $this->uri,
                '@so_author_name' => (string) $this->name,
                '@so_author_catch' => true,
                '@so_author_about' => (string) $this->gistAbout->uri,
            ),
        ) );
    }
    function model_store( $data ){
        so_dom::ensure( $data );
        $this->storage->content= (string) $data;
        return $data;
    }
    
    function get( $data= null ){
        $output= $this->storage->version ? so_output::ok() : so_output::missed();
        $output->content= array(
            '@so_page_uri' => (string) $this->uri,
            '@so_page_title' => (string) $this->name,
            $this->model,
            $this->gistAbout->teaser,
        );
        
        return $output;
    }
    
    function move( $data ){
        $name= strtr( $data[ 'name' ], array( "\n" => '', "\r" => '' ) );
        $author= so_author::makeInstance()->name( $name )->primary();
        
        if( $author != $this ):
            $author->gistAbout->content= $this->gistAbout->content;
            $this->gistAbout->content= "    /Author renamed to [{$author->name}\\{$author->uri}]/.\n";
        endif;
        
        return so_output::ok()->content(array( 'so_relocation' => (string) $author->uri ));
    }

}
