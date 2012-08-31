<?php

class so_gist
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
        $uri= '?gist';
        if( $this->name ) $uri.= '=' . urlencode( $this->name );
        return $uri;
    }
    
    protected $_name;
    function get_name( $name ){
        if( isset( $name ) ) return $name;
        return $this->request[ 'gist' ]->value;
    }
    function set_name( $name ){
        if( isset( $this->name ) ) throw new Exception( 'Property $name already defined' );
        return $name;
    }
    
    protected $_title;
    function get_title( $title ){
        if( isset( $title ) ) return $title;
        return $this->name ?: 'Gist!';
    }
    
    protected $_contentDefault;
    function get_contentDefault( $contentDefault ){
        if( isset( $contentDefault ) ) return $contentDefault;
        
        return so_dom::make( array( 'so:gist' => array(
            '@xmlns:so' => 'https://github.com/nin-jin/so',
            'so:gist_uri' => $this->uri,
            'so:gist_name' => $this->name,
            'so:gist_content' => '!!! ' . $this->title . "\n    ...\n",
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
            'so:page_title' => $this->title,
            //'so:path' => array(
            //    $this->name ?
            //    array( 'so:path_item' => array(
            //        'so:path_title' => $this->author,
            //        'so:path_link' => so_gist::make()->author( $this->author )->uri,
            //    ) )
            //    : null,
            //),
            'so:page_aside' => array(
                123
                //'html:include' => array(
                //    '@href' => 'so/content/navigation.gist.xml',
                //),
            ),
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
        $dom[ 'so:gist_content' ]= $this->request[ 'content' ]->value;
        $this->dom= $dom;
        
        $this->get();
        
        return $this;
    }

}
