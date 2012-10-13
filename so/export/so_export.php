<?php

class so_export
{

    static function start( ){
        $rootDir= so_root::make()->dir;
        $export= $rootDir[ '-export' ];
        
        $rootDir[ 'release.php' ]->copy( $export[ 'index.php' ] );
        $rootDir[ '.htaccess' ]->copy( $export[ '.htaccess' ] );
        $rootDir[ 'php.ini' ]->copy( $export[ 'php.ini' ] );
        
        foreach( so_root::make()->packages as $package ):
            foreach( $package->sources as $source ):
                $file= $source->file;
                if( preg_match( '~\.(js|css|xsl|php|meta.tree|doc.xml)$~', $file->name ) )
                    continue;
                
                $target= $export->go( $file->relate( $rootDir ) );
                $file->copy( $target );
            endforeach;
            
            $mix= $package->dir[ '-mix' ];
            $target= $export[ $package->name ][ '-mix' ];
            $mix[ 'release.js' ]->copy( $target[ 'release.js' ] );
            $mix[ 'release.css' ]->copy( $target[ 'release.css' ] );
            $mix[ 'release.xsl' ]->copy( $target[ 'release.xsl' ] );
            $mix[ 'release.php' ]->copy( $target[ 'release.php' ] );
        endforeach;
        
        return so_output::found( so_root::make()->dir[ '-export' ]->relate( so_front::make()->dir ) . '/' );
    }
    
}
