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
    
    var $fileOrinal_value;
    function fileOrinal_make( ){
        return $this->storage->dir[ 'so_image_original.jpeg' ];
    }
    
    var $fileMaximal_value;
    function fileMaximal_make( ){
        return $this->storage->dir[ 'so_image_maximal.jpeg' ];
    }
    
    var $model_value;
    function model_make( ){
        return so_dom::make( array(
            'so_image' => array(
                '@so_uri' => (string) $this->uri,
                '@so_image_original' => (string) $this->fileOrinal->uri,
                '@so_image_maximal' => (string) $this->fileMaximal->uri,
            ),
        ) );
    }
    
    function get_resource( $data= null ){
        $output= so_output::ok();
        
        $output->content= array(
            '@so_page_uri' => (string) $this->uri,
            '@so_page_title' => (string) $this->id,
            $this->model,
        );
        
        return $output;
    }
    
    function post_resource( $data ){
        $this->storage->dir->exists= true;
        
        $image= new Imagick( (string) $data[ 'file' ] );
        $image->writeImage( (string) $this->fileOrinal );
        
        $size= $image->getImageGeometry();
        
        if( $size[ 'width' ] > 800 or $size[ 'height' ] > 600 )
            $image->adaptiveResizeImage( 800, 600, true );
        $image->writeImage( (string) $this->fileMaximal );
        
        return so_output::ok()->content( $this->model );
    }

}
