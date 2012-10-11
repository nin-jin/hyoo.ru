<?php

class so_package
implements ArrayAccess
{
    use so_meta;
    
    use so_registry;
    static $id_prop= 'dir';
    
    var $dir_value;
    function dir_make( ){
        throw new Exception( 'Property [dir] is not defined' );
    }
    function dir_store( $data ){
        return so_file::make( $data );
    }
    
    var $name_value;
    function name_make( ){
        return $this->dir->name;
    }
    
    var $exists_value;
    function exists_make( ){
        return $this->dir->exists;
    }

    var $root_value;
    function root_make( ){
        return so_root::make( $this->dir->parent );
    }
    
    var $packages_value;
    function packages_make( ){
        return so_package_collection::make(array( (string) $this->dir => $this ));
    }
    
    var $modules_value;
    function modules_make(){
        $list= array();
        
        foreach( $this->dir->childs as $child ):
            if( $child->type != 'dir' )
                continue;
            $module= $this[ $child->name ];
            $list+= $module->modules->list;
        endforeach;
        
        return so_module_collection::make( $list );
    }
    
    var $sources_value;
    function sources_make( ){
        return $this->modules->sources;
    }
    
    var $depends_value;
    function depends_make( ){
        return $this->modules->depends;
    }
    
    var $version_value;
    function version_make( ){
        $version= '';
        
        foreach( $this->modules as $module )
            if( ( $v= $module->version ) > $version )
                $version= $v;
        
        return $version;
    }
    
    function offsetExists( $name ){
        return $this->dir->go( $name )->exists;
    }
    
    function offsetGet( $name ){
        return so_module::make( $this->dir->go( $name ) );
    }

    function offsetSet( $name, $value ){
        throw new Exception( "Not implemented" );
    }

    function offsetUnset( $name ){
        throw new Exception( "Not implemented" );
    }

}