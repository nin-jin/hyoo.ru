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
                    if( $file[0] == "." || $file[0] == "-" )
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

copy_r('.', '-export');
rchdir( '-export' );
header( 'Location: -export/', true, 302 );
virtual( 'index.php' );
	unlink( 'export.php' );
    unlink( 'index.php' );
    foreach( glob( '*', GLOB_ONLYDIR ) as $pack ):
		if( $pack[0] === '-' ) continue;
        rchdir( $pack );
			unlink( '.gitignore' );
            rchdir( '-mix' );
                @copy( 'compiled.css', 'index.css' );
                @copy( 'compiled.xsl', 'index.xsl' );
                @copy( 'compiled.js', 'index.js' );
            rchdir( '..' );
            rchdir( '-mix+doc' );
                @copy( 'compiled.css', 'index.css' );
                @copy( 'compiled.xsl', 'index.xsl' );
                @copy( 'compiled.js', 'index.js' );
            rchdir( '..' );
            foreach( glob( '*', GLOB_ONLYDIR ) as $module ):
			    if( $module[0] === '-' ) continue;
                rchdir( $module );
					unlink( '.gitignore' );
                    foreach( glob( '*.tree' ) as $file ):
                        unlink( $file );
                    endforeach;
                    foreach( glob( '*.css' ) as $file ):
                        unlink( $file );
                    endforeach;
                    foreach( glob( '*.jam' ) as $file ):
                        unlink( $file );
                    endforeach;
                    foreach( glob( '*.js' ) as $file ):
                        unlink( $file );
                    endforeach;
                    foreach( glob( '*.vml' ) as $file ):
                        unlink( $file );
                    endforeach;
                    foreach( glob( '*.xsl' ) as $file ):
                        unlink( $file );
                    endforeach;
                rchdir( '..' );
                @rmdir( $module );
            endforeach;
        rchdir( '..' );
        @rmdir( $pack );
    endforeach;
rchdir( '..' );
