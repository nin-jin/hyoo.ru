<?php

class so_user
{
    use so_meta;

    protected $_id;
    function get_id( $id ){
        if( isset( $id ) ) return $id;
        
        $cookie= so_cookie::make( 'so_user_id' );
        
        $id= $cookie->value;
        
        if( !$id ):
            $id= so_crypt::generateId();
            $cookie->value= $id;
        endif;
        
        return $id;
    }
    function set_id( $id ){
        if( isset( $this->id ) ) throw new Exception( 'Redeclaration of $id' );
        return $id;
    }

    protected $_key;
    function get_key( $key ){
        if( isset( $key ) ) return $key;
        
        $cookie= so_cookie::make( 'so_user_key' );
        
        $key= $cookie->value;
        
        if( !$key ):
            $key= so_crypt::generateKey();
            $cookie->value= $key;
        endif;
        
        return $key;
    }
    function set_key( $key ){
        so_cookie::make( 'so_user_key' )->value= $key;
        return $key;
    }

}