<?php

class so_phpinfo
extends so_resource
{
    
    protected $responseClass= so_htmlResponse;
    
    protected $_uri;
    function get_uri( $uri ){
        if( isset( $uri ) ) return $uri;
        $uri= '?phpinfo';
        return $uri;
    }
    
    function get( ){
        ob_start();
        phpinfo();
        $html= ob_get_clean();
        $this->response->ok( $html );
        return $this;
    }
    
}
