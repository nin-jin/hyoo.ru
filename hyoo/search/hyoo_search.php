<?php

class hyoo_search
{
    use so_resource;
    
    var $uri_depends= array( 'uri', 'text' );
    function uri_make( ){
        return so_query::make(array(
            'search' => $this->text,
        ))->uri;
    }
    function uri_store( $data ){
        $query= so_uri::make( $data )->query;
        $this->text= $query[ 'search' ];
    }
    
    var $text_value;
    var $text_depends= array( 'uri', 'text' );
    function text_make( ){
        return '';
    }
    function text_store( $data ){
        return (string) $data;
    }
    
    var $searchId_value= 1969354;
    
    var $frame_value;
    function frame_make( ){
        $query= so_query_compatible::make( array(
            'text' => $this->text,
            'searchid' => $this->searchId,
            'frame' => 1,
            'topdoc' => 'http://hyoo.ru/' . (string) $this->uri,
        ) );
        return so_uri_compatible::make( 'http://yandex.ru/sitesearch' . $query->uri );
    }
    
    function get_resource( ){
        $output= so_output::ok();
        
        $output->content= array(
            '@so_page_uri' => (string) $this->uri,
            '@so_page_title' => (string) $this->text,
            'hyoo_search' => array(
                '@so_uri' => (string) $this->uri,
                '@hyoo_search_text' => (string) $this->text,
                '@hyoo_search_frame' => (string) $this->frame,
            ),
        );
        
        return $output;
    }
    
}
