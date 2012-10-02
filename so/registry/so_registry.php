<?php

trait so_registry
{
    use so_factory {
        so_factory::make as makeInstance;
    }

    static $all= array();
    #static $id_prop= 'id';
    
    static function make( $id= null ){
        if( $id instanceof static )
            $id= $id->{ static::$id_prop };
        
        $cached= &static::$all[ (string) $id ];
        if( $cached ) return $cached;
        
        $obj= static::makeInstance();
        $obj->{ static::$id_prop }= $id;
        $cached2= &static::$all[ (string) $obj->{ static::$id_prop } ];
        
        if( !$cached2 ) $cached2= $obj;
        $cached= $cached2;
        
        return $cached;
    }
    
    static function ensure( &$value ){
        return $value= static::make( $value );
    }
    
    function primary( ){
        $cache= &static::$all[ (string) $this->{ static::$id_prop } ];
        if( !$cache ) $cache= $this;
        return $cache;
    }
    
}