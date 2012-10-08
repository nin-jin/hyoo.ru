<?php

class so_content
{
    use so_meta;

    static function make( $value ){
        $obj= new static;
        $obj->value= $value;
        return $obj;
    }
    
    static $mime2extension= array(
        'text/plain' => 'txt',
        'text/html' => 'html',
        'application/xml' => 'xml',
        'application/octet-stream' => 'blob',
    );
    
    static function ensure( &$value ){
        return $value= static::make( $value );
    }
    
    var $value_value;
    function value_store( $data ){
        return $data;
    }
    
    var $mime_value;
    function mime_make( ){
        $value= $this->value;
        
        if( is_scalar( $value ) )
            return 'text/plain';
        
        if( isset( $value->mime ) )
            return $value->mime;
        
        return 'application/octet-stream';
    }

    var $extension_value;
    function extension_make( ){
        return static::$mime2extension[ $this->mime ];
    }
    
    function _string_meta( $prefix= '' ){
        return (string) $this->value;
    }
    
}