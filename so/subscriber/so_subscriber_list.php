<?php

class so_subscriber_list
{
    use so_resource;
    
    var $uri_value;
    var $uri_depends= array( 'uri', 'subject' );
    function uri_make( ){
        return so_query::make(array(
            'subscriber;list',
            'for' => (string) $this->subject,
        ))->uri;
    }
    function uri_store( $data ){
        $query= so_uri::make( $data )->query;
        $this->subject= $query[ 'for' ];
    }
    
    var $subject_value;
    var $subject_depends= array( 'uri', 'subject' );
    function subject_make( ){
        throw new Exception( 'Property [subject] is not defined' );
    }
    function subject_store( $data ){
        return so_uri::make( $data );
    }
    
    var $storage_value;
    function storage_make( ){
        return so_storage::make( $this->uri );
    }
    
    var $database_value;
    function database_make( ){
        return so_subscriber_database::make( $this->storage->dir[ 'so_subscriber_list.sqlite' ] );
    }
    
    var $model_value;
    var $model_depends= array();
    function model_make( ){
        $subsribers= array();
        
        foreach( $this->database->dump as $sub )
            $subsribers[]= array( 'so_subscriber' => $sub );
        
        return so_dom::make( array(
            'so_subscriber_list' => array(
                '@so_uri' => (string) $this->uri,
                $subsribers,
            ),
        ) );
    }
    
    var $teaser_value;
    function teaser_make( ){
        return $this->model;
    }
    
    function get_resource( $data ){
        return so_output::ok()->content( array(
            '@so_page_uri' => (string) $this->uri,
            $this->model,
        ) );
    }

    function post( $data ){
        $subject= so_uri::make( $data[ 'subject' ] );
        $event= (string) $data[ 'event' ];
        $this->database[]= array(
            'so_subscriber_uri' => $subject,
            'so_subscriber_event' => $event,
        );
        return $this;
    }

}
