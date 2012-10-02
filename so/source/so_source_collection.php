<?php

class so_source_collection
implements Countable, ArrayAccess, IteratorAggregate
{
    use so_meta2;
    use so_collection;
    
    var $uses_value;
    function uses_make( ){
        $list= array();
        
        foreach( $this as $source )
            $list+= $source->uses->list;
        
        return so_module_collection::make( $list );
    }
    
    var $sources_value;
    function sources_make( ){
        return $this;
    }
    
}
