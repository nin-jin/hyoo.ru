<?php

class so_Compile_CSS {
    function __construct( $pack, $mixModule ){
        $files= $pack->selectFiles( '|\\.css$|' );
        
        $indexFile= $mixModule->createFile( 'index.css' );
        if( count( $files ) > 32 ):
            $pages= array();
            foreach( array_values( $files ) as $index => $file ):
                $pageNumb= floor( $index / 30 );
                $pages[ $pageNumb ][]= $file;
            endforeach;
            $indexContent= '';
            foreach( $pages as $pageNumb => $page ):
                $pageFile= $mixModule->createFile( "page_{$pageNumb}.css" );
                $pageContent= '';
                foreach( $page as $file ):
                    $pageContent.= "@import url( '../../{$file->id}?{$file->version}' );\n";
                endforeach;
                $pageFile->content= $pageContent;
                $indexContent.= "@import url( '../../{$pageFile->id}?{$pageFile->version}' );\n";
            endforeach;
            $indexFile->content= $indexContent;
        else:
            $content= '';
            foreach( $files as $id => $file ):
                $content.= "@import url( '../../{$file->id}?{$file->version}' );\n";
            endforeach;
            $indexFile->content= $content;
        endif;
        
        $head= '';
        $content= '';
        foreach( $files as $file ):
            preg_match_all
            (   '/^\s*@namespace .*$/m'
            ,   $file->content
            ,   $namespaceList
            );
            $head.= implode( "\n", $namespaceList[0] );
            $content.= "/* @import url( '../../{$file->id}' ); */\n{$file->content}\n";
        endforeach;
        $compiled= $head . $content;
        $mixModule->createFile( 'compiled.css' )->content= $compiled;
        
        $minified= $compiled;
        $minified= preg_replace( '~/\\*[\w\W]*?\\*/~', '', $minified );
        $minified= preg_replace( '~^\s+~m', '', $minified );
        $minified= preg_replace( '~[\r\n]~', '', $minified );
        
        //$replacer= function( $matches ) use( $mixModule ) {
        //    list( $str, $prefix, $url, $postfix )= $matches;
        //    $file= so_file::make( $mixModule->path . '/' )->go( $url );
        //    $type= image_type_to_mime_type( exif_imagetype( $file ) );
        //    return $prefix . 'data:' . $type . ';base64,' . base64_encode( $file->content ) . $postfix;
        //};
        //$minified= preg_replace_callback( "~(url\(\s*')([^:]+(?:/|$).*?)('\s*\))~", $replacer, $minified );
            
        $mixModule->createFile( 'min.css' )->content= $minified;
    }
}
