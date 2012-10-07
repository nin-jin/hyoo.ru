<?php

trait so_meta_fall {
    
    function _make_meta( $name ){
        throw new Exception( "Property [$name] is not defined in (" . get_class( $this ) . ")" );
    }
    
    function _store_meta( $name, $value ){
        throw new Exception( "Property [$name] is not defined in (" . get_class( $this ) . ")" );
    }
    
    function _drop_meta( $name ){
        throw new Exception( "Property [$name] is not defined in (" . get_class( $this ) . ")" );
    }
    
    function _call_meta( $name, $args ){
        throw new Exception( "Method [$name] is not defined in (" . get_class( $this ) . ")" );
    }
    
}
