<?php

class so_WC_Module extends so_WC_MetaModule {
    protected $_name;
    function set_name( $name ){
        if( isset( $this->name ) ) throw new Exception( 'Redeclaration of $name' );
        return $name;
    }
    
    protected $_id;
    function get_id( $id ){
        if( isset( $id ) ) return $id;
        return $this->pack->id . '/' . $this->name;
    }
    
    protected $_path;
    function get_path( $path ){
        if( isset( $path ) ) return $path;
        return $this->pack->path->go( $this->name . '/' );
    }
    
    protected $_exists;
    function get_exists( $exists ){
        return is_dir( $this->path );
    }
    function set_exists( $exists ){
        so_file::make( $this->path )->exists= $exists;
        return $exists;
    }
    
    protected $_root;
    function get_root( $root ){
        if( isset( $root ) ) return $root;
        return $this->pack->root;
    }
    
    protected $_pack;
    function set_pack( $pack ){
        if( isset( $this->pack ) ) throw new Exception( 'Redeclaration of $pack' );
        return $pack;
    }
    
    protected $_files;
    function get_files( $files ){
        if( isset( $files ) ) return $files;
        $files= array();
        foreach( $this->childNameList as $name ):
            $file= $this->createFile( $name );
            if( !$file->exists ) continue;
            $files[ $file->id ]= $file;
        endforeach;
        return $files;
    }
    
    protected $_fileCache= array();
    function createFile( $name ){
        if( key_exists( $name, $this->_fileCache ) ) return $this->_fileCache[ $name ];

        $file= new so_WC_File;
        $file->name= $name;
        $file->module= $this;
        $this->_fileCache[ $name ]= $file;

        return $file;
    }
}
