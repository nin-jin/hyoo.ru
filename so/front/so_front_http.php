<?php

class so_front_http
extends so_front
{

    static $codeMap= array(
        'ok' => 200,
        'moved' => 301,
        'found' => 302,
        'see' => 303,
        'missed' => 404,
        'error' => 500,
    );
    
    var $dir_value;
    function dir_make( ){
        return so_file::make( dirname( so_value::make( $_SERVER[ 'SCRIPT_FILENAME' ] ) ) );
    }
    
    var $uri_value;
    function uri_make( ){
        $uri= so_value::make( $_SERVER[ 'REQUEST_URI' ] );
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
                return so_query::make( $_POST + $_FILES );
            
            default:
                $raw= '';
                $input= fopen( 'php://input', 'r' );
                while( $chunk= fread( $input, 1024 ) ) $raw.= $chunk;
                fclose( $input );
                
                $type= strtolower( so_value::make( $_SERVER[ 'CONTENT_TYPE' ] ) ?: '' );
                
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

    function send( ){
        $output= $this->result;
        $content= $output->content;
        $mime= $content->mime;
        $cache= $output->cache;
        $private= $output->private;
        
        if( $mime === 'application/xml' ):
            $accept= preg_split( '~, ?~', strtolower( so_value::make( $_SERVER[ 'HTTP_ACCEPT' ] ) ?: '' ) );
            if( in_array( 'text/html', $accept ) && !in_array( 'application/xhtml+xml', $accept ) ):
                
                $xs= new so_XStyle;
                $xs->pathXSL= (string) so_front::make()->package['-mix']['index.xsl']->file;
                $xsl= $xs->docXSL;
                foreach( $xsl->childs[ 'xsl:include' ] as $dom ):
                    $dom['@href']= preg_replace( '!\?[^?]*$!', '', $dom['@href'] );
                endforeach;
                
                $content= (string) $xs->process( $content );
                $content= preg_replace( '~^<\\?xml .+?\\?>\n?~', '', $content );
                $mime= 'text/html';
            endif;
        endif;
        
        $encoding= $output->encoding;
        $code= static::$codeMap[ $output->status ];
        header( "Content-Type: {$mime}", true, $code );
        
        if( !$private )
            header( "Access-Control-Allow-Origin: *", true );
        
        if( $cache ):
            header( "Cache-Control: " . ( $private ? 'private' : 'public' ), true );
        else:
            header( "Cache-Control: no-cache", true );
        endif;
        
        if( $location= $output->location ):
            header( "Location: {$location}", true );
        endif;
        
        echo str_pad( $content, 512, ' ', STR_PAD_RIGHT );
        
        return $this;
    }
    
}