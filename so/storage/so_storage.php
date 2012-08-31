<?php

class so_storage
extends so_meta
{

    static $all= array();
    
    static function make( $key= '' ){
        return static::$all[ $key ] ?: static::$all[ $key ]= parent::make()->key( $key );
    }
    
    protected $_key;
    function set_key( $key ){
        if( isset( $this->key ) ) throw new Exception( 'Property [$key] is alredy defined' );
        return $key;
    }
    
    static $dir= '';
    protected $_dir;
    function get_dir( $dir ){
        if( isset( $dir ) ) return $dir;
        $key= md5( $this->key );
        $key= chunk_split( substr( $key, 0, 6 ), 3, '/' ) . substr( $key, 6 );
        #$key= strtr( $this->key, array( '?' => '', '=' => '/=', '+' => '/+' ) );
        return so_file::make( 'so/storage/data/' )->go( $key . '/' );
    }
    
    protected $_version;
    function get_version( $version ){
        if( isset( $version ) ) return $version;
        return (int) array_pop( $this->dir->childList );
    }
    
    protected $_content;
    function get_content( $content ){
        if( isset( $content ) ) return $content;
        return @file_get_contents( $this->dir->go( $this->version )->path );
    }
    function set_content( $content ){
        if( $content == $this->content ) return $content;
        $this->dir->go( $this->version + 1 )->content= $content;
        $this->_version= null;
        return $content;
    }
    
    protected $_uri;
    function get_uri( $uri ){
        return strtr( $this->dir->go( $this->version )->path, array( '~' => '%7E', '#' => '%23' ) );
    }
    
}
