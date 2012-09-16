<?php

trait so_meta2 {
    
    static function className( ){
        return get_class();
    }
    
    function __get( $name ){
        $valueField= $name . '_value';
        if( !property_exists( $this, $valueField ) )
            return $this->_make_meta( $name );
        
        $value= &$this->{ $valueField };
        if( isset( $value ) )
            return $value;
        
        $makeMethod= $name . '_make';
        if( !method_exists( $this, $makeMethod ) )
            return $value;
        
        return $value= $this->{ $makeMethod }();
    }
    protected function _make_meta( $name ){
        throw new Exception( "Property [$name] is not defined" );
    }
    
    function __isset( $name ){
        $valueField= $name . '_value';
        if( !property_exists( $this, $valueField ) )
            return false;
        
        return isset( $this->{ $valueField } );
    }
    
    function __set( $name, $value ){
        $valueField= $name . '_value';
        if( !property_exists( $this, $valueField ) )
            return $this->_store_meta( $name, $value );
        
        $storeMethod= $name . '_store';
        if( !method_exists( $this, $storeMethod ) )
            throw new Exception( "Property [$name] is read only" );
        
        $dependsField= $name . '_depends';
        $depends= property_exists( $this, $dependsField ) ? $this->{ $dependsField } : array( $name );
        
        foreach( $depends as $prop ):
            if( !isset( $this->{ $prop } ) ) continue;
            throw new Exception( "Property [$name] can not be stored because [$prop] is defined" );
        endforeach;
        
        $this->{ $valueField }= $this->{ $storeMethod }( $value );
        
        return $this;
    }
    protected function _store_meta( $name, $value ){
        throw new Exception( "Property [$name] is not defined" );
    }
    
    function __unset( $name ){
        $valueField= $name . '_value';
        if( !property_exists( $this, $valueField ) )
            return $this->_drop_meta( $name );
        
        unset( $this->{ $valueField } );
        
        return $this;
    }
    protected function _drop_meta( $name ){
        throw new Exception( "Property [$name] is not defined" );
    }
    
    function __call( $name, $args ){
        if( !property_exists( $this, $name . '_value' ) )
            return $this->_call_meta( $name, $args );
        
        $count= count( $args );
        
        switch( $count ):
            case 0: return $this->__get( $name );
            case 1: return $this->__set( $name, $args[ 0 ] );
        endswitch;
        
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
