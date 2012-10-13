<?php

class so_source
{
    use so_meta;
    
    use so_registry {
        so_registry::make as makeAdapter;
    }
    static $id_prop= 'file';
    
    static function make( $file ){
        so_file::ensure( $file );
        $nameList= $file->nameList;
        
        while( $nameList ):
            array_shift( $nameList );
            $className= __NAMESPACE__ . '\\' . ( $nameList ? 'so_source__' . implode( '_', $nameList ) : 'so_source' );
            if( class_exists( $className ) )
                return $className::makeAdapter( $file );
        endwhile;
        
        throw new \Exception( "Can not make (so_source) for [{$file}]" );
    }
    
    var $file_value;
    function file_make( ){
        throw new \Exception( 'Property [file] is not defined' );
    }
    function file_store( $data ){
        return so_file::make( $data );
    }
    
    var $uri_value;
    function uri_make( ){
        return $this->file->uri;
    }
    
    var $name_value;
    function name_make( ){
        return $this->file->name;
    }
    
    var $extension_value;
    function extension_make( ){
        return $this->file->extension;
    }
    
    var $content_value;
    function content_make( ){
        return $this->file->content;
    }
    function content_store( $data ){
        return $this->file->content= $data;
    }
    
    var $root_value;
    function root_make( ){
        return $this->module->root;
    }
    
    var $package_value;
    function package_make( ){
        return $this->module->package;
    }
    
    var $module_value;
    function module_make( ){
        return so_module::make( $this->file->parent );
    }
    
    var $sources_value;
    function sources_make( ){
        return so_source_collection::make(array( (string) $this->file => $this ));
    }
    
    var $exists_value;
    function exists_make( ){
        return $this->file->exists;
    }

    var $version_value;
    function version_make( ){
        return $this->file->version;
    }
    
    var $uses_value;
    function uses_make( ){
        return so_module_collection::make();
    }
    
}