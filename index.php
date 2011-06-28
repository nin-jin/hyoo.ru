<?php require_once( 'so/autoload/so_autoload.php' );

$reqFile= $_SERVER[ 'QUERY_STRING' ];

if( $reqFile === basename( __FILE__ ) ):
    highlight_file( __FILE__ );
    die();
endif;

new so_Compile_All;

if( $reqFile ):
    $reqFile.= '?' . time();
else:
    $root= new so_WC_Root;
    $file= $root->createPack( 'doc' )->mainFile;
    $reqFile= $file->id . '?' . $file->version;
endif;

?>
<!doctype html>
<title>WC Auto Compiler</title>
<style> * { margin: 0; padding: 0; border: none; width: 100%; height: 100% } html, body { overflow: hidden } </style>
<iframe src="<?= $reqFile; ?>" frameborder="0"></iframe> 
