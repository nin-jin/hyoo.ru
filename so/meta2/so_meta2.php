<?php

trait so_meta2 {
    
    static function className( ){
        return get_class();
    }
    
    function __get( $name ){
        $field= $name . '_prop';
        if( !property_exists( $this, $field ) )
            return $this->_make_meta( $name );
        
        $property= &$this->{ $field };
        if( isset( $property[ 'data' ] ) )
            return $property[ 'data' ];
        
        $method= $name . '_make';
        if( method_exists( $this, $method ) )
            return $property[ 'data' ]= $this->{ $method }();
        
        return $property[ 'data' ]= null;
    }
    protected function _make_meta( $name ){
        throw new Exception( "Property [$name] is not defined" );
    }
    
    function __isset( $name ){
        $field= $name . '_prop';
        if( !property_exists( $this, $field ) )
            return false;
        
        return isset( $this->{ $field }[ 'data' ] );
    }
    
    function __set( $name, $value ){
        $field= $name . '_prop';
        if( !property_exists( $this, $field ) )
            return $this->_store_meta( $name, $value );
        
        $method= $name . '_store';
        if( !method_exists( $this, $method ) )
            throw new Exception( "Property [$name] is read only" );
        
        $property= &$this->{ $field };
        $depends= &$property[ 'depends' ];
        if( !isset( $depends ) )
            $depends= array( $name );
        
        foreach( $depends as $prop ):
            if( !isset( $this->{ $prop } ) ) continue;
            throw new Exception( "Property [$name] can not be stored because [$prop] is defined" );
        endforeach;
        
        $property[ 'data' ]= $this->{ $method }( $value );
        
        return $this;
    }
    protected function _store_meta( $name, $value ){
        throw new Exception( "Property [$name] is not defined" );
    }
    
    function __unset( $name ){
        $field= $name . '_prop';
        if( !property_exists( $this, $field ) )
            $this->_drop_meta( $name );
        
        $property= &$this->{ $field };
        unset( $property[ 'data' ] );
        
        return $this;
    }
    protected function _drop_meta( $name ){
        throw new Exception( "Property [$name] is not defined" );
    }
    
    function __call( $name, $args ){
        if( !property_exists( $this, $name . '_prop' ) )
            return $this->_call_meta( $name, $args );
        
        $count= count( $args );
        
        if( count( $args ) === 0 )
            return $this->{ $name };
        
        if( count( $args ) === 1 ):
            $this->{ $name }= $args[ 0 ];
            return $this;
        endif;
        
        throw new Exception( "Wrong arguments count ($count)" );
    }
    
    protected function _call_meta( $name, $args ){
        throw new Exception( "Method [$name] is not defined" );
    }
    
    function __toString( ){
        return print_r( $this, true );
    }

    function destroy( ){
        $vars= get_object_vars( $this );
        
        foreach( $vars as $name => $value )
            unset( $this->$name );
    }
}
