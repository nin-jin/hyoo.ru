<?php

trait so_collection
#implements Countable, ArrayAccess, IteratorAggregate
{
    #use so_meta2;
    
    var $list_value;
    function list_make( ){
        return array();
    }
    function list_store( $list ){
        return (array) $list;
    }
    
    function wrapItem( $item ){
        return $item;
    }

    function count( ){
        return count( $this->list );
    }

    function offsetExists( $key ){
        return isset( $this->list[ $key ] );
    }
    
    function offsetGet( $key ){
        return $this->wrapItem( $this->list[ $key ] );
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
