<?php

class so_phpinfo
{
    use so_resource;
    
    function uri_make( ){
        return so_query::make(array( 'phpinfo' ))->uri;
    }
    function uri_store( $data ){
        return null;
    }    
    
    function get_resource( ){
        ob_start();
            phpinfo();
        $html= ob_get_clean();
        $doc= new \DOMDocument( '1.0', 'utf-8' );
        $doc->loadHTML( $html . '<style> .v{ background: #eee } a:link { background: none } </style>' );
        $dom= so_dom::make( $doc )->select( '/*/*' );
        return so_output::ok()->content(array( 'html' => $dom ));
    }
    
}
