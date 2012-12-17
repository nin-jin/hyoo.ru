<?php

class so_compiler
{
    use so_meta;
    use so_factory;
    
    static function start( $package= null ){
        $packages= $package ? array( $package ) : so_root::make()->packages;
        foreach( $packages as $package )
            static::make()->package( $package )->clean()->compile()->minify()->bundle()->dumpDepends();
    }
    
    var $package_value;
    var $package_depends= array( 'package', 'modules' );
    function package_make( ){
        throw new \Exception( "Property [package] is not defined" );
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
            $list+= $source->sources->list;
        endforeach;
        
        return so_source_collection::make( $list );
    }
    
    var $sourcesCSS_value;
    function sourcesCSS_make( ){
        $list= array();
        foreach( $this->sources as $source )
            if( $source->extension == 'css' )
                $list+= $source->sources->list;
        return so_source_collection::make( $list );
    }
    
    var $sourcesXSL_value;
    function sourcesXSL_make( ){
        $list= array();
        foreach( $this->sources as $source )
            if( $source->extension == 'xsl' )
                $list+= $source->sources->list;
        return so_source_collection::make( $list );
    }
    
    var $sourcesDOC_value;
    function sourcesDOC_make( ){
        $list= array();
        foreach( $this->sources as $source )
            if( $source->extension == 'xhtml' )
                $list+= $source->sources->list;
        return so_source_collection::make( $list );
    }
    
    var $sourcesPHP_value;
    function sourcesPHP_make( ){
        $list= array();
        foreach( $this->sources as $source )
            if( $source->extension == 'php' )
                $list+= $source->sources->list;
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
        $this->compileDOC();
        $this->copyAddons();
        return $this;
    }
    
