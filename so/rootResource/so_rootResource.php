<?php

class so_rootResource
{
    use so_resource;
    
    var $uri_value;
    function uri_make( ){
        return so_author::make()->uri;
    }
    function uri_store( $data ){
        if( !(string)$data )
            return null;
        return $data;
    }
    
    function get( $data ){
        if( $this->uri )
            return so_output::missed( "Missed handler for [{$this->uri}]" );
        
        return so_output::moved( '?author' );
    }
    
}
