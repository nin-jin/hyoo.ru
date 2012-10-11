<?php

class so_source__php
extends so_source
{

    var $uses_value;
    function uses_make( ){
        
        preg_match_all
        (   '/\b(?:extends|implements|use|new)\s+([a-zA-Z0-9]+)_([a-zA-Z0-9]+)/'
        ,   $this->file->content
        ,   $matches1
        ,   PREG_SET_ORDER
        );
        
        preg_match_all
        (   '/\b([a-zA-Z0-9]+)_([a-zA-Z0-9]+)\w*::/'
        ,   $this->file->content
        ,   $matches2
        ,   PREG_SET_ORDER
        );
        
        $matches= array_merge( $matches1, $matches2 );
        
        $depends= array();
        if( $matches ) foreach( $matches as $item ):
            list( $str, $packName, $moduleName )= $item;
            
            $module= $this->root[ $packName ][ $moduleName ];
            if( !$module->exists )
                throw new Exception( "Module [{$module->dir}] not found for [{$this->file}]" );
            
            $depends+= $module->modules->list;
        endforeach;
        
        return so_module_collection::make( $depends );
    }

}