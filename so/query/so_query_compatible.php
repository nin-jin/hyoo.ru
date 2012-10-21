<?php

class so_query_compatible
extends so_query
implements \Countable, \ArrayAccess, \IteratorAggregate
{
    static $sepaName= '=';
    static $sepaChunk= '&;';    
}