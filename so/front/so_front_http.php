<?php

class so_front_http
extends so_front
{

    static $codeMap= array(
        'ok' => 200,
        'created' => 201,
        'moved' => 301,
        'found' => 302,
        'see' => 303,
        'forbidden' => 403,
        'missed' => 404,
        'conflict' => 409,
        'error' => 500,
    );
    
    var $dir_value;
    function dir_make( ){
        return so_file::make( dirname( so_value::make( $_SERVER[ 'SCRIPT_FILENAME' ] ) ) );
    }
    
    var $uri_value;
    function uri_make( ){
        $uri= so_value::make( $_SERVER[ 'REQUEST_URI' ] );
        //$uri= mb_convert_encoding( $uri, 'UTF-8', 'windows-1251' );
        $uri= iconv( 'windows-1251', 'UTF-8', $uri );
        return so_uri::make( $uri );
    }

    var $method_value;
    function method_make( ){
        $method= so_value::make( $_SERVER[ 'REQUEST_METHOD' ] );
        return strtolower( $method ?: 'get' );
    }

    var $data_value;
    function data_make( ){
        switch( $this->method ):
            
            case 'get':
            case 'head':
                return null;
            
            case 'post':
                $data= $_POST;
                foreach( $_FILES as $key => $info )
                    $data[ $key ]= so_file::make( $info[ 'tmp_name' ] );
                return so_query::make( $data );
            
            default:
                $raw= '';
                $input= fopen( 'php://input', 'r' );
                while( $chunk= fread( $input, 1024 ) ) $raw.= $chunk;
                fclose( $input );
                
                $type= preg_replace( '~;.*$~', '', so_value::make( $_SERVER[ 'CONTENT_TYPE' ] ) ?: '' );
                
                switch( $type ):
                    
                    case 'application/x-www-form-urlencoded':
                        return so_query::make( $raw );
                        break;
                    
                    case 'text/xml':
                    case 'application/xml':
                        return so_dom::make( $raw );
                    
                    case 'text/json':
                    case 'application/json':
                        return json_decode( $raw );
                    
                    default:
                        return $raw;
                    
                endswitch;
            
        endswitch;
    }

    function send( $response ){
        $content= $response->content;
        $mime= $content->mime;
        $cache= $response->cache;
        $private= $response->private;
        
        if( $mime === 'application/xml' ):
            $accept= preg_split( '~[,;] ?~', strtolower( so_value::make( $_SERVER[ 'HTTP_ACCEPT' ] ) ?: '' ) );
            if( !in_array( 'application/xhtml+xml', $accept ) ):
                $xs= new so_xstyle;
                $xs->pathXSL= (string) so_package::make()['-mix']['release.xsl']->file;
                $xsl= $xs->docXSL;
                foreach( $xsl->childs[ 'xsl:include' ] as $dom ):
                    $dom['@href']= preg_replace( '!\?[^?]*$!', '', $dom['@href'] );
                endforeach;
                
                $content= (string) $xs->process( (string) $content );
                $content= preg_replace( '~^<\\?xml .+?\\?>\n?~', '', $content );
                $mime= 'text/html';
            endif;
        endif;
        
        $encoding= $response->encoding;
        $code= static::$codeMap[ $response->status ];
        header( "Content-Type: {$mime}", true, $code );
        
        if( !$private )
            header( "Access-Control-Allow-Origin: *", true );
        
        if( $cache ):
            header( "Cache-Control: " . ( $private ? 'private' : 'public' ), true );
        else:
            header( "Cache-Control: no-cache", true );
        endif;
        
        if( $location= $response->location ):
            header( "Location: {$location}", true );
        endif;
        
        echo str_pad( $content, 512, ' ', STR_PAD_RIGHT );
        
        return $this;
    }
    
}