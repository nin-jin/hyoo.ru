<?php require_once( __DIR__ . '/../so/autoload/so_autoload.php' );

if( !so_value::make( $_SERVER[ 'HTTP_ORIGIN' ] ) )
    so_compiler::start( 'hyoo' );
    
so_application::start( 'hyoo', 'dev' );
