<?php

class so_page
{

    static function make( $data ){
        $page= array();
        
        $mix= so_WC_Root::make()->currentPack->createModule( '-mix' );
        
        $page[ '?xml-stylesheet' ]= array(
            'href' => '-mix/index.xsl?' . $mix->createFile( 'index.xsl' )->version,
            'type' =>'text/xsl',
        );
        
        $page[ 'html' ]= array(
            //'@xmlns' => 'http://www.w3.org/1999/xhtml',
            'so_page' => array(
                'so_page_script' => '-mix/index.js?' . $mix->createFile( 'index.js' )->version,
                'so_page_stylesheet' => '-mix/index.css?' . $mix->createFile( 'index.css' )->version,
                $data,
            ),
        );
        
        return so_dom::make()->append( $page );
    }

}