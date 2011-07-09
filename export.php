<?php

function rrmdir($dir) { 
   if (is_dir($dir)) { 
     $objects = scandir($dir); 
     foreach ($objects as $object) { 
       if ($object != "." && $object != "..") { 
         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
       } 
     } 
     reset($objects); 
     rmdir($dir); 
   } 
 }

function rchdir($dir) {
   chdir( $dir ) or die( "Can not chdir to [{$dir}]");
}

function copy_r( $path, $dest )
{
	if( is_dir($path) )
	{
		@mkdir( $dest );
		$objects = scandir($path);
		if( sizeof($objects) > 0 )
		{
			foreach( $objects as $file )
			{
				if( $file[0] == "." || $file == "-export" )
					continue;
				// go on
				if( is_dir( $path.'/'.$file ) )
				{
					copy_r( $path.'/'.$file, $dest.'/'.$file );
				}
				else
				{
					copy( $path.'/'.$file, $dest.'/'.$file );
				}
			}
		}
		return true;
	}
	elseif( is_file($path) )
	{
		return copy($path, $dest);
	}
	else
	{
		return false;
	}
}

function pack_file( $from, $to ){
	$content= file_get_contents( $from );
	$zip= gzopen( $to, 'w9' );
	gzwrite( $zip, $content );
	gzclose( $zip );
}

copy_r('.', '-export');
rchdir( '-export' );
	unlink( 'export.php' );
    unlink( 'index.php' );
    foreach( glob( '*', GLOB_ONLYDIR ) as $pack ):
		if( $pack[0] === '-' ) continue;
        rchdir( $pack );
			foreach( array( '-mix', '-mix+doc' ) as $module ):
				rchdir( $module );
					@rename( 'index.css', '-index.css' );
					@rename( 'index.xsl', '-index.xsl' );
					@rename( 'index.js', '-index.js' );
					@rename( 'compiled.css', 'index.css' );
					@rename( 'compiled.xsl', 'index.xsl' );
					@rename( 'compiled.js', 'index.js' );
					@pack_file( 'index.css', 'index.css.gz' );
					@pack_file( 'index.xsl', 'index.xsl.gz' );
					@pack_file( 'index.js', 'index.js.gz' );
				rchdir( '..' );
			endforeach;
        rchdir( '..' );
    endforeach;
rchdir( '..' );
header( 'Location: -export/', true, 302 );
