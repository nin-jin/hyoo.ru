<?php

class so_HttpRequest
{
    use so_meta;
    
    static function create( ){
        return new self;
    }
    
    protected $_method;
    function get_method( $method ){
        return strtolower( $_SERVER[ 'REQUEST_METHOD' ] );
    }
    
    protected $_type;
    function get_type( $type ){
        return strtolower( $_SERVER[ 'CONTENT_TYPE' ] );
    }
    
    protected $_data;
    function get_data( $data ){
        if( $data ) return $data;
        
        $query= $_SERVER[ 'QUERY_STRING' ];
        
        if(( $this->method === 'put' )and( $this->type === 'application/x-www-form-urlencoded' )):
            $raw= '';
            $input= fopen( 'php://input', 'r' );
            while( $chunk= fread( $input, 1024 ) ) $raw.= $chunk;
            fclose( $input );
            $query.= '/' . $raw;
        endif;
        
        $data= array();
        
        $chunkList= preg_split( '![&/]!', $query );
        foreach( $chunkList as $chunk ):
            if( !$chunk ) continue;
            list( $key, $val )= explode( '=', $chunk, 2 );
            $data[ urldecode( $key ) ]= urldecode( $val );
        endforeach;
        
        $data+= $_POST;
        
        foreach( $data as $key => $value ):
            $keyList= explode( '_', $key );
            $current= &$data;
            while( count( $keyList ) > 1 ):
                $current= &$current[ array_shift( $keyList ) ];
            endwhile;
            $current[ $keyList[ 0 ] ]= $value;
        endforeach;
        
        return $data;
    }
    
    protected $_query;
    function get_query( $query ){
        if( $query ) return $query;
        
        $chunkList= array();
        $keyList= array();
        $iterator= new RecursiveIteratorIterator( new RecursiveArrayIterator( $this->data ), RecursiveIteratorIterator::SELF_FIRST );
        foreach( $iterator as $key => $val ):
            array_splice( $keyList, $iterator->getDepth() );
            $keyList[]= $key;
            if( is_scalar( $val ) ):
                $chunk= array( implode( '_', $keyList ) );
                if( $val ) $chunk[]= $val;
                $chunkList[]= implode( '=', $chunk );
            endif;
        endforeach;
        $query= implode( '/', $chunkList );
        
        return $query;
    }
    
}
