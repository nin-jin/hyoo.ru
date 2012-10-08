<?php

class so_root
implements ArrayAccess
{
    use so_meta;
    
    use so_singleton;
    static $id_prop= 'dir';
    
    static $mainPackageName;

    var $dir_value;
    function dir_make( ){
        return so_file::make( __DIR__ . '/../..' );
    }
    function dir_store( $data ){
        return so_file::make( $data );
    }
    
    var $root_value;
    function root_make( ){
        return $this;
    }
    
    var $packages_value;
    function packages_make(){
        $list= array();
        
        foreach( $this->dir->childs as $child ):
            if( $child->type != 'dir' )
                continue;
            $package= $this[ $child->name ];
            $list+= $package->packages->list;
        endforeach;
        
        return pms_package_collection::make( $list );
    }
    
    var $modules_value;
    function modules_make( ){
        return $this->packages->modules;
    }
    
    var $sources_value;
    function sources_make( ){
        return $this->packages->sources;
    }
    
    var $version_value;
    function version_make( ){
        $version= '';
        
        foreach( $this->packages as $package )
            if( ( $v= $package->version ) > $version )
                $version= $v;
        
        return $version;
    }

    function offsetExists( $name ){
        return $this->dir->go( $name )->exists;
    }
    
    function offsetGet( $name ){
        return pms_package::make( $this->dir->go( $name ) );
    }

    function offsetSet( $name, $value ){
        throw new Exception( "Not implemented" );
    }

    function offsetUnset( $name ){
        throw new Exception( "Not implemented" );
    }

}
