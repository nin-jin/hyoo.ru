<?php

class so_uri
{
    use so_meta2;
    use so_registry;
    
    var $id_prop= array(
        'depends' => array( 'id', 'scheme', 'login', 'password', 'host', 'port', 'path', 'queryString', 'query', 'anchor' ),
    );
    function id_make( ){
        $id= '';
        
        if( $this->scheme )
            $id.= urlencode( $this->scheme ) . ':';
        
        if( $this->host ):
            $id.= '//';
            if( $this->login ):
                $id.= urlencode( $this->login );
                if( $this->password )
                    $id.= ':' . urlencode( $this->password );
                $id.= '@';
            endif;
            $id.= urlencode( $this->host );
            if( $this->port )
                $id.= ':' . urlencode( $this->port );
        endif;
        
        if( $this->path )
            $id.= $this->path;
        
        $queryString= $this->queryString;
        if( $queryString )
            $id.= '?' . $queryString;
        
        if( $this->anchor )
            $id.= '#' . urlencode( $this->anchor );
        
        return $id;
    }
    function id_store( $id ){
        preg_match( '~^(?:(\w*):)?(?://(?:([^/:]*)(?:\:([^/]*))?@)?([^/:]*)(?:\:(\d*))?)?([^?#]*)(?:\?([^#]*))?(?:#(.*)|)?$~', $id, $found );
        
        $this->scheme= so_value::make( $found[ 1 ] );
        $this->login= so_value::make( $found[ 2 ] );
        $this->password= so_value::make( $found[ 3 ] );
        $this->host= so_value::make( $found[ 4 ] );
        $this->port= so_value::make( $found[ 5 ] );
        $this->path= so_value::make( $found[ 6 ] );
        $this->queryString= so_value::make( $found[ 7 ] );
        $this->anchor= so_value::make( $found[ 8 ] );
        
        return $this->id_make();
    }
    
    var $scheme_prop= array(
        'depends' => array( 'id', 'scheme' ),
    );
    function scheme_store( $data ){
        return (string) $data;
    }
    
    var $login_prop= array(
        'depends' => array( 'id', 'login' ),
    );
    function login_store( $data ){
        return (string) $data;
    }
    
    var $password_prop= array(
        'depends' => array( 'id', 'password' ),
    );
    function password_store( $data ){
        return (string) $data;
    }
    
    var $host_prop= array(
        'depends' => array( 'id', 'host' ),
    );
    function host_store( $data ){
        return (string) $data;
    }
    
    var $port_prop= array(
        'depends' => array( 'id', 'port' ),
    );
    function port_store( $data ){
        return (integer) $data;
    }
    
    var $path_prop= array(
        'depends' => array( 'id', 'path' ),
    );
    function path_make( ){
        if( $this->host )
            return '/';
        return '';
    }
    function path_store( $data ){
        return (string) $data;
    }
    
    var $queryString_prop= array(
        'depends' => array( 'id', 'query', 'queryString' ),
    );
    function queryString_make( ){
        if( isset( $this->query ) ):
            return (string) $this->query;
        endif;
        return '';
    }
    function queryString_store( $data ){
        return (string) $data;
    }
    
    var $query_prop= array(
        'depends' => array( 'id', 'query', 'queryString' ),
    );
    function query_make( ){
        return so_query::make( $this->queryString );
    }
    function query_store( $data ){
        return so_query::make( $data );
    }
    
    var $anchor_prop= array(
        'depends' => array( 'id', 'anchor' ),
    );
    function anchor_store( $data ){
        return (string) $data;
    }
    
    var $content_prop= array(
        'depends' => array( ),
    );
    function content_make( ){
        $curl= curl_init( $this->id );
        ob_start();
            curl_exec( $curl );
        $data= ob_get_clean();
        $info= curl_getinfo( $curl );
        curl_close( $curl );
        
        switch( preg_replace( '~;.*$~', '', $info[ 'content_type' ] ) ):
            case 'text/xml':
            case 'application/xml':
                return so_dom::make( $data );
            case 'text/json':
            case 'application/json':
                return json_decode( $data );
        endswitch;
        
        return $data;
    }
    function content_store( $data ){
        $curl= curl_init( $this->id );
        $file= tmpfile();
        fwrite( $file, $data );
        fseek( $file, 0 ); // ?
        curl_setopt( $curl, CURLOPT_PUT, true ); 
        curl_setopt( $curl, CURLOPT_INFILE, $file ); 
        curl_setopt( $curl, CURLOPT_INFILESIZE, strlen( $data ) );
        ob_start();
            curl_exec( $curl );
        ob_end_flush();
        fclose( $file );
        curl_close( $curl );
        return $data;
    }
    
    function __toString( ){
        return $this->id;
    }
    
}