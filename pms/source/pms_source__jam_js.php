<?php

class pms_source__jam_js
extends pms_source
{

    var $uses_value;
    function uses_make( ){
        $root= $this->root;
        $package= $this->package;
        
        preg_match_all
        (   '/\$([a-zA-Z0-9]+)(?:[._]([a-zA-Z0-9]+))?(?![a-zA-Z0-9$])/'
        ,   $this->file->content
        ,   $matches
        ,   PREG_SET_ORDER
        );
        
        $uses= array();
        
        $mainModule= $package[ $package->name ];
        if( $mainModule->exists )
            $uses+= $mainModule->modules->list;
        
        if( $matches ) foreach( $matches as $item ):
            list( $str, $packName )= $item;
            $moduleName= so_value::make( $item[2] );
            if( !$moduleName )
                $moduleName= $packName;
            
            $module= $root[ $packName ][ $moduleName ];
            if( $module === $this->module )
                continue;
            if( !$module->exists )
                throw new Exception( "Module [{$module->dir}] not found for [{$this->file}]" );
            
            $uses+= $module->modules->list;
        endforeach;
        
        $uses+= $this->module->modules->list;
        
        return pms_module_collection::make( $uses );
    }

    function uriJS_make( ){
        return $this->file->uri;
    }

    function contentJS_make( ){
        return $this->file->content;
    }

}