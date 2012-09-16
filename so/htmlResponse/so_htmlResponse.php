<?php

class so_htmlResponse
{
    use so_meta;

    public $type= 'text/html';
    public $encoding= 'utf-8';
    public $status= '';
    public $location= '';
    public $content= '';
    
    function error( $error ){
        $this->status= 'error';
        $this->content .= "<pre>{$error}</pre>";
        return $this;
    }

    function ok( $content ){
        $this->status= 'ok';
        $this->content= $content;
        return $this;
    }

    function found( $location ){
        $this->status= 'found';
        $this->location= $location;
        $this->content= "Found: {$location}";
        return $this;
    }

}
