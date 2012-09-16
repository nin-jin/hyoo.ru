<?php

class so_dom_list
implements Countable, ArrayAccess, IteratorAggregate
{
    use so_meta;
    
    static function make( $list= array() ){
        if( $list instanceof so_dom_list ) $list= $list->list;
        return (new static)->list( $list );
    }

    function __toString( ){
        return implode( '', $this->list );
    }
    
    protected $_list;
    function get_list( $list ){
        if( isset( $list ) ) return $list;
        return array();
    }
    function set_list( $list ){
        if( isset( $this->list ) ) throw new Exception( 'Redeclaration of $list' );
        
        $list2= array();
        foreach( $list as $item ):
            $list2[]= so_dom::wrap( $item );
        endforeach;
        
        return $list2;
    }

    function drop( ){
        
        return $this;
    }
    
    function count( ){
        return count( $this->list );
    }

    function offsetExists( $key ){
        if( is_numeric( $key ) ):
            return isset( $this->list[ $key ] );
        endif;
        
        foreach( $this->list as $item ):
            if( $item->name !== $key ) continue;
            return true;
        endforeach;
        
        return false;
    }
    
    function offsetGet( $key ){
        if( is_numeric( $key ) ):
            return $this->list[ $key ];
        endif;
        
        $list= array();
        foreach( $this->list as $item ):
            if( $item->name != $key ) continue;
            $list= array_merge( $list, $item->childList->list );
        endforeach;
        
        return so_dom_list::make( $list );
    }
    
    function offsetSet( $key, $value ){
        if( is_numeric( $key ) ):
            $this->list[ $key ]->content= $value;
            return $this;
        endif;
        
        $list= array();
        foreach( $this->list as $item ):
            if( $item->name != $key ) continue;
            $list= array_merge( $list, $item->child->list );
        endforeach;
        
        return $this;
    }
    
    function offsetUnset( $key ){
        if( is_numeric( $key ) ):
            $this->list[ $key ]->parent= null;
            return $this;
        endif;
        
        foreach( $this->list as $item ):
            if( $item->name != $key ) continue;
            $item->parent= null;
        endforeach;
        
        return $this;
    }
    
    function getIterator( ){
        return so_dom_Iterator::make( $this->list );
    }
    
}
