<?php

class so_cookie
extends so_meta
{

    static function make( $name ){
        $cookie= new so_cookie;
        $cookie->name= $name;
        return $cookie;
    }
    
    protected $_name;
    function set_name( $name ){
        if( isset( $this->name ) ) throw new Exception( 'Redeclaration of $name' );
        return $name;
    }

    protected $_expires;
    function get_expires( $expires ){
        if( isset( $expires ) ) return $expires;
        $expires= mktime( 0, 0, 0, 1, 1, 2030 );
        return $expires;
    }
    function set_expires( $expires ){
        if( isset( $this->expires ) ) throw new Exception( 'Redeclaration of $expires' );
        return $expires;
    }

    protected $_value;
    function get_value( $value ){
        return $_COOKIE[ $this->name ];
    }
    function set_value( $value ){
        $_COOKIE[ $this->name ]= $value;
        setcookie( $this->name, $value, $this->expires );
        return $value;
    }
}