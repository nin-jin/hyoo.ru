<?php

class so_Dom_Iterator
extends so_Meta
implements Iterator
{

    static function create( $list ){
        $iterator= new self;
        $iterator->list= $list;
        return $iterator;
    }

    protected $_list;
    function &get_list( &$list ){
        return $list;
    }
    function set_list( $list ){
        if( isset( $this->list ) ) throw new Exception( 'Redeclaration of [list]' );
        return $list;
    }

    protected $_iterator;
    function &get_iterator( &$iterator ){
        if( isset( $iterator ) ) return $iterator;
        return new ArrayIterator( $this->list );
    }

    function current( ){
        return so_Dom::wrap( $this->iterator->current() );
    }
    
    function key( ){
        return $this->current()->name;
    }
    
    function next( ){
        $this->iterator->next();
        return $this;
    }
    
    function rewind( ){
        $this->iterator->rewind();
        return $this;
    }
    
    function valid( ){
        return $this->iterator->valid();
    }
    
}
