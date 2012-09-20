<?php

class so_user
{
    use so_meta2;
    use so_registry;

    var $id_value;
    function id_make( ){
        $cookie= so_cookie::make( 'so_user_id' );
        
        $id= $cookie->value;
        
        if( !$id ):
            $id= so_crypt::generateId();
            $cookie->value= $id;
        endif;
        
        return $id;
    }

    var $key_value;
    function key_make( $key ){
        $cookie= so_cookie::make( 'so_user_key' );
        
        $key= $cookie->value;
        
        if( !$key ):
            $key= so_crypt::generateKey();
            $cookie->value= $key;
        endif;
        
        return $key;
    }

}