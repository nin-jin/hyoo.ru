<?php

class so_dom_collection
implements Countable, ArrayAccess, IteratorAggregate
{
    use so_meta2;
    use so_factory;
    use so_collection;
    
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
            return $this->list_value[ $key ]= so_file::make( $this->list[ $key ] );
        endif;
        
        $list= array();
        foreach( $this as $item ):
            if( $item->name != $key ) continue;
            $list[]= $item;
        endforeach;
        
        return so_dom_collection::make()->list( $list );
    }
    
}
