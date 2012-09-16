<?php require_once( __DIR__ . '/../so/autoload/so_autoload.php' );

ini_set( 'html_errors', 0 );
ini_set( 'display_errors', 1 );

so_error::monitor();

//include_once( __DIR__ . '/so/-mix/index.php' );

$cacheControl= &$_SERVER[ 'HTTP_CACHE_CONTROL' ];
$origin= &$_SERVER[ 'HTTP_ORIGIN' ];
if( !$origin && $cacheControl == 'no-cache' ):
    new so_Compile_All;
endif;

so_clientHttp::make()->run();
