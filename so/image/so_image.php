<?php

class so_image
{
    use so_resource;
    
    var $uri_value;
    var $uri_depends= array( 'uri', 'id' );
    function uri_make( ){
        return so_query::make(array(
            'image' => $this->id,
        ))->uri;
    }
    function uri_store( $data ){
        $query= so_uri::make( $data )->query;
        $this->id= $query[ 'image' ];
    }
    
    var $id_value;
    var $id_depends= array( 'uri', 'id' );
    function id_make( ){
        return so_crypt::generateId();
    }
    function id_store( $data ){
        if( !$data ) return null;
        return (string) $data;
    }
    
    var $storage_value;
    function storage_make( ){
        return so_storage::make( $this->uri );
    }
    
    var $model_value;
    function model_make( ){
        $storage= $this->storage;
        
        return so_dom::make( array(
            'so_image' => array(
                '@so_uri' => (string) $this->uri,
                '@so_image_link' => (string) $storage->uri,
            ),
        ) );
    }
    
    function get( $data= null ){
        $output= so_output::ok();
        
        $output->content= array(
            '@so_page_uri' => (string) $this->uri,
            '@so_page_title' => (string) $this->id,
            $this->model,
        );
        
        return $output;
    }
    
    function put( $data ){
        $this->storage->content= base64_decode( preg_replace( '~^.*?,~', '', $data[ 'content' ] ) );
        $image= new Imagick( (string) $this->storage->file );
        $size= $image->getImageGeometry();
        if( $size[ 'width' ] > 800 or $size[ 'height' ] > 600 ):
            $image->adaptiveResizeImage( 800, 600, true );
            $image->writeImage( (string) $this->storage->file );
        endif;
        return so_output::ok()->content( $this->model );
    }

    function post( $data ){
    }

}
