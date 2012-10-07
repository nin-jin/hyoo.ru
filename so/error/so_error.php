<?php

class so_error
{

    static $codeMap= array(
        E_ERROR => 'Error',
        E_WARNING => 'Warning',
        E_PARSE => 'Parse',
        E_NOTICE => 'Notice',
        E_CORE_ERROR => 'Core Error',
        E_COMPILE_WARNING => 'Core Warning',
        E_COMPILE_ERROR => 'Compile Error',
        E_COMPILE_WARNING => 'Compile Warning',
        E_USER_ERROR => 'User Error',
        E_USER_WARNING => 'User Warning',
        E_USER_NOTICE => 'User Notice',
        E_STRICT => 'Strict',
        E_RECOVERABLE_ERROR => 'Recoverable Error',
        E_DEPRECATED => 'Deprecated',
        E_USER_DEPRECATED => 'User Deprecated',
    );

    static function handle( $code , $message, $file, $line, $context ){
        echo "\n" . static::$codeMap[ $code ] . ': ' . $message . "\n";
        echo "#0 " . $file . '(' . $line . ")\n";
        
        $exception= new ErrorException( '', $code, 0, $file, $line );
        $trace= $exception->getTraceAsString();
        $trace= preg_replace( '~^.*?\n|\n.*?$~', '', $trace );
        
        echo $trace . "\n";
        
        return true;
    }
    
    static function monitor( ){
        set_error_handler( array( 'so_error', 'handle' ), E_ALL );
    }
    
    static function trigger( $message ){
        trigger_error( $message, E_USER_WARNING );
    }
    
}

