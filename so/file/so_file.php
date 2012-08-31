<?php

class so_file
extends so_meta
{

    static $all= array();
    
    static function make( $path ){
        if( !preg_match( '~^([^/\\\\:]+:|[/\\\\])~', $path ) ):
            $path= dirname( dirname( __DIR__ ) ) . '/' . $path;
        endif;
        
        $path= strtr( $path, array( '\\' => '/' ) );
        
        while( true ):
            $last= $path;
            $path= preg_replace( '~/[^/:]+/\\.\\.~', '', $path, 1 );
            if( $path === $last ) break 1;
        endwhile;
        
        return static::$all[ $path ] ?: static::$all[ $path ]= parent::make()->path( $path );
    }
    
    protected $_path;
    function set_path( $path ){
        if( isset( $this->path ) ) throw new Exception( 'Redeclaration of $path' );
        return $path;
    }
    
    protected $_name;
    function get_name( $name ){
        if( isset( $name ) ) return $name;
        return basename( $this->path );
    }
    
    protected $_nameList;
    function get_nameList( $nameList ){
        if( isset( $nameList ) ) return $nameList;
        return explode( '.', $this->name );
    }
    
    protected $_parent;
    function get_parent( $parent ){
        if( isset( $parent ) ) return $parent;
        return so_file::make( dirname( $this->path ) . '/' );
    }
    
    protected $_exists;
    function get_exists( $exists ){
        return file_exists( $this->path );
    }
    function set_exists( $exists ){
        if( $exists ):
            if( $this->exists ) return $exists;
            $this->parent->exists= true;
            @mkdir( $this->path, 0777, true );
        else:
            @unlink( $this->path );
        endif;
        return $exists;
    }
    
    protected $_content;
    function get_content( $content ){
        if( isset( $content ) ) return $content;
        return @file_get_contents( $this->path );
    }
    function set_content( $content ){
        if( $content == $this->content ) return $content;
        $this->parent->exists= true;
        file_put_contents( $this->path, $content );
        $this->_version= null;
        return $content;
    }
    
    protected $_version;
    function get_version( $version ){
        if( isset( $version ) ) return $version;
        
        return strtoupper( base_convert( filemtime( $this->path ), 10, 36 ) );
    }
    
    protected $_childList;
    function get_childList( $childList ){
        if( isset( $childList ) ) return $childList;
        
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
    
    function __toString( ){
        return $this->path;
    }

}
