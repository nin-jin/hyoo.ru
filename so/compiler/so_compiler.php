<?php

class so_compiler
{
    use so_meta2;
    use so_factory;
    
    var $package_value;
    var $package_depends= array( 'package', 'modules' );
    function package_make( ){
        throw new Exception( "Property [package] is not defined" );
    }
    function package_store( $data ){
        return so_package::make( $data );
    }

    var $modules_value;
    var $modules_depends= array( 'package', 'modules' );
    function modules_make( ){
        return $this->package->depends;
    }
    function modules_store( $data ){
        return so_module_collection::make( $data );
    }

    var $sources_value;
    function sources_make( ){
        $exclude= $this->exclude->depends;
        $sources= array();
        
        foreach( $this->modules as $key => $module ):
            if( isset( $exclude[ $key ] ) ) continue;
            $sources+= $module->sources->list;
        endforeach;
        
        return so_source_collection::make( $sources );
    }

    var $exclude_value;
    function exclude_make( ){
        return so_package_collection::make();
    }
    function exclude_store( $data ){
        return so_package_collection::make( $data );
    }

    var $target_value;
    function target_make( ){
        return $this->package[ '-mix' ];
    }
    function target_store( $data ){
        return so_module::make( $data );
    }
    
    var $sourcesJS_value;
    function sourcesJS_make( ){
        $list= array();
        
        foreach( $this->sources as $source ):
            if( $source->extension != 'js' ) continue;
            $list[]= $source;
        endforeach;
        
        return so_source_collection::make( $list );
    }
    
    var $sourcesCSS_value;
    function sourcesCSS_make( ){
        $list= array();
        foreach( $this->sources as $source )
            if( $source->extension == 'css' )
                $list[]= $source;
        return so_source_collection::make( $list );
    }
    
    var $sourcesXSL_value;
    function sourcesXSL_make( ){
        $list= array();
        foreach( $this->sources as $source )
            if( $source->extension == 'xsl' )
                $list[]= $source;
        return so_source_collection::make( $list );
    }
    
    var $sourcesPHP_value;
    function sourcesPHP_make( ){
        $list= array();
        foreach( $this->sources as $source )
            if( $source->extension == 'php' )
                $list[]= $source;
        return so_source_collection::make( $list );
    }
    
    function clean( ){
        $this->target->dir->childs->exists= false;
        return $this;
    }
    
    function compile( ){
        $this->compileJS();
        $this->compileCSS();
        $this->compileXSL();
        $this->compilePHP();
        return $this;
    }
    
