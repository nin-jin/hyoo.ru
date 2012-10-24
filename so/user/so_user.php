<?php

class so_user
{
    use so_meta;
    use so_singleton;

    var $author_value;
    function author_make( ){
        $cookie= so_cookie::make( 'so_user_author' );
        
        if( $cookie->value ):
            $author= mixer_author::make( $cookie->value );
            
            if( !$author->key )
                return $author;
            
            if( $author->key == so_crypt::hash( $author->uri, $this->key ) )
                return $author;
        endif;
        
        $author= mixer_author::makeInstance()->name( so_crypt::generateId() )->primary();
        $cookie->value= (string) $author;
        
        return $author;
    }
    function author_store( $data ){
        mixer_author::ensure( $data );
        $data->key= so_crypt::hash( $data->uri, $this->key );
        $cookie= so_cookie::make( 'so_user_author' );
        $cookie->value= (string) $data;
        return $data;
    }

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