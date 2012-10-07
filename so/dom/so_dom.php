<?php

class so_dom
implements Countable, ArrayAccess, IteratorAggregate
{
    use so_meta;

    static function make( $DOMNode= null ){
        
        if( !isset( $DOMNode ) )
            return new static;
        
        if( $DOMNode instanceof so_dom )
            return $DOMNode;
        
        $obj= new static;
        
        if( $DOMNode instanceof DOMNode )
            return $obj->DOMNode( $DOMNode );
        
        if( is_string( $DOMNode ) ):
            $doc= new DOMDocument( '1.0', 'utf-8' );
            $doc->loadXML( $DOMNode, LIBXML_COMPACT );
            return $obj->DOMNode( $doc->documentElement );
        endif;
        
        if( $DOMNode instanceof SimpleXMLElement )
            return $obj->DOMNode( dom_import_simplexml( $DOMNode ) );
        
        if( is_array( $DOMNode ) ):
            $obj[]= $DOMNode;
            return $obj->root;
        endif;
        
        throw new Exception( 'Unsupported type of argument' );
    }
    
    static function ensure( &$value ){
        return $value= static::make( $value );
    }
    
    public $mime= 'application/xml';

    var $DOMNode_value;
    function DOMNode_make( ){
        return new DOMDocument( '1.0', 'utf-8' );
    }
    function DOMNode_store( $value ){
        return $value;
    }

    var $DOMDocument_value;
    function DOMDocument_make( ){
        $DOMNode= $this->DOMNode;
        $DOMDocument= $DOMNode->ownerDocument;
        if( $DOMDocument ) return $DOMDocument;
        return $DOMNode;
    }
    
    var $doc_value;
    function doc_make( ){
        return so_dom::make( $this->DOMDocument );
    }

    var $root_value;
    function root_make( ){
        $rootElement= $this->DOMDocument->documentElement;
        if( !$rootElement ) throw new Exception( "Document have not a root element" );
        return so_dom::make( $rootElement );
    }

    function _string_meta( ){
        return $this->DOMDocument->saveXML( $this->DOMNode );
    }
    
    var $name_value;
    function name_make( ){
        return $this->DOMNode->nodeName;
    }

    var $value_value;
    function value_make( ){
        return $this->DOMNode->nodeValue;
    }
    
    var $parent_value;
    function parent_make( ){
        $parent= $this->DOMNode->parentNode;
        if( !$parent ) return null;
        return so_dom::make( $parent );
    }
    function parent_store( $parent ){
        if( $parent ):
            $parent= so_dom::make( $parent );
            $parent[]= $this;
            return $parent;
        else:
            $DOMNode= $this->DOMNode;
            $DOMNode->parentNode->removeChild( $DOMNode );
        endif;
    }

    var $childs_value;
    function childs_make( ){
        $list= array();
        foreach( $this->DOMNode->childNodes as $node )
            $list[]= $node;
        return so_dom_collection::make( $list );
    }

    var $attrs_value;
    function attrs_make( ){
        $list= array();
        foreach( $this->DOMNode->attributes as $node )
            $list[]= $node;
        return so_dom_collection::make( $list );
    }

    function select( $query ){
        $xpath = new domxPath( $this->DOMDocument );
        $found= $xpath->query( $query, $this->DOMNode );
        $nodeList= array();
        foreach( $found as $node ):
            $nodeList[]= $node;
        endforeach;
        return so_dom_collection::make( $nodeList );
    }
    
    function drop( ){
        $DOMNode= $this->DOMNode;
        $DOMNode->parentNode->removeChild( $DOMNode );
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
        
        return isset( $this->child[ $key ] );
    }
    
    function offsetGet( $key ){
        if( $key[0] === '@' ):
            $name= substr( $key, 1 );
            return $this->DOMNode->getAttribute( $name );
        endif;
        
        $list= array();
        foreach( $this->child as $item ):
            if( $item->name != $key ) continue;
            $list= array_merge( $list, $item->childs->list );
        endforeach;
        
        return so_dom_collection::make( $list );
    }
    
    function offsetSet( $key, $value ){
        if( !$key ):
            if( !isset( $value ) )
                return $this;
            
            $DOMNode= $this->DOMNode;
            
            if( is_scalar( $value ) ):
                $value= $this->DOMDocument->createTextNode( $value );
                $DOMNode->appendChild( $value );
                return $this;
            endif;
            
            if( is_object( $value ) ):
                if( $value instanceof SimpleXMLElement ):
                    $value= dom_import_simplexml( $value );
                    $DOMNode->appendChild( $value );
                    return $this;
                endif;
                
                if( isset( $value->DOMNode ) ):
                    $value= $value->DOMNode;
                endif;
                
                if( $value instanceof DOMDocument ):
                    foreach( $value->childNodes as $node ):
                        $this[]= $node;
                    endforeach;
                    return $this;
                endif;
                
                if( $value instanceof DOMNode ):
                    $value= $this->DOMDocument->importNode( $value->cloneNode( true ), true );
                    $DOMNode->appendChild( $value );
                return $this;
                endif;
            endif;
            
            foreach( $value as $key => $value ):
                
                if( is_int( $key ) ):
                    $this[]= $value;
                    continue 1;
                endif;
                
                if( $key[0] === '#' ):
                    if( !is_scalar( $value ) ):
                        $value= (string) so_dom::make( $value );
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
                        $value= (string) so_dom::make( $value );
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
                    $public= &$value[ 'public' ];
                    $system= &$value[ 'system' ];
                    
                    $implementation= $this->DOMDocument->implementation;
                    $content= $implementation->createDocumentType( $value[ 'name' ], $public, $system );
                    $DOMNode->appendChild( $content ); // FIXME: не работает =(
                    continue 1;
                endif;
                
                $keys= explode( '/', $key );
                while( count( $keys ) > 1 )
                    $value= array( array_pop( $keys ) => $value );
                $key= $keys[0];
                
                $element= $this->DOMDocument->createElement( $key );
                so_dom::make( $element )[]= $value;
                $DOMNode->appendChild( $element );
            endforeach;
            return $this;
        endif;
        
        if( $key[0] === '@' ):
            $name= substr( $key, 1 );
            $this->DOMNode->setAttribute( $name, $value );
            return $this;
        endif;
        
        $this->child[ $key ]->parent= null;
        
        $this[]= array( $key => $value );
        return $this;
    }
    
    function offsetUnset( $key ){
        if( $key[0] === '@' ):
            $name= substr( $key, 1 );
            $this->DOMNode->removeAttribute( $name );
            return $this;
        endif;

        $this->child[ $key ]->parent= null;
        
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
        
        return so_dom_collection::make( $list )->getIterator();
    }
    
}
