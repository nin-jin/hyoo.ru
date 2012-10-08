<?php

class so_output
{
    use so_meta;
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
    
    var $status_value;
    function status_store( $data ){
        return (string) $data;
    }
    
    var $location_value;
    function location_store( $data ){
        return (string) $data;
    }
    
    var $content_value;
    function content_store( $data ){
        return so_content::make( so_page::make( $data ) );
    }

    var $encoding_value;
    function encoding_make( ){
        return 'utf-8';
    }
    function encoding_store( $data ){
        return (string) $data;
    }
    
    var $private_value;
    function private_make( ){
        return false;
    }
    function private_store( $data ){
        return (boolean) $data;
    }
    
    var $cache_value;
    function cache_make( ){
        return false;
    }
    function cache_store( $data ){
        return (boolean) $data;
    }
    
    function _string_meta( ){
        return $this->content;
    }
}