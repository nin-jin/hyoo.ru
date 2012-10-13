<?php

class so_array
extends \ArrayObject
{
    use so_meta;
    
    static function make( $array ){
        return new static( $array );
    }

    static function ensure( &$value ){
        return $value= static::make( $value );
    }
    
    function _string_meta( ){
        $string= '';
        
        foreach( $this as $key => $val ):
            if( is_string( $key ) )
                $key= "'" . strtr( $key, array( "'" => "\'", "\n" => "\\n" ) ) . "'";
            
            if( is_array( $val ) )
                $val= (string) so_array::make( $val );
            
            if( is_string( $val ) )
                $val= "'" . strtr( $val, array( "'" => "\'", "\n" => "\\n" ) ) . "'";
            
            if( is_bool( $val ) )
                $val= $val ? 'TRUE' : 'FALSE';
            
            if( is_null( $val ) )
                $val= 'NULL';
            
            $string.= $key . " => " .  trim( $val, "\n" ) . "\n";
        endforeach;
        
        $string= preg_replace( '~^~m', '    ', $string );
        $string= "array(\n" . $string . ")\n";
            
        return $string;
    }
}