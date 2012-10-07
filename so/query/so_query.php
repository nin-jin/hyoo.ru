<?php

class so_query
implements Countable, ArrayAccess, IteratorAggregate
{
    use so_meta;
    use so_factory;
    
    static $sepaName= '=';
    static $sepaChunk= '/&';
    
    static function make( $uri= null ){
        $obj= new static;
        if( is_null( $uri ) ) return $obj;
        if( is_string( $uri ) ) return $obj->string( $uri );
        if( is_array( $uri ) ) return $obj->struct( $uri );
        if( $uri instanceof so_query ) return $uri;
        throw new Exception( 'Wrong type of argument' );
    }
    
    static function ensure( &$value ){
        return $value= static::make( $value );
    }
    
    var $string_value;
    var $string_depends= array( 'string', 'struct' );
    function string_make( ){
        $chunkList= array();
        foreach( $this->struct as $key => $val ):
            if( is_null( $val ) )
                continue;
            
            $key= is_numeric( $key ) ? '' : urlencode( $key );
            if( $val ):
                $chunk= urlencode( $val );
                if( $key ):
                    $chunk= $key . static::$sepaName[0] . $chunk;
                endif;
            else:
                $chunk= $key;
            endif;
            
            $chunkList[]= $chunk;
        endforeach;
        
        return implode( static::$sepaChunk[0], $chunkList );
    }
    function string_store( $data ){
        return (string) $data;
    }
    
    var $struct_value;
    var $struct_depends= array( 'struct', 'string' );
    function struct_make( ){
        $struct= array( );
        
        $chunkList= preg_split( '~[' . static::$sepaChunk . ']~', $this->string );
        foreach( $chunkList as $chunk ):
            if( !$chunk ) continue;
            $pair= preg_split( '~[' . static::$sepaName . ']~', $chunk, 2 );
            $key= &$pair[0];
            $val= &$pair[1];
            $struct[ urldecode( $key ) ]= urldecode( $val );
        endforeach;
        
        return $struct;
    }
    function struct_store( $data ){
        return (array) $data;
    }
    
    var $uri_value;
    function uri_make( ){
        return so_uri::makeInstance()->query( $this )->primary();
    }
    
    var $resource_value;
    function resource_make( ){
        $keyList= array_keys( $this->struct );
        array_unshift( $keyList, 'so' );
        
        while( count( $keyList ) ):
            $class= implode( '_', $keyList );
            if( count( $keyList ) < 2 ) $class.= '_rootResource';
            if( class_exists( $class ) ) break;
            array_pop( $keyList );
        endwhile;
        
        return $class::make( $this->uri );
    }
    
    function _string_meta( ){
        return $this->string;
    }
    
    function count( ){
        return count( $this->struct );
    }

    function offsetExists( $key ){
        return key_exists( $key, $this->struct );
    }
    
    function offsetGet( $key ){
        $struct= $this->struct;
        return so_value::make( $struct[ $key ] );
    }
    
    function offsetSet( $key, $value ){
        throw new Exception( 'Query is read only' );
    }
    
    function offsetUnset( $key ){
        throw new Exception( 'Query is read only' );
    }
    
    function getIterator( ){
        return new ArrayIterator( $this->struct );
    }
    
}