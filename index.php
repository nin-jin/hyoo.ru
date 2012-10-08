<?php require_once( __DIR__ . '/so/autoload/so_autoload.php' );

foreach( parse_ini_file( __DIR__ . '/php.ini' ) as $key => $value )
    ini_set( $key, $value );

so_error::monitor();
so_root::$mainPackageName= 'appGist';

$cacheControl= &$_SERVER[ 'HTTP_CACHE_CONTROL' ];
$origin= &$_SERVER[ 'HTTP_ORIGIN' ];
if( !$origin && $cacheControl == 'no-cache' ):
    pms_compiler::make()
        ->package( so_root::$mainPackageName )
        ->clean()
        ->compile()
        //->minify()
        //->bundle()
    ;
endif;

so_clientHttp::make()->run();
