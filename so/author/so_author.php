<?php

class so_author
{
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
        return so_user::make()->author->name;
    }
    function name_store( $data ){
        $data= (string) $data;
        
        if( !$data )
            return null;
        
        return $data;
    }
    
    var $storage_value;
    function storage_make( ){
        return so_storage::make( $this->uri );
    }
    
    var $version_value;
    function version_make( ){
        return $this->storage->version;
    }
    
    var $about_value;
    function about_make( ){
        if( $this->version )
            return so_gist::make( $this->model[ '@so_author_about' ] );
        
        return so_gist::makeInstance()->id( $this->uri )->author( $this )->primary();
    }
    
    var $key_value;
    function key_make( ){
        if( !$this->version )
            return '';
        
        return (string) $this->model[ '@so_author_key' ];
    }
    function key_store( $data ){
        $model= $this->model;
        
        $model[ '@so_author_key' ]= (string) $data;
        
        $this->model= $model;
    }
    
    var $model_value;
    var $model_depends= array();
    function model_make( ){
        if( $this->version )
            return so_dom::make( $this->storage->content );
        
        return so_dom::make( array(
            'so_author' => array(
                '@so_uri' => (string) $this->uri,
                '@so_author_name' => (string) $this->name,
                '@so_author_about' => (string) $this->about,
            ),
        ) );
    }
    function model_store( $data ){
        $this->storage->content= $data->doc;
        unset( $this->version );
        unset( $this->key );
        return $data;
    }
    
    function get_resource( $data= null ){
        $output= $this->version ? so_output::ok() : so_output::missed();
        
        $output->content= array(
            '@so_page_uri' => (string) $this->uri,
            '@so_page_title' => (string) $this->name,
            $this->model,
            $this->about->teaser,
        );
        
        return $output;
    }
    
    #function move_resource( $data ){
    #    $name= $data[ 'name' ];
    #    $target= so_author::makeInstance()->name( $name )->primary();
    #    
    #    if( $target != $this ):
    #        $target->about->put(array( 'content' => $this->about->content ));
    #        $this->delete(array( 'content' => "    /Author moved to [new location\\{$target}]/.\n" ));
    #    endif;
    #    
    #    return so_output::ok()->content(array( 'so_relocation' => (string) $target ));
    #}
    #
    function put_resource( $data ){
        $author= $this->version ? $user->author : $this;
        so_user::make()->author= $author;
        
        return so_output::ok()->content(array( 'so_relocation' => (string) $author ));
    }

}
