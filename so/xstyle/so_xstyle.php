<?php

class so_xstyle
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
    var $docXS_depends= array();
    function docXS_make( ){
        $docXS= so_dom::make( so_file::make( $this->pathXS ) );
        return $docXS;
    }
    function docXS_store( $docXS ){
        $docXS= so_dom::make( $docXS );
        if( !$this->pathXS )
            return $docXS;
        
        if( $this->pathXS )
            file_put_contents( $this->pathXS, $docXS );
        
        return $docXS;
    }

    var $docXSL_value;
    var $docXSL_depends= array();
    function docXSL_make( ){
        $docXSL= so_dom::make( so_file::make( $this->pathXSL ) );
        return $docXSL;
    }
    function docXSL_store( $docXSL ){
        $docXSL= so_dom::make( $docXSL );
        if( !$this->pathXSL )
            return $docXSL;
        
        if( $this->pathXSL )
            file_put_contents( $this->pathXSL, $docXSL );
        
        return $docXSL;
    }

    var $processor_value;
    function processor_make(){
        $processor= new \XSLTProcessor( );
        $processor->importStyleSheet( $this->docXSL->DOMNode );
        return $processor;
    }

    function process( $doc ){
        $doc= so_dom::make( $doc );
        
        $doc= $this->processor->transformToDoc( $doc->DOMNode );
        
        return so_dom::make( $doc );
    }

    function sync( ){
        if( ( filemtime( $this->pathXS ) - filemtime( $this->pathXSL ) ) > 1 ):
            $this->docXSL= so_xstyle_compiler::make()->process( $this->docXS );
        #    $this->docXS= $this->docXS;
        #elseif( ( filemtime( $this->pathXSL ) - filemtime( $this->pathXS ) ) > 1 ):
        #    echo '<!--xsl2xs-->';
        #    $xsl2xs= new $this;
        #    $xsl2xs->pathXSL= __DIR__ . '/so_xstyle_compiler/so_xstyle_xsl2xs.xsl';
        #    $this->docXS= $xsl2xs->process( $this->docXSL );
        #    $this->docXSL= $this->docXSL;
        endif;
        return $this;
    }

}
