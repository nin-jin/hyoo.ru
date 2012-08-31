<?php

class so_front
extends so_meta
{
    
    static $httpCodes= array(
        'ok' => 200,
        'found' => 302,
        'moved' => 301,
        'missed' => 404,
        'error' => 500,
    );
    
    protected $_namespace;
    function get_namespace( $namespace ){
        if( isset( $namespace ) ) return $namespace;
        return array();
    }
    function set_namespace( $namespace ){
        if( isset( $this->namespace ) ) throw new Exception( 'Redeclaration of $namespace' );
        if( is_string( $namespace ) ) $namespace= explode( '/', $namespace );
        return (array)$namespace;
    }
    
    protected $_request;
    function get_request( $request ){
        if( isset( $request ) ) return $request;
        
        $method= strtolower( $_SERVER[ 'REQUEST_METHOD' ] );
        $type= strtolower( $_SERVER[ 'CONTENT_TYPE' ] );
        $query= $_SERVER[ 'QUERY_STRING' ];
        
        if(( $method === 'put' )and( strpos( $type, 'application/x-www-form-urlencoded' ) === 0 )):
            $raw= '';
            $input= fopen( 'php://input', 'r' );
            while( $chunk= fread( $input, 1024 ) ) $raw.= $chunk;
            fclose( $input );
            $query.= '/' . $raw;
        endif;
        
        $data= array( '@query' => '?' . $query );
        
        $chunkList= preg_split( '![&/]!', $query );
        foreach( $chunkList as $chunk ):
            if( !$chunk ) continue;
            list( $key, $val )= explode( '=', $chunk, 2 );
            if( !preg_match( '!^\w+$!', $key ) ) continue;
            $data[ $key ]= urldecode( $val ) ?: null;
        endforeach;
        
        $data+= $_POST;
        
        foreach( $data as $key => $value ):
            $keyList= explode( '_', $key );
            $current= &$data;
            while( count( $keyList ) > 1 ):
                $current= &$current[ array_shift( $keyList ) ];
            endwhile;
            $current[ $keyList[ 0 ] ]= $value;
        endforeach;
        
        return so_dom::make(array( $method => $data ));
    }
    function set_request( $request ){
        if( isset( $this->request ) ) throw new Exception( 'Redeclaration of $request' );
        return $request;
    }

    protected $_resource;
    function get_resource( $resource ){
        if( isset( $resource ) ) return $resource;
        
        $keyList= $this->namespace;
        foreach( $this->request as $key => $value ):
            if( $key[0] == '@' ) continue;
            $keyList[]= $key;
        endforeach;
        
        while( $keyList ):
            $Class= implode( '_', $keyList );
            if( class_exists( $Class ) ) break;
            array_pop( $keyList );
        endwhile;
        
        $resource= $Class::make()->request( $this->request );
        return $resource;
    }
    
    function run( ){
        header( "Content-Type: text/html,charset=utf-8", true, 500 );
        
        $front= $this;
        register_shutdown_function( function( ) use( $front ) {
            $error= trim( ob_get_clean(), " \r\n");
            if( !$error ) return;
            $response= so_xmlResponse::make()->error( $error ) ;
            $front->send( $response );
        });
        
        $html_errors= ini_get( 'html_errors' );
        ini_set( 'html_errors', 0 );
            ob_start();
                $uri= $this->resource->uri;
                try {
                    $response= $this->resource->run();
                } catch( Exception $error ){
                    $response= $this->resource->response->error( $error );
                }
            $error= ob_get_clean();
        ini_set( 'html_errors', $html_errors );
        
        if( $error !== '' ):
            $response= $this->resource->error( $error );
        endif;
        
        if( !$response ):
            $response= $this->resource->error( 'Response is empty' );
        endif;
        
        $this->send( $response );
        
        return $this;
    }
    
    function send( $response ){
        $type= $response->type;
        $content= $response->content;
        
        if( $type === 'application/xml' ):
            if( !preg_match( '!\\b(Presto|Gecko|AppleWebKit|Trident)\\b!', $_SERVER['HTTP_USER_AGENT'] ) ):
                $type= 'text/html';
                $xs= new so_XStyle;
                $xs->pathXSL= 'so/-mix/index.xsl';
                foreach( $xs->docXSL as $key => $dom ):
                    if( $key != 'xsl:include' ) continue;
                    $dom['@href']= preg_replace( '!\?[^?]*$!', '', $dom['@href']->value );
                endforeach;
                $content= $xs->process( (string)$content );
                $type= 'text/html';
            endif;
        endif;
        
        $encoding= $response->encoding;
        $httpCode= static::$httpCodes[ $response->status ];
        header( "Content-Type: {$type}", true, $httpCode );
        
        if( $location= $response->location ):
            header( "Location: {$location}", true );
        endif;
        
        echo $content;
        
        return $this;
    }
    
}
