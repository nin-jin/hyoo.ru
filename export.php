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
    foreach( glob( '*.*' ) as $file ):
        if( !preg_match( '/\.(php|cmd)$/', $file ) ) continue;
        unlink( $file );
    endforeach;
    foreach( glob( '*', GLOB_ONLYDIR ) as $pack ):
        if( $pack[0] === '-' ) continue;
        rchdir( $pack );
            foreach( glob( '*.*' ) as $file ):
                if( !preg_match( '/\.(cmd)$/', $file ) ) continue;
                unlink( $file );
            endforeach;
            foreach( glob( '*', GLOB_ONLYDIR ) as $module ):
                if( in_array( $module, array( '-mix', '-mix+doc' ) ) ):
                    rchdir( $module );
                        @unlink( 'index.css' );
                        @unlink( 'index.xsl' );
                        @unlink( 'index.js' );
                        @unlink( 'index.php' );
                        @rename( 'compiled.css', 'index.css' );
                        @rename( 'compiled.xsl', 'index.xsl' );
                        @rename( 'compiled.js', 'index.js' );
                        @rename( 'compiled.php', 'index.php' );
                        foreach( glob( '*.*' ) as $file ):
                            if( in_array( $file, array( 'index.css', 'index.js', 'index.xsl', 'index.php', 'index.doc.xml' ) ) ) continue;
                            unlink( $file );
                        endforeach;
                    rchdir( '..' );
                    continue;
                endif;
                rchdir( $module );
                    foreach( glob( '*.*' ) as $file ):
                        if( !preg_match( '/\.(css|xsl|js|jam|tree|cmd|php)$/', $file ) ) continue;
                        unlink( $file );
                    endforeach;
                rchdir( '..' );
                if( !glob( $module . '/*' ) ):
                    rrmdir( $module );
                endif;
            endforeach;
        rchdir( '..' );
    endforeach;
rchdir( '..' );
@header( 'Location: -export/', true, 302 );
