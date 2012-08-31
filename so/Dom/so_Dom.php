<?php

class so_dom
extends so_meta
implements Countable, ArrayAccess, IteratorAggregate
{

    static function make( $DOMNode= null ){
        
        if( !isset( $DOMNode ) ):
            return new self;
        elseif( $DOMNode instanceof so_dom ):
            return $DOMNode;
        elseif( is_string( $DOMNode ) ):
            $DOMNode= DOMDocument::loadXML( $DOMNode, LIBXML_COMPACT )->documentElement;
        elseif( $DOMNode instanceof SimpleXMLElement ):
            $DOMNode= dom_import_simplexml( $DOMNode );
        elseif( $DOMNode instanceof DOMNode ):
            $DOMNode= $DOMNode->cloneNode( true );
        else:
            return so_dom::make()->append( $DOMNode )->root;
        endif;
        
        return so_dom::wrap( $DOMNode );
    }
    
    static function wrap( $DOMNode ){
        if( !( $DOMNode instanceof DOMNode ) ) throw new Exception( "[{$DOMNode}] is not a DOMNode" );
        $dom= new so_dom;
        $dom->DOMNode= $DOMNode;
        return $dom;
    }

    protected $_DOMNode;
    function get_DOMNode( $DOMNode ){
        if( !isset( $DOMNode ) ) return new DOMDocument;
        return $DOMNode;
    }
    function set_DOMNode( $DOMNode ){
        if( isset( $this->DOMNode ) ) throw new Exception( 'Redeclaration of [DOMNode]' );
        return $DOMNode;
    }

    protected $_doc;
    function get_doc( $doc ){
        if( isset( $doc ) ) return $doc;
        $DOMDocument= $this->DOMNode->ownerDocument;
        if( $DOMDocument ) return so_dom::wrap( $this->DOMDocument );
        return $this;
    }

    protected $_root;
    function get_root( $root ){
        if( isset( $root ) ) return $root;
        $rootElement= $this->DOMDocument->documentElement;
        if( !$rootElement ) throw new Exception( "Document have not a root element" );
        return so_dom::wrap( $rootElement );
    }

    protected $_DOMDocument;
    function get_DOMDocument( $DOMDocument ){
        if( isset( $DOMDocument ) ) return $DOMDocument;
        $DOMNode= $this->DOMNode;
        $DOMDocument= $DOMNode->ownerDocument;
        if( $DOMDocument ) return $DOMDocument;
        return $DOMNode;
    }
    
    function __toString( ){
        return $this->DOMDocument->saveXML( $this->DOMNode );
    }
    
    protected $_name;
    function get_name( $name ){
        if( isset( $name ) ) return $name;

        $DOMNode= $this->DOMNode;
        $name= $DOMNode->nodeName;
        
        if( $DOMNode instanceof DOMAttr ):
            return "@{$name}";
        endif;
        
        if( $DOMNode instanceof DOMProcessingInstruction ):
            return "?{$name}";
        endif;

        return $name;
    }

    protected $_value;
    function get_value( $value ){
        return $this->DOMNode->nodeValue;
    }

    protected $_childList;
    function get_childList( $childList ){
        $list= new so_dom_List;
        $list->DOMNodeList= $this->DOMNode->childNodes;
        return $list;
    }

    protected $_attrList;
    function get_attrList( $attrList ){
        $list= new so_dom_List;
        $list->DOMNodeList= $this->DOMNode->attributes;
        return $list;
    }

    function append( ){
        foreach( func_get_args() as $arg ):
            if( is_null( $arg ) ) continue 1;
            
            $DOMNode= $this->DOMNode;
            
            if( is_scalar( $arg ) ):
                $arg= $this->DOMDocument->createTextNode( $arg );
                $DOMNode->appendChild( $arg );
                continue 1;
            endif;
            
            if( is_object( $arg ) ):
                if( $arg instanceof SimpleXMLElement ):
                    $arg= dom_import_simplexml( $arg );
                    $DOMNode->appendChild( $arg );
                    continue 1;
                endif;
                
                if( $arg->DOMNode ):
                    $arg= $arg->DOMNode;
                endif;
                
                if( $arg instanceof DOMDocument ):
                    foreach( $arg->childNodes as $node ):
                        $this->append( $node );
                    endforeach;
                    continue 1;
                endif;
                
                if( $arg instanceof DOMNode ):
                    $arg= $this->DOMDocument->importNode( $arg->cloneNode( true ), true );
                    $DOMNode->appendChild( $arg );
                    continue 1;
                endif;
            endif;
            
            foreach( $arg as $key => $value ):
                if( is_numeric( $key ) ):
                    $this->append( $value );
                    continue 1;
                endif;
                
                if( $key[0] === '#' ):
                    if( !is_scalar( $value ) ):
                        $value= '' . so_dom::make( $value )->root();
                    endif;

                    if( $key === '#text' ):
                        $value= $this->DOMDocument->createTextNode( $value );
                        $DOMNode->appendChild( $value );
                        continue 1;
                    endif;
                            
                    if( $key === '#comment' ):
                        $value= $this->DOMDocument->createComment( $value );
                        $DOMNode->appendChild( $value );
                        continue 1;
                    endif;
                            
                    throw new Exception( "Wrong special element name [{$key}]" );
                endif;
                        
                if( $key[0] === '@' ):
                    $name= substr( $key, 1 );
                    
                    if( !is_scalar( $value ) ):
                        $value= '' . so_dom::make( $value )->root();
                    endif;

                    $DOMNode->setAttribute( $name, $value );
                    continue 1;
                endif;
                        
                if( $key[0] === '?' ):
                    $name= substr( $key, 1 );
                    
                    if( is_array( $value ) ):
                        $valueList= array();
                        foreach( $value as $k => $v ):
                            $valueList[]= htmlspecialchars( $k ) . '="' . htmlspecialchars( $v ) . '"';
                        endforeach;
                        $value= implode( " ", $valueList );
                    endif;
                    
                    $content= $this->DOMDocument->createProcessingInstruction( $name, $value );
                    $DOMNode->appendChild( $content );
                    continue 1;
                endif;
                
                if( $key === '!DOCTYPE' ):
                    $name= substr( $key, 1 );
                    
                    if( !$value ):
                        $value= array();
                    endif;
                    
                    $implementation= $this->DOMDocument->implementation;
                    $content= $implementation->createDocumentType( $value[ 'name' ], $value[ 'public' ], $value[ 'system' ] );
                    $DOMNode->appendChild( $content ); // FIXME: не работает =(
                    continue 1;
                endif;
                
                $element= $this->DOMDocument->createElement( $key );
                so_dom::wrap( $element )->append( $value );
                $DOMNode->appendChild( $element );
            endforeach;

        endforeach;
        return $this;
    }
    
    function select( $query ){
        $xpath = new DOMXPath( $this->doc->DOMNode );
        $found= $xpath->query( $query, $this->DOMNode );
        $nodeList= array();
        foreach( $found as $node ):
            $nodeList[]= so_dom::make( $node );
        endforeach;
        return $nodeList;
    }
    
    function drop( ){
        echo '[' . htmlentities( $this->root ) . ']';
        $this->DOMNode->parentNode->removeChild( $this->DOMNode );
        return $this;
    }
    
    function count( ){
        $DOMNode= $this->DOMNode;
        return $DOMNode->attributes->length + $DOMNode->childNodes->length;
    }

    function offsetExists( $key ){
        if( $key[0] === '@' ):
            $name= substr( $key, 1 );
            return $this->DOMNode->hasAttribute( $name );
        endif;
        
        foreach( $this->DOMNode->childNodes as $child ):
            if( $child->nodeName !== $key ) continue;
            return true;
        endforeach;
        
        return false;
    }
    
    function offsetGet( $key ){
        if( $key[0] === '@' ):
            $name= substr( $key, 1 );
            return so_dom::wrap( $this->DOMNode->getAttributeNode( $name ) );
        endif;

        foreach( $this->DOMNode->childNodes as $child ):
            if( $child->nodeName !== $key ) continue;
            return so_dom::wrap( $child );
        endforeach;
        
        return null;
    }
    
    function offsetSet( $key, $value ){
        if( !$key ):
            $this->append( $value );
            return $this;
        endif;
        
        if( $key[0] === '@' ):
            $this->append(array( $key => $value ));
            return $this;
        endif;
        
        unset( $this[ $key ] );
        
        $this->append(array( $key => $value ));
        return $this;
    }
    
    function offsetUnset( $key ){
        if( $key[0] === '@' ):
            $name= substr( $key, 1 );
            $this->DOMNode->removeAttribute( $name );
            return $this;
        endif;

        foreach( $this->DOMNode->childNodes as $child ):
            if( $child->nodeName !== $key ) continue;
            $this->DOMNode->removeChild( $child );
        endforeach;
        
        return $this;
    }
    
    function getIterator( ){
        $list= array();

        if( $attributes= $this->DOMNode->attributes ):
            foreach( $attributes as $child ):
                $list[]= $child;
            endforeach;
        endif;

        foreach( $this->DOMNode->childNodes as $child ):
            $list[]= $child;
        endforeach;
        
        return so_dom_Iterator::make( $list );
    }
    
}
