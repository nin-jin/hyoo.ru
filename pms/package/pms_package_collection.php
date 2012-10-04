<?php

class pms_package_collection
implements Countable, ArrayAccess, IteratorAggregate
{
    use so_meta;
    use so_collection;
    
    function list_store( $list ){
        $list= (array) $list;
        
        foreach( $list as $key => $package ):
            if( !is_numeric( $key ) )
                continue;
            unset( $list[ $key ] );
            $package= pms_package::make( $package );
            $list+= $package->packages->list;
        endforeach;
        
        return $list;
    }
    
    var $modules_value;
    function modules_make( ){
        $list= array();
        
        foreach( $this as $package )
            $list+= $package->modules->list;
        
        return pms_module_collection::make( $list );
    }
    
    var $sources_value;
    function sources_make( ){
        return $this->modules->sources;
    }
    
    var $depends_value;
    function depends_make( ){
        return $this->modules->depends;
    }
    
}
