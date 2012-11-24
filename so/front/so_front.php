<?php

class so_front
{
    use so_meta;
    use so_singleton {
        so_singleton::makeInstance as makeAdapter;
    }
    
    static function makeInstance( ){
        if( so_value::make( $_SERVER[ 'SESSIONNAME' ] ) == 'Console' )
            return so_front_console::makeAdapter();
        
        return so_front_http::makeAdapter();
    }
    
    var $dir_value;
    function dir_make( ){
        return so_file::make( getcwd() );
    }
    
    var $uri_value;
    var $method_value;
    var $data_value;

    function send( $response ){
        echo $response->content;
    }
    
}