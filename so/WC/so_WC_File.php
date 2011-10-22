<?php

class so_WC_File extends so_Meta {
    protected $_name;
    function set_name( $name ){
        if( isset( $this->name ) ) throw new Exception( 'Redeclaration of $name' );
        return $name;
    }
    
    protected $_id;
    function get_id( $id ){
        if( isset( $id ) ) return $id;
        return $this->module->id . '/' . $this->name;
    }
    
    protected $_path;
    function get_path( $path ){
        if( isset( $path ) ) return $path;
        return $this->module->path . '/' . $this->name;
    }
    
    protected $_ext;
    function get_ext( $ext ){
        if( isset( $ext ) ) return $ext;
        return pathinfo( $this->name, PATHINFO_EXTENSION );
    }
    
    protected $_exists;
    function get_exists( $exists ){
        return is_file( $this->path );
    }
    function set_exists( $exists ){
        if( $exists ) throw new Exception( '$exists=true is not implemented' );
        else @unlink( $this->path );
        return $exists;
    }
    
    protected $_root;
    function get_root( $root ){
        if( isset( $root ) ) return $root;
        return $this->module->root;
    }
    
    protected $_pack;
    function get_pack( $pack ){
        if( isset( $pack ) ) return $pack;
        return $this->module->pack;
    }
    function set_pack( $pack ){
        if( isset( $this->pack ) ) throw new Exception( 'Redeclaration of $pack' );
        return $pack;
    }
    
    protected $_module;
    function set_module( $module ){
        if( isset( $this->module ) ) throw new Exception( 'Redeclaration of $module' );
        return $module;
    }
    
    protected $_content;
    function get_content( $content ){
        if( isset( $content ) ) return $content;
        return @file_get_contents( $this->path );
    }
    function set_content( $content ){
        if( $content == $this->content ) return $content;
        $this->module->exists= true;
        file_put_contents( $this->path, $content );
        $this->_version= '';
        return $content;
    }
    
    protected $_version;
    function get_version( $version ){
        if( isset( $version ) ) return $version;

        return strtoupper( base_convert( filemtime( $this->path ), 10, 36 ) );
    }

    protected $_dependModules;
    function get_dependModules( $depends ){
        if( isset( $depends ) ) return $depends;
        $depends= array();
        
        if( $this->ext === 'jam' ):
            preg_match_all
            (   '/with\s*\(\s*\$(\w+)\$\s*\)/'
            ,   $this->content
            ,   &$nspaces
            ,   PREG_SET_ORDER
            );
            $donorList= array();
            if( $nspaces ):
                foreach( $nspaces as $ns ):
                    $donor= $this->root->createPack( $ns[1] );
                    if( !$donor->exists ) throw new Exception( "Undefined package [{$donor->id}] in [{$this->id}]" );
                    array_unshift( $donorList, $donor );
                endforeach;
            endif;
            
            preg_match_all
            (   '/(?:\$(\w+)\$\.)?\$(\w+)(?![\w$])/'
            ,   $this->content
            ,   &$matches
            ,   PREG_SET_ORDER
            );
            if( $matches ) foreach( $matches as $item ):
                list( $str, $packName, $moduleName )= $item;
                
                if( $packName ):
                    $pack= $this->root->createPack( $packName );
                    $module= $pack->createModule( $moduleName );
                else:
                    $module= null;
                    foreach( $donorList as $pack ):
                        $module= $pack->createModule( $moduleName );
                        if( !$module->exists ) continue;
                        break;
                    endforeach;
                endif;

                if( !$module || !$module->exists ) throw new Exception( "Module [{$module->id}] not found for [{$this->id}]" );
                $depends[ $module->id ]= $module;

                $module= $module->pack->mainModule;
                if( $module->exists ) $depends[ $module->id ]= $module;
            endforeach;
        endif;
        
        if( $this->ext === 'tree' ):
            $meta= new so_Tree;
            $meta->string= $this->content;
            foreach( $meta->get( 'include pack' ) as $packId ):
                $pack= $this->root->createPack( trim( $packId ) );
                if( !$pack->exists ) throw new Exception( "Pack [{$pack->id}] not found for [{$this->id}]" );
                $depends+= $pack->modules;
            endforeach;
            foreach( $meta->get( 'include module' ) as $moduleId ):
                $names= explode( '/', trim( $moduleId ) );
                if( !$names[ 1 ] ) array_push( $names, $this->pack->name );
                $pack= $this->root->createPack( $names[0] );
                $module= $pack->createModule( $names[1] );
                if( !$module->exists ) throw new Exception( "Module [{$module->id}] not found for [{$this->id}]" );
                $depends[ $module->id ]= $module;
            endforeach;
        endif;
        return $depends;
    }

}
