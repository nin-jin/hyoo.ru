<?php

class so_gist_all
extends so_resource
{

    protected $responseClass= so_xmlResponse;
    
    protected $_uri;
    function get_uri( $uri ){
        if( isset( $uri ) ) return $uri;
        return '?gist/all';
    }
    
    function get( ){
        $root= new so_WC_Root;
        $files= $root->createPack( 'so' )->createModule( 'content' )->selectFiles( '/\\.gist\\.xml$/' );
        $content= array(
            'so:path' => array(
                array( 'so:path_item' => array(
                    'so:path_title' => 'Записи',
                    'so:path_link' => '?gist/all',
                ) ),
            ),
            'so:gist_creator' => array(
                'so:gist_name' => $this->request['gist']->value,
                'so:gist_author' => $this->request['author']->value,
            ),
        );
        foreach( $files as $file ):
            $content[]= array(
                so_gist::make()->file( $file )->data,
                //'html:include' => array(
                //    '@href' => $file->id . '?' . $file->version,
                //),
            );
        endforeach;
        
        $this->response->ok( $content );
        
        return $this;
    }
    
}
