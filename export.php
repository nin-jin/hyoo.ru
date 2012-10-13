<?php require_once( __DIR__ . '/so/autoload/so_autoload.php' );

so_compiler::start();
so_export::start();

header( 'Location: -export/', true, 303 );