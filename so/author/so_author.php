<?php

class so_author
extends so_resource
{
    
    protected $responseClass= so_xmlResponse;
    
    protected $_storage;
    function get_storage( $storage ){
        if( isset( $storage ) ) return $storage;
        return so_storage::make( $this->uri );
    }
    
    protected $_uri;
    function get_uri( $uri ){
        if( isset( $uri ) ) return $uri;
        return so_uri::makeInner( array(
            'author' => $this->name,
        ) );
    }
    
    protected $_name;
    function get_name( $name ){
        if( isset( $name ) ) return $name;
        return $this->request[ 'author' ]->value;
    }
    function set_name( $name ){
        if( isset( $this->name ) ) throw new Exception( 'Property $name already defined' );
        return $name;
    }
    
    protected $_contentDefault;
    function get_contentDefault( $contentDefault ){
        if( isset( $contentDefault ) ) return $contentDefault;
        
        return so_dom::make( array( 'so:author' => array(
            '@xmlns:so' => 'https://github.com/nin-jin/so',
            'so:author_uri' => $this->uri,
            'so:author_name' => $this->name,
            'so:author_about' => so_gist::make()->name( 'about' )->author( $this->name )->dom,
        ) ) );
    }
    
    protected $_dom;
    function get_dom( $dom ){
        if( isset( $dom ) ) return $dom;
        
        $dom= $this->storage->version ? so_dom::make( $this->storage->content ) : $this->contentDefault;
        
        return $dom;
    }
    function set_dom( $dom ){
        $this->storage->content= $dom;
        return $dom;
    }
    
    function get( ){
        $page= array(
            'so:page_title' => $this->name,
            $this->dom,
        );
        
        if( $this->storage->version ) $this->response->ok( $page );
        else $this->response->missed( $page );
        
        return $this;
    }
    
    function delete( ){
        $this->dom= $this->contentDefault;
        $this->get();
        return $this;
    }

    function put( ){
        
        $dom= $this->dom;
        //$dom[ 'so:gist_content' ]= $this->request[ 'content' ]->value;
        $this->dom= $dom;
        
        $this->get();
        
        return $this;
    }

}
