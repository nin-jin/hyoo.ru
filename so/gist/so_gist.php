<?php

class so_gist
{
    use so_resource;
    
    var $uri_value;
    var $uri_depends= array( 'uri', 'id', 'author' );
    function uri_make( ){
        return so_query::make(array(
            'gist',
            'author' => $this->author,
            'id' => $this->id,
        ))->uri;
    }
    function uri_store( $data ){
        $query= so_uri::make( $data )->query;
        $this->author= $query[ 'author' ];
        $this->id= $query[ 'id' ];
    }
    
    var $id_value;
    var $id_depends= array( 'uri', 'id' );
    function id_store( $data ){
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
    
    var $content_value;
    var $content_depends= array();
    function content_make( ){
        if( $this->version )
            return $this->model[ '@so_gist_content' ];
        
        return '    ...';
    }
    function content_store( $data ){
        $data= (string) $data;
        $model= $this->model;
        $model[ '@so_gist_content' ]= $data;
        $this->model= $model;
        return $data;
    }
    
    var $model_value;
    var $model_depends= array();
    function model_make( ){
        if( $this->version )
            return so_dom::make( $this->storage->content );
        
        return so_dom::make( array(
            'so_gist' => array(
                '@so_uri' => (string) $this->uri,
                '@so_gist_id' => (string) $this->id,
                '@so_gist_author' => (string) $this->author,
                '@so_gist_content' => (string) $this->content,
            ),
        ) );
    }
    function model_store( $data ){
        $this->storage->content= $data->doc;
        unset( $this->version );
        return $data;
    }
    
    var $teaser_value;
    function teaser_make( ){
        if( !$this->version )
            return $this->model;
        
        return so_dom::make(array(
            'so_gist/@so_uri_external' => (string) $this->storage->uri,
        ));
    }
    
    function delete( $data ){
        $this->content= $data[ 'content' ] ?: "    /Content deleted/.\n";
        return so_output::ok( 'Content deleted' );
    }

    function put( $data ){
        $this->content= (string) $data[ 'content' ];
        return so_output::ok( 'Content updated' );
    }

}
