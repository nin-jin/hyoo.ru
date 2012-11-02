<?php

class so_rootResource
{
    use so_resource;
    
    function uri_make( ){
        return so_uri::make( '?phpinfo' );
    }
    function uri_store( $data ){
        if( !(string)$data )
            return null;
        return so_uri::make( $data );
    }
    
    function get_resource( $data ){
        return so_output::missed( "Missed handler for [{$this->uri}]" );
    }
    
}
