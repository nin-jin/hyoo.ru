<?php

class so_dom_collection
implements Countable, ArrayAccess, IteratorAggregate
{
    use so_meta;
    use so_collection;
    
    var $parent_value;
    function parent_make( ){
        $list= array();
        foreach( $this as $item ):
            $list[]= $item->parent;
        endforeach;
        return so_dom_collection::make( $list );
    }
    function parent_store( $parent ){
        foreach( $this as $item ):
            $item->parent= $parent;
        endforeach;
    }

    function offsetExists( $key ){
        if( is_int( $key ) ):
            return isset( $this->list[ $key ] );
        endif;
        
        foreach( $this as $item ):
            if( $item->name !== $key ) continue;
            return true;
        endforeach;
        
        return false;
    }
    
    function offsetGet( $key ){
        if( is_int( $key ) ):
            return $this->list_value[ $key ]= so_dom::make( $this->list[ $key ] );
        endif;
        
        $list= array();
        foreach( $this as $item ):
            if( $item->name != $key ) continue;
            $list[]= $item;
        endforeach;
        
        return so_dom_collection::make( $list );
    }
    
    function offsetSet( $key, $value ){
        if( is_int( $key ) ):
            $this->list[ $key ]->content= $value;
            return $this;
        endif;
        throw new Exception( 'Not implemented yet' );
        $list= array();
        foreach( $this as $item ):
            if( $item->name != $key ) continue;
            $list= array_merge( $list, $item->childs->list );
        endforeach;
        
        return $this;
    }
    
    function offsetUnset( $key ){
        if( is_int( $key ) ):
            $this->list[ $key ]->parent= null;
            return $this;
        endif;
        
        $this[ $key ]->parent= null;
        
        return $this;
    }
    
    function _string_meta( ){
        $string= '';
        
        foreach( $this as $item )
            $string.= $item;
        
        return $string;
    }
    
}
