<?php

class mixer_article
{
    use so_gist;
    
    var $uri_value;
    var $uri_depends= array( 'uri', 'name', 'author' );
    function uri_make( ){
        return so_query::make(array(
            'article' => $this->name,
            'author' => (string) $this->author->name,
        ))->uri;
    }
    function uri_store( $data ){
        $query= so_uri::make( $data )->query;
        $this->name= $query[ 'article' ];
        $author= mixer_author::makeInstance()->name( $query[ 'author' ] )->primary();
        $this->author= $author;
    }
    
    var $name_value;
    var $name_depends= array( 'uri', 'name' );
    function name_make( ){
        return so_crypt::generateId();
    }
    function name_store( $data ){
        if( !(string)$data )
            return null;
        return (string) $data;
    }
    
    var $author_value;
    var $author_depends= array( 'uri', 'author' );
    function author_make( ){
        return mixer_author::make();
    }
    function author_store( $data ){
        return mixer_author::make( $data );
    }
    
    var $modelBase_value;
    function modelBase_make( ){
        return so_dom::make( array(
            'mixer_article' => array(
                '@so_uri' => (string) $this->uri,
                '@mixer_article_name' => (string) $this->name,
                '@mixer_article_author' => (string) $this->author,
            ),
        ) );
    }
    
    var $content_value;
    var $content_depends= array();
    function content_make( ){
        return (string) $this->model[ '@mixer_article_content' ];
    }
    function content_store( $data ){
        $model= $this->model;
        $model[ '@mixer_article_content' ]= (string) $data;
        $this->model= $model;
    }
    
    var $annotation_value;
    var $annotation_depends= array();
    function annotation_make( ){
        return (string) $this->model[ '@mixer_article_annotation' ];
    }
    function annotation_store( $data ){
        $model= $this->model;
        $model[ '@mixer_article_annotation' ]= (string) $data;
        $this->model= $model;
    }
    
    function listList_make( ){
        return array(
            $this->author->articleList,
            mixer_article_list::make(),
        );
    }
    
    function get_resource( $data= null ){
        $output= $this->exists ? so_output::ok() : so_output::missed();
        
        $output->content= array(
            '@so_page_uri' => (string) $this->uri,
            '@so_page_title' => (string) $this->name,
            $this->teaser,
            $this->author->teaser,
        );
        
        return $output;
    }
    
    function post_resource( $data ){
        if( $this->author !== mixer_author::make() )
            return so_output::forbidden( "Permission denied" );
        
        $this->content= $data[ 'mixer_article_content' ] ?: $this->content;
        $this->annotation= $data[ 'mixer_article_annotation' ] ?: $this->annotation;
        $this->exists= true;
        
        return so_output::created( (string) $this );
    }
    
    function delete_resource( $data ){
        if( $this->author !== mixer_author::make() )
            return so_output::forbidden( "Permission denied" );
        
        $this->content= $data[ 'mixer_article_content' ] ?: '    /Article removed/';
        $this->annotation= $data[ 'mixer_article_annotation' ] ?: '';
        $this->exists= false;
        
        return so_output::ok( 'Deleted' );
    }
    
    function move_resource( $data ){
        $name= strtr( $data[ 'mixer_article_name' ], array( "\n" => '', "\r" => '' ) );
        $force= ( $data[ 'so_conflict_force' ] == 'true' );
        
        $target= mixer_article::makeInstance()->name( $name )->primary();
        
        if( $target === $this )
            return so_output::on( 'Same name' );
        
        if( $target->exists && !$force )
            return so_output::conflict( 'Article already exists' );
        
        $target->post_resource(array(
            'mixer_article_content' => $this->content,
            'mixer_article_annotation' => $this->annotation,
        ));
        
        if( $this->exists && $target->author === $this->author ):
            $this->delete_resource(array(
                'mixer_article_content' => "    /Article moved to [new location\\{$target}]/.\n",
            ));
        endif;
        
        return so_output::created( (string) $target );
    }

}
