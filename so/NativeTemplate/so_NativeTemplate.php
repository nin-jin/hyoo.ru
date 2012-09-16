<?php

class so_NativeTemplate
{
    use so_meta;

    public $path= '';
    public $param= array();
    
    protected $_content;
    function get_content(){
        ob_start();
            $this->exec();
        return ob_get_clean();
    }
    
    function exec() {
        extract( $this->param );
        include( $this->path );
    }
} 
