<?php

class so_gist_list extends so_meta {
    
    static function create( $request ){
        $obj= new self;
        $obj->request= $request;
        return $obj;
    }
    
    protected $_request;
    function set_request( $request ){
        if( isset( $this->request ) ) throw new Exception( 'Redeclaration of $request' );
        return $request;
    }
    
    protected $_response;
    function get_response( $response ){
        if( isset( $response ) ) return $response;
        return so_xmlResponse::create();
    }
    
    protected $_uri;
    function get_uri( $uri ){
        if( isset( $uri ) ) return $uri;
        return '?so/list';
    }
    
    function get( ){
        $response= array(
            'so:gist_Creator' => array(
                'so:gist_name' => (string)$this->request['gist']->value,
            )
        );
        
        $root= new so_WC_Root;
        $files= $root->createPack( 'so' )->createModule( 'content' )->selectFiles( '/\\.gist\\.xml$/' );
        foreach( $files as $file ):
            $name= preg_replace( '/\\.gist\\.xml$/', '', $file->name );
            $response[]= array(
                'a' => array(
                    '@href' => '?so/gist=' . $name,
                    $name,
                )
            );
        endforeach;
        
        return so_xmlResponse::ok( $response );
    }
    
}
