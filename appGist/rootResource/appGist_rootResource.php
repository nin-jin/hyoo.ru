<?php

class appGist_rootResource
extends so_rootResource
{

    var $uri_value;
    function uri_make( ){
        return so_author::make()->uri;
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