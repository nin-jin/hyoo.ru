<?php

class so_resource
extends so_meta
{

    protected $_request;
    function set_request( $request ){
        if( isset( $this->request ) ) throw new Exception( 'Redeclaration of $request' );
        return so_dom::make( $request );
    }
    
    protected $_response;
    function get_response( $response ){
        if( isset( $response ) ) return $response;
        $class= $this->responseClass;
        return $class::make();
    }
    
    function run( ){
        $method= $this->request->name;
        if( $method == 'get' && $this->request[ '@query' ]->value != $this->uri ):
            return $this->response->moved( $this->uri );
        endif;
        $this->{ $method }();
        return $this->response;
    }
    
    function error( $error ){
        return $this->response->error( $error );
    }
    
}
