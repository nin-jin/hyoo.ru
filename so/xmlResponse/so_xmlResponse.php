<?php

class so_xmlResponse
extends so_meta
{

    public $type= 'application/xml';
    public $encoding= 'utf-8';
    public $status= '';
    public $location= '';

    protected $_content;
    function get_content( $content ){
        if( isset( $content ) ) return $content;
        
        $response= array();
        
        $root= new so_WC_Root;
        $mix= $root->createPack( 'so' )->createModule( '-mix' );
        
        $response[ '!DOCTYPE' ]= array( 'name' => 'html' );
        
        $response[ '?xml-stylesheet' ]= array(
            'href' => 'so/-mix/index.xsl?' . $mix->createFile( 'index.xsl' )->version,
            'type' =>'text/xsl',
        );
        
        $response[ 'html' ]= array(
            '@xmlns' => 'http://www.w3.org/1999/xhtml',
            '@xmlns:html' => 'http://www.w3.org/1999/xhtml',
            '@xmlns:so' => 'https://github.com/nin-jin/so',
            '@xmlns:wc' => 'https://github.com/nin-jin/wc',
            'so:page' => array(
                'so:page_script' => 'so/-mix/index.js?' . $mix->createFile( 'index.js' )->version,
                'so:page_stylesheet' => 'so/-mix/index.css?' . $mix->createFile( 'index.css' )->version,
            ),
        );
        
        return so_dom::make()->append( $response );
    }
    
    function error( $error ){
        $this->status= 'error';
        $this->content->root['so:page']->append( array(
            'so:Error' => (string)$error,
        ) );
        return $this;
    }

    function ok( $content ){
        $this->status= 'ok';
        $this->content->root['so:page']->append( $content );
        return $this;
    }

    function missed( $content ){
        $this->status= 'missed';
        $this->content->root['so:page']->append( $content );
        return $this;
    }

    function found( $location ){
        $this->status= 'found';
        $this->location= $location;
        $this->content->root['so:page']->append( array(
            'so:found' => (string)$location,
        ) );
        return $this;
    }

    function moved( $location ){
        $this->status= 'moved';
        $this->location= $location;
        $this->content->root['so:page']->append( array(
            'so:moved' => (string)$location,
        ) );
        return $this;
    }

}
