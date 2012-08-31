<?php

class so_Compile_JS {
    function __construct( $pack, $mixModule ){
        $files= $pack->selectFiles( '|(?:\\.jam)?\\.js$|' );
        
        $indexFile= $mixModule->createFile( 'index.js' );
        $indexPath= '/' . $indexFile->id;
        if( count( $files ) ):
            $tpl= new so_Compile_JS_Index;
            $tpl->param= compact( 'indexPath', 'files' );
            $indexFile->content= $tpl->content;
        else:
            $indexFile->exists= false;
        endif;
        
        $compiled= '';
        foreach( $files as $file ):
            $compiled.= ";// {$file->id}\n" . $file->content . "\n";
        endforeach;
        $mixModule->createFile( 'compiled.js' )->content= $compiled;

        $minified= $compiled;
        $minified= preg_replace( '~/\\*[\w\W]*?\\*/~', '', $minified );
        $minified= preg_replace( '~^\s+~m', '', $minified );
        $minified= preg_replace( '~;[\r\n]~', ';', $minified );
        $minified= preg_replace( '~//.*?$\n~m', '', $minified );
        $mixModule->createFile( 'min.js' )->content= $minified;
        
    }
}
