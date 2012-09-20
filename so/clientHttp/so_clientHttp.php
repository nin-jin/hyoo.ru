<?php

class so_clientHttp
{
    use so_meta2;
    use so_singleton;
    
    static $codeMap= array(
        'ok' => 200,
        'moved' => 301,
        'found' => 302,
        'see' => 303,
        'missed' => 404,
        'error' => 500,
    );
    
    var $query_value;
    function query_make( ){
        $query= &$_SERVER[ 'QUERY_STRING' ];
        return so_query::make( $query ?: '' );
    }
    
    var $method_value;
    function method_make(){
        $method= &$_SERVER[ 'REQUEST_METHOD' ];
        $method= $method ? strtolower( $method ) : 'get';
        
        if( !in_array( $method, array( 'get', 'put', 'post', 'delete', 'move', 'copy' ) ) )
            throw new Exception( "Method [{$method}] is not supported" );
        
        return $method;
    }
    
    var $input_value;
    function input_make(){
        switch( $this->method ):
            
            case 'move':
            case 'put':
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
                        return so_dom_collection::make()->list( json_decode( $raw ) );
                        
                    default:
                        return so_query::make( array() );
                        
                endswitch;
                
            case 'post':
                return so_query::make( $_POST + $_FILES );
                
            default:
                return so_query::make( array() );
                
        endswitch;
    }
    
    var $output_value;
    var $output_depends= array();
    function output_make( ){
        return so_output::error( 'Response is empty' );
    }
    function output_store( $data ){
        return $data;
    }
    
    function send( ){
        $output= $this->output;
        $type= $output->mime;
        $content= $output->content;
        
        if( $type === 'application/xml' ):
            $accept= preg_split( '~, ?~', strtolower( so_value::make( $_SERVER[ 'HTTP_ACCEPT' ] ) ?: '' ) );
            if( in_array( 'text/html', $accept ) && !in_array( 'application/xhtml+xml', $accept ) ):
                $type= 'text/html';
                $xs= new so_XStyle;
                $xs->pathXSL= so_file::make( 'so/-mix/index.xsl' )->path;
                $xsl= $xs->docXSL;
                foreach( $xsl[ 'xsl:include' ] as $dom ):
                    $dom['@href']= preg_replace( '!\?[^?]*$!', '', $dom['@href']->value );
                endforeach;
                $content= (string) $xs->process( $content );
                $content= preg_replace( '~^<\\?xml .+?\\?>\n?~', '', $content );
                $type= 'text/html';
            endif;
        endif;
        
        $encoding= $output->encoding;
        $code= static::$codeMap[ $output->status ];
        header( "Content-Type: {$type}", true, $code );
        
        if( $location= $output->location ):
            header( "Location: {$location}", true );
        endif;
        
        echo $content;
        
        return $this;
    }
    
    function run( ){
        return so_front::make()->client( $this )->run();
    }
    
}