<?php

class so_query
implements \Countable, \ArrayAccess, \IteratorAggregate
{
    use so_meta;
    use so_factory;
    
    static $sepaName= '=';
    static $sepaChunk= ';&';
    
    static function make( $uri= null ){
        $obj= new static;
        if( is_null( $uri ) ) return $obj;
        if( is_string( $uri ) ) return $obj->string( $uri );
        if( is_array( $uri ) ) return $obj->struct( $uri );
        if( $uri instanceof so_query ) return $uri;
        throw new \Exception( 'Wrong type of argument' );
    }
    
    static function ensure( &$value ){
        return $value= static::make( $value );
    }
    
    function escape( $string ){
        $string= preg_replace_callback
        (   "=[^- a-zA-Z/?:@!$'()*+,._~\x{A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}\x{10000}-\x{1FFFD}\x{20000}-\x{2FFFD}\x{30000}-\x{3FFFD}\x{40000}-\x{4FFFD}\x{50000}-\x{5FFFD}\x{60000}-\x{6FFFD}\x{70000}-\x{7FFFD}\x{80000}-\x{8FFFD}\x{90000}-\x{9FFFD}\x{A0000}-\x{AFFFD}\x{B0000}-\x{BFFFD}\x{C0000}-\x{CFFFD}\x{D0000}-\x{DFFFD}\x{E1000}-\x{EFFFD}\x{E000}-\x{F8FF}\x{F0000}-\x{FFFFD}\x{100000}-\x{10FFFD}]+=u"
        ,   function( $str ){
                return rawurlencode( $str[0] );
            }
        ,   (string) $string
        );
        $string= strtr( $string, ' ', '+' );
        return $string;
    }
    
    var $string_value;
    var $string_depends= array( 'string', 'struct' );
    function string_make( ){
        $chunkList= array();
        foreach( $this->struct as $key => $val ):
            if( is_null( $val ) )
                continue;
            
            $key= is_numeric( $key ) ? '' : static::escape( $key );
            if( $val ):
                $chunk= static::escape( $val );
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
        $struct= array( );
        
        $chunkList= preg_split( '~[' . static::$sepaChunk . ']~', $data );
        foreach( $chunkList as $chunk ):
            if( !$chunk ) continue;
            $pair= preg_split( '~[' . static::$sepaName . ']~', $chunk, 2 );
            $key= &$pair[0];
            $val= &$pair[1];
            $struct[ urldecode( $key ) ]= urldecode( $val );
        endforeach;
        
        $this->struct= $struct;
    }
    
    var $struct_value;
    var $struct_depends= array( 'struct', 'string' );
    function struct_make( ){
        return array();
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
        array_unshift( $keyList, so_front::make()->package->name );
        
        while( count( $keyList ) ):
            $class= __NAMESPACE__ . '\\' . implode( '_', $keyList );
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
        throw new \Exception( 'Query is read only' );
    }
    
    function offsetUnset( $key ){
        throw new \Exception( 'Query is read only' );
    }
    
    function getIterator( ){
        return new \ArrayIterator( $this->struct );
    }
    
}