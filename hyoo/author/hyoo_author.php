<?php

class hyoo_author
{
    use so_gist;
    
    var $uri_depends= array( 'uri', 'name' );
    function uri_make( ){
        return so_query::make(array(
            'author' => $this->name,
        ))->uri;
    }
    function uri_store( $data ){
        $query= so_uri::make( $data )->query;
        $this->name= $query[ 'author' ];
    }
    
    var $name_value;
    var $name_depends= array( 'uri', 'name' );
    function name_make( ){
        $cookie= so_cookie::make( 'hyoo_author_name' );
        $userKey= so_user::make()->key;
        
        if( $cookie->value ):
            $author= hyoo_author::makeInstance()->name( $cookie->value );
            
            if( !$author->key )
                return $author->name;
            
            if( $author->key == so_crypt::hash( (string) $author->uri, $userKey ) )
                return $author->name;
        endif;
        
        return substr( md5( $userKey ), 0, 8 );
    }
    function name_store( $data ){
        $data= (string) $data;
        
        if( !$data )
            return null;
        
        return $data;
    }
    
    var $articleList_value;
    function articleList_make( ){
        return hyoo_article_list_author::makeInstance()->author( $this )->primary();
    }
    
    var $modelBase_value;
    function modelBase_make( ){
        return so_dom::make( array(
            'hyoo_author' => array(
                '@so_uri' => (string) $this->uri,
                '@hyoo_author_name' => (string) $this->name,
                '@hyoo_author_article-list' => (string) $this->articleList,
            ),
        ) );
    }
    
    function teaser_make( ){
        $dom= $this->model->cloneTree();
        unset( $dom[ '@hyoo_author_about' ] );
        return $dom;
    }
    
    var $about_value;
    var $about_depends= array();
    function about_make( ){
        return (string) $this->model[ '@hyoo_author_about' ];
    }
    function about_store( $data ){
        $model= $this->model;
        $model[ '@hyoo_author_about' ]= (string) $data;
        $this->model= $model;
    }
    
    var $key_value;
    var $key_depends= array();
    function key_make( ){
        return (string) $this->model[ '@hyoo_author_key' ];
    }
    function key_store( $data ){
        $model= $this->model;
        $model[ '@hyoo_author_key' ]= (string) $data;
        $this->model= $model;
    }
    
    function get_resource( $data= null ){
        $output= $this->exists ? so_output::ok() : so_output::missed();
        
        $output->content= array(
            '@so_page_uri' => (string) $this->uri,
            '@so_page_title' => (string) $this->name,
            $this->link,
        );
        
        return $output;
    }
    
    function post_resource( $data ){
        $authorCurrent= hyoo_author::make();
        
        if( $this->key && $this !== $authorCurrent )
            return so_output::forbidden( "User [{$this->name}] is already registered" );
        
        $this->about= $data[ 'hyoo_author_about' ] ?: $this->about;
        $this->key= so_crypt::hash( $this->uri, so_user::make()->key );
        $this->exists= true;
        
        so_cookie::make( 'hyoo_author_name' )->value= $this->name;
        
        #return so_output::ok( 'Updated' );
        return so_output::created( (string) $this );
    }
    
    #function move_resource( $data ){
    #    $name= $data[ 'name' ];
    #    $target= hyoo_author::makeInstance()->name( $name )->primary();
    #    
    #    if( $target != $this ):
    #        $target->about->put(array( 'content' => $this->about->content ));
    #        $this->delete(array( 'content' => "    /Author moved to [new location\\{$target}]/.\n" ));
    #    endif;
    #    
    #    return so_output::ok()->content(array( 'so_relocation' => (string) $target ));
    #}
    #

}
