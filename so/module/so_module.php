<?php

class so_module
implements \ArrayAccess
{
    use so_meta;
    
    use so_registry;
    static $id_prop= 'dir';
    
    var $dir_value;
    function dir_make( ){
        throw new \Exception( 'Property [dir] is not defined' );
    }
    function dir_store( $data ){
        return so_file::make( $data );
    }
    
    var $name_value;
    function name_make( ){
        return $this->dir->name;
    }
    
    var $root_value;
    function root_make( ){
        return $this->package->root;
    }
    
    var $package_value;
    function package_make( ){
        return so_package::make( $this->dir->parent );
    }
    
    var $modules_value;
    function modules_make( ){
        return so_module_collection::make(array( (string) $this->dir => $this ));
    }
    
    var $sources_value;
    function sources_make(){
        $list= array();
        
        foreach( $this->dir->childs as $child ):
            if( $child->type != 'file' )
                continue;
            $source= $this[ $child->name ];
            $list+= $source->sources->list;
        endforeach;
        
        return so_source_collection::make( $list );
    }
    
    var $exists_value;
    function exists_make( ){
        return $this->dir->exists;
    }

    var $version_value;
    function version_make( ){
        $version= '';
        
        foreach( $this->sources as $source )
            if( ( $v= $source->version ) > $version )
                $version= $v;
        
        return $version;
    }
    
    var $uses_value;
    function uses_make( ){
        return $this->sources->uses;
    }
    
    function offsetExists( $name ){
        return $this->dir->go( $name )->exists;
    }
    
    function offsetGet( $name ){
        return so_source::make( $this->dir->go( $name ) );
    }

    function offsetSet( $name, $value ){
        throw new \Exception( "Not implemented" );
    }

    function offsetUnset( $name ){
        throw new \Exception( "Not implemented" );
    }

}