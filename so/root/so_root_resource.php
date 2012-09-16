<?php

class so_root_resource
{
    use so_meta2;
    use so_registry;
    
    var $id_value;
    function id_make( ){
        return '';
    }
    function id_store( $data ){
        if( !(string)$data )
            $data= so_query::make(array( 'author' ))->resource->id;
        
        return (string) $data;
    }
    
    function get( $data ){
        if( $this->id )
            return so_output::missed( "Missed handler for [{$this->id}]" );
        
        return so_output::moved( '?author' );
    }
    
}
