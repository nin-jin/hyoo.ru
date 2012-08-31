<?php

class so
extends so_resource
{
    
    protected $responseClass= so_xmlResponse;
    
    var $uri;
    
    function run( ){
        if( count( $this->request ) > 1 ):
            return $this->response->missed(array( 'so:Error' => 'Missed handler ' ));
        else:
            return $this->response->moved( '?gist' );
        endif;
    }
    
}
