<?php

class so_input
{
    use so_meta;
    
    static $_instance;
    static function make( ){
        if( static::$_instance ) return static::$_instance;
        return static::$_instance= new static;
    }
    
    protected $_query;
    function get_query( $query ){
        return $query ?: '';
    }
    function set_query( $query ){
        if( isset( $this->query ) ) throw new Exception( 'Redeclaration of [query]' );
        if( isset( $this->param ) ) throw new Exception( 'Redeclaration of [param]' );
        return '' + $query;
    }
    
    protected $_method;
    function get_method( $method ){
        return $method ?: 'get';
    }
    function set_method( $method ){
        if( isset( $this->method ) ) throw new Exception( 'Redeclaration of [method]' );
        return strtolower( $method );
    }
    
    protected $_param;
    function get_param( $param ){
        if( isset( $param ) ) return $param;
        return so_dom_list::make( so_uri::parseQuery( $this->query ) );
    }
    function set_param( $param ){
        if( isset( $this->param ) ) throw new Exception( 'Redeclaration of [param]' );
        if( isset( $this->query ) ) throw new Exception( 'Redeclaration of [query]' );
        return so_dom_list::make( $param );
    }
    
    protected $_data;
    function get_data( $data ){
        if( isset( $data ) ) return $data;
        return so_dom_list::make( array() );
    }
    function set_data( $data ){
        if( isset( $this->data ) ) throw new Exception( 'Redeclaration of [data]' );
        return so_dom_list::make( $data );
    }
    
}