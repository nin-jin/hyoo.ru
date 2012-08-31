<?php

class so_Compile_PHP {
    function __construct( $pack, $mixModule ){
        $files= $pack->selectFiles( '|\\.php$|' );
        
        $indexFile= $mixModule->createFile( 'index.php' );
        $compiledFile= $mixModule->createFile( 'compiled.php' );
        $minFile= $mixModule->createFile( 'min.php' );
        
        if( !count( $files ) ):
            $indexFile->exists= false;
            $compiledFile->exists= false;
            return;
        endif;
        
        $indexFile->content= "<?php require_once( dirname( dirname( __DIR__ ) ) . '/so/autoload/so_autoload.php' );\n";
        
        $compiled= "<?php \n";
        foreach( $files as $file ):
            $compiled.= "# {$file->id}\n" . substr( $file->content, 6 ). "\n\n\n";
        endforeach;
        $compiledFile->content= $compiled;
        
        $minified= $compiled;
        $mixModule->createFile( 'min.php' )->content= $minified;
        
    }
}
