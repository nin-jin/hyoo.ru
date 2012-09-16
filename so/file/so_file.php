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
        return basename( $this->path );
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
        unset( $this->childList );
        if( $exists ):
            if( $this->exists ) return $exists;
            $this->parent->exists= true;
            @mkdir( $this->path, 0777, true );
        else:
            @unlink( $this->path );
        endif;
        return $exists;
    }
    
    var $content_value;
    var $content_depends= array();
    function content_make( ){
        $path= $this->path;
        if( !is_file( $path ) ) return null;
        return file_get_contents( $path );
    }
    function content_store( $content ){
        if( isset( $this->content ) )
            if( $content == $this->content )
                return $content;
        
        $this->parent->exists= true;
        file_put_contents( $this->path, $content );
        unset( $this->version );
        return $content;
    }
    
    function append( $content ){
        $f= fopen( $this->path, 'ab' );
        if( !$f )
            throw new Exception( "Can not open file [{$this->path}]" );
        
        $count= fwrite( $f, (string) $content );
        fclose( $f );
        
        if( $count === false )
            throw new Exception( "Can not append to file [{$this->path}]" );
        
        return $this;
    }
    
    var $version_value;
    function version_make( ){
        return strtoupper( base_convert( filemtime( $this->path ), 10, 36 ) );
    }
    
    var $childList_value;
    function childList_make( ){
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
        
        return $list;
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
