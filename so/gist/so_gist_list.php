<?php

trait so_gist_list
{
    use so_resource;
    
    var $database_value;
    function database_make( ){
        $storage= so_storage::make( $this->uri );
        return so_gist_database::make( $storage->dir[ 'so_gist_list.sqlite' ] );
    }
    
    var $map_value;
    function map_make( ){
        $map= $this->database->dump;
        
        foreach( $map as &$record )
            $record= so_uri::make( $record[ 'so_gist_uri' ] )->query->resource;
        
        return $map;
    }
    
    function append( $resource ){
        $this->database[]= array(
            'so_gist_uri' => (string) $resource->uri,
        );
        unset( $this->map );
        return $this;
    }
    
    function drop( $resource ){
        unset( $this->database[ (string) $resource->uri ] );
        unset( $this->map );
        return $this;
    }
    
}