    function compileJS( ){
        $sources= $this->sourcesJS;
        $target= $this->target;
        
        $index= <<<JS
;(function( modules ){
    var scripts= document.getElementsByTagName( 'script' )
    var script= document.currentScript || scripts[ scripts.length - 1 ]
    var dir= script.src.replace( /[^\/]+$/, '' )
        
    try {
        document.write( '' )
        var canWrite= true
    } catch( e ){ }
    
    if( canWrite ){
        var module
        while( module= modules.shift() ){
            document.write( '<script src="' + dir + module + '"><' + '/script>' )
        }
    } else {
        var next= function( ){
            var module= modules.shift()
            if( !module ) return
            var loader= document.createElement( 'script' )
            loader.src= dir + module
            loader.onload= next
            script.parentNode.insertBefore( loader, script )
        }
        next()
    }
}).call( this, [

JS;
        
        foreach( $sources as $source )
            $index.= "    '../../" .  $source->uri . "', \n";
        
        $index.= "    null \n])\n";
        
        $target[ 'index.js' ]->content= $index;
        
        $compiled= array();
        foreach( $sources as $source )
            $compiled[]= "// ../../" . $source->uri . "\n\n" .  $source->content;
        $compiled= implode( "\n\n", $compiled );
        
        $target[ 'compiled.js' ]->content= $compiled;
        
        $library= <<<JS
new function( window, document ){
    with ( this ){
{$compiled}
        var scripts= document.getElementsByTagName( 'script' )
        var currentScript= document.currentScript || scripts[ scripts.length - 1 ]
        if( currentScript.src ) eval( currentScript.innerHTML )
    }
}( this.window, this.document )
JS;
        $target[ 'library.js' ]->content= $library;
        
        return $this;
    }
    
    function compileCSS( ){
        $sources= $this->sourcesCSS;
        $target= $this->target;
        
        $index= array();
        if( count( $sources ) > 32 ):
            $pages= array();
            $i= 0;
            foreach( $sources as $source ):
                $pageNumb= floor( $i++ / 30 );
                $pages[ $pageNumb ][]= $source;
            endforeach;
            foreach( $pages as $pageNumb => $page ):
                $page= so_source_collection::make( $page );
                $pageContent= array();
                foreach( $page as $source ):
                    $pageContent[]= "@import url( '../../{$source->uri}' );";
                endforeach;
                $pageFile= $target[ "page_{$pageNumb}.css" ];
                $pageFile->content= implode( "\n", $pageContent );
                $index[]= "@import url( '../../{$pageFile->uri}' );";
            endforeach;
        else:
            foreach( $sources as $source )
                $index[]= "@import url( '../../" . $source->uri . "' );";
        endif;
        $index= implode( "\n", $index );
        
        $target[ 'index.css' ]->content= $index;
        
        $compiled= array();
        foreach( $sources as $source )
            $compiled[]= "/* ../../" . $source->uri . " */\n\n" .  $source->content;
        $compiled= implode( "\n\n", $compiled );
        
        $target[ 'compiled.css' ]->content= $compiled;
        
        return $this;
    }
    
    function compileXSL( ){
        $sources= $this->sourcesXSL;
        $target= $this->target;
        
        $index= so_dom::make( array(
            'xsl:stylesheet' => array(
                '@version' => '1.0',
                '@xmlns:xsl' => 'http://www.w3.org/1999/XSL/Transform',
            ),
        ) );
        
        foreach( $this->exclude as $package ):
            $index[]= array( 'xsl:include' => array(
                '@href' => "../../" . $package[ '-mix' ][ 'index.xsl' ]->uri, 
            ) );
        endforeach;
        
        foreach( $sources as $source ):
            $index[]= array( 'xsl:include' => array(
                '@href' => "../../" . $source->uri, 
            ) );
        endforeach;
        
        $target[ 'index.xsl' ]->content= $index;
        
        $compiled= so_dom::make( array(
            'xsl:stylesheet' => array(
                '@version' => '1.0',
                '@xmlns:xsl' => 'http://www.w3.org/1999/XSL/Transform',
            ),
        ) );
        
        foreach( $this->exclude as $package ):
            $compiled[]= array( 'xsl:include' => array(
                '@href' => "../../" . $package[ '-mix' ][ 'compiled.xsl' ]->uri, 
            ) );
        endforeach;
        
        foreach( $sources as $source ):
            $compiled[]= array(
                '#comment' => " ../../{$source->uri} ",
                $source->content->childs,
            );
        endforeach;
        
        $target[ 'compiled.xsl' ]->content= $compiled;
        
        return $this;
    }
    
    function compilePHP( ){
        $sources= $this->sourcesPHP;
        $target= $this->target;
        
        $index= array( "<?php" );
        foreach( $sources as $source )
            $index[]= "require( '" . $source->uri . "' );";
        $index= implode( "\n", $index );
        
        $target[ 'index.php' ]->content= $index;
        
        $compiled= array( "<?php" );
        foreach( $sources as $source )
            $compiled[]= "// ../../" . $source->uri . " */\n" . substr( $source->content, 6 );
        $compiled= implode( "\n\n", $compiled );
        
        $target[ 'compiled.php' ]->content= $compiled;
        
        return $this;
    }
    
    function minify( ){
        $target= $this->target;
        
        $minifiedJS= $target[ 'library.js' ]->content;
        $minifiedJS= preg_replace( '~/\\*[\w\W]*?\\*/~', '', $minifiedJS );
        $minifiedJS= preg_replace( '~^\s+~m', '', $minifiedJS );
        $minifiedJS= preg_replace( '~//[^"\'\n\r]*?$\n~m', '', $minifiedJS );
        $minifiedJS= preg_replace( '~;[\r\n]~', ';', $minifiedJS );
        
        $minifiedCSS= $target[ 'compiled.css' ]->content;
        $minifiedCSS= preg_replace( '~/\\*[\w\W]*?\\*/~', '', $minifiedCSS );
        $minifiedCSS= preg_replace( '~^\s+~m', '', $minifiedCSS );
        $minifiedCSS= preg_replace( '~[\r\n]~', '', $minifiedCSS );
        
        $minifiedXSL= new DOMDocument();
        $minifiedXSL->formatOutput= false;
        $minifiedXSL->preserveWhiteSpace= false;
        $minifiedXSL->loadXML( (string) $target[ 'compiled.xsl' ]->content );
        $minifiedXSL= $minifiedXSL->C14N();
        
        $minifiedPHP= $target[ 'compiled.php' ]->content;
        
        $target[ 'minified.js' ]->content= $minifiedJS;
        $target[ 'minified.css' ]->content= $minifiedCSS;
        $target[ 'minified.xsl' ]->content= so_dom::make( $minifiedXSL );
        $target[ 'minified.php' ]->content= $minifiedPHP;
        
        return $this;
    }
    
    function bundle( ){
        $target= $this->target;
        
        $replacer= function( $matches ) use( $target ) {
            list( $str, $prefix, $url, $postfix )= $matches;
            $file= $target->dir->go( $url );
            $type= image_type_to_mime_type( exif_imagetype( $file ) );
            return $prefix . 'data:' . $type . ';base64,' . base64_encode( $file->content ) . $postfix;
        };
        $bundleCSS= $target[ 'minified.css' ]->content;
        $bundleCSS= preg_replace_callback( "~(url\(\s*')([^:]+(?:/|$).*?)('\s*\))~", $replacer, $bundleCSS );
        
        $target[ 'bundle.css' ]->content= $bundleCSS;
        
        $bundleCSS= strtr( $bundleCSS, array( '"' => '\\"', '\\' => '\\\\' ) );
        
        $bundleJS= $target[ 'minified.js' ]->content;
        $bundleJS= <<<JS
document.getElementsByTagName( 'head' )[0].appendChild( document.createElement( 'style' ) ).textContent= "{$bundleCSS}"
{$bundleJS}
JS;
        
        $target[ 'bundle.js' ]->content= $bundleJS;
        
        $bundleXSL= $target[ 'minified.xsl' ]->content;
        
        $list= $bundleXSL->select( '/*/xsl:template[ @name = "so_compiler_bundle" ]' );
        if( count( $list ) ):
            $dom= $list[0];
            $dom[]= array(
                'script' => array(
                    $bundleJS,
                )
            );
            
            $target[ 'bundle.xsl' ]->content= $bundleXSL;
        endif;
        
        return $this;
    }
    
}