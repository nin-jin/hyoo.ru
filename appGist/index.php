<?php require_once( __DIR__ . '/../so/autoload/so_autoload.php' );

if( !so_value::make( $_SERVER[ 'HTTP_ORIGIN' ] ) )
    so_compiler::make()->package( 'appGist' )->clean()->compile()->minify(); //->bundle();

so_front::start( 'appGist' );
