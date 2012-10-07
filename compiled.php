<?php

foreach( parse_ini_file( __DIR__ . '/php.ini' ) as $key => $value )
    ini_set( $key, $value );

require_once( __DIR__ . '/so/-mix/index.php' );

so_error::monitor();

so_clientHttp::make()->run();
