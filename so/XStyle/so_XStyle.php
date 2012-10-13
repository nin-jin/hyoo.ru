<?php

class so_XStyle
{
    use so_meta;
    use so_factory;

    var $dir_value;
    function dir_make( ){
        return dirname( $this->pathXSL );
    }

    var $pathXS_value;
    var $pathXS_depends= array( 'pathXS', 'pathXSL' );
    function pathXS_store( $pathXS ){
        return realpath( $pathXS );
    }
    
    var $pathXSL_value;
    var $pathXSL_depends= array( 'pathXS', 'pathXSL' );
    function pathXSL_make( ){
        return preg_replace( '!\.xs$!i', '.xsl', $this->pathXS );
    }
    function pathXSL_store( $pathXSL ){
        return realpath( $pathXSL );
    }

    var $docXS_value;
    function docXS_make( ){
        $docXS= new \DOMDocument( '1.0', 'utf-8' );
        if( file_exists( $this->pathXS ) ) $docXS->load( $this->pathXS, LIBXML_COMPACT );
        return $docXS;
    }
    function docXS_store( $docXS ){
        $docXS= so_dom::make( $docXS );
        if( file_exists( $this->pathXS ) ) unlink( $this->pathXS );
        file_put_contents( $this->pathXS, $docXS );
        return null;
    }

    var $docXSL_value;
    function docXSL_make( ){
        if( file_exists( $this->pathXS ) ) $this->sync();
        $docXSL= so_dom::make( so_file::make( $this->pathXSL ) );
        return $docXSL;
    }
    function docXSL_store( $docXSL ){
        $docXSL= so_dom::create( $docXSL );
        if( file_exists( $this->pathXSL ) ) unlink( $this->pathXSL );
        file_put_contents( $this->pathXSL, $docXSL );
        return null;
    }

    var $processor_value;
    function processor_make(){
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
