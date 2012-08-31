<?php

class so_textResponse
extends so_meta
{

    public $type= 'text/plain';
    public $encoding= 'utf-8';
    public $status= '';
    public $location= '';
    public $content= '';
    
    function error( $error ){
        $this->status= 'error';
        $this->content= "Error: {$error}";
    }

    function ok( $content ){
        $this->status= 'ok';
        $this->content= $content;
    }

    function found( $location ){
        $this->status= 'found';
        $this->location= $location;
        $this->content= "Found: {$location}";
    }

}
