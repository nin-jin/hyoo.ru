<?php

class so_crypt
{

    static $lexicon64= 'abcdefghijklmnopqrstuvwxyz-ABCDEFGHIJKLMNOPQRSTUVWXYZ_0123456789';

    static function generateId( ){
        $symbolList= str_split( base_convert( uniqid( ), 16, 4 ), 3 );
        
        $id= '';
        foreach( $symbolList as $symbol ):
            $id.= so_crypt::$lexicon64[ intval( $symbol, 4 ) ];
        endforeach;
        
        return $id;
    }

    static function generateKey( ){
        $longNumber= '';
        for( $i= 0; $i < 5; ++$i ):
            $randNumber= mt_rand( 1, pow( 64, 5 ) );
            $longNumber.= base_convert( $randNumber, 10, 4 );
        endfor;
        $symbolList= str_split( $longNumber, 3 );
        
        $key= '';
        foreach( $symbolList as $symbol ):
            $key.= static::$lexicon64[ intval( $symbol, 4 ) ];
        endforeach;
        
        return $key;
    }

}
