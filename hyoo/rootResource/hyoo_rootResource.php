<?php

class hyoo_rootResource
extends so_rootResource
{

    function uri_make( ){
        return so_uri::make( '?article;list' );
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