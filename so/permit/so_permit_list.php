<?php

class so_permit_list
{
    use so_resource;
    
    var $uri_value;
    var $uri_depends= array( 'uri', 'name' );
    function uri_make( ){
        return so_query::make(array(
            'permit;list',
            'for' => (string) $this->owner,
        ))->uri;
    }
    function uri_store( $data ){
        $query= so_uri::make( $data )->query;
        $this->owner= $query[ 'for' ];
    }
    
    var $owner_value;
    function owner_make( ){
        return hyoo_author::make();
    }
    function owner_store( $data ){
        if( !$data )
            return null;
        
        return hyoo_author::make( $data );
    }
    
    var $database_value;
    function database_make( ){
        return so_permit_list_db::make( so_storage::make( $this->uri )->dir[ 'so_permit_list.sqlite' ] );
    }
    
    var $list_value;
    function list_make( ){
        $list= $this->database->dump;
        
        foreach( $list as &$permit )
            so_query::ensure( $permit[ 'so_permit_pattern' ] );
        
        return $list;
    }
    
    function check( $subject, $action ){
        $subject= so_uri::make( $subject )->query;
        
        foreach( $this->list as $permit ):
            if( !$permit[ 'so_permit_pattern' ]->match( $subject ) )
                continue;
            
            if( $permit[ 'so_permit_action' ] != (string) $action )
                continue;
            
            return true;
        endforeach;
        
        return false;
    }
    
    function allow( $pattern, $action ){
        so_query::ensure( $pattern );
        
        if( $this->check( $pattern->uri, $action ) )
            return $this;
        
        $this->storage[]= array(
            'so_permit_pattern' => (string) $pattern,
            'so_permit_action' => (string) $action,
        );
        
        unset( $this->list );
        
        return $this;
    }
    
    function disallow( $subject, $action ){
        so_uri::ensure( $subject );
        
        foreach( $this->list as $permit ):
            if( !$subject->query->match( $permit[ 'so_permit_pattern' ] ) )
                continue;
            
            if( $permit[ '@so_permit_action' ] != (string) $action )
                continue;
            
            unset( $this->storage[ $permit[ 'id' ] ] );
        endforeach;
        
        unset( $this->list );
        
        return $this;
    }
    
}
