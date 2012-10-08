<?php

class so_uri
{
    use so_meta;
    
    use so_registry;
    static $id_prop= 'string';
    
    var $string_value;
    var $string_depends= array( 'string', 'scheme', 'login', 'password', 'host', 'port', 'path', 'queryString', 'query', 'anchor' );
    function string_make( ){
        $string= '';
        
        if( $this->scheme )
            $string.= urlencode( $this->scheme ) . ':';
        
        if( $this->host ):
            $string.= '//';
            if( $this->login ):
                $string.= urlencode( $this->login );
                if( $this->password )
                    $string.= ':' . urlencode( $this->password );
                $string.= '@';
            endif;
            $string.= urlencode( $this->host );
            if( $this->port )
                $string.= ':' . urlencode( $this->port );
        endif;
        
        if( $this->path )
            $string.= $this->path;
        
        $queryString= $this->queryString;
        if( $queryString )
            $string.= '?' . $queryString;
        
        if( $this->anchor )
            $string.= '#' . urlencode( $this->anchor );
        
        return $string;
    }
    function string_store( $string ){
        preg_match( '~^(?:(\w*):)?(?://(?:([^/:]*)(?:\:([^/]*))?@)?([^/:]*)(?:\:(\d*))?)?([^?#]*)(?:\?([^#]*))?(?:#(.*)|)?$~', $string, $found );
        
        $this->scheme= so_value::make( $found[ 1 ] );
        $this->login= so_value::make( $found[ 2 ] );
        $this->password= so_value::make( $found[ 3 ] );
        $this->host= so_value::make( $found[ 4 ] );
        $this->port= so_value::make( $found[ 5 ] );
        $this->path= so_value::make( $found[ 6 ] );
        $this->queryString= so_value::make( $found[ 7 ] );
        $this->anchor= so_value::make( $found[ 8 ] );
    }
    
    var $scheme_value;
    var $scheme_depends= array( 'string', 'scheme' );
    function scheme_store( $data ){
        return (string) $data;
    }
    
    var $login_value;
    var $login_depends= array( 'string', 'login' );
    function login_store( $data ){
        return (string) $data;
    }
    
    var $password_value;
    var $password_depends= array( 'string', 'password' );
    function password_store( $data ){
        return (string) $data;
    }
    
    var $host_value;
    var $host_depends= array( 'string', 'host' );
    function host_store( $data ){
        return (string) $data;
    }
    
    var $port_value;
    var $port_depends= array( 'string', 'port' );
    function port_store( $data ){
        return (integer) $data;
    }
    
    var $path_value;
    var $path_depends= array( 'string', 'path' );
    function path_make( ){
        if( $this->host )
            return '/';
        return '';
    }
    function path_store( $data ){
        return (string) $data;
    }
    
    var $queryString_value;
    var $queryString_depends= array( 'string', 'query', 'queryString' );
    function queryString_make( ){
        return (string) $this->query;
    }
    function queryString_store( $data ){
        $this->query= so_query::make( (string) $data );
    }
    
    var $query_value;
    var $query_depends= array( 'string', 'query', 'queryString' );
    function query_make( ){
        return so_query::make();
    }
    function query_store( $data ){
        return so_query::make( $data );
    }
    
    var $anchor_value;
    var $anchor_depends= array( 'string', 'anchor' );
    function anchor_store( $data ){
        return (string) $data;
    }
    
    var $content_value;
    var $content_depends= array();
    function content_make( ){
        $curl= curl_init( $this->string );
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
        $curl= curl_init( $this->string );
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
    
    function _string_meta( ){
        return $this->string;
    }
    
}