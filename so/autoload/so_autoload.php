<?php

if( !class_exists( 'so_autoload' ) ):

    class so_autoload
    {
    
        static function load( $class ){
            static $root;
            if( !$root ) $root= dirname( dirname( dirname( __FILE__ ) ) );
            
            $class= strtr( $class, array( '\\' => '_') );
            $chunks= explode( '_', $class );
            
            $pack= $chunks[0];
            $module= &$chunks[1];
            if( !$module ) $module= $pack;
            
            $path2class= "{$class}.php";
            $path= "{$root}/{$pack}/{$module}/{$class}.php";
            
            if( file_exists( $path ) ) include_once( $path );
        }
    
    }
    
    spl_autoload_register(array( 'so_autoload', 'load' ));

endif;