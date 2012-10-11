<?php

class so_front_console
extends so_front
{

    var $uri_value;
    function uri_make( ){
        $uri= so_value::make( $_SERVER[ 'argv' ][ 1 ] );
        return so_uri::make( $uri );
    }

    var $method_value;
    function method_make( ){
        $method= so_value::make( $_SERVER[ 'argv' ][ 2 ] );
        return strtolower( $method ?: 'get' );
    }

    var $data_value;
    function data_make( ){
        return null;
    }
    
}