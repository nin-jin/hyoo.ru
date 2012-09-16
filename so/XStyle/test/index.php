<?php

header( 'content-type:application/xml' );

require_once( '../../../so/autoload/so_autoload.php' );

$xstyle= new so_XStyle;
$xstyle->pathXS= __DIR__ . '/test.xs';
$xsl= $xstyle->sync()->docXSL;

echo $xsl;
