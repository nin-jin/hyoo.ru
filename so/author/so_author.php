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
        return 'Nin Jin'; //so_user::make()->id;
    }
    function name_store( $data ){
        if( !(string)$data )
            return null;
        return (string) $data;
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
            return so_gist::make( $this->model[ '@so_author_gist' ] );
        
        return so_gist::makeInstance()->id( $this->uri )->author( $this->name )->primary();
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
                '@so_author_gist' => (string) $this->gist,
            ),
        ) );
    }
    function model_store( $data ){
        $this->storage->content= $data->doc;
        unset( $this->version );
        return $data;
    }
    
    function get( $data= null ){
        $output= $this->version ? so_output::ok() : so_output::missed();
        
        $output->content= array(
            '@so_page_uri' => (string) $this->uri,
            '@so_page_title' => (string) $this->name,
            $this->model,
            $this->gist->teaser,
        );
        
        return $output;
    }
    
    function delete( $data ){
        $this->gist->content= $data[ 'content' ] ?: "    /Author deleted/.\n";
        return so_output::ok( 'Author deleted' );
    }

    function move( $data ){
        $name= $data[ 'name' ];
        $target= so_author::makeInstance()->name( $name )->primary();
        
        if( $target != $this ):
            $target->gist->put(array( 'content' => $this->gist->content ));
            $this->delete(array( 'content' => "    /Author moved to [new location\\{$target}]/.\n" ));
        endif;
        
        return so_output::ok()->content(array( 'so_relocation' => (string) $target ));
    }

}
