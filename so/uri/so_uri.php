<?php

class so_uri
extends so_meta
{

    function makeInner( $params ){
        foreach( $params as $key => $value ):
            if( isset( $value ) ) continue;
            unset( $params[ $key ] );
        endforeach;
        
        $query= http_build_query
        (   $params
        ,   null
        ,   '/'
        );
        $uri= '?' . preg_replace( '~=(?=/|$)~', '', $query );
        
        return $uri;
    }

}