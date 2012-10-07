<?php

class so_export
{
    use so_resource;
    
    var $uri_value;
    function uri_make( ){
        return so_query::make(array( 'export' ))->uri;
    }
    function uri_store( $data ){
    }    
    
    function get( ){
        
        $root= so_root::make()->dir;
        $export= $root[ '-export' ];
        
        $root[ 'compiled.php' ]->copy( $export[ 'index.php' ] );
        $root[ '.htaccess' ]->copy( $export[ '.htaccess' ] );
        $root[ 'php.ini' ]->copy( $export[ 'php.ini' ] );
        
        foreach( so_root::make()->packages as $package ):
            foreach( $package->sources as $source ):
                $file= $source->file;
                if( preg_match( '~\.(js|css|xsl|php|meta.tree|doc.xml)$~', $file->name ) )
                    continue;
                
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
        
        return so_output::found( '-export/' );
    }
    
}
