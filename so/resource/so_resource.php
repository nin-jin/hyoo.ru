<?php

trait so_resource
{
    use so_meta {
        so_resource::_string_meta insteadof so_meta;
    }
    use so_registry;
    static $id_prop= 'uri';
    
    var $uri_value;
    function uri_make( ){
        throw new \Exception( "Property [uri] is not defined" );
    }
    function uri_store( $data ){
        return so_uri::make( $data );
    }
    
    #var $subscribers_value;
    #function subscribers_make( ){
    #    return so _subscriber_list::makeInstance()->subject( $this->uri );
    #}
    
    function execute( $method, $data= null ){
        return $this->{ $method . '_resource' }( $data );
    }
    
    function _string_meta( $prefix= '' ){
        return $this->uri;
    }
}