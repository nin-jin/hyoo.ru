<?php

function so_autoload( $class ){
    static $root;
    if( !$root ) $root= dirname( dirname( dirname( __FILE__ ) ) );
    $chunks= explode( '_', $class );
    $pack= $chunks[0];
    $module= $chunks[1];
    $path2class= "{$class}.php";
    if( $module ) $path2class= "{$module}/{$path2class}";
    include( "{$root}/{$pack}/{$path2class}" );
}

spl_autoload_register( 'so_autoload' );
