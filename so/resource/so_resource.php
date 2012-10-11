<?php

trait so_resource
{
    use so_meta {
        so_resource::_string_meta insteadof so_meta;
    }
    use so_registry;
    static $id_prop= 'uri';
    
    function execute( $method, $data= null ){
        return $this->{ $method }( $data );
    }
    
    function _string_meta( $prefix= '' ){
        return $this->uri;
    }
}