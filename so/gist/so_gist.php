<?php

class so_gist
{
    use so_meta2;
    use so_resource;
    
    var $uri_value;
    var $uri_depends= array( 'uri', 'name', 'author' );
    function uri_make( ){
        return so_query::make(array(
            'gist' => $this->name,
            'by' => $this->author,
        ))->uri;
    }
    function uri_store( $data ){
        $query= so_uri::make( $data )->query;
        $this->name= $query[ 'gist' ];
        $this->author= $query[ 'by' ];
    }
    
    var $name_value;
    var $name_depends= array( 'uri', 'name' );
    function name_store( $data ){
        return (string) $data;
    }
    
    var $author_value;
    var $author_depends= array( 'uri', 'author' );
    function author_make( ){
        return so_user::make()->id;
    }
    function author_store( $data ){
        return (string) $data ?: $this->author_make();
    }
    
    var $storage_value;
    function storage_make( ){
        return so_storage::make( $this->uri );
    }
    
    var $content_value;
    var $content_depends= array();
    function content_make( ){
        return $this->model[ '@so_gist_content' ];
    }
    function content_store( $data ){
        $data= (string) $data;
        $model= $this->model;
        $model[ '@so_gist_content' ]= $data;
        $this->model= $model;
        return $data;
    }
    
    var $model_value;
    var $model_depends= array();
    function model_make( ){
        $storage= $this->storage;
        
        if( $storage->version )
            return so_dom::make( $storage->content );
        
        return so_dom::make( array(
            'so_gist' => array(
                '@so_uri' => (string) $this->uri,
                '@so_gist_name' => (string) $this->name,
                '@so_gist_author' => (string) $this->author,
                '@so_gist_content' => "    ...\n",
            ),
        ) );
    }
    function model_store( $data ){
        $this->storage->content= (string) $data->doc;
        unset( $this->teaser );
        return $data;
    }
    
    var $teaser_value;
    function teaser_make( ){
        $storage= $this->storage;
        
        if( !$storage->version )
            return $this->model;
        
        return so_dom::make( array(
            'so_gist' => array(
                '@so_uri' => (string) $this->uri,
                '@so_gist_name' => (string) $this->name,
                '@so_gist_author' => (string) $this->author,
                '@so_gist_external' => (string) $storage->uri,
            ),
        ) );
    }
    
    function get( $data= null ){
        $output= $this->storage->version ? so_output::ok() : so_output::missed();
        
        $output->content= array(
            '@so_page_uri' => (string) $this->uri,
            '@so_page_title' => (string) $this->name,
            //'so_page_aside' => array(
            //    123
            //    //'include' => array(
            //    //    '@href' => 'so/content/navigation.gist.xml',
            //    //),
            //),
            $this->teaser,
        );
        
        return $output;
    }
    
    function delete( $data ){
        $this->content= $data[ 'content' ] ?: "    /Content deleted/.\n";
        return so_output::ok( 'Content deleted' );
    }

    function put( $data ){
        $this->content= (string) $data[ 'content' ];
        return so_output::ok( 'Content updated' );
    }

    function move( $data ){
        $name= strtr( $data[ 'name' ], array( "\n" => '', "\r" => '' ) );
        $gist= so_gist::makeInstance()->name( $name )->author( $this->author )->primary();
        
        if( $gist != $this ):
            $gist->put(array( 'content' => $this->content ));
            $this->delete(array( 'content' => "    /Content moved to [new location\\{$gist->uri}]/.\n" ));
        endif;
        
        return so_output::ok()->content(array( 'so_relocation' => (string) $gist->uri ));
    }

}
