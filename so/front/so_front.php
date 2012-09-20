<?php

class so_front
{
    use so_meta2;
    use so_factory;
    
    var $client_value;
    function client_make( ){
        throw new Exception( 'Default client is not implement yet' );
    }
    function client_store( $data ){
        return $data;
    }
    
    function finalize( ){
        $client= $this->client;
        
        $error= trim( ob_get_clean(), " \r\n" );
        
        if( $error )
            $client->output( so_output::error( $error ) );
        
        $client->send();
    }
    
    function run( ){
        register_shutdown_function(array( $this, 'finalize' ));
        
        ob_start();
        
        $client= $this->client;
        $query= $client->query;
        $resource= $query->resource;
        
        $url= $resource->id;
        if( $query->uri->id != $url )
            return $client->output= so_output::moved( $url );
        
        return $client->output= $resource->{ $client->method }( $client->input );
    }
    
}
