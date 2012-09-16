<?php

trait so_registry
{
    use so_factory {
        so_factory::make as makeInstance;
    }

    static $all= array();
    
    static function make( $id= null ){
        if( !isset( $id ) )
            return static::makeInstance();
        
        if( $id instanceof static )
            $id= $id->id;
        
        $cached= &static::$all[ (string) $id ];
        if( $cached ) return $cached;
        
        $obj= static::makeInstance();
        $obj->id= $id;
        $cached2= &static::$all[ $obj->id ];
        
        if( !$cached2 ) $cached2= $obj;
        $cached= $cached2;
        
        return $cached;
    }
    
    function primary( ){
        $cache= &static::$all[ $this->id ];
        if( !$cache ) $cache= $this;
        return $cache;
    }
    
}