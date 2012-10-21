<?php

class so_query_collection
implements \Countable, \ArrayAccess, \IteratorAggregate
{
    use so_meta;
    use so_collection;
    
    function match( $subject ){
        foreach( $this->list as $pattern )
            if( $pattern->match( $subject ) )
                return true;
        return false;
    }
    
}
