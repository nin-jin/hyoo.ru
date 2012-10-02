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
    function _make_meta( $name ){
        throw new Exception( "Property [$name] is not defined in (" . get_class( $this ) . ")" );
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
    function _store_meta( $name, $value ){
        throw new Exception( "Property [$name] is not defined in (" . get_class( $this ) . ")" );
    }
    
    function __unset( $name ){
        $valueField= $name . '_value';
        
        if( !property_exists( $this, $valueField ) )
            return $this->_drop_meta( $name );
        
        $this->{ $valueField }= null;
        
        return $this;
    }
    function _drop_meta( $name ){
        throw new Exception( "Property [$name] is not defined in (" . get_class( $this ) . ")" );
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
    
    function _call_meta( $name, $args ){
        throw new Exception( "Method [$name] is not defined in (" . get_class( $this ) . ")" );
    }
    
    function _string_meta( $prefix= '' ){
        $string= '';
        
        foreach( get_object_vars( $this ) as $key => $val ):
            $key= preg_replace( '~_value$~', '', $key );
            
            if( preg_match( '~_depends$~', $key ) )
                continue;
            
            $key= $prefix . $key;
            
            if( is_array( $val ) )
                $val= so_array::make( $val );
            
            if( is_string( $val ) )
                $val= "'" . strtr( $val, array( "'" => "\'", "\n" => "\\n" ) ) . "'";
            
            if( is_bool( $val ) )
                $val= $val ? 'TRUE' : 'FALSE';
            
            if( is_null( $val ) )
                $val= 'NULL';
            
            $string.= $key . '= ' . trim( $val, "\n" ) . "\n";// . '=' . $key . "\n";
        endforeach;
        
        $string= preg_replace( '~^~m', '    ', $string );
        
        return get_class( $this ) . " {\n" . $string . "}\n";
    }
    
    function __toString( ){
        static $processing;
        
        if( $processing )
            return '@';
        
        $processing= true;
        
        try {
            $string= (string) $this->_string_meta();
        } catch( Exception $exception ){
            echo $exception;
            $string= '#';
        }
        
        $processing= false;
        return $string;
    }

    function destroy( ){
        $vars= get_object_vars( $this );
        
        foreach( $vars as $name => $value )
            unset( $this->$name );
    }
    
}
