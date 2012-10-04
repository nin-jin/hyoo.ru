<?php

class so_page
{

    static function make( $data ){
        $page= array();
        
        $moduleMix= pms_module::make( __DIR__ )->package[ '-mix' ];
        
        $page[ '?xml-stylesheet' ]= array(
            'href' => (string) $moduleMix[ 'compiled.xsl' ]->uri,
            'type' =>'text/xsl',
        );
        
        $page[]= array(
            'so_page' => array(
                '@so_page_script' => (string) $moduleMix[ 'index.js' ]->uri,
                '@so_page_stylesheet' => (string) $moduleMix[ 'index.css' ]->uri,
                $data,
            ),
        );
        
        return so_dom::make( $page )->doc;
    }

}