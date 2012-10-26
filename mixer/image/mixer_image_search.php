<?php

class mixer_image_search
{
    use so_resource;
    
    var $uri_value;
    var $uri_depends= array( 'uri', 'search', 'version' );
    function uri_make( ){
        return so_query::make(array(
            'image' => $this->version,
            'search' => $this->search,
        ))->uri;
    }
    function uri_store( $data ){
        $query= so_uri::make( $data )->query;
        $this->version= $query[ 'image' ];
        $this->search= $query[ 'search' ];
    }
    
    var $search_value;
    var $search_depends= array( 'uri', 'search' );
    function search_make( ){
        return 'facepalm';
    }
    function search_store( $data ){
        if( !$data ) return null;
        return (string) $data;
    }
    
    var $version_value;
    var $version_depends= array( 'uri', 'version' );
    function version_make( ){
        return 0;
    }
    function version_store( $data ){
        return (integer) $data;
    }
    
    var $storage_value;
    function storage_make( ){
        return so_storage::make( $this->uri );
    }
    
    var $imageList_value;
    function imageList_make( ){
        if( $this->storage->version )
            return so_dom::make( $this->storage->content );
        
        $query= so_query_compatible::make(array(
            'q' => $this->search,
            'tbm' => 'isch',
        ));
        $html= so_uri_compatible::make( 'http://www.google.ru/search?' . $query )->content;
        $dom= so_dom::make(array( '#html' => $html ));
        
        $links= $dom->select( '//*[@id="search"]//a/@href' );
        $imageList= so_dom::make(array( 'mixer_image_list' => null ));
        foreach( $links as $link ):
            $imageList[]= array(
                'mixer_image/@so_uri' => (string) so_uri::make( $link->value )->query[ 'imgurl' ],
            );
        endforeach;
        
        $this->storage->content= $imageList;
        
        return $imageList;
    }
    
    var $image_value;
    function image_make( ){
        $list= $this->imageList->childs;
        return $list[ $this->version % count( $list ) ][ '@so_uri' ];
    }
    
    function get_resource( $data= null ){
        return so_output::moved( (string) $this->image )->cache( true );
    }
    
}
