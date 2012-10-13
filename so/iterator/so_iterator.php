<?php

class so_iterator
implements \Iterator
{
    use so_meta;
    use so_factory;

    var $collection_value;
    function collection_make( ){
        throw new \Exception( 'Property [collection] is not defined' );
    }
    function collection_store( $value ){
        return $value;
    }

    var $keyList_value;
    function keyList_make( ){
        return array_keys( $this->collection->list );
    }

    var $ArrayIterator_value;
    function ArrayIterator_make( ){
        return new \ArrayIterator( $this->keyList );
    }
    
    function current( ){
        return $this->collection[ $this->key() ];
    }
    
    function key( ){
        return $this->ArrayIterator->current();
    }
    
    function next( ){
        $this->ArrayIterator->next();
        return $this;
    }
    
    function rewind( ){
        $this->ArrayIterator->rewind();
        return $this;
    }
    
    function valid( ){
        return $this->ArrayIterator->valid();
    }

}
