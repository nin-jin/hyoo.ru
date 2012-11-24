<?php

class hyoo_image_search
{
    use so_resource;
    
    var $uri_depends= array( 'uri', 'search', 'version' );
    function uri_make( ){
        return so_query::make(array(
            'image',
            'search' => $this->search,
        ))->uri;
    }
    function uri_store( $data ){
        $query= so_uri::make( $data )->query;
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
    
    var $storage_value;
    function storage_make( ){
        return so_storage::make( $this->uri );
    }
    
    var $imageList_value;
    function imageList_make( ){
        if( $this->storage->version )
            return so_dom::make( $this->storage->content );
        
        $query= so_query_compatible::make(array(
            'v' => '1.0',
            'q' => $this->search,
            'rsz' => 1,
            'imgsz' => 'xlarge',
            'safe' => 'off',
        ));
        $json= json_decode( so_uri_compatible::make( 'http://ajax.googleapis.com/ajax/services/search/images?' . $query )->content );
        var_dump( $json->responseData->results[0]->unescapedUrl );
        #$links= $dom->select( '//*[@id="search"]//a/@href' );
        $imageList= so_dom::make(array( 'hyoo_image_list' => null ));
        #foreach( $links as $link ):
        #    $imageList[]= array(
        #        'hyoo_image/@so_uri' => (string) so_uri::make( $link->value )->query[ 'imgurl' ],
        #    );
        #endforeach;
        #
        #$this->storage->content= $imageList;
        
        return $imageList;
    }
    
    var $image_value;
    function image_make( ){
        if( $this->storage->version )
            return so_dom::make( $this->storage->content );
        
        $query= so_query_compatible::make(array(
            'v' => '1.0',
            'q' => $this->search,
            'rsz' => 1,
            'imgsz' => 'xlarge',
            'safe' => 'off',
        ));
        $json= json_decode( so_uri_compatible::make( 'http://ajax.googleapis.com/ajax/services/search/images?' . $query )->content );
        
        $image= so_dom::make(array(
            'hyoo_image_search' => null,
        ));
        foreach( $json->responseData->results[0] as $prop => $value ):
            $image[ '@hyoo_image_search_' . $prop ]= $value;
        endforeach;
        
        $this->storage->content= $image;
        
        return $image;
    }
    
    function get_resource( $data= null ){
        $url= (string) $this->image[ '@hyoo_image_search_unescapedUrl' ];
        return so_output::moved( $url )->cache( true );
    }
    
}
