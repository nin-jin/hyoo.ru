<?php

class so_phpinfo_resource
{
    use so_meta2;
    use so_registry;
    
    var $id_prop= array(
        'depends' => array( 'id', 'uri' ),
    );
    function id_make( ){
        return (string) $this->uri;
    }
    function id_store( $data ){
        $this->uri= $data;
        return $this->id_make();
    }
    
    var $uri_prop= array(
        'depends' => array( 'id', 'uri' ),
    );
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
        return so_output::ok()->mime( 'text/html' )->content( $html );
    }
    
}
