<?php require_once( __DIR__ . '/../so/autoload/so_autoload.php' );

if( !so_value::make( $_SERVER[ 'HTTP_ORIGIN' ] ) )
    so_compiler::start( 'appGist' );
    
so_application::start( 'appGist', 'dev' );
