<?php

class so_console
{
    use so_resource;
    
    var $uri_value;
    function uri_make( ){
        return so_query::make(array( 'console' ))->uri;
    }
    function uri_store( $data ){
    }
    
    var $model_value;
    function model_make( ){
        return so_dom::make( array(
            'so_console' => array(
                '@so_uri' => (string) $this->uri,
            ),
        ) );
    }
    
    function get_resource( $data= null ){
        $output= so_output::ok();
        
        $output->content= array(
            '@so_page_uri' => (string) $this->uri,
            '@so_page_title' => 'Console!',
            $this->model,
        );
        
        return $output;
    }
    
    function post_resource( $data ){
        $result= eval( $data[ 'code' ] );
        $lang= 'text';
        if( $result instanceof DOMNode ) $result= so_dom::make( $result );
        if( $result instanceof so_dom ) $lang= 'sgml';
        
        if( is_array( $result ) ):
            $result= var_export( $result, true );
            $lang= 'php';
        endif;
        
        return so_output::ok()->content( array(
            'so_console_result' => array(
                '@so_console_lang' => $lang,
                'so_console_content' => (string) $result,
            )
        ) );
    }

}
