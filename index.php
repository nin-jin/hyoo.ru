<?php require_once( __DIR__ . '/so/autoload/so_autoload.php' );

foreach( parse_ini_file( __DIR__ . '/php.ini' ) as $key => $value )
    ini_set( $key, $value );

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
