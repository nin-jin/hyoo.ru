<?php

class so_source__meta_tree
extends so_source
{

    var $uses_value;
    function uses_make( ){
        
        $meta= new so_Tree;
        $meta->string= $this->file->content;
        
        $depends= array();
        
        foreach( $meta->get( 'include pack' ) as $packageId ):
            $package= $this->root[ trim( $packageId ) ];
            
            if( !$package->exists )
                throw new \Exception( "Pack [{$package->dir}] not found for [{$this->file}]" );
            
            $depends+= $package->modules->list;
        endforeach;
        
        foreach( $meta->get( 'include module' ) as $moduleId ):
            $names= explode( '/', trim( $moduleId ) );
            
            if( !isset( $names[ 1 ] ) )
                array_push( $names, $this->package->name );
            
            $module= $this->root[ $names[0] ][ $names[1] ];
            if( !$module->exists )
                throw new \Exception( "Module [{$module->dir}] not found for [{$this->file}]" );
            
            $depends+= $module->modules->list;
        endforeach;
        
        return so_module_collection::make( $depends );
    }

}