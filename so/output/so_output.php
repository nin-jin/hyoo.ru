<?php

class so_output
{
    use so_meta2;
    use so_factory;
    
    static function ok( $message= null ){
        $obj= static::make()->status( 'ok' );
        if( $message ) $obj->content= array( 'so_done' => $message );
        return $obj;
    }

    static function found( $uri ){
        return static::make()->status( 'found' )->location( $uri )->content(array( 'so_relocation' => $uri ));
    }

    static function moved( $uri ){
        return static::make()->status( 'moved' )->location( $uri )->content(array( 'so_relocation' => $uri ));
    }

    static function see( $uri ){
        return static::make()->status( 'see' )->location( $uri )->content(array( 'so_relocation' => $uri ));
    }

    static function missed( ){
        return static::make()->status( 'missed' );
    }
    
    static function error( $message= null ){
        return static::make()->status( 'error' )->content(array( 'so_error' => $message ));
    }
    
    var $status_prop= array();
    function status_store( $data ){
        return (string) $data;
    }
    
    var $location_prop= array();
    function location_store( $data ){
        return (string) $data;
    }
    
    var $content_prop= array();
    function content_store( $data ){
        return so_page::make( $data );
    }

    var $mime_prop= array();
    function mime_make( ){
        $content= $this->content;
        
        if( is_string( $content ) )
            return 'text/plain';
        
        if( !$content )
            return '';
        
        return $content->mime;
    }
    function mime_store( $data ){
        return (string) $data;
    }
    
    var $encoding_prop= array();
    function encoding_make( ){
        return 'utf-8';
    }
    function encoding_store( $data ){
        return (string) $data;
    }
    
    function __toString( ){
        return (string) $this->content;
    }
}