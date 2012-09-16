<?php

class so_WC_Root
extends so_WC_Node
{
    use so_singleton;

    protected $_path;
    function get_path( $path ){
        if( isset( $path ) ) return $path;
        return so_file::make( __DIR__ )->parent->parent;
    }
    
    protected $_currentPack;
    function get_currentPack( $pack ){
        if( isset( $pack ) ) return $pack;
        return $this->createPack( basename( getcwd() ) );
    }
    
    protected $_packs;
    function get_packs( $packs ){
        if( isset( $packs ) ) return $packs;
        $packs= array();
        foreach( $this->childNameList as $name ):
            $pack= $this->createPack( $name );
            if( !$pack->exists ) continue;
            $packs[ $pack->id ]= $pack;
        endforeach;
        return $packs;
    }

    protected $_modules;
    function get_modules( $modules ){
        if( isset( $modules ) ) return $modules;
        $modules= array();
        foreach( $this->packs as $pack ):
            $modules= array_merge( $modules, $pack->modules );
        endforeach;
        return $modules;
    }

    protected $_files;
    function get_files( $files ){
        if( isset( $files ) ) return $files;
        $files= array();
        foreach( $this->modules as $module ):
            $files= array_merge( $files, $module->files );
        endforeach;
        return $files;
    }

    protected $_packCache= array();
    function createPack( $name ){
        if( key_exists( $name, $this->_packCache ) ) return $this->_packCache[ $name ];

        $pack= new so_WC_Pack;
        $pack->name= $name;
        $pack->root= $this;
        $this->_packCache[ $name ]= $pack;
        
        return $pack;
    }
}
