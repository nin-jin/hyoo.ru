<?php require_once( __DIR__ . '/so/autoload/so_autoload.php' );

//include_once( __DIR__ . '/so/-mix/index.php' );

new so_Compile_All;

so_front::make()->namespace( 'so' )->run();
