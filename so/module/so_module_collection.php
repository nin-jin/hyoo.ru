<?php

class so_module_collection
implements Countable, ArrayAccess, IteratorAggregate
{
    use so_meta2;
    use so_collection;
    
    var $sources_value;
    function sources_make( ){
        $list= array();
        
        foreach( $this as $module )
            $list+= $module->sources->list;
        
        return so_source_collection::make( $list );
    }
    
    var $depends_value;
    function depends_make( ){
        $out= array();
        
        foreach( $this as $inId => $in ):
            if( isset( $out[ $inId ] ) ) continue;
            $mark= array( $inId => $in );
            
            while( $mark ):
                end( $mark );
                list( $curId, $cur )= each( $mark );
                
                foreach( $cur->uses as $depId => $dep ):
                    if( isset( $out[ $depId ] ) ) continue;
                    if( isset( $mark[ $depId ] ) ) continue;
                    
                    $mark[ $depId ]= $dep;
                    continue 2;
                endforeach;
                
                $out[ $curId ]= $cur;
                unset( $mark[ $curId ] );
            endwhile;
        endforeach;
        
        return so_module_collection::make( $out );
    }
    
}
