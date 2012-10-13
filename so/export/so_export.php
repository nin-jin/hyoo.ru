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
            $mix[ 'compiled.js' ]->copy( $target[ 'index.js' ] );
            $mix[ 'compiled.css' ]->copy( $target[ 'index.css' ] );
            $mix[ 'compiled.xsl' ]->copy( $target[ 'index.xsl' ] );
            $mix[ 'compiled.php' ]->copy( $target[ 'index.php' ] );
        endforeach;
        
        return so_output::found( so_root::make()->dir[ '-export' ]->relate( so_front::make()->dir ) . '/' );
    }
    
}
