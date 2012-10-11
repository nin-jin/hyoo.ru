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
    
    static function start( $package ){
        return static::make()->package( $package )->run();
    }
    
    var $dir_value;
    function dir_make( ){
        return so_file::make( getcwd() );
    }
    
    var $result_value;
    var $result_depends= array();
    function result_make( ){
        return so_output::error( 'Response is empty' );
    }
    function result_store( $data ){
        return $data;
    }
    
    var $package_value;
    function package_make( ){
        return so_package::make( 'so' );
    }
    function package_store( $data ){
        return so_package::make( $data );
    }
    
    function finalize( ){
        $error= trim( ob_get_clean(), " \r\n" );
        
        if( $error )
            $this->result= so_output::error( $error );
        
        $this->send();
    }
    
    function run( ){
        register_shutdown_function(array( $this, 'finalize' ));
        
        ob_start();
        
        so_error::monitor();
        
        foreach( parse_ini_file( so_file::make( 'php.ini' ) ) as $key => $value )
            ini_set( $key, $value );
        
        $query= $this->uri->query;
        $resource= $query->resource;
        
        $uriStandard= $resource->uri;
        if( $query->uri != $uriStandard )
            return $this->result= so_output::moved( $uriStandard );
        
        return $this->result= $resource->execute( $this->method, $this->data );
    }
    
    function send( ){
        echo $this->result->content;
    }
    
}