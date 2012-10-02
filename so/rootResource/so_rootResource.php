<?php

class so_rootResource
{
    use so_meta2;
    use so_resource;
    
    var $uri_value;
    function uri_make( ){
        return '';
    }
    function uri_store( $data ){
        if( !(string)$data )
            $data= so_query::make(array( 'author' ))->resource->uri;
        
        return $data;
    }
    
    function get( $data ){
        if( $this->uri )
            return so_output::missed( "Missed handler for [{$this->uri}]" );
        
        return so_output::moved( '?author' );
    }
    
}
