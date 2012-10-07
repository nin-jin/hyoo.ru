<?php

header( 'Content-Type: text/plain', true );

foreach( parse_ini_file( __DIR__ . '/php.ini' ) as $key => $value )
    ini_set( $key, $value );

require_once( __DIR__ . '/so/autoload/so_autoload.php' );

$root= so_root::make()->dir;
$export= $root[ '-export' ];

$root[ 'compiled.php' ]->copy( $export[ 'index.php' ] );
$root[ '.htaccess' ]->copy( $export[ '.htaccess' ] );
$root[ 'php.ini' ]->copy( $export[ 'php.ini' ] );

foreach( so_root::make()->packages as $package ):
    foreach( $package->sources as $source ):
        $file= $source->file;
        $target= $export->go( $file->relate( $root ) );
        $file->copy( $target );
    endforeach;
    
    $mix= $package->dir[ '-mix' ];
    $target= $export[ $package->name ][ '-mix' ];
    $mix[ 'compiled.js' ]->copy( $target[ 'index.js' ] );
    $mix[ 'compiled.css' ]->copy( $target[ 'index.css' ] );
    $mix[ 'compiled.xsl' ]->copy( $target[ 'index.xsl' ] );
    $mix[ 'compiled.php' ]->copy( $target[ 'index.php' ] );
endforeach;

@header( 'Location: -export/', true, 302 );

exit();


function pack_file( $from, $to ){
    $content= file_get_contents( $from );
    $zip= gzopen( $to, 'w9' );
    gzwrite( $zip, $content );
    gzclose( $zip );
}

