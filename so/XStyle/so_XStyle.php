<?php

class so_XStyle
{
    use so_meta;

    protected $_dir;
    function get_dir( $dir ){
        if( isset( $dir ) ) return $dir;
        return dirname( $this->pathXSL );
    }

    protected $_pathXS;
    function set_pathXS( $pathXS ){
        if( isset( $this->pathXSL ) or isset( $this->pathXS ) ) throw new Exception( 'Redeclaration of $pathXS' );
        return realpath( $pathXS );
    }
    
    protected $_pathXSL;
    function get_pathXSL( $pathXSL ){
        if( isset( $pathXSL ) ) return $pathXSL;
        return preg_replace( '!\.xs$!i', '.xsl', $this->pathXS );
    }
    function set_pathXSL( $pathXSL ){
        if( isset( $this->pathXSL ) or isset( $this->pathXS ) ) throw new Exception( 'Redeclaration of $pathXSL' );
        return realpath( $pathXSL );
    }

    protected $_docXS;
    function get_docXS( $docXS ){
        if( isset( $docXS ) ) return $docXS;
        $docXS= new DOMDocument( '1.0', 'utf-8' );
        if( file_exists( $this->pathXS ) ) $docXS->load( $this->pathXS, LIBXML_COMPACT );
        return $docXS;
    }
    function set_docXS( $docXS ){
        $docXS= so_dom::make( $docXS );
        if( file_exists( $this->pathXS ) ) unlink( $this->pathXS );
        file_put_contents( $this->pathXS, $docXS );
        return null;
    }

    protected $_docXSL;
    function get_docXSL( $docXSL ){
        if( isset( $docXSL ) ) return $docXSL;
        if( file_exists( $this->pathXS ) ) $this->sync();
        $dir= getcwd();
        chdir( $this->dir );
        $docXSL= so_dom::make( file_get_contents( $this->pathXSL ) );
        chdir( $dir );
        return $docXSL;
    }
    function set_docXSL( $docXSL ){
        $docXSL= so_dom::create( $docXSL );
        if( file_exists( $this->pathXSL ) ) unlink( $this->pathXSL );
        file_put_contents( $this->pathXSL, $docXSL );
        return null;
    }

    protected $_processor;
    function get_processor( $processor ){
        if( isset( $processor ) ) return $processor;
        $processor= new XSLTProcessor( );
        $processor->importStyleSheet( $this->docXSL->DOMNode );
        return $processor;
    }

    function process( $doc ){
        $doc= so_dom::make( $doc );
        
        $doc= $this->processor->transformToDoc( $doc->DOMNode );
        
        return so_dom::make( $doc );
    }

    function sync( ){
        if( !file_exists( $this->pathXS ) ) return $this;
        if
        (    file_exists( $this->pathXSL )
        &&  ( filemtime( $this->pathXS ) === fileatime( $this->pathXS ) )
        ) return $this;

        $xs2xsl= new $this;
        $xs2xsl->pathXSL= __DIR__ . '/compiler/so_XStyle_compiler.xsl';
        $this->docXSL= $xs2xsl->process( $this->docXS );
        $this->docXS= $this->docXS;
        return $this;
    }

}
