<?php

trait so_singleton
{
    use so_factory {
        so_factory::make as makeInstance;
    }

    static $all= array();
    
    static function make( ){
        $obj= end( static::$all );
        
        if( $obj === false )
            $obj= static::$all[]= static::makeInstance();
        
        return $obj;
    }
    
}