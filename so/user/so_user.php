<?php

class so_user
{
    use so_meta;
    use so_singleton;

    var $key_value;
    function key_make( ){
        $cookie= so_cookie::make( 'so_user_key' );
        
        $key= $cookie->value;
        
        if( !$key ):
            $key= so_crypt::generateKey();
            $cookie->value= $key;
        endif;
        
        return $key;
    }

}