    function compileJS( ){
        $sources= $this->sourcesJS;
        $target= $this->target;
        
        if( !count( $sources ) )
            return $this;
        
        $index= <<<JS
;(function( modules ){
    var scripts= document.getElementsByTagName( 'script' )
    var script= document.currentScript || scripts[ scripts.length - 1 ]
    var dir= script.src.replace( /[^\/]+$/, '' )
        
    var next= function( ){
        var module= modules.shift()
        if( !module ) return
        var loader= document.createElement( 'script' )
        loader.parentScript= script
        loader.src= dir + module
        loader.onload= next
        script.parentNode.insertBefore( loader, script )
    }
    next()
}).call( this, [

JS;
        
        foreach( $sources as $source )
            $index.= "    '" .  $source->file->relate( $target->dir ) . '?' . $source->version . "', \n";
        
        $index.= "    null \n])\n";
        
        $target[ 'dev.js' ]->content= $index;
        
        $compiled= array();
        foreach( $sources as $source ):
            $content= $source->content;
            $content= preg_replace( '~^\s*//.*$\n~m', '', $content );
            $content= preg_replace( '~\n/\\*[\w\W]*?\\*/~', '', $content );
            
            $compiled[]= "// " . $source->file->uri . "\n" .  $content;
        endforeach;
        $compiled= implode( ";\n", $compiled );
        
        $target[ 'release.js' ]->content= $compiled;
        
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
        
        if( !count( $sources ) )
            return $this;
        
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
                    $pageContent[]= "@import url( '" . $source->file->relate( $target->dir ) . '?' . $source->version . "' );";
                endforeach;
                $pageFile= $target[ "page_{$pageNumb}.css" ]->file;
                $pageFile->content= implode( "\n", $pageContent );
                $index[]= "@import url( '" . $pageFile->relate( $target->dir ) . '?' . $pageFile->version . "' );";
            endforeach;
        else:
            foreach( $sources as $source )
                $index[]= "@import url( '" . $source->file->relate( $target->dir ) . '?' . $source->version . "' );";
        endif;
        $index= implode( "\n", $index );
        
        $target[ 'dev.css' ]->content= $index;
        
        $compiled= array();
        foreach( $sources as $source )
            $compiled[]= "/* " . $source->file->relate( $target->dir ) . '?' . $source->version . " */\n\n" .  $source->content;
        $compiled= implode( "\n\n", $compiled );
        
        $target[ 'release.css' ]->content= $compiled;
        
        return $this;
    }
    
    function compileXSL( ){
        $sources= $this->sourcesXSL;
        $target= $this->target;
        
        if( !count( $sources ) )
            return $this;
        
        $index= so_dom::make( array(
            'xsl:stylesheet' => array(
                '@version' => '1.0',
                '@xmlns:xsl' => 'http://www.w3.org/1999/XSL/Transform',
            ),
        ) );
        
        foreach( $this->exclude as $package ):
            $file= $package[ '-mix' ][ 'dev.xsl' ]->file;
            $index[]= array( 'xsl:include' => array(
                '@href' => $file->relate( $target->dir ) . '?' . $file->version,
            ) );
        endforeach;
        
        foreach( $sources as $source ):
            $index[]= array( 'xsl:include' => array(
                '@href' => $source->file->relate( $target->dir ) . '?' . $source->version,
            ) );
        endforeach;
        
        $target[ 'dev.xsl' ]->content= $index;
        
        $compiled= so_dom::make( array(
            'xsl:stylesheet' => array(
                '@version' => '1.0',
                '@xmlns:xsl' => 'http://www.w3.org/1999/XSL/Transform',
            ),
        ) );
        
        foreach( $this->exclude as $package ):
            $file= $package[ '-mix' ][ 'release.xsl' ]->file;
            $compiled[]= array( 'xsl:include' => array(
                '@href' => $file->relate( $target->dir ) . '?' . $file->version,
            ) );
        endforeach;
        
        foreach( $sources as $source ):
            $compiled[]= array(
                '#comment' => " " . $source->file->relate( $target->dir ) . '?' . $source->version . " ",
                $source->content->childs,
            );
        endforeach;
        
        $target[ 'release.xsl' ]->content= $compiled;
        
        return $this;
    }
    
    function compileDOC( ){
        $sources= $this->sourcesDOC;
        $target= $this->target;
        
        if( !count( $sources ) )
            return $this;
        
        $index= so_dom::make();
        $index[]= array(
            '?xml-stylesheet' => array(
                'href' => '../../doc/-mix/dev.xsl',
                'type' => 'text/xsl',
            ),
            'doc_list' => array(
                //'@xmlns' => 'http://www.w3.org/1999/xhtml',
            ),
        );
        
        $root= $index->root;
        foreach( $sources as $source ):
            $root[]= array(
                'doc_root' => array(
                    '@doc_link' => $source->file->relate( $target->dir ) . '?' . $source->version,
                    '@doc_title' => $source->content[ '@doc_title' ],
                ),
            );
        endforeach;
        
        $target[ 'dev.doc.xhtml' ]->content= $index;
        
        $compiled= so_dom::make();
        $compiled[]= array(
            '?xml-stylesheet' => array(
                'href' => '../../doc/-mix/release.xsl',
                'type' => 'text/xsl',
            ),
            'doc_list' => array(
                //'@xmlns' => 'http://www.w3.org/1999/xhtml',
            ),
        );
        
        $root= $compiled->root;
        foreach( $sources as $source ):
            $source->content[ '@doc_link' ]= $source->file->relate( $target->dir ) . '?' . $source->version;
            $root[]= $source->content;
        endforeach;
        
        $target[ 'release.doc.xhtml' ]->content= $compiled;
        
        return $this;
    }
    
    function compilePHP( ){
        $sources= $this->sourcesPHP;
        $target= $this->target;
        
        if( !count( $sources ) )
            return $this;
        
        $index= array( "<?php" );
        foreach( $sources as $source )
            $index[]= "require( __DIR__ . '/" . $source->file->relate( $target->dir ) . "' );";
        $index= implode( "\n", $index );
        
        $target[ 'dev.php' ]->content= $index;
        
        $compiled= array( "<?php namespace " . $this->package->name . ";" );
        foreach( $sources as $source )
            $compiled[]= "// " . $source->file->relate( $target->dir ) . " \n" . substr( $source->content, 6 );
        $compiled= implode( "\n\n", $compiled );
        
        $target[ 'release.php' ]->content= $compiled;
        
        return $this;
    }
    
    function copyAddons( ){
        $target= $this->target->dir;
        foreach( $this->modules as $key => $module ):
            foreach( $module->dir->childs as $dir ):
                if( $dir->type != 'dir' )
                    continue;
                $dir->copy( $target[ $dir->name ] );
            endforeach;
        endforeach;
    }
    
    function minify( ){
        $target= $this->target;
        
        $minifiedJS= $target[ 'library.js' ]->content;
        $minifiedJS= preg_replace( '~^\s+~m', '', $minifiedJS );
        $minifiedJS= preg_replace( '~^//.*$\n~m', '', $minifiedJS );
        $minifiedJS= preg_replace( '~\n/\\*[\w\W]*?\\*/~', '', $minifiedJS );
        
        $minifiedCSS= $target[ 'release.css' ]->content;
        $minifiedCSS= preg_replace( '~/\\*[\w\W]*?\\*/~', '', $minifiedCSS );
        $minifiedCSS= preg_replace( '~^\s+~m', '', $minifiedCSS );
        $minifiedCSS= preg_replace( '~[\r\n]~', '', $minifiedCSS );
        
        $minifiedXSL= null;
        if( $target[ 'release.xsl' ]->exists ):
            $minifiedXSL= $target[ 'release.xsl' ]->content;
            $doc= new \DOMDocument();
            $doc->formatOutput= false;
            $doc->preserveWhiteSpace= false;
            $doc->loadXML( (string) $minifiedXSL );
            $minifiedXSL= $doc->C14N();
        endif;
        
        $minifiedPHP= $target[ 'release.php' ]->content;
        
        if( $minifiedJS ) $target[ 'minified.js' ]->content= $minifiedJS;
        if( $minifiedCSS ) $target[ 'minified.css' ]->content= $minifiedCSS;
        if( $minifiedXSL ) $target[ 'minified.xsl' ]->content= so_dom::make( $minifiedXSL );
        if( $minifiedPHP ) $target[ 'minified.php' ]->content= $minifiedPHP;
        
        return $this;
    }
    
    function bundle( ){
        $target= $this->target;
        
        $bundleCSS= $target[ 'minified.css' ]->content;
        if( $bundleCSS ):
            $replacer= function( $matches ) use( $target ) {
                list( $str, $prefix, $url, $postfix )= $matches;
                $file= $target->dir->go( $url );
                $type= image_type_to_mime_type( exif_imagetype( $file ) );
                return $prefix . 'data:' . $type . ';base64,' . base64_encode( $file->content ) . $postfix;
            };
            $bundleCSS= preg_replace_callback( "~(url\(\s*')([^:]+(?:/|$).*?)('\s*\))~", $replacer, $bundleCSS );
            
            $target[ 'bundle.css' ]->content= $bundleCSS;
        endif;
        
        if( $bundleJS= $target[ 'minified.js' ]->content ):
            $bundleCSS= strtr( $bundleCSS, array( '"' => '\\"', '\\' => '\\\\' ) );
            $bundleJS= <<<JS
document.getElementsByTagName( 'head' )[0].appendChild( document.createElement( 'style' ) ).textContent= "{$bundleCSS}"
{$bundleJS}
JS;
            $target[ 'bundle.js' ]->content= $bundleJS;
        endif;
        
        return $this;
    }
    
    function dumpDepends( ){
        $root= so_root::make();
        
        $map= array();
        foreach( $this->modules as $base ):
            foreach( $base->uses as $target ):
                $map[ $target->dir->relate( $root->dir ) ][ $base->dir->relate( $root->dir ) ]= 1;
            endforeach;
        endforeach;
        foreach( $map as &$module ):
            $module= array_keys( $module );
        endforeach;
        
        $this->target[ 'depends.json' ]->content= json_encode( $map, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES );
    }
    
}