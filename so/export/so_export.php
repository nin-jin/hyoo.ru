<?php

class so_export
{

    static function start( ){
        $rootDir= so_root::make()->dir;
        $export= $rootDir[ '-so_export' ];
        
        $rootDir[ 'release.php' ]->copy( $export[ 'index.php' ] );
        $rootDir[ '.htaccess' ]->copy( $export[ '.htaccess' ] );
        $rootDir[ 'php.ini' ]->copy( $export[ 'php.ini' ] );
        $rootDir[ 'icon.png' ]->copy( $export[ 'icon.png' ] );
        
        foreach( so_root::make()->packages as $package ):
            foreach( $package->sources as $source ):
                $file= $source->file;
                if( preg_match( '~\.(js|css|xsl|php|meta.tree|doc.xhtml)$~', $file->name ) )
                    continue;
                
                $target= $export->go( $file->relate( $rootDir ) );
                $file->copy( $target );
            endforeach;
            
            $target= $export[ $package->name ];
            foreach( $package->dir->childs as $file ):
                if( $file->type != 'file' )
                    continue;
                
                if( !preg_match( '~^release\.~', $file->name ) )
                    continue;
                
                $file->copy( $target[ $file->name ] );
            endforeach;
            
            $target= $export[ $package->name ][ '-mix' ];
            foreach( $package->dir[ '-mix' ]->childs as $file ):
                if( ( $file->type === 'file' ) && !preg_match( '~^(release|bundle)\.~', $file->name ) )
                    continue;
                
                $file->copy( $target[ $file->name ] );
            endforeach;
            
        endforeach;
        
        return $export->uri;
    }
    
}
