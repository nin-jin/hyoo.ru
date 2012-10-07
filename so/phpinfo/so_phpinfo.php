<?php

class so_phpinfo
{
    use so_resource;
    
    var $uri_value;
    function uri_make( ){
        return so_query::make(array( 'phpinfo' ))->uri;
    }
    function uri_store( $data ){
        return so_uri::make( $data );
    }    
    
    function get( ){
        ob_start();
            phpinfo();
        $html= ob_get_clean();
        $doc= new DOMDocument( '1.0', 'utf-8' );
        $doc->loadHTML( $html );
        $dom= so_dom::make( $doc )->select( '/*/*' );
        return so_output::ok()->content(array( 'html' => $dom ));
    }
    
}
