<?php

class so_xmlResponse
{
    use so_meta;

    public $type= 'application/xml';
    public $encoding= 'utf-8';
    public $status= '';
    public $location= '';

    protected $_content;
    function get_content( $content ){
        if( isset( $content ) ) return $content;
        
        $response= array();
        
        $mix= so_file::make( 'so/-mix/' );
        
        $response[ '!DOCTYPE' ]= array( 'name' => 'html' );
        
        $response[ '?xml-stylesheet' ]= array(
            'href' => 'so/-mix/index.xsl?' . $mix->go( 'index.xsl' )->version,
            'type' =>'text/xsl',
        );
        
        $response[ 'html' ]= array(
            '@xmlns' => 'http://www.w3.org/1999/xhtml',
            '@xmlns:html' => 'http://www.w3.org/1999/xhtml',
            '@xmlns:so' => 'https://github.com/nin-jin/so',
            'so_page' => array(
                'so_page_script' => 'so/-mix/index.js?' . $mix->go( 'index.js' )->version,
                'so_page_stylesheet' => 'so/-mix/index.css?' . $mix->go( 'index.css' )->version,
            ),
        );
        
        return so_dom::make()->append( $response );
    }
    
    function error( $error ){
        $this->status= 'error';
        $this->content->root['so_page']->append( array(
            'so_error' => (string)$error,
        ) );
        return $this;
    }

    function ok( $content ){
        $this->status= 'ok';
        $this->content->root['so_page']->append( $content );
        return $this;
    }

    function missed( $content ){
        $this->status= 'missed';
        $this->content->root['so_page']->append( $content );
        return $this;
    }

    function found( $location ){
        $this->status= 'found';
        $this->location= $location;
        $this->content->root['so_page']->append( array(
            'so_found' => (string)$location,
        ) );
        return $this;
    }

    function moved( $location ){
        $this->status= 'moved';
        $this->location= $location;
        $this->content->root['so_page']->append( array(
            'so_moved' => (string)$location,
        ) );
        return $this;
    }

}
