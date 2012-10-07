<?php

class so_Tree
{
    use so_meta;
    use so_factory;

    static $separatorOfChunks= "\n";
    static $separatorOfPair= "= ";
    static $separatorOfKeys= " ";
    static $indentationToken= "    ";

    var $struct_value;
    function struct_make( ){
        return array();
    }
    function struct_store( $data ){
        return (array) $data;
    }

    var $string_value;
    function string_make( ){
        $chunkList= array();
        $lastKeyList= array();
        
        foreach( $this->struct as $index => $keyList ):
            $prefix= array();
            $actualKeyList= array();
            $value= array_pop( $keyList );
            
            foreach( $keyList as $index => $key ):
                if( !$actualKeyList && $key === $lastKeyList[ $index ] ):
                    $prefix[]= str_pad( " ", strlen( $key ) );//$this->indentationToken;
                else:
                    $actualKeyList[]= $key;
                endif;
            endforeach;
            
            $chunk= implode( str_pad( " ", strlen( staic::separatorOfKeys ) ), $prefix );
            $chunk.= implode( static::$separatorOfKeys, $actualKeyList );
            $chunk.= static::$separatorOfPair . $value;
            $lastKeyList= $keyList;
            $chunkList[]= $chunk;
        endforeach;

        $val= implode( static::$separatorOfChunks, $chunkList );
        return $val;
    }
    function string_store( $val ){
        $chunkList= explode( static::$separatorOfChunks, $val );
        $struct= array();
        $lastKeyList= array();
        foreach( $chunkList as $chunk ):
            
            $pair= explode( static::$separatorOfPair, $chunk, 2 );
            
            $keyList= explode( static::$indentationToken, $pair[0] );
            $keyListTail= array_pop( $keyList );
            if( $keyListTail ):
                $keyListTail= explode( static::$separatorOfKeys, $keyListTail );
                $keyList= array_merge( $keyList, $keyListTail );
            endif;
            
            foreach( $keyList as $index => &$key ):
                if( !$key ) $key= $lastKeyList[ $index ];
            endforeach;
            $lastKeyList= $keyList; 
            
            $keyList[]= so_value::make( $pair[1] );
            $struct[]= $keyList;

        endforeach;
        $this->struct= $struct;
        return null;
    }
    
    function get( $keyList ){
        if( is_string( $keyList ) ) $keyList= explode( static::$separatorOfKeys, $keyList );
        if( !is_array( $keyList ) ) throw new Exception( 'Wrong path type' );
        $filtered= array();
        foreach( $this->struct as $chunk ):
            if( count( $chunk ) !== count( $keyList ) + 1 ) continue;
            foreach( $keyList as $index => $key ):
                if( $key !== $chunk[ $index ] ) continue 2;
            endforeach;
            $filtered[]= end( $chunk );
        endforeach;
        return $filtered;
    }

} 
