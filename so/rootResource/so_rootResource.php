<?php

class so_rootResource
{
    use so_resource;
    
    var $uri_value;
    function uri_store( $data ){
        return so_uri::make( $data );
    }
    
    function get_resource( $data ){
        return so_output::missed( "Missed handler for [{$this->uri}]" );
    }
    
}
