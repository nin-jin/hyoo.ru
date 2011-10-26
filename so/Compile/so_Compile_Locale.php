<?php

class so_Compile_Locale {
    function __construct( $pack, $mixModule ){
        $namePattern= '|\\.locale=(\w*)\\.xml$|';
        $fileList= $pack->selectFiles( $namePattern );
        
        $index= array();
        foreach( $fileList as $file ):
            preg_match( $namePattern, $file->name, $locale );
            $index[ $locale[1] ][ $file->id ]= $file;
        endforeach;
        
        $xstyle= new so_XStyle;
        
        foreach( $index as $locale => $fileList ):
            
            $indexStruct= array();
            foreach( $fileList as $file ):
                $indexStruct[]=  array( '#comment' => $file->id );
                $indexStruct[]=  DOMDocument::load( $file->path )->documentElement;
            endforeach;
            
            $indexDOM= $xstyle->aDocument(array(
                'locale:list' => array(
                    '@xmlns:locale' => 'https://github.com/nin-jin/locale',
                    $indexStruct,
                ),
            ));
            
            $indexFile= $mixModule->createFile( "index.locale={$locale}.xml" );
            $indexFile->content= $indexDOM->saveXML();
            
        endforeach;
        
    }
}
