<?php

class so_file_collection
implements Countable, ArrayAccess, IteratorAggregate
{
    use so_meta2;
    use so_collection;
    
    var $exists_value;
    function exists_make( ){
        foreach( $this as $file )
            if( !$file->exists )
                return false;
        return true;
    }
    function exists_store( $data ){
        foreach( $this as $file )
            $file->exists= $data;
        return $data;
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
            return $this->list_value[ $key ]= so_file::make( $this->list[ $key ] );
        endif;
        
        $list= array();
        foreach( $this as $item ):
            if( $item->name != $key ) continue;
            $list[]= $item;
        endforeach;
        
        return so_dom_collection::make( $list );
    }
    
}
