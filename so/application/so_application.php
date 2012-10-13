<?php

class so_application
{
    use so_meta;
    use so_singleton;
    
    static $response;
    
    static function start( $package, $mode= 'dev' ){
        register_shutdown_function(array( get_class(), 'finalize' ));
        
        ob_start();
        
        so_error::monitor();
        
        foreach( parse_ini_file( so_file::make( 'php.ini' ) ) as $key => $value )
            ini_set( $key, $value );
        
        so_page::$mode= $mode;
        
        $front= so_front::make()->package( $package );
        
        $query= $front->uri->query;
        $resource= $query->resource;
        
        $uriStandard= $resource->uri;
        if( $query->uri != $uriStandard )
            return static::$response= so_output::moved( $uriStandard );
        
        static::$response= $resource->execute( $front->method, $front->data );
    }
    
    static function finalize( ){
        $error= trim( ob_get_clean(), " \r\n" );
        
        if( $error )
            static::$response= so_output::error( $error );
        
        so_front::make()->send( static::$response );
    }
    
}