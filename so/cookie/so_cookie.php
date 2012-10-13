<?php

class so_cookie
{
    use so_meta;
    use so_factory;

    static function make( $name ){
        $cookie= new so_cookie;
        $cookie->name= $name;
        return $cookie;
    }
    
    var $name_value;
    function name_make( ){
        throw new \Exception( "Property [name] is not defined" );
    }
    function name_store( $value ){
        return (string) $value;
    }

    var $expires_value;
    function expires_make( ){
        return mktime( 0, 0, 0, 1, 1, 2030 );
    }
    function expires_store( $value ){
        return (integer) $value;
    }

    var $value_value;
    var $value_depends= array();
    function value_make( ){
        return so_value::make( $_COOKIE[ $this->name ] );
    }
    function value_store( $value ){
        $_COOKIE[ $this->name ]= $value;
        setcookie( $this->name, $value, $this->expires );
        return $value;
    }
}