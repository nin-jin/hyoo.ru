<?php

trait so_proxy
{
    #use so_meta2;
    #static $proxy_prop= 'proxy';

    function _make_meta( $name ){
        return $this->{ static::$proxy_prop }->{ $name };
    }
    
    function _store_meta( $name, $value ){
        return $this->{ static::$proxy_prop }->{ $name }= $value;
    }
    
    function _drop_meta( $name ){
        unset( $this->{ static::$proxy_prop }->{ $name } );
        return $this;
    }
    
    function _call_meta( $name, $args ){
        return call_user_func_array( array( $this->{ static::$proxy_prop }, $name ), $args );
    }
    
}