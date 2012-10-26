<?php

trait so_gist
{
    use so_resource;
    
    var $storage_value;
    function storage_make( ){
        return so_storage::make( $this->uri );
    }
    
    var $version_value;
    function version_make( ){
        return $this->storage->version;
    }
    
    var $exists_value;
    var $exists_depends= array();
    function exists_make( ){
        return $this->model[ '@so_gist_exists' ] == 'true';
    }
    function exists_store( $data ){
        $model= $this->model;
        $model[ '@so_gist_exists' ]= $data ? 'true' : 'false';
        $this->model= $model;
    }
    
    var $model_value;
    var $model_depends= array();
    function model_make( ){
        if( $this->version )
            return so_dom::make( $this->storage->content );
        
        return $this->modelBase;
    }
    function model_store( $data ){
        $doc= so_dom::make();
        $doc[]= $data;
        $this->storage->content= $doc;
        unset( $this->version );
        unset( $this->exists );
        unset( $this->teaser );
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
    
}
