<?php

class so_file
implements ArrayAccess
{
    use so_meta;
    
    use so_registry;
    static $id_prop= 'path';
    
    var $path_value;
    function path_make(){
        throw new Exception( 'Property [path] is not defined' );
    }
    function path_store( $data ){
        if( !preg_match( '~^([^/\\\\:]+:|[/\\\\])~', $data ) ):
            $data= dirname( dirname( __DIR__ ) ) . '/' . $data;
        endif;
        
        $data= strtr( $data, array( '\\' => '/' ) );
        
        while( true ):
            $last= $data;
            $data= preg_replace( '~/[^/:]+/\\.\\.(?=/|$)~', '', $data, 1 );
            if( $data === $last ) break 1;
        endwhile;
        
        return $data;
    }
    
    var $uri_value;
    function uri_make( ){
        $uri= so_uri::makeInstance();
        $uri->path= $this->relate( so_root::make()->dir );
        $uri->queryString= $this->version;
        return $uri->primary();
    }
    
    var $name_value;
    function name_make( ){
        return $this->SplFileInfo->getBasename();
    }
    
    var $extension_value;
    function extension_make( ){
        return $this->SplFileInfo->getExtension();
    }
    
    var $nameList_value;
    function nameList_make( ){
        return explode( '.', $this->name );
    }
    
    var $type_value;
    function type_make( ){
        return $this->SplFileInfo->getType();
    }
    
    var $parent_value;
    function parent_make( ){
        return so_file::make( dirname( $this->path ) );
    }
    
    var $exists_value;
    var $exists_depends= array();
    function exists_make( ){
        return file_exists( $this->path );
    }
    function exists_store( $exists ){
        if( $exists ):
            if( $this->exists ) return $exists;
            $this->parent->exists= true;
            mkdir( $this->path, 0777, true );
        else:
            unlink( $this->path );
            unset( $this->childs );
        endif;
        return $exists;
    }
    
    var $content_value;
    var $content_depends= array();
    function content_make( ){
        $path= $this->path;
        if( !is_file( $path ) ) return null;
        $SplFileObject= $this->SplFileObject;
        $SplFileObject->rewind();
        
        $content= '';
        foreach( $SplFileObject as $line )
            $content.= $line;
        
        return $content;
    }
    function content_store( $content ){
        if( isset( $this->content ) )
            if( $content == $this->content )
                return $content;
        
        $this->parent->exists= true;
        
        $lock= $this->lock;
        $this->lock= true;
            $SplFileObject= $this->SplFileObject;
            $SplFileObject->ftruncate( 0 );
            $SplFileObject->fwrite( $content );
            $SplFileObject->fflush();
        $this->lock= $lock;
        
        unset( $this->version );
        unset( $this->exists );
        return $content;
    }
    
    var $lock_value= false;
    var $lock_depends= array();
    function lock_store( $value ){
        if( $value == $this->lock )
            return $value;
        
        $this->SplFileObject->flock( $value ? LOCK_EX : LOCK_UN );
        
        return $value;
    }
    
    var $SplFileInfo_value;
    function SplFileInfo_make( ){
        return new SplFileInfo( $this->path );
    }
    
    var $SplFileObject_value;
    function SplFileObject_make( ){
        return new SplFileObject( $this->path, 'a+b' );
    }
    
    function append( $content ){
        $count= $this->SplFileObject->fwrite( (string) $content );
        
        if( is_null( $count ) )
            throw new Exception( "Can not append to file [{$this->path}]" );
        
        unset( $this->version );
        
        return $this;
    }
    
    var $version_value;
    function version_make( ){
        if( !$this->exists )
            return '';
        return strtoupper( base_convert( $this->SplFileInfo->getMTime(), 10, 36 ) );
    }
    
    var $childs_value;
    function childs_make( ){
        $list= array();
        
        if( $this->exists ):
            $dir= dir( $this->path );
            while( false !== ( $file= $dir->read() ) ):
                if( $file[ 0 ] == '.' ) continue;
                if( $file[ 0 ] == '-' ) continue;
                $list[]= $this->path . '/' . $file;
            endwhile;
            $dir->close();
        endif;
        
        natsort( $list );
        
        return so_file_collection::make( $list );
    }
    
    function relate( $base ){
        $base= explode( '/', (string) so_file::make( $base ) );
        $path= explode( '/', $this->path );
        
        while( isset( $base[ 0 ] ) ):
            if( $base[ 0 ] != $path[ 0 ] ) break;
            array_shift( $base );
            array_shift( $path );
        endwhile;
        
        $path= implode( '/', $path );
        
        if( count( $base ) )
            $path= str_repeat( '../', count( $base ) ) . $path;
        
        return $path;
    }
    
    function go( $path ){
        return so_file::make( rtrim( $this->path, '/' ) . '/' . $path );
    }
    
    function createUniq( $prefix= '', $postfix= '' ){
        while( true ):
            $file= $this->go( uniqid( $prefix ) . $postfix );
            if( !$file->exists ) return $file;
        endwhile;
    }
    
    function move( $target ){
        if( !$this->exists )
            return $this;
            
        so_file::ensure( $target );
        $target->parent->exists= true;
        
        if( false === rename( (string) $this, (string) $target ) )
            throw new Exception( "Can not copy [{$this}] to [{$target}]" );
        
        unset( $this->version );
        unset( $this->exists );
        unset( $this->content );
        unset( $target->version );
        unset( $target->exists );
        unset( $target->content );
        
        return $target;
    }
    
    function copy( $target ){
        if( !$this->exists )
            return $this;
            
        so_file::ensure( $target );
        $target->parent->exists= true;
        
        if( false === copy( (string) $this, (string) $target ) )
            throw new Exception( "Can not copy [{$this}] to [{$target}]" );
        
        unset( $target->version );
        unset( $target->exists );
        unset( $target->content );
        
        return $target;
    }
    
    function _string_meta( ){
        return $this->path;
    }

    function offsetExists( $name ){
        return $this->go( $name )->exists;
    }
    
    function offsetGet( $name ){
        return $this->go( $name );
    }

    function offsetSet( $name, $value ){
        throw new Exception( "Not implemented" );
    }

    function offsetUnset( $name ){
        throw new Exception( "Not implemented" );
    }

}
