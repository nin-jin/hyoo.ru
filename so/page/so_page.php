<?php

class so_page
{

    static $mode= 'dev';

    static function make( $data ){
        $page= array();
        
        $moduleMix= so_front::make()->package[ '-mix' ];
        
        $page[ '?xml-stylesheet' ]= array(
            'href' => (string) $moduleMix[ static::$mode . '.xsl' ]->uri,
            'type' =>'text/xsl',
        );
        
        $page[]= array(
            'so_page' => array(
                $data,
            ),
        );
        
        return so_dom::make( $page )->doc;
    }

}