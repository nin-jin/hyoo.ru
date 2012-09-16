<?php

class so_storage
{
    use so_meta2;
    use so_registry;

    var $id_prop= array( );
    function id_store( $id ){
        return (string) $id;
    }
    
    var $dir_prop= array( );
    function dir_make( ){
        $id= md5( $this->id );
        $id= substr( $id, 0, 3 ) . '/' . substr( $id, 4 );
        #$id= strtr( $this->id, array( '?' => '', '=' => '/=', '+' => '/+' ) );
        return so_file::make( 'so/storage/data/' )->go( $id . '/' );
    }
    
    var $index_prop= array( );
    function index_make( ){
        return $this->dir->go( 'index.txt' );
    }
    
    var $version_prop= array(
        'depends' => array( ),
    );
    function version_make( ){
        preg_match( '~= ([^\n]*)\n?$~', $this->index->content, $found );
        
        if( !$found )
            return null;
        
        return $found[1];
    }
    function version_store( $version ){
        $version= $version;
        $this->index->append( '= ' . $version . "\n" );
        unset( $this->file );
        return $version;
    }
    
    var $file_prop= array();
    function file_make( ){
        $version= $this->version;
        if( !$version )
            return null;
        
        return $this->dir->go( $version );
    }
    
    var $content_prop= array(
        'depends' => array( ),
    );
    function content_make( ){
        $file= $this->file;
        return $file ? $file->content : null;
    }
    function content_store( $content ){
        if( $content == $this->content )
            return $content;
        
        $file= $this->dir->createUniq();
        $file->content= (string) $content;
        $this->version= $file->name;
        
        return $content;
    }
    
    //protected $_uri;
    //function get_uri( $uri ){
    //    return strtr( $this->dir->go( $this->version )->path, array( '~' => '%7E', '#' => '%23' ) );
    //}
    
}
