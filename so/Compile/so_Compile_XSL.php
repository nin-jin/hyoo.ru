<?php

class so_Compile_XSL {
    function __construct( $pack, $mixModule ){
        $fileList= $mixModule->root->createPack( 'so' )->createModule( 'XStyle' )->selectFiles( '|\\.xsl$|' );
        $fileList= array_merge( $fileList, $pack->selectFiles( '|\\.xsl$|' ) );
        
        $index= array();
        foreach( $fileList as $file ):
            $index[]= array(
                'xsl:include' => array(
                    '@href' => "../../{$file->id}?{$file->version}" 
                ),
            );
        endforeach;
        
        $index= so_dom::make()->append( array(
            'xsl:stylesheet' => array(
                '@version' => '1.0',
                '@xmlns:xsl' => 'http://www.w3.org/1999/XSL/Transform',
                $index,
            ),
        ) );

        $mixModule->createFile( 'index.xsl' )->content= $index;

        $compiled= so_dom::make( '<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml" />' );
        
        foreach( $fileList as $file ):
            $docEl= DOMDocument::load( $file->path )->documentElement;
            $prefix= $file->pack->name;
            $ns= $docEl->lookupNamespaceURI( $prefix );
            if( $ns ):
                $compiled[ '@xmlns:' . $prefix ]= $ns;
            endif;
            $compiled[]= array(
                '#comment' => " {$file->id} ",
                $docEl->childNodes,
            );
        endforeach;
        
        
        $mixModule->createFile( 'compiled.xsl' )->content= $compiled;
        
        $minified= new DOMDOcument();
        $minified->formatOutput= false;
        $minified->preserveWhiteSpace= false;
        $minified->loadXML( $compiled );
        $mixModule->createFile( 'min.xsl' )->content= $minified->C14N();
    }
}
