<?php

class so_page
{

    static $mode= 'dev';

    static function make( $data ){
        $page= array();
        
        $moduleMix= so_front::make()->package[ '-mix' ]->dir;
        
        $page[]= array( '?xml-stylesheet' => array(
            'href' => (string) $moduleMix[ static::$mode . '.xsl' ]->uriVersioned,
            'type' =>'text/xsl',
        ));
        
        $page[]= array(
            'so_page' => array(
                '@so_page_styles' => (string) $moduleMix[ static::$mode . '.css' ]->uriVersioned,
                '@so_page_script' => (string) $moduleMix[ static::$mode . '.js' ]->uriVersioned,
                '@so_page_icon' => (string) so_file::make( 'icon.png' )->uriVersioned,
                $data,
            ),
        );
        
        return so_dom::make( $page )->doc;
    }

}