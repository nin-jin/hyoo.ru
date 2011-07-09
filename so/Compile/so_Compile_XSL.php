<?php

class so_Compile_XSL {
    function __construct( $pack, $mixModule ){
        $fileList= $mixModule->root->createPack( 'so' )->createModule( 'XStyle' )->filesByExt( 'xsl' );
        $fileList= array_merge( $fileList, $pack->filesByExt( 'xsl' ) );
                    
        $xstyle= new so_XStyle;

        $index= array();
        foreach( $fileList as $file ):
            $index[]= array(
                'import' => array(
                    '@href' => "../../{$file->id}?{$file->version}" 
                ),
            );
        endforeach;
        
        $index= $xstyle->aDocument(array(
            'stylesheet' => array(
                '@version' => '1.0',
                '@xmlns' => 'http://www.w3.org/1999/XSL/Transform',
                $index,
            ),
        ));

        $mixModule->createFile( 'index.xsl' )->content= $index->saveXML();

        $compiled= array();
        foreach( $fileList as $file ):
            $docEl= DOMDocument::load( $file->path )->documentElement;
            $nsName= 'xmlns:'.$file->pack->name;
            $nsValue= $docEl->getAttribute( $nsName );
            if( $nsValue ) $compiled[ '@' . $nsName ]= $nsValue;
            $compiled[]= array(
                '#comment' => " {$file->id} ",
                $docEl->childNodes,
            );
        endforeach;
        
        $compiled= $xstyle->aDocument(array(
            'stylesheet' => array(
                '@version' => '1.0',
                '@xmlns' => 'http://www.w3.org/1999/XSL/Transform',
                '@xmlns:html' => 'http://www.w3.org/1999/xhtml',
                '#text' => "\n\n",
                $compiled,
            ),
        ));
        
        $mixModule->createFile( 'compiled.xsl' )->content= $compiled->saveXML();
    }
}