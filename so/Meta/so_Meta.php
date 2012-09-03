<?php

class so_meta {
    function __toString( ){
        return print_r( $this, true );
    }
    
    function __set( $name, $value= null ){
        //$this->_aPropertyName( &$name );
        $method= 'set_' . $name;
        if( !method_exists( $this, $method ) ) $method= 'set_';
        $value= $this->$method( $value );
        $this->{ '_' . $name }= $value;
        return $this;
    }
    function set_( $val ){
        throw new Exception( 'property is read only' );
    }
    
    function __get( $name ){
        //$this->_aPropertyName( &$name );
        $name= '_' . $name;
        $method= 'get' . $name;
        //if( !method_exists( $this, $method ) ) $method= 'get_';
        //$value= $this->{ $name }[ 'value' ];
        if( !property_exists( $this, $name ) ) throw new Exception( "Property not found [{$name}]" );
        $value= $this->{ $name }= $this->{ $method }( $this->{ $name } );
        return $value;
    }
    function get_( $val ){
        return $val;
    }
    
    function __isset( $name ){
        //$this->_aPropertyName( &$name );
        return property_exists( $this, '_' . $name ) && isset( $this->{ '_' . $name } );
    }
    
    function __call( $name, $args ){
        $nameList= explode( '_', $name );
        
        if( count( $nameList ) > 1 ):
            $type= array_shift( $nameList );
            $name= '_' . implode( '/', $nameList );
            switch( $type ){
                case 'get':
                    if( !property_exists( $this, $name ) ) throw new Exception( "Property not found [{$name}]" );
                    return $this->get_( $this->{$name} );
                case 'set': return $this->set_( $args[0] );
            }
            return $this->_call( $name, $args );
        else:
            if( !property_exists( $this, '_' . $name ) ):
                return $this->_call( $name, $args );
            endif;
            switch( count( $args ) ):
                case 0: return $this->__get( $name );
                case 1: return $this->__set( $name, $args[0] );
            endswitch;
        endif;
        
        throw new Exception( "Wrong parameters count for [{$name}]" );
    }
    function _call( $name, $args ){
        throw new Exception( "Method not found [{$name}]" );
    }
    
    function _aPropertyName( $val ){
        if( $val[0] !== '_' ) $val= '_' . $val;
        if( !property_exists( $this, $val ) ) throw new Exception( "Property [{$val}] not found" );
        if( !$this->$val ) $this->$val= array();
        return $val;
    }

    static function make( ){
        return new static;
    }
    
    function destroy( ){
        $vars= get_object_vars( $this );
        foreach( $vars as $key => $value ):
            unset( $this->$key );
        endforeach;
    }
}
