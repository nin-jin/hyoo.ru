<?php

class so_htmlResponse
extends so_meta
{

    public $type= 'text/html';
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
