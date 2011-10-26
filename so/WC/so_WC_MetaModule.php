<?php

class so_WC_MetaModule extends so_WC_Node {
    protected $_files;
    function set_files( $files ){
        if( isset( $this->files ) ) throw new Exception( 'Redeclaration of $files' );
        return $files;
    }
    
    function selectFiles( $regexp ){
        $res= array();
        foreach( $this->files as $file ):
            if( !preg_match( $regexp, $file->name ) ) continue;
            $res[ $file->id ]= $file;
        endforeach;
        return $res;
    }
    
    protected $_dependModules;
    function get_dependModules( $depends ){
        if( isset( $depends ) ) return $depends;
        $depends= array();
        foreach( $this->files as $file ):
            $depends= array_merge( $depends, $file->dependModules );
        endforeach;
        unset( $depends[ $this->id ] );
        return $depends;
    }
    
    protected $_version;
    function get_version( $version ){
        if( isset( $version ) ) return $version;
        $version= '';
        foreach( $this->files as $file ):
            if( $file->version <= $version ) continue;
            $version= $file->version;
        endforeach;
        return $version;
    }
    
    /*protected $_content
    function get_content( $content ){
        $content= '';
        foreach( $this->files as $id => $file ):
            $content.= "// {$id}\n" . $file->content . "\n";
        endforeach;
        return $content;
    }*/
}
