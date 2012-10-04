<?php

trait so_collection
#implements Countable, ArrayAccess, IteratorAggregate
{
    #use so_meta;
    
    static function make( $list= null ){
        if( $list instanceof static )
            return $list;
        
        $obj= new static;
        
        if( isset( $list ) )
            $obj->list( $list );
        
        return $obj;
    }
    
    static function ensure( &$value ){
        return $value= static::make( $value );
    }
    
    var $list_value;
    function list_make( ){
        return array();
    }
    function list_store( $list ){
        return (array) $list;
    }
    
    function count( ){
        return count( $this->list );
    }

    function offsetExists( $key ){
        return isset( $this->list[ $key ] );
    }
    
    function offsetGet( $key ){
        return $this->list[ $key ];
    }
    
    function offsetSet( $key, $value ){
        throw new Exception( 'Collection is read only' );
    }
    
    function offsetUnset( $key ){
        throw new Exception( 'Collection is read only' );
    }
    
    function getIterator( ){
        return so_iterator::make()->collection( $this );
    }
    
}
