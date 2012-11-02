<?php

trait so_gist
{
    use so_resource;
    
    var $title_value;
    function title_make( ){
        return $this->uri;
    }
    
    var $storage_value;
    function storage_make( ){
        return so_storage::make( $this->uri );
    }
    
    var $version_value;
    function version_make( ){
        return $this->storage->version;
    }
    
    var $listList_value;
    function listList_make( ){
        return array();
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
        
        if( $data ):
            foreach( $this->listList as $list )
                $list->append( $this );
        else:
            foreach( $this->listList as $list )
                $list->drop( $this );
        endif;
    }
    
    var $model_value;
    var $model_depends= array();
    function model_make( ){
        if( !$this->version )
            return $this->modelBase;
        
        $model= so_dom::make( (string) $this->storage->content );
        $model[]= $this->modelBase->attrs;
        
        if( $this->storage->content != $model )
            $this->model= $model;
        
        return $model;
    }
    function model_store( $data ){
        $doc= so_dom::make();
        $doc[]= $data;
        $this->storage->content= $doc;
        unset( $this->version );
        unset( $this->exists );
        unset( $this->teaser );
        unset( $this->link );
        return $data;
    }
    
    var $teaser_value;
    function teaser_make( ){
        return $this->model;
    }
    
    var $link_value;
    function link_make( ){
        if( !$this->version )
            return $this->model;
        
        return so_dom::make(array(
            'so_gist' => array(
                '@so_gist_title' => (string) $this->title,
                '@so_gist_uri' => (string) $this->uri,
                '@so_uri_external' => (string) $this->storage->uri,
            ),
        ));
    }
    
}
