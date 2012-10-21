<?php

class so_uri_compatible
extends so_uri
{

    var $queryString_value;
    var $queryString_depends= array( 'string', 'query', 'queryString' );
    function queryString_make( ){
        return (string) $this->query;
    }
    function queryString_store( $data ){
        $this->query= so_query_compatible::make( (string) $data );
    }
    
    var $query_value;
    var $query_depends= array( 'string', 'query', 'queryString' );
    function query_make( ){
        return so_query_compatible::make();
    }
    function query_store( $data ){
        return so_query_compatible::make( $data );
    }
    
}