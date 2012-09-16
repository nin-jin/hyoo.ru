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
                    $doc= new DOMDocument( '1.0', 'utf-8' );
                    $doc->load( $file->path );
                    $fileList[]= array(
                        'file' => array(
                            'link' => "../../{$file->id}?{$file->version}",
                            'title' => $doc->getElementsByTagName( 'h1' )->item(0)->nodeValue,
                        ),
                    );
                endforeach;
            else:
                $doc= new DOMDocument( '1.0', 'utf-8' );
                $doc->load( $mainFile->path );
                $fileList= array(
                    'file' => array(
                        'link' => "../../{$mainFile->id}?{$mainFile->version}",
                        'title' => $doc->getElementsByTagName( 'h1' )->item(0)->nodeValue,
                    ),
                );
            endif;
            $index[]= array( 'pack' => $fileList );
        endforeach;
        
        $index= so_dom::make( array(
            'root' => array(
                '@xmlns' => 'https://github.com/nin-jin/doc',
                $index,
            ),
        ) );
    
        $xmlIndex->content= $index;
    }
}
