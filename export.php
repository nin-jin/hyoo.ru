<?php require_once( __DIR__ . '/so/autoload/so_autoload.php' );

so_compiler::start();

header( 'Location: ' . so_export::start() . '/', true, 303 );