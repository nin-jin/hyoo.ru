<?php

class so_Compile_XML {
    function __construct( $packSource, $mixModule ){
        $xmlIndex = $mixModule->createFile( 'index.doc.xml' );

        #if( $packSource->version === $xmlIndex->version ) return;
        
        $index= array();

        foreach( $mixModule->root->packs as $pack ):
            $mainFile= $pack->mainFile;
            if( !$mainFile->exists ) continue;
            
            if( $pack->id === $mixModule->pack->id ):
                $fileList= array();
                
                foreach( $pack->selectFiles( '|\\.doc\\.xml$|' ) as $file ):
                    $fileList[]= array(
                        'file' => array(
                            'link' => "../../{$file->id}?{$file->version}",
                            'title' => DOMDocument::load( $file->path )->getElementsByTagName( 'h1' )->item(0)->nodeValue,
                        ),
                    );
                endforeach;
            else:
                $fileList= array(
                    'file' => array(
                        'link' => "../../{$mainFile->id}?{$mainFile->version}",
                        'title' => DOMDocument::load( $mainFile->path )->getElementsByTagName( 'h1' )->item(0)->nodeValue,
                    ),
                );
            endif;
            $index[]= array( 'pack' => $fileList );
        endforeach;
        
        $xstyle= new so_XStyle;

        $index= $xstyle->aDocument(array(
            'root' => array(
                '@xmlns' => 'https://github.com/nin-jin/doc',
                $index,
            ),
        ));
    
        $xmlIndex->content= $index->saveXML();
    }
}
