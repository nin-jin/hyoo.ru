<?php

class so_Compile_All {
    function __construct(){        
        $root= so_WC_Root::make();
        $docModules= $root->createPack( 'doc' )->index->modules;
        $packs= $root->packs;
        //$packs= array( $root->createPack( 'so' ) );
        foreach( $packs as $pack ):
            $srcPack= $pack->index;
            $docPack= new so_WC_MetaPack;
            $docPack->modules= array_merge( $docModules, $srcPack->modules );
            
            new so_Compile_JS( $srcPack, $pack->mixModule );
            new so_Compile_JS( $docPack, $pack->mixDocModule );
            
            new so_Compile_CSS( $srcPack, $pack->mixModule );
            new so_Compile_CSS( $docPack, $pack->mixDocModule );
            
            new so_Compile_XSL( $srcPack, $pack->mixModule );
            new so_Compile_XSL( $docPack, $pack->mixDocModule );
            
            new so_Compile_XML( $srcPack, $pack->mixModule );
            new so_Compile_XML( $docPack, $pack->mixDocModule );

            new so_Compile_Locale( $srcPack, $pack->mixModule );
            new so_Compile_Locale( $docPack, $pack->mixDocModule );

            new so_Compile_PHP( $srcPack, $pack->mixModule );
            new so_Compile_PHP( $docPack, $pack->mixDocModule );

            # new so_Compile_Other( $srcPack, $pack->mixModule );
            # new so_Compile_Other( $docPack, $pack->mixDocModule );
            
            
            $names= array();
            $minName= 'A';
            
            $fileList= array(
                $pack->mixModule->createFile( 'min.xsl' ),
                $pack->mixModule->createFile( 'min.css' ),
                $pack->mixModule->createFile( 'min.js' ),
                $pack->mixModule->createFile( 'min.php' ),
                $pack->mixDocModule->createFile( 'min.xsl' ),
                $pack->mixDocModule->createFile( 'min.css' ),
                $pack->mixDocModule->createFile( 'min.js' ),
                $pack->mixDocModule->createFile( 'min.php' ),
            );
            
            $replacer= function( $matches ) use( &$names, &$minName ) {
                list( $str, $prefix, $bs, $localName )= $matches;
                $name= &$names[ $prefix . ':' . $localName ];
                if( !$name ) $name= $minName++;
                return $prefix . $bs . ':' . $name;
            };
            foreach( $fileList as $file ):
                $minified= preg_replace_callback( '/\b(wc|lang)(\\\\?):([a-z0-9_-]+)/i', $replacer, $file->content );
                $file->content= $minified;
            endforeach;
            
            $replacer= function( $matches ) use( &$names, &$minName ) {
                list( $str, $prefix, $localName )= $matches;
                $name= &$names[ $prefix . ':' . $localName ];
                if( !$name ) $name= $minName++;
                return '$' . $prefix . '.' . $name;
            };
            foreach( $fileList as $file ):
                $minified= preg_replace_callback( '/\$(wc|jam|lang)\.([a-z0-9_-]+)/i', $replacer, $file->content );
                $file->content= $minified;
            endforeach;
            
            foreach( $fileList as $file ):
                $file->module->createFile( $file->name . '.gz' )->content= gzencode( $file->content, 9 );
            endforeach;
            
            $registry= so_dom::make( '<so_compile_name-list xmlns:so="https://github.com/nin-jin/so" />' );
            foreach( $names as $orig => $min ):
                $registry[]= array( 'so_compile_name' => array( '@orig' => $orig, '@min' => 'm:' . $min ) );
            endforeach;
            $pack->mixModule->createFile( 'names.xml' )->content= $registry;
            
            //$pack->mixModule->createFile( 'index.xsl' )->content= $pack->mixModule->createFile( 'min.xsl' )->content;
            //$pack->mixModule->createFile( 'index.css' )->content= $pack->mixModule->createFile( 'min.css' )->content;
            //$pack->mixModule->createFile( 'index.js' )->content= $pack->mixModule->createFile( 'min.js' )->content;
            //$pack->mixModule->createFile( 'index.php' )->content= $pack->mixModule->createFile( 'min.php' )->content;
        
        endforeach;
    }
}
