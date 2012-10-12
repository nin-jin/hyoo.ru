<?php require_once( __DIR__ . '/../so/autoload/so_autoload.php' );

so_compiler::make()->package( 'libPMS' )->clean()->compile();

echo 'libPMS is compiled!';