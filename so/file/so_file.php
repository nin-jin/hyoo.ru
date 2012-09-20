<?php

class so_file
{
    use so_meta2;
    use so_registry;

    var $id_value;
    var $id_depends= array( 'id', 'path' );
    function id_make(){
        return $this->path;
    }
    function id_store( $path ){
        $this->path= $path;
        return $this->path;
    }

    var $path_value;
    var $path_depends= array( 'id', 'path' );
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
            $data= preg_replace( '~/[^/:]+/\\.\\.~', '', $data, 1 );
            if( $data === $last ) break 1;
        endwhile;
        
        return $data;
    }
    
    var $uri_value;
    function uri_make( ){
        $base= so_WC_Root::make()->currentPack->path;
        $path= preg_replace( '~^' . preg_quote( $base ) . '~', '', $this->path );
        return so_uri::make( $path );
    }
    
    var $name_value;
    function name_make( ){
        return $this->SplFileObject->getBasename();
    }
    
    var $nameList_value;
    function nameList_make( ){
        return explode( '.', $this->name );
    }
    
    var $parent_value;
    function parent_make( ){
        return so_file::make( dirname( $this->path ) . '/' );
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
            mkdir( $this->path, null, true );
        else:
            unlink( $this->path );
            unset( $this->child );
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
    
    var $SplFileObject_value;
    function SplFileObject_make( ){
        return new SplFileObject( $this->path, 'a+b' );
    }
    
    function append( $content ){
        $count= $this->SplFileObject->fwrite( (string) $content );
        
        if( is_null( $count ) )
            throw new Exception( "Can not append to file [{$this->path}]" );
        
        return $this;
    }
    
    var $version_value;
    function version_make( ){
        return strtoupper( base_convert( $this->SplFileObject->getMTime(), 10, 36 ) );
    }
    
    var $child_value;
    function child_make( ){
        $list= array();
        
        if( $this->exists ):
            $dir= dir( $this->path );
            while( false !== ( $file= $dir->read() ) ):
                if( $file === '.' ) continue;
                if( $file === '..' ) continue;
                $list[]= $file;
            endwhile;
            $dir->close();
        endif;
        
        natsort( $list );
        
        return so_file_collection::make()->list( $list );
    }
    
    function go( $path ){
        return so_file::make( preg_replace( '~[^/]+$~', '', $this->path ) . $path );
    }
    
    function createUniq( $prefix= '', $postfix= '' ){
        while( true ):
            $file= $this->go( uniqid( $prefix ) . $postfix );
            if( !$file->exists ) return $file;
        endwhile;
    }
    
    function __toString( ){
        return $this->path;
    }

}
