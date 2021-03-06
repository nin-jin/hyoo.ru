<?php

class so_storage
//implements \Countable, \ArrayAccess, \IteratorAggregate
{
    use so_meta;
    
    use so_registry;
    static $id_prop= 'id';

    var $id_value;
    function id_store( $id ){
        return (string) $id;
    }
    
    var $dir_value;
    function dir_make( ){
        $pair= explode( '=', $this->id, 2 );
        
        $key= strtr( $pair[ 0 ], array( '?' => '=' ) );
        
        $val= &$pair[ 1 ];
        $val= md5( $val );
        $val= substr( $val, 0, 3 ) . '/' . substr( $val, 4, 7 );
        
        $path= $key . '/' . $val;
        
        return so_file::make( '-so_storage' )->go( $path );
    }
    
    var $index_value;
    function index_make( ){
        return so_storage_index::make( $this->dir->go( 'index.sqlite' ) );
    }
    
    var $version_value;
    var $version_depends= array();
    function version_make( ){
        return $this->index->tail[ 'so_storage_version' ];
    }
    function version_store( $version ){
        $this->index[]= array( 'so_storage_version' => $version );
        unset( $this->file );
        return $version;
    }
    
    var $file_value;
    function file_make( ){
        $version= $this->version;
        if( !$version )
            return null;
        
        return $this->dir->go( $version );
    }
    
    var $content_value;
    var $content_depends= array();
    function content_make( ){
        $file= $this->file;
        return $file ? $file->content : null;
    }
    function content_store( $content ){
        so_content::ensure( $content );
        if( (string) $content == $this->content )
            return $content;
        
        $file= $this->dir->createUniq( $content->extension );
        $file->content= (string) $content;
        $this->version= $file->name;
        
        return $content;
    }
    
    var $uri_value;
    function uri_make(){
        return $this->dir->go( $this->version )->uri;
    }
    
    function append( $value ){
        
        if( $this->file ):
            $this->file->append( $value );
            unset( $this->content );
        else:
            $this->content= $value;
        endif;
        
        return $this;
    }
    
}
