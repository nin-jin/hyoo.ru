<?php require_once( __DIR__ . '/so/autoload/so_autoload.php' );

ini_set( 'html_errors', 0 );
ini_set( 'display_errors', 1 );

//include_once( __DIR__ . '/so/-mix/compiled.php' );

so_error::monitor();

$cacheControl= &$_SERVER[ 'HTTP_CACHE_CONTROL' ];
$origin= &$_SERVER[ 'HTTP_ORIGIN' ];
if( !$origin && $cacheControl == 'no-cache' ):
    pms_compiler::make()
        ->package( 'so' )
        ->clean()
        ->compile()
        ->minify()
        //->bundle()
    ;
endif;

so_clientHttp::make()->run();
