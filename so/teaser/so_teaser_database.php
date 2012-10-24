<?php

class so_teaser_database
implements \ArrayAccess
{
    use so_meta;
    use so_database;
    
    var $name= 'so_teaser_database';
    var $mainField= 'so_teaser_uri';
    var $fields= array( 'so_teaser_content' );
    var $indexes= array();
    
